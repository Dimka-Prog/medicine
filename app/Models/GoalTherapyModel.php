<?php

namespace App\Models;

use App\Services\MedicalService;
use Illuminate\Database\Eloquent\{Factories\HasFactory, Model};
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class GoalTherapyModel extends Model
{
    use HasFactory;

    protected $table = 'goals_therapies';
    protected $connection = 'mysql';

    private MedicalService $medicalService;

    public function __construct()
    {
        $this->medicalService = new MedicalService();
        parent::__construct();
    }

    public function createGoalTherapy(): void
    {
        foreach ($this->medicalService->getGoalsTherapies() as $goalTherapy) {
            $this->getTable()
                ->updateOrInsert(
                    ['ID' => $goalTherapy['ID']],
                    [
                        'TherapyID' => $goalTherapy['TherapyID'],
                        'Name' => $goalTherapy['Name']
                    ]
                );
        }
    }

    public function getTable(): Builder
    {
        return DB::table($this->table);
    }
}
