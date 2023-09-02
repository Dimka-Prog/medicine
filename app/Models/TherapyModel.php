<?php

namespace App\Models;

use App\Services\MedicalService;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class TherapyModel extends Model
{
    use HasFactory;

    protected $table = 'therapies';
    protected $connection = 'mysql';

    private MedicalService $medicalService;

    public function __construct()
    {
        $this->medicalService = new MedicalService();
        parent::__construct();
    }

    public function createTherapy(): void
    {
        foreach ($this->medicalService->getTherapies() as $therapy) {
            $this->getTable()
                ->updateOrInsert(
                    ['ID' => $therapy['ID']],
                    [
                        'DiseaseID' => $therapy['DiseaseID'],
                        'Name' => $therapy['Name']
                    ]
                );
        }
    }

    public function getTable(): Builder
    {
        return DB::table($this->table);
    }
}
