<?php
require_once("./res/php/common.php");

if (isset($_POST["user-name"]) && isset($_POST["first-name"]) && isset($_POST["last-name"])
&& isset($_POST["email-address"]) && isset($_POST["phone-number"]) && isset($_POST["password"])
&& isset($_POST["birth-day"]) && isset($_POST["birth-month"]) && isset($_POST["birth-year"])
&& isset($_POST["gender"])) { //4 7 verifie que tous les champs sont remplis 
    $user_name = mysqli_escape_string($conn, $_POST["user-name"]);
    $first_name = mysqli_escape_string($conn, $_POST["first-name"]);
    $last_name = mysqli_escape_string($conn, $_POST["last-name"]);
    $email_address = mysqli_escape_string($conn, $_POST["email-address"]);
    $phone_number = mysqli_escape_string($conn, $_POST["phone-number"]);
    $password = mysqli_escape_string($conn, $_POST["password"]);
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $birth_day = mysqli_escape_string($conn, $_POST["birth-day"]);
    $birth_month = mysqli_escape_string($conn, $_POST["birth-month"]);
    $birth_year = mysqli_escape_string($conn, $_POST["birth-year"]);
    $birth_date = "$birth_year-$birth_month-$birth_day";
    $gender = mysqli_escape_string($conn, $_POST["gender"]);
//8 19 recupere les valeurs des champs 
    $check_sql = "SELECT COUNT(*) FROM `Users` WHERE `Users`.`user_name` = '$user_name' OR `Users`.`email_address` = '$email_address';";
    $check_res = mysqli_query($conn, $check_sql); //verifie si un utilisateur a meme username ou @
    if ($check_res != false) {
        $check_row = mysqli_fetch_array($check_res);
        if ($check_row) {
            if ($check_row[0] == 0) {
                $sql = "INSERT INTO `Users` (`id`, `user_name`, `first_name`, `last_name`, `email_address`, `phone_number`, `birth_date`, `password`, `registration_date`) VALUES (NULL, '$user_name', '$first_name', '$last_name', '$email_address', '$phone_number', '$birth_date', '$password_hash', NOW());";
                $res = mysqli_query($conn, $sql); //rajoute utilsateur dans bdd
                if ($res == true) {
                    $_SESSION["CONFIRM_EMAIL_login"] = $user_name;
                    redirect("$PATH/confirm-email.php"); //redirige vers page de confirmation de mail 
                } else {
                    $_SESSION["SIGNUP_error_msg"] = "L'inscription a échoué";
                }
            } else {
                $_SESSION["SIGNUP_error_msg"] = "Nom d'utilisateur et/ou adresse e-mail déjà utilisé(s). Si vous avez déjà un compte, <a href='$PATH/login.php'>connectez-vous.</a>";
            }
        } else {
            $_SESSION["SIGNUP_error_msg"] = "Erreur inconnue";
        }
    } else {
        $_SESSION["SIGNUP_error_msg"] = "La connexion au serveur a échoué";
    }
}

get_user_info();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Inscription — <?= $WEBSITE_NAME ?></title>
        <?php load("res/templates/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="<?= res("res/css/signup.css") ?>" />
    </head>
    <body onload="init();">
        <?php load("res/templates/header.php"); ?>

        <?php
        if (isset($_POST["birth-day"]) && isset($_POST["birth-month"]) && isset($_POST["birth-year"])) {
        ?>
            <form id="POST-data">
                <input type="hidden" id="POST-birth-day" value="<?= $_POST["birth-day"] ?>">
                <input type="hidden" id="POST-birth-month" value="<?= $_POST["birth-month"] ?>">
                <input type="hidden" id="POST-birth-year" value="<?= $_POST["birth-year"] ?>">
            </form>
        <?php
        }
        ?>

        <table style="margin-left: auto;margin-right: auto">
            <form action="<?= $PATH ?>/signup.php" method="POST" onsubmit="return check_form();">
                <tr>
                    <td>
                        <h1>Créez votre compte</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" id="gender-female" name="gender" value="female" oninput="check_gender();" <?php
                            if (isset($_POST["gender"]) && $_POST["gender"] == "female")
                                echo "checked";
                        ?>>
                        <label for="gender-female">Femme</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" id="gender-male" name="gender" value="male" oninput="check_gender();" <?php
                            if (isset($_POST["gender"]) && $_POST["gender"] == "male")
                                echo "checked";
                        ?>>
                        <label for="gender-male">Homme</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="radio" id="gender-other" name="gender" value="other" oninput="check_gender();" <?php
                            if (isset($_POST["gender"]) && $_POST["gender"] == "other")
                                echo "checked";
                        ?>>
                        <label for="gender-other">Personnalisé</label>
                    </td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="gender-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="user-name">Nom d'utilisateur</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="user-name" name="user-name" onblur="check_user_name();" value="<?php if (isset($_POST["user-name"])) echo $_POST["user-name"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="user-name-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="first-name">Prénom</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="first-name" name="first-name" onblur="check_first_name();" value="<?php if (isset($_POST["first-name"])) echo $_POST["first-name"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="first-name-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="last-name">Nom de famille</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="last-name" name="last-name" onblur="check_last_name();" value="<?php if (isset($_POST["last-name"])) echo $_POST["last-name"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="last-name-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="email-address">Adresse e-mail</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="text" id="email-address" name="email-address" onblur="check_email_address();" value="<?php if (isset($_POST["email-address"])) echo $_POST["email-address"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="email-address-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="phone-number">Numéro de téléphone</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="tel" id="phone-number" name="phone-number" onblur="check_phone_number();" value="<?php if (isset($_POST["phone-number"])) echo $_POST["phone-number"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="phone-number-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="password">Mot de passe</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="password" id="password" name="password" onblur="check_password();">
                    </td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="password-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="confirm-password">Confirmez le mot de passe</label></td>
                </tr>
                <tr>
                    <td>
                        <input type="password" id="confirm-password" onblur="check_confirm_password();">
                    </td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="confirm-password-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="birth-day">Jour de naissance</label></td>
                </tr>
                <tr>
                    <td><select id="birth-day" name="birth-day" oninput="check_birth_date();"></select></td>
                </tr>
                <tr>
                    <td><label for="birth-month">Mois de naissance</label></td>
                </tr>
                <tr>
                    <td><select id="birth-month" name="birth-month" oninput="gen_birth_days(); check_birth_date();"></select></td>
                </tr>
                <tr>
                    <td><label for="birth-year">Année de naissance</label></td>
                </tr>
                <tr>
                    <td><select id="birth-year" name="birth-year" oninput="gen_birth_days(); check_birth_date();"></select></td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="birth-date-error-msg">Champ invalide</span></td>
                </tr>
                
               
                <tr>
                    <td>
                        <input style="border-radius: 10px" type="submit" value="S'inscrire">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="error-msg <?php if (!isset($_SESSION["SIGNUP_error_msg"])) echo "invisible"; ?>" id="general-error-msg"><?php
                            if (isset($_SESSION["SIGNUP_error_msg"])) {
                                echo $_SESSION["SIGNUP_error_msg"];
                                unset($_SESSION["SIGNUP_error_msg"]);
                            } else {
                                echo "Tout va bien !";
                            }
                        ?></span>
                    </td>
                </tr>
            </form>
        </table>

        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/signup.js") ?>"></script>
    </body>
</html>
