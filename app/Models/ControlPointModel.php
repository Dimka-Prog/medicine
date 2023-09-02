<?php

namespace App\Models;

use App\Services\MedicalService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ControlPointModel extends Model
{
    use HasFactory;

    protected $table = 'control_points';
    protected $connection = 'mysql';

    private MedicalService $medicalService;

    public function __construct()
    {
        $this->medicalService = new MedicalService();
        parent::__construct();
    }

    public function createControlPoint(): void
    {
        foreach ($this->medicalService->getControlPoints() as $controlPoint) {
            $this->getTable()
                ->updateOrInsert(
                    [
                        'TherapyID' => $controlPoint['TherapyID'],
                        'ElapsedTime' => intval($controlPoint['ElapsedTime']),
                        'UnitMeasurement' => $controlPoint['UnitMeasurement'],
                        'Symptom' => $controlPoint['Symptom'],
                    ],
                    [
                        'ID' => $controlPoint['ID'],
                        'ValueSymptom' => $controlPoint['ValueSymptom']
                    ]
                );
        }
    }

    public function getNeedCorrection(array $symptoms, int $pastDays, int $pastHours): bool
    {
        foreach ($symptoms as $symptom) {
            $controlSymptoms = $this->getTable()
                ->where('TherapyID', $symptom['TherapyID'])
                ->where('Symptom', $symptom['Symptom'])
                ->get(['ElapsedTime', 'UnitMeasurement', 'ValueSymptom'])
                ->all();

            if (!empty($controlSymptoms)) {
                $elapsedTime = null;
                foreach ($controlSymptoms as $controlSymptom) {
                    if ($symptom['TypeValue'] !== 'enumerable') {
                        $isInRange = $symptom['ValueSymptom'] <= $controlSymptom->ValueSymptom[0] &&
                            $symptom['ValueSymptom'] >= $controlSymptom->ValueSymptom[1];

                        if ($controlSymptom->UnitMeasurement === 'час') {
                            if (empty($elapsedTime)) {
                                $elapsedTime = $this->getElapsedTime($controlSymptoms, $pastHours);
                            }
                        } else {
                            if (empty($elapsedTime)) {
                                $elapsedTime = $this->getElapsedTime($controlSymptoms, $pastDays);
                            }
                        }
                        if ($elapsedTime && $controlSymptom->ElapsedTime === $elapsedTime && $isInRange) {
                            return true;
                        }
                    }
                }
            } else break;
        }
        return false;
    }

    private function getElapsedTime(array $controlSymptoms, int $time): int
    {
        $timeIntervals = [];
        foreach ($controlSymptoms as $controlSymptom) {
            $timeIntervals[] = $controlSymptom->ElapsedTime;
        }

        for ($interval = 0; $interval < count($timeIntervals); $interval++) {
            if (!isset($timeIntervals[$interval + 1])) {
                if ($time >= $timeIntervals[$interval]) {
                    return $timeIntervals[$interval];
                }
            } else {
                if ($time >= $timeIntervals[$interval] && $time < $timeIntervals[$interval + 1]) {
                    return $timeIntervals[$interval];
                }
            }
        }

        return 0;
    }


    public function getTable(): Builder
    {
        return DB::table($this->table);
    }
}
