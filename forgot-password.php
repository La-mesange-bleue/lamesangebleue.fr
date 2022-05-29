<?php
require_once("./res/php/common.php");

/*
status codes ($STATUS):
-1 = default (defined only to avoid exceptions)
 0 = asking for user login
 1 = link sent by mail
 2 = link clicked from mail, asking for new password
 3 = password updated
*/

$STATUS = -1;
if (isset($_GET["reset-code"])) { // if code is passed to the page, verify it
    $code = strtolower(mysqli_escape_string($conn, $_GET["reset-code"])); //recupere code de reinitialisation du mdp passé a la page 
    $sql = "SELECT `ResetCodes`.`id`, `ResetCodes`.`user` FROM `ResetCodes` WHERE `ResetCodes`.`code` = '$code';";
    $res = mysqli_query($conn, $sql); //verifie en bdd si le code existe
    if ($res != false) {
        $row = mysqli_fetch_array($res);
        if ($row) {
            $user_id = $row["user"];
            if (isset($_POST["new-password"])) { //si saisi nouv mdp
                $new_password = mysqli_escape_string($conn, $POST["new-password"]);
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT); //hacher mdp
                $sql = "UPDATE `Users` SET `Users`.`password` = '$new_password_hash' WHERE `Users`.`id` = $user_id;";
                $res = mysqli_query($conn, $sql); //maj en bdd
                if ($res == true) {
                    $STATUS = 3;
                } else {
                    $_SESSION["FORGOT_PASSWORD_error_msg"] = "La réinitialisation du mot de passe a échoué";
                }
            } else {
                $STATUS = 2;
            }
        } else {
            $_SESSION["FORGOT_PASSWORD_error_msg"] = "Lien invalide";
        }
    } else {
        $_SESSION["FORGOT_PASSWORD_error_msg"] = "La connexion au serveur a échoué";
    }
} elseif (isset($_POST["email-address"]) && isset($_POST["user-name"])) { // if user has submitted their email address and username, generate a new code, store it in DB and send it by mail to user
    $email_address = mysqli_escape_string($conn, $_POST["email-address"]); //recupere @ saisi
    $user_name = mysqli_escape_string($conn, $_POST["user-name"]); //recupere username saisi
    $sql = "SELECT `Users`.`id`, `Users`.`first_name` FROM `Users` WHERE `Users`.`user_name` = '$user_name' AND `Users`.`email_address` = '$email_address';";
    $res = mysqli_query($conn, $sql); //requete verifie s'il y a un user qui corresspond
    if ($res != false) {
        $row = mysqli_fetch_array($res);
        if ($row) {
            $unique = false;
            while (! $unique) {
                $code = random_code(16);
                $check_sql = "SELECT COUNT(*) FROM `ResetCodes` WHERE `ResetCodes`.`code` = '$code';";
                $check_res = mysqli_query($conn, $check_sql);
                if ($check_res != false) {
                    $check_row = mysqli_fetch_array($check_res);
                    if ($check_row && $check_row[0] == 0) $unique = true;
                }
            } //49 58 generer code unique aleatoire
            $sql2 = "DELETE FROM `ResetCodes` WHERE `ResetCodes`.`user` = {$row['id']};"; //supprmie les anciens codes
            $sql3 = "INSERT INTO `ResetCodes` (`id`, `code`, `user`) VALUES (NULL, '$code', {$row['id']});"; //insere nouv code 
            $res2 = mysqli_query($conn, $sql2);
            $res3 = mysqli_query($conn, $sql3);
            if ($res3 == true) {
                require_once "./res/php/mail.php";
                send_mail(
                    $to = $email_address,
                    $subject = "Réinitialisation de votre mot de passe",
                    $body = format_str(read("res/templates/mail/reset-password"), [
                        "firstName" => $row["first_name"],
                        "code" => strtoupper($code),
                        "pageUrl" => "http://{$_SERVER['HTTP_HOST']}$PATH/forgot-password.php"
                    ])
                ); // 64 73 envoie le lien de reinitialisation du mdp par mail
                $STATUS = 1;
            } else {
                $_SESSION["FORGOT_PASSWORD_error_msg"] = "La création du lien de réinitialisation de mot de passe a échoué";
            }
        } else {
            $_SESSION["FORGOT_PASSWORD_error_msg"] = "Compte introuvable";
        }
    } else {
        $_SESSION["FORGOT_PASSWORD_error_msg"] = "La connexion au serveur a échoué";
    }
} else {
    $STATUS = 0;
}

