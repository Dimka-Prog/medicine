<?php

namespace App\Models;

use App\Services\MedicalService;
use Brick\Math\BigInteger;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SymptomModel extends Model
{
    use HasFactory;

    protected $table = 'symptoms';
    protected $connection = 'mysql';

    private MedicalService $medicalService;

    public function __construct()
    {
        $this->medicalService = new MedicalService();
        parent::__construct();
    }

    public function createSymptom(): void
    {
        foreach ($this->medicalService->getSymptoms() as $symptom) {
            $this->getTable()
                ->updateOrInsert(
                    ['ID' => $symptom['ID']],
                    [
                        'TherapyID' => $symptom['TherapyID'],
                        'Name' => $symptom['Name'],
                        'Values' => $symptom['Values'],
                        'TypeValue' => $symptom['TypeValue']
                    ]
                );
        }
    }

    public function getSymptomsDisease(string $diseaseName): array
    {
        $diseaseId = (new DiseaseModel())->getTable()
            ->where('Name', $diseaseName)
            ->value('ID');

        $therapies = (new TherapyModel())->getTable()
            ->where('DiseaseID', $diseaseId)
            ->pluck('ID')
            ->values()
            ->all();

        $symptomsDisease = $this->getTable()
            ->whereIn('TherapyID', $therapies)
            ->orderBy('Name')
            ->groupBy(['Name', 'Values', 'TypeValue'])
            ->get(['Name', 'Values', 'TypeValue'])
            ->all();

        // Объединение массивов с одинаковым значением "Name" и удаление дубликатов
        $result = [];
        foreach ($symptomsDisease as $symptom) {
            $name = $symptom->Name;
            if (!isset($result[$name])) {
                $result[$name] = $symptom;
                $result[$name]->Values = json_decode($result[$name]->Values);
            } else {
                $array = json_decode($symptom->Values, true);
                $merge = array_merge($result[$name]->Values, $array);
                $result[$name]->Values = array_map('unserialize', array_unique(array_map('serialize', $merge)));
            }
        }
        // Преобразование результата в индексированный массив
        return array_values($result);
    }

    public function getTable(): Builder
    {
        return DB::table($this->table);
    }
}
