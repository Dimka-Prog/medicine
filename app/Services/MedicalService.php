<?php

declare(strict_types=1);

namespace App\Services;

class MedicalService extends NodeUtils
{
    /**
     * Возвращает массив заболеваний, где каждое заболевание содержит поля ID и Name.
     *
     * @return array
     */
    public function getDiseases(): array
    {
        $namesDiseases = [];
        $arrayDiseases = $this->getArrayNodes(self::$arrayData, 'Заболевание');

        foreach ($arrayDiseases as $disease)
            $namesDiseases[] = [
                'ID' => $disease['id'],
                'Name' => $disease['name']
            ];

        return $namesDiseases;
    }

    /**
     * Возвращает массив терапий для каждого заболевания,
     * где каждая терапия содержит поля: DiseaseID, ID, Name.
     *
     * @return array
     */
    public function getTherapies(): array
    {
        $therapies = [];
        $arrayDiseases = $this->getArrayNodes(self::$arrayData, 'Заболевание');

        foreach ($arrayDiseases as $disease) {
            $arrayTherapies = $this->getArrayNodes($disease, 'Вид терапии');
            foreach ($arrayTherapies as $therapy) {
                $therapies[] = [
                    'DiseaseID' => $disease['id'],
                    'ID' => $therapy['id'],
                    'Name' => $therapy['name']
                ];
            }
        }

        return $therapies;
    }

    public function getGoalsTherapies(): array
    {
        $goalsTherapies = [];

        $arrayTherapies = $this->getArrayNodes(self::$arrayData, 'Вид терапии');
        foreach ($arrayTherapies as $therapy) {
            $arrayGoalsTherapies = $this->getArrayNodes($therapy, 'Цель терапии');
            foreach ($arrayGoalsTherapies as $goalTherapy) {
                $goalsTherapies[] = [
                    'TherapyID' => $therapy['id'],
                    'ID' => $goalTherapy['id'],
                    'Name' => $goalTherapy['name']
                ];
            }
        }

        return $goalsTherapies;
    }

    public function getSymptoms(): array
    {
        $symptoms = [];
        $invalidCriteria = ['Возраст', 'Вес', 'Беременность'];

        $arrayTherapies = $this->getArrayNodes(self::$arrayData, 'Вид терапии');
        foreach ($arrayTherapies as $therapy) {
            $arraySymptoms = $this->getArrayNodes($therapy, 'Критерий');
            foreach ($arraySymptoms as $symptom) {
                if (!in_array($symptom['name'], $invalidCriteria)) {
                    $duplicate = false;
                    if (!empty($symptoms)) {
                        foreach ($symptoms as $key) {
                            if ($key['TherapyID'] === $therapy['id'] && $key['Name'] === $symptom['name']) {
                                $duplicate = true;
                                break;
                            }
                        }
                    }

                    if (!$duplicate) {
                        $symptomValues = $this->getSymptomValues($therapy, $symptom['name']);
                        if ($symptomValues['values']) {
                            $symptoms[] = [
                                'TherapyID' => $therapy['id'],
                                'ID' => $symptom['id'],
                                'Name' => $symptom['name'],
                                'Values' => json_encode($symptomValues['values']),
                                'TypeValue' => $symptomValues['type']
                            ];
                        }
                    }
                }
            }
        }

        return $symptoms;
    }

    // Мега костылище
    private function getSymptomValues(array $nodeTherapy, string $nameSymptom): array
    {
        $symptomValues = [];
        $arrayAllValues = [];

        $arraySymptoms = $this->getArrayNodes($nodeTherapy, $nameSymptom);
        foreach ($arraySymptoms as $symptom) {
            $qualitativeValues = $this->getArrayNodes($symptom, 'Качественные значения');
            foreach ($qualitativeValues as $qualitativeValue) {
                foreach ($qualitativeValue['successors'] as $key) {
                    if (!in_array($key['value'], $arrayAllValues)) {
                        $arrayAllValues[] = $key['value'];
                    }
                }
            }

            if (empty($qualitativeValues)) {
                $currentValues = [];

                $numericValues = $this->getArrayNodes($symptom, 'Числовые значения');
                if (!empty($this->getArrayNodes($numericValues, 'Значения'))) {
                    $numericValues = $this->getArrayNodes($numericValues, 'Значения');
                }

                foreach ($numericValues[0]['successors'] as $numericValue) {
                    if ($numericValue['valtype'] !== 'STRING') {
                        $currentValues[] = $numericValue['value'];
                    } else {
                        $symptomValues['type'] = $numericValue['value'];
                    }
                }

                if (!empty($currentValues) && !in_array($currentValues, $arrayAllValues)) {
                    $arrayAllValues[] = $currentValues;
                }
            } else {
                $symptomValues['type'] = 'enumerable';
            }
        }

        if (count($arrayAllValues) > 1 && in_array('имеется', $arrayAllValues))
            array_splice($arrayAllValues, array_search('имеется', $arrayAllValues), 1);

        $symptomValues['values'] = $arrayAllValues;
        return $symptomValues;
    }

    public function getControlPoints(): array
    {
        $controlPoints = [];

        $arrayTherapies = $this->getArrayNodes(self::$arrayData, 'Вид терапии');
        foreach ($arrayTherapies as $therapy) {
            $arrayControlPoints = $this->getArrayNodes($therapy, 'Контрольные точки оценки эффективности терапии');
            if (!empty($arrayControlPoints)) {
                foreach ($arrayControlPoints as $nodeControlPoint) {
                    foreach ($nodeControlPoint['successors'] as $controlPoint) {
                        $elapsedTime = null;
                        $unitMeasurement = null;
                        $evaluationCriteria = null;
                        foreach ($controlPoint['successors'] as $timeSegments) {
                            if ($timeSegments['meta'] === "Значение") {
                                $elapsedTime = $timeSegments['value'];
                            } elseif ($timeSegments['meta'] === "Единица измерения") {
                                $unitMeasurement = $timeSegments['value'];
                            }

                            if ($timeSegments['meta'] === "Критерии оценки эффективности терапии") {
                                $evaluationCriteria = $timeSegments;
                            }
                        }

                        $disease = $this->getArrayNodes($evaluationCriteria, 'Критерий')[0]['name'];
                        $valueDisease = null;
                        $qualitativeValues = $this->getArrayNodes($evaluationCriteria, 'Качественные значения')[0] ??
                            $this->getArrayNodes($evaluationCriteria, 'Качественные значения');

                        if (!empty($qualitativeValues)) {
                            $valueDisease = $qualitativeValues['successors'][0]['value'];
                        } else {
                            $currentValues = [];
                            $numericValues = $this->getArrayNodes($evaluationCriteria, 'Числовые значения')[0] ??
                                $this->getArrayNodes($evaluationCriteria, 'Числовые значения');
                            foreach ($numericValues['successors'] as $numericValue) {
                                if ($numericValue['valtype'] === "REAL") {
                                    $currentValues[] = $numericValue['value'];
                                }
                            }
                            $valueDisease = $currentValues;
                        }

                        $controlPoints[] = [
                            'TherapyID' => $therapy['id'],
                            'ID' => $nodeControlPoint['id'],
                            'ElapsedTime' => $elapsedTime,
                            'UnitMeasurement' => $unitMeasurement,
                            'Symptom' => $disease,
                            'ValueSymptom' => json_encode($valueDisease)
                        ];
                    }
                }
            }
        }

        return $controlPoints;
    }
}