get_user_info();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Réinitialisez votre mot de passe — <?= $WEBSITE_NAME ?></title>
        <?php load("res/templates/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="<?= res("res/css/forgot-password.css") ?>" />
    </head>
    <body>
        <?php load("res/templates/header.php"); ?>

        <?php
        if ($STATUS == 3) {
        ?>
            <h3>Votre mot de passe a été réinitialisé. <a href="login.php">Reconnectez-vous.</a></h3>
        <?php
        } elseif ($STATUS == 1) {
        ?>
            <h3>Un lien de réinitialisation de votre mot de passe vous a été envoyé à l'adresse <?= $email_address ?>. Il peut mettre quelques instants à parvenir jusqu'à votre boîte mail. Vérifiez également vos mails indésirables.</h3>
        <?php
        } elseif ($STATUS == 2) {
        ?>
        <table>
            <form id="reset-form" action="<?= $PATH ?>/forgot-password.php<?= (isset($_GET['reset-code'])? "?reset-code={$_GET['reset_code']}": '') ?>" method="post" onsubmit="check_reset_form();">
                <tr>
                    <td><label for="new-password">Nouveau mot de passe</label></td>
                </tr>
                <tr>
                    <td><input type="password" name="new-password" id="new-password" onblur="check_password();"></td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="new-password-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><label for="confirm-new-password">Confirmez le mot de passe</label></td>
                </tr>
                <tr>
                    <td><input type="password" name="confirm-new-password" id="confirm-new-password" onblur="check_confirm_password();"></td>
                </tr>
                <tr>
                    <td><span class="error-msg invisible" id="confirm-new-password-error-msg">Champ invalide</span></td>
                </tr>
                <tr>
                    <td><input type="submit" value="Valider"></td>
                </tr>
                <tr>
                    <td><span class="error-msg <?php if (! isset($_SESSION["FORGOT_PASSWORD_error_msg"])) echo "invisible"; ?>" id="general-error-msg"><?php
                        if (isset($_SESSION["FORGOT_PASSWORD_error_msg"])) {
                            echo $_SESSION["FORGOT_PASSWORD_error_msg"];
                            unset($_SESSION["FORGOT_PASSWORD_error_msg"]);
                        } else {
                            echo "Tout va bien !";
                        }
                    ?></span></td>
                </tr>
            </form>
        </table>
        <?php
        } else {
        ?>
            <table>
                <form id="send-form" action="<?= $PATH ?>/forgot-password.php" method="post" onsubmit="check_send_form();">
                    <tr>
                        <td><label for="user-name">Nom d'utilisateur</label></td>
                    </tr>
                    <tr>
                        <td><input type="text" id="user-name" name="user-name" onblur="check_user_name();" value="<?php if (isset($_POST["user-name"])) echo $_POST["user-name"]; ?>"></td>
                    </tr>
                    <tr>
                        <td><span class="error-msg invisible" id="user-name-error-msg">Champ invalide</span></td>
                    </tr>
                    <tr>
                        <td><label for="email-address">Adresse e-mail</label></td>
                    </tr>
                    <tr>
                        <td><input type="text" name="email-address" id="email-address" onblur="check_email_address();" value="<?php if (isset($_POST["email-address"])) echo $_POST["email-address"]; ?>"></td>
                    </tr>
                    <tr>
                        <td><span class="error-msg invisible" id="email-address-error-msg">Champ invalide</span></td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Envoyer"></td>
                    </tr>
                    <tr>
                        <td><span class="error-msg <?php if (! isset($_SESSION["FORGOT_PASSWORD_error_msg"])) echo "invisible"; ?>" id="general-error-msg"><?php
                        if (isset($_SESSION["FORGOT_PASSWORD_error_msg"])) {
                            echo $_SESSION["FORGOT_PASSWORD_error_msg"];
                            unset($_SESSION["FORGOT_PASSWORD_error_msg"]);
                        } else {
                            echo "Tout va bien !";
                        }
                        ?></span></td>
                    </tr>
                </form>
            </table>
        <?php
        }
        ?>
        
        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/forgot-password.js") ?>"></script>
    </body>
</html>
