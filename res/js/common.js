leap_year = year => (year % 4 == 0 && year % 100 != 0) || year % 400 == 0; //fonction qui retourne si l'année passée en paramètre est bissextile ou non, dans formulaire d'inscription 


function remove_prefix(str, prefix) {
    while (str.startsWith(prefix))
        str = str.slice(prefix.length);
    return str;
} //permet d'enlever automatiquement prefixe


function remove_suffix(str, suffix) {
    while (str.endsWith(suffix))
        str = str.slice(0, str.length - suffix.length);
    return str;
} //permet d'enlever automatiquement suffixe 


function show_error_msg(id, msg = "current") {
    let e = document.querySelector("#" + id + "-error-msg");
    if (msg == "current") msg = e.innerHTML;
    e.innerHTML = msg;
    if (e.classList.contains("invisible")) e.classList.remove("invisible");
    return (e.innerHTML == msg) && (!e.classList.contains("invisible"));
} //permet d'afficher un message d'erreur sur une page 


function hide_error_msg(id, keep_msg = true) {
    const default_msg = "Erreur";
    let e = document.querySelector("#" + id + "-error-msg");
    if (!keep_msg) e.innerHTML = default_msg;
    if (!e.classList.contains("invisible")) e.classList.add("invisible");
    return e.classList.contains("invisible");
} //permet de cacher le message d'erreur 


function scroll_to_top() {
    window.scrollTo(window.scrollX, 0);
} //scroll tout en haut page 


function check_assistance_form() {
    const exp = new RegExp("^[a-z0-9._-]+@[a-z0-9.-]+.[a-z0-9.-]+$");
    let msg_e = document.querySelector("#assistance-form #questions");
    msg_e.value = remove_prefix(msg_e.value, " ");
    msg_e.value = remove_suffix(msg_e.value, " ");
    let email_e = document.querySelector("#assistance-form #email-address");
    email_e.value = email_e.value.toLowerCase();
    email_e.value = remove_prefix(email_e.value, " ");
    email_e.value = remove_suffix(email_e.value, " ");
    if (msg_e.value.length <= 0) {
        msg_e.focus();
        return false;
    } else if (email_e.value.length <= 0) {
        email_e.focus();
        return false;
    } else if (exp.test(email_e.value)) {
        return true;
    } else {
        const r = confirm("L'adresse e-mail \"" + email_e.value + "\" ne semble pas valide.\nAttention : si cette adresse n'est pas joignable, nous ne serons pas en mesure de répondre à votre demande.\n\nCliquez sur OK si vous êtes sûr(e) de pouvoir y accéder.");
        return r;
    }
} //permet de verifier que le formulaire de contact a été rempli correctement 
