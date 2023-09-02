let formMain = $('#formMain');
let formModal = $('#formModal');

let selectDiseases = $('#diseases')
let selectSymptoms = $('.symptoms')
let selectValuesSymptom = $('.valuesSymptom')

// Обработчик реагирующий на выбор значения в select
selectDiseases.on('change', function () {
    formMain.submit();
});

selectSymptoms.on('change', function () {
    formMain.submit();
});

selectValuesSymptom.on('change', function () {
    formMain.submit();
});

formMain.on('submit', function () {
    // Получаем выбранное значение из <select>
    let selectedDisease = selectDiseases.val();
    // Заносим значение в скрытое поле с id = hiddenDiseaseInput
    $('#hiddenDiseaseInput').val(selectedDisease);

    let selectedSymptoms = [];
    selectSymptoms.each(function () {
        let selectedSymptom = $(this).val();
        if (selectedSymptom !== '') {
            selectedSymptoms.push(selectedSymptom);
        }
    });

    let selectedValuesSymptom = []
    selectValuesSymptom.each(function () {
        let selectedValue = $(this).val();
        selectedValuesSymptom.push(selectedValue);
    });

    $('#hiddenSymptomsInput').val(JSON.stringify(selectedSymptoms));
    $('#hiddenValuesSymptomInput').val(JSON.stringify(selectedValuesSymptom));
});

if (formModal.length) {
    // Обработчик реагирущий на отправку формы
    formModal.on('submit', function (event) {
        // Отменяем стандартное поведение формы (отправку)
        event.preventDefault();
        formMain.submit();
    });
}

selectDiseases.select2({
    placeholder: "Выберите диагноз",
    allowClear: true
});

selectSymptoms.select2({
    placeholder: "Признак",
});

$('.selectValues').select2({
    placeholder: "Значение",
    allowClear: true
});
