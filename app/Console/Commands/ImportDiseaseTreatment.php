<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MedicalService;
use App\Models\{ControlPointModel, DiseaseModel, GoalTherapyModel, SymptomModel, TherapyModel};
use App\Services\KnowledgeBase;
use Illuminate\Console\Command;

class ImportDiseaseTreatment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:diseases {jsonFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обрабатывает данные о лечении заболеваний из JSON файла и заносит их в базу';

    public function handle(): void
    {
        $jsonFile = $this->argument('jsonFile');
        $pathToFile = public_path("json/$jsonFile");
        $jsonContents = file_get_contents($pathToFile);
        $arrayData = json_decode($jsonContents, true);

        KnowledgeBase::setArray($arrayData);

        dd((new SymptomModel())->getSymptomsDisease('Гастроэзофагеальная рефлюксная болезнь с эзофагитом'))

        (new DiseaseModel())->createDisease();
        (new TherapyModel())->createTherapy();
        (new GoalTherapyModel())->createGoalTherapy();
        (new SymptomModel())->createSymptom();
        (new ControlPointModel())->createControlPoint();
    }
}
