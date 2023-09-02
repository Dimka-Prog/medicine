<?php

namespace App\Http\Controllers;

use App\Models\{ControlPointModel, DiseaseModel, SymptomModel, UsersInfoModel};
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\View\View;
use function PHPUnit\Framework\isFalse;

class PatientCardController extends Controller
{
    public function correctionTreatment(Request $request): View|RedirectResponse
    {
        $passportNum = $request->cookie('passport');

        $tableDiseases = new DiseaseModel();
        $tableSymptoms = new SymptomModel();
        $usersInfo = new UsersInfoModel();

        $userInfo = $usersInfo->getTable()->where('PassportNum', $passportNum)->first();
        $oldData = $request->all();

        $patientFio = (string)str_replace(' ', '', "Настюшина Дарья Романовна");
        $startTimeTreatment = null;
        $file = public_path("json/{$patientFio}.json");

        if (file_exists($file)) {
            $jsonContents = file_get_contents($file);
            $arrayData = json_decode($jsonContents, true);
            $startTimeTreatment = $arrayData['startTimeTreatment'];

            $oldTime = \DateTime::createFromFormat('Y-m-d H:i', $startTimeTreatment);
            $currentTime = \DateTime::createFromFormat('Y-m-d H:i', date('Y-m-d H:i'));

            $interval = $currentTime->diff($oldTime);

            $days = $interval->d;
            $hours = $interval->h;

            $pastTime = "";
            if ($days > 0) {
                $pastTime .= $days . " " . Str::plural("day", $days);
            }
            if ($hours > 0) {
                $pastTime .= " " . $hours . " " . Str::plural("hour", $hours);
            }
        }

        if ($request->isMethod('POST')) {
            if ($request->has('buttonLogout')) {
                Cookie::queue(Cookie::forget('auth'));
                Cookie::queue(Cookie::forget('passport'));

                return redirect()->route('user.authentication');
            }

            $deletePatient = $request->has('buttonDeletePatient');
            $buttonAnalysis = $request->has('buttonAnalysis');
            $buttonSave = $request->has('buttonSave');

            if (app()->environment('local')) {
                if ($buttonAnalysis || $buttonSave) {
                    if (!file_exists($file)) {
                        $startTimeTreatment = date('Y-m-d H:i');
                        $content = json_encode(['startTimeTreatment' => $startTimeTreatment]);
                        file_put_contents($file, $content);
                    }
                }
            }

            $oldData['disease'] = $request->input('hiddenDiseaseInput');
            if (isset($oldData['disease'])) {
                $symptoms = $tableSymptoms->getSymptomsDisease($oldData['disease']);
            }

            $jsonSymptoms = $request->input('hiddenSymptomsInput');
            if ($jsonSymptoms) {
                $oldData['symptom'] = json_decode($jsonSymptoms, true);
            }
            $oldData['valuesSymptom'] = json_decode($request->input('hiddenValuesSymptomInput'), true);

//            if ($buttonAnalysis && isset($symptoms) && isset($oldData['symptom']) && isset($days) && isset($hours)) {
//                $selectedSymptoms = [];
//                foreach ($symptoms as $symptom) {
//                    foreach ($oldData['symptom'] as $key => $oldSymptom) {
//                        if ($symptom->Name === $oldSymptom) {
//                            $selectedSymptoms[] = [
//                                'TherapyID' => $symptom->TherapyID,
//                                'Symptom' => $symptom->Name,
//                                'TypeValue' => $symptom->TypeValue,
//                                'ValueSymptom' => $oldData['valuesSymptom'][$key]
//                            ];
//                        }
//                    }
//                }
//                $needCorrection = (new ControlPointModel())->getNeedCorrection($selectedSymptoms, $days, $hours);
//            }
        }

        if (!isset($userInfo->FIO)) {
            Cookie::queue(Cookie::forget('auth'));
            Cookie::queue(Cookie::forget('passport'));

            return redirect()->route('user.authentication');
        }

        return view('patientCard', [
            'fio' => $userInfo->FIO,
            'specialization' => $userInfo->Specialization,
            'deletePatient' => $deletePatient ?? null,
            'diseases' => $tableDiseases->getNamesDiseases(),
            'symptoms' => $symptoms ?? null,
            'startTimeTreatment' => $startTimeTreatment ?? "----.--.-- --:--",
            'pastTime' => !empty($pastTime) ? $pastTime : "----.--.-- --:--"
        ])->with('oldData', $oldData);
    }
}
