function onlyNumbers(inputID)
{
    let inputElement = document.getElementById(inputID)

    inputElement.addEventListener("input", function() {
        let value = inputElement.value
        inputElement.value = value.replace(/\D/g, '') // Удаляет все нецифровые символы
    })
}
