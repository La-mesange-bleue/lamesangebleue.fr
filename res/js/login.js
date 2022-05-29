function check_user_login() {
    let e = document.querySelector("#user-login");
    e.value = e.value.toLowerCase();
    e.value = remove_prefix(e.value, " ");
    e.value = remove_suffix(e.value, " ");
    if (e.value.length > 0) {
        e.classList.remove("invalid");
        hide_error_msg("user-login");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("user-login", "Veuillez remplir ce champ");
        return false;
    }
} //verifie si le champ username a été rempli correctement au moment de la connexion


function check_password() {
    let e = document.querySelector("#password");
    if (e.value.length > 0) {
        e.classList.remove("invalid");
        hide_error_msg("password");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("password", "Veuillez remplir ce champ");
        return false;
    }
} //verifie si le champ mdp a été rempli correctement 


function check_form() {
    const a = check_user_login();
    const b = check_password();
    return a && b;
} // verifie si les deux fonctions sont valides 
