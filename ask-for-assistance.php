<?php
require_once("./res/php/common.php");

/*
status codes ($STATUS):
-1 = default (defined only to avoid exceptions)
 0 = request sent
*/

$STATUS = -1;
if (isset($_POST["questions"]) && isset($_POST["email-address"])) {
    $msg = $_POST["questions"];
    $email_address = $_POST["email-address"]; // recupere infos passés dans le formulaire 
    
    $sql = "SELECT `Users`.`first_name`, `Users`.`email_address` FROM `Users`  WHERE `Users`.`is_active` = 1 AND `Users`.`permission_level` >= 2 ORDER BY RAND() LIMIT 1";
    $res = mysqli_query($conn, $sql); //requete recupere un admin au hasard du site

    if ($res != false) {
        $row = mysqli_fetch_array($res);
        if ($row) {
            require_once("$ROOT$PATH/res/php/mail.php");
            send_mail( //envoie mail
                $to = $row["email_address"],
                $subject = "[ADMIN] Nouvelle demande d'assistance",
                $body = format_str(read("res/templates/mail/new-request-for-assistance"), [
                    "assistantFirstName" => $row["first_name"],
                    "userEmailAddress" => $email_address,
                    "from" => $mail->Username,
                    "date" => date("d/m/Y"), // day/month/year (European format)
                    "time" => date("H:i"), // hours:minutes
                    "message" => $msg
                ])
            );
            $STATUS = 0;
        } else {
            $_SESSION["ASK_FOR_ASSISTANCE_error_msg"] = "Aucun administrateur du site n'a été trouvé";
        }
    } else {
        $_SESSION["ASK_FOR_ASSISTANCE_error_msg"] = "La connexion au serveur a échoué";
    }
} else {
    redirect("$PATH/");
} 

get_user_info();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Demande d'assistance — <?= $WEBSITE_NAME ?></title>
        <?php load("res/templates/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="<?= res("res/css/ask-for-assistance.css") ?>" />
    </head>
    <body>
        <?php load("res/templates/header.php"); ?>

        <?php
        if ($STATUS == 0) {
        ?>
            <h3>Votre demande a été prise en compte</h3>
            <p>Nos conseillers mettront tout en œuvre pour vous répondre dans les plus brefs délais.</p>
        <?php
        } else {
        ?>
            <span class="error-msg <?php if (!isset($_SESSION["ASK_FOR_ASSISTANCE_error_msg"])) echo "invisible"; ?>" id="general-error-msg"><?php
                if (isset($_SESSION["ASK_FOR_ASSISTANCE_error_msg"])) {
                    echo $_SESSION["ASK_FOR_ASSISTANCE_error_msg"];
                    unset($_SESSION["ASK_FOR_ASSISTANCE_error_msg"]);
                } else {
                    echo "Tout va bien !";
                }
            ?></span>
        <?php
        }
        ?>

        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/ask-for-assistance.js") ?>"></script>
    </body>
</html>
