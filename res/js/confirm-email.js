function format_activation_code() {
    let e = document.querySelector("#activation-code");
    e.value = e.value.toUpperCase();
    e.value = e.value.replaceAll(" ", "");
}


function check_activation_code() {
    const exp = new RegExp("^[A-Z0-9]{8}$");
    format_activation_code();
    let e = document.querySelector("#activation-code");
    if (exp.test(e.value)) {
        e.classList.remove("invalid");
        hide_error_msg("general");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("general", "Veuillez entrer un code à huit caractères alphanumériques");
        return false;
    }
}


function check_form() {
    const a = check_activation_code();
    return a;
}
