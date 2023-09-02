<?php

namespace App\Models;

use App\Services\MedicalService;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class DiseaseModel extends Model
{
    use HasFactory;

    protected $table = 'diseases';
    protected $connection = 'mysql';

    private MedicalService $medicalService;

    public function __construct()
    {
        $this->medicalService = new MedicalService();
        parent::__construct();
    }


    public function createDisease(): void
    {
        foreach ($this->medicalService->getDiseases() as $disease) {
            $this->getTable()
                ->updateOrInsert(
                    ['ID' => $disease['ID']],
                    ['Name' => $disease['Name']]
                );
        }
    }

    public function getNamesDiseases(): array
    {
        return $this->getTable()->pluck('Name')->all();
    }

    public function getTable(): Builder
    {
        return DB::table($this->table);
    }
}
