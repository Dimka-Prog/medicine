@extends('layouts.layoutPatientCard')

@section('title')
    Карточка пациента
@endsection

@section('Main')
    @if($deletePatient)
        <div
            class="modal modal-alert position-fixed d-flex align-items-center justify-content-center background-dark-transparent py-5"
            tabindex="-1" role="dialog" id="modalChoice">
            <div class="modal-dialog min-width w-25" role="document">
                <div class="modal-content rounded-3 shadow">
                    <div class="modal-body p-4 text-center">
                        <h5 class="mb-0">Удалить пациента?</h5>
                        <p class="mb-0">При удалении пациента, он автоматически будет добавлен в архив.</p>
                    </div>
                    <form action="{{ route('user.patientCard') }}" method="POST" class="modal-footer flex-nowrap p-0"
                          id="formModal">
                        <button type="button" class="btn btn-lg btn-outline-danger fs-6 col-6 m-0 rounded-0 border-0"
                                name="buttonModalDeletePatient"><strong>Удалить</strong>
                        </button>
                        <button type="submit" class="btn btn-lg btn-outline-primary fs-6 col-6 m-0 rounded-0 border-0"
                                data-bs-dismiss="modal" name="buttonModalCancel">Отмена
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <main class="container d-flex flex-column rounded-4 shadow min-width bg-white" style="width: 60%;">
        <h2 class="text-center mb-3 p-3 fw-normal text-secondary">Карточка пациента</h2>

        <form action="{{ route('user.patientCard') }}" method="POST" class="row align-items-md-stretch flex-grow-1 pb-3"
              id="formMain">
            <input type="hidden" name="hiddenDiseaseInput" id="hiddenDiseaseInput" value="">
            <div class="col-md-3 text-center">
                <div class="bg-white rounded-3 h-100 pb-3 p-2">
                    <img src="{{ asset('images/patientIcon.png') }}" alt="mdo" width="100" height="100"
                         class="rounded-circle">
                    <h6 class="pt-2 pb-2" style="font-size: 18px;">Настюшина Дарья Романовна</h6>
                    <button type="submit" class="btn btn-outline-danger w-75" name="buttonDeletePatient">Удалить
                    </button>
                    <div>
                        <label class="h6 mt-5 mb-0 w-100">Начало лечения</label>
                        <label>{{ $startTimeTreatment }}</label>
                        <label class="h6 mt-3 mb-0 w-100">Прошло времени</label>
                        <label>{{ $pastTime }}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                {{-- Заменить костыль с максимальным значением высоты на динамический --}}
                <div class="bg-light border rounded-3 p-3 overflow-auto" style="max-height: 650px;">
                    <label class="h5" for="description">О пациенте:</label>
                    <textarea class="form-control text-justify max-height-250 mb-4 w-100" rows="4" id="description"
                              name="description">{{ $oldData['description'] ?? '' }}</textarea>

                    <div class="w-100 mb-3">
                        <label class="h5" style="font-size: 18px; margin-right: 5px;" for="diseases">Диагноз:</label>
                        <select class="diseases" style="width: 65%;" id="diseases" name="diseases">
                            <option value="">Выберите диагноз</option>
                            @foreach($diseases as $disease)
                                <option
                                    @if(isset($oldData['disease']) && $disease === $oldData['disease']) selected @endif>
                                    {{ $disease }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="h5 w-100" style="font-size: 18px">Признаки (симптомы):</div>
                    <input type="hidden" name="hiddenSymptomsInput" id="hiddenSymptomsInput" value="">
                    <input type="hidden" name="hiddenValuesSymptomInput" id="hiddenValuesSymptomInput" value="">
                    @if($symptoms)
                        @for ($num = 0; $num < count($symptoms); $num++)
                            <div class="w-100 mb-2">
                                <select class="symptoms" style="width: 40%;">
                                    <option value="">Признак</option>
                                    @foreach($symptoms as $symptom)
                                        @if(isset($oldData['symptom'][$num]) && $symptom->Name === $oldData['symptom'][$num])
                                            <option selected>{{ $symptom->Name }}</option>
                                        @endif
                                        @if(!in_array($symptom->Name, $oldData['symptom']))
                                            <option>{{ $symptom->Name }}</option>
                                        @endif
                                    @endforeach
                                </select>&nbsp;&nbsp;:&nbsp;
                                @if(isset($oldData['symptom'][$num]))
                                    @foreach($symptoms as $symptom)
                                        @if($symptom->Name === $oldData['symptom'][$num])
                                            @if($symptom->TypeValue !== 'enumerable')
                                                <label style="width: 30%;">
                                                    <input type="text" class="valuesSymptom rounded-1 w-100"
                                                           placeholder="Значение"
                                                           @if(isset($oldData['valuesSymptom'][$num]))
                                                               value="{{$oldData['valuesSymptom'][$num]}}"
                                                        @endif>
                                                </label>&nbsp;&nbsp;{{ $symptom->TypeValue }}
                                            @else
                                                <select class="valuesSymptom selectValues" style="width: 30%;">
                                                    <option value="">Значение</option>
                                                    @foreach($symptom->Values as $value)
                                                        @if(isset($oldData['valuesSymptom'][$num]) && $value === $oldData['valuesSymptom'][$num])
                                                            <option selected>{{ $value }}</option>
                                                        @else
                                                            <option>{{ $value }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        @endfor
                    @endif

                    <label class="h5 @if(isset($oldData['disease'])) mt-1 @else mt-3 @endif" style="font-size: 18px"
                           for="recommendTreatment">Рекомендации по лечению:</label>
                    <textarea class="form-control text-justify mb-2 max-height-250 w-100" rows="5"
                              id="recommendTreatment"
                              name="recommendTreatment">{{ $oldData['recommendTreatment'] ?? '' }}</textarea>

                    <div class="alert alert-success text-center w-100 p-2 mb-1">
                        Коррекция лечения не требуется
                    </div>
{{--                    <div class="alert alert-warning text-center w-100 p-2 mb-1">--}}
{{--                        Необходимо скорректировать лечение--}}
{{--                    </div>--}}
                </div>
                <div class="w-100 text-end mt-3">
                    <button type="submit" class="btn btn-outline-primary mx-1" name="buttonAnalysis">
                        Проанализировать
                    </button>
                    <button type="submit" class="btn btn-outline-success px-4" name="buttonSave">Сохранить</button>
                </div>
            </div>
        </form>
    </main>
@endsection
