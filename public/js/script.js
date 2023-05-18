function clearGrades() {
    var inputs = document.querySelectorAll('#grade-input');
    for (var i = 0; i < inputs.length; i++) {
    inputs[i].value = '';
    }
}