<?php
require_once("./res/php/common.php");

if (isset($_POST["user-login"]) && isset($_POST["password"])) {
    $login = mysqli_escape_string($conn, $_POST["user-login"]);
    $password = mysqli_escape_string($conn, $_POST["password"]);

    $sql = "SELECT `Users`.`id`, `Users`.`email_address`, `Users`.`password`, `Users`.`is_active` FROM `Users` WHERE `Users`.`user_name` = '$login' OR `Users`.`email_address` = '$login';";
    $res = mysqli_query($conn, $sql);
    if ($res != false) {
        $row = mysqli_fetch_array($res);
        if ($row) {
            if (password_verify($password, $row["password"])) {
                if ($row["is_active"]) {
                    $_SESSION["user_id"] = $row["id"];
                    redirect("$PATH/");
                } else {
                    $_SESSION["CONFIRM_EMAIL_login"] = $login;
                    redirect("$PATH/confirm-email.php");
                }
            } else {
                $_SESSION["LOGIN_error_msg"] = "Mot de passe incorrect. <a href='forgot-password.php'>Vous l'avez oublié ?</a>";
            }
        } else {
            $_SESSION["LOGIN_error_msg"] = "Aucun compte n'est associé à cet identifiant. <a href='signup.php'>Inscrivez-vous.</a>";
        }
    } else {
        $_SESSION["LOGIN_error_msg"] = "La connexion au serveur a échoué";
    }
}

get_user_info();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Connexion — <?= $WEBSITE_NAME ?></title>
        <?php load("res/templates/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="<?= res("res/css/login.css") ?>" />
    </head>
    <body>
        <?php load("res/templates/header.php"); ?>

        <table class="login" style="text-align: center">
            <form action="<?= $PATH ?>/login.php" method="POST" onsubmit="return check_form();">
                <tr>
                    <td>
                        <h2 class="textcenter" style="margin: 40px">Vous avez déjà un compte</h2>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="user-login">Nom d'utilisateur ou adresse e-mail</label><br />
                        <input type="text" id="user-login" name="user-login" onblur="check_user_login();" value="<?php if (isset($_POST["user-login"])) echo $_POST["user-login"]; ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="error-msg invisible" id="user-login-error-msg">Champ invalide</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label for="password">Mot de passe</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="password" id="password" name="password" onblur="check_password();">
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="error-msg invisible" id="password-error-msg">Champ invalide</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input class="buttonls" type="submit" value="Se connecter">
                    </td>
                </tr>
                <tr>
                    
                    <td>
                        <h2 class="textcenter">Vous n'avez pas encore de compte</h2>
                        <a href="<?= $PATH ?>/signup.php"><input class="buttonls" type="button" value="S'inscrire"></a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="error-msg <?php if (!isset($_SESSION["LOGIN_error_msg"])) echo "invisible"; ?>" id="general-error-msg"><?php
                            if (isset($_SESSION["LOGIN_error_msg"])) {
                                echo $_SESSION["LOGIN_error_msg"];
                                unset($_SESSION["LOGIN_error_msg"]);
                            } else {
                                echo "Tout va bien !";
                            }
                        ?></span>
                    </td>
                </tr>
            </form>
        </table>

        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/login.js") ?>"></script>
    </body>
</html>
