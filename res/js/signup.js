function check_user_name() {
    const exp = new RegExp("^[a-z][a-z0-9._-]+$");
    let e = document.querySelector("#user-name");
    e.value = e.value.toLowerCase();
    e.value = remove_prefix(e.value, " ");
    e.value = remove_suffix(e.value, " ");
    if (exp.test(e.value)) {
        e.classList.remove("invalid");
        hide_error_msg("user-name");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("user-name", "Nom d'utilisateur invalide");
        return false;
    }
    // TO-DO (?): check if username is already taken (AJAX)
}


function check_first_name() {
    let e = document.querySelector("#first-name");
    e.value = remove_prefix(e.value, " ");
    e.value = remove_suffix(e.value, " ");
    if (e.value.length > 0) {
        e.classList.remove("invalid");
        hide_error_msg("first-name");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("first-name", "Veuillez remplir ce champ");
        return false;
    }
}


function check_last_name() {
    let e = document.querySelector("#last-name");
    e.value = remove_prefix(e.value, " ");
    e.value = remove_suffix(e.value, " ");
    if (e.value.length > 0) {
        e.classList.remove("invalid");
        hide_error_msg("last-name");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("last-name", "Veuillez remplir ce champ");
        return false;
    }
}


function check_email_address() {
    const exp = new RegExp("^[a-z0-9._-]+@[a-z0-9.-]+.[a-z0-9.-]+$");
    let e = document.querySelector("#email-address");
    e.value = e.value.toLowerCase();
    e.value = remove_prefix(e.value, " ");
    e.value = remove_suffix(e.value, " ");
    if (exp.test(e.value)) {
        e.classList.remove("invalid");
        hide_error_msg("email-address");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("email-address", "Adresse e-mail invalide");
        return false;
    }
    // TO-DO: check if email address is already taken
}


function check_phone_number() {
    const exp = new RegExp("^\\d{10}$");
    let e = document.querySelector("#phone-number");
    e.value = e.value.replaceAll(" ", "");
    if (exp.test(e.value)) {
        e.classList.remove("invalid");
        hide_error_msg("phone-number");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("phone-number", "Numéro de téléphone invalide. Il doit être composé de dix chiffres.");
        return false;
    }
}


function check_password() {
    const exps = [
        new RegExp("\\d+"),
        new RegExp("[a-z]+"),
        new RegExp("[A-Z]+")
    ];
    let e = document.querySelector("#password");
    let valid = true;
    if (e.value.length < 6)
        valid = false;
    else
        exps.forEach(function (element) {
            if (!element.test(e.value))
                valid = false;
        });
    if (valid) {
        e.classList.remove("invalid");
        hide_error_msg("password");
        return true;
    } else {
        e.classList.add("invalid");
        show_error_msg("password", "Créez une combinaison d'au moins six chiffres, lettres majuscules et lettres minuscules.");
        return false;
    }
}


function check_confirm_password() {
    let e1 = document.querySelector("#password");
    let e2 = document.querySelector("#confirm-password");
    if (e1.value == e2.value) {
        e2.classList.remove("invalid");
        hide_error_msg("confirm-password");
        return true;
    } else {
        e2.classList.add("invalid");
        show_error_msg("confirm-password", "Les mots de passe ne correspondent pas");
        return false;
    }
}


function check_birth_date() {
    const current_date = new Date();

    let d = document.querySelector("#birth-day");
    let m = document.querySelector("#birth-month");
    let y = document.querySelector("#birth-year");

    const birth_day = parseInt(d.value);
    const birth_month = parseInt(m.value);
    const birth_year = parseInt(y.value);
    const birth_date = new Date(birth_year, birth_month, birth_day);

    const diff_date = new Date(current_date.getTime() - birth_date.getTime());
    const age = diff_date.getUTCFullYear() - 1970;

    if (age < 18) {
        d.classList.add("invalid");
        m.classList.add("invalid");
        y.classList.add("invalid");
        show_error_msg("birth-date", "Vous devez avoir au moins 18 ans pour vous inscrire");
        return false;
    } else {
        d.classList.remove("invalid");
        m.classList.remove("invalid");
        y.classList.remove("invalid");
        hide_error_msg("birth-date");
        return true;

    }
}


function check_gender() {
    let e = document.querySelector("[name='gender']:checked");
    if (e == null) {
        document.querySelectorAll("[name='gender']").forEach(function (element) {
            element.classList.add("invalid");
        });
        show_error_msg("gender", "Veuillez indiquer votre genre");
        return false;
    } else {
        document.querySelectorAll("[name='gender']").forEach(function (element) {
            element.classList.remove("invalid");
        });
        hide_error_msg("gender");
        return true;
    }
}


function check_form() {
    const a = check_user_name();
    const b = check_first_name();
    const c = check_last_name();
    const d = check_email_address();
    const e = check_phone_number();
    const f = check_password();
    const g = check_confirm_password();
    const h = check_birth_date();
    const i = check_gender();
    return a && b && c
        && d && e && f
        && g && h && i;
}


function gen_birth_years() {
    let year_select = document.querySelector("#birth-year");
    const max = new Date().getUTCFullYear() - 1;
    const min = max - 120;
    year_select.innerHTML = "";
    for (let i = max; i >= min; i--) {
        let child = document.createElement("option");
        child.value = i;
        child.innerHTML = i;
        year_select.appendChild(child);
    }
}


function gen_birth_months() {
    let month_select = document.querySelector("#birth-month");

    const months = [
        "janvier", "février", "mars", "avril",
        "mai", "juin", "juillet", "août",
        "septembre", "octobre", "novembre", "décembre"
    ];

    month_select.innerHTML = "";
    for (let i in months) {
        let child = document.createElement("option");
        child.value = parseInt(i) + 1;
        child.innerHTML = months[i];
        month_select.appendChild(child);
    }
}


function gen_birth_days() {
    const year_select = document.querySelector("#birth-year");
    const month_select = document.querySelector("#birth-month");
    let day_select = document.querySelector("#birth-day");
    const year = parseInt(year_select.value);

    const days = {
        1: 31,
        2: leap_year(year) ? 29 : 28,
        3: 31,
        4: 30,
        5: 31,
        6: 30,
        7: 31,
        8: 31,
        9: 30,
        10: 31,
        11: 30,
        12: 31
    };

    let old_value = (day_select.value.length > 0) ? day_select.value : "1";
    const max = days[parseInt(month_select.value)];
    if (parseInt(old_value) > max) old_value = max.toString();
    
    day_select.innerHTML = "";
    for (let i = 1; i <= max; i++) {
        let child = document.createElement("option");
        child.value = i;
        child.innerHTML = i;
        day_select.appendChild(child);
    }
    day_select.value = old_value;
}


function restore_birth_date() {
    let form = document.querySelector("form#POST-data");
    if (form != null) {
        const day = document.querySelector("form#POST-data > #POST-birth-day").value;
        const month = document.querySelector("form#POST-data > #POST-birth-month").value;
        const year = document.querySelector("form#POST-data > #POST-birth-year").value;
        let day_option = document.querySelector("#birth-day > option[value='" + day + "']");
        if (day_option != null) day_option.selected = true;
        let month_option = document.querySelector("#birth-month > option[value='" + month + "']");
        if (month_option != null) month_option.selected = true;
        let year_option = document.querySelector("#birth-year > option[value='" + year + "']");
        if (year_option != null) year_option.selected = true;
        form.remove();
    }
}


function init() {
    gen_birth_years();
    gen_birth_months();
    gen_birth_days();
    restore_birth_date();
}
