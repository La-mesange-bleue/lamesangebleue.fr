<?php
require_once("./res/php/common.php");

/*
status codes ($STATUS):
-1 = default (defined only to avoid exceptions)
 0 = code sent by mail
 1 = account activated
 2 = account was already active
*/

$STATUS = -1;
if (isset($_GET["activation-code"])) { // if code is passed to the page, verify it
    $code = strtolower(mysqli_escape_string($conn, $_GET["activation-code"]));
    $sql = "SELECT `ActivationCodes`.`id`, `ActivationCodes`.`user` FROM `ActivationCodes` WHERE `ActivationCodes`.`code` = '$code';";
    $res = mysqli_query($conn, $sql);
    if ($res != false) {
        $row = mysqli_fetch_array($res);
        if ($row) {
            $user_id = $row["user"];
            $update_sql = "UPDATE `Users` SET `Users`.`is_active` = 1 WHERE `Users`.`id` = $user_id;";
            $delete_sql = "DELETE FROM `ActivationCodes` WHERE `ActivationCodes`.`user` = $user_id;";
            $update_res = mysqli_query($conn, $update_sql);
            $delete_sql = mysqli_query($conn, $delete_sql);
            if ($update_res == true) {
                $STATUS = 1;
            } else {
                $_SESSION["CONFIRM_EMAIL_error_msg"] = "L'activation du compte a échoué";
            }
        } else {
            $_SESSION["CONFIRM_EMAIL_error_msg"] = "Code incorrect";
        }
    } else {
        $_SESSION["CONFIRM_EMAIL_error_msg"] = "La connexion au serveur a échoué";
    }
} elseif (isset($_SESSION["CONFIRM_EMAIL_login"])) { // if user comes from login or signup page, generate a new code, store it in DB and send it by mail to user
    $login = $_SESSION["CONFIRM_EMAIL_login"];
    unset($_SESSION["CONFIRM_EMAIL_login"]);
    $sql = "SELECT `Users`.`id`, `Users`.`first_name`, `Users`.`email_address`, `Users`.`is_active` FROM `Users` WHERE `Users`.`user_name` = '$login' OR `Users`.`email_address` = '$login';";
    $res = mysqli_query($conn, $sql);
    if ($res != false) {
        $row = mysqli_fetch_array($res);
        if (!$row["is_active"]) {
            $unique = false;
            while (!$unique) {
                $code = random_code(8);
                $check_sql = "SELECT COUNT(*) FROM `ActivationCodes` WHERE `ActivationCodes`.`code` = '$code';";
                $check_res = mysqli_query($conn, $check_sql);
                if ($check_res != false) {
                    $check_row = mysqli_fetch_array($check_res);
                    if ($check_row && $check_row[0] == 0) $unique = true;
                }
            }
            $sql2 = "DELETE FROM `ActivationCodes` WHERE `ActivationCodes`.`user` = " . $row["id"] . ";";
            $sql3 = "INSERT INTO `ActivationCodes` (`id`, `code`, `user`) VALUES (NULL, '$code', " . $row["id"] . ");";
            $res2 = mysqli_query($conn, $sql2);
            $res3 = mysqli_query($conn, $sql3);
            if ($res3 == true) {
                require_once("./res/php/mail.php");
                send_mail(
                    $to = $row["email_address"],
                    $subject = "Code de confirmation de votre compte",
                    $body = format_str(read("res/templates/mail/confirm-email"), [
                        "firstName" => $row["first_name"],
                        "code" => strtoupper($code),
                        "pageUrl" => "http://" . $_SERVER["HTTP_HOST"] . "$PATH/confirm-email.php"
                    ])
                );
                $STATUS = 0;
            } else {
                $_SESSION["CONFIRM_EMAIL_error_msg"] = "La création du code de confirmation a échoué";
            }
        } else {
            $STATUS = 2;
        }
    } else {
        $_SESSION["CONFIRM_EMAIL_error_msg"] = "La connexion au serveur a échoué";
    }
}

get_user_info();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Confirmez votre adresse e-mail — <?= $WEBSITE_NAME ?></title>
        <?php load("res/templates/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="<?= res("res/css/confirm-email.css") ?>" />
    </head>
    <body>
        <?php load("res/templates/header.php"); ?>

        <?php
        if ($STATUS == 1) {
        ?>
            <h3>Votre compte a été activé !</h3>
        <?php
        } elseif ($STATUS == 2) {
        ?>
            <h3>Votre compte est déjà actif !</h3>
        <?php
        } else {
            if ($STATUS == 0) {
            ?>
                <h3>Un code de confirmation vient de vous être envoyé à l'adresse <?= $row["email_address"] ?>. Il peut mettre quelques instants à parvenir jusqu'à votre boîte mail. Vérifiez également vos mails indésirables.</h3>
            <?php
            }
            ?>
            <table>
                <form action="<?= $PATH ?>/confirm-email.php" method="GET" onsubmit="return check_form();">
                    <tr>
                        <td><label for="activation-code">Code de confirmation</label></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="text" id="activation-code" name="activation-code" oninput="format_activation_code();" onblur="check_activation_code();">
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Envoyer"></td>
                    </tr>
                    <tr>
                        <td>
                            <span class="error-msg <?php if (!isset($_SESSION["CONFIRM_EMAIL_error_msg"])) echo "invisible"; ?>" id="general-error-msg"><?php
                                if (isset($_SESSION["CONFIRM_EMAIL_error_msg"])) {
                                    echo $_SESSION["CONFIRM_EMAIL_error_msg"];
                                    unset($_SESSION["CONFIRM_EMAIL_error_msg"]);
                                } else {
                                    echo "Tout va bien !";
                                }
                            ?></span>
                        </td>
                    </tr>
                </form>
            </table>
        <?php
        }
        ?>

        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/confirm-email.js") ?>"></script>
    </body>
</html>
