<?php
require_once("./res/php/common.php");

if (isset($_GET["id"])) {
    $id = mysqli_escape_string($conn, $_GET["id"]);

    $sql = "SELECT `Categories`.*, `Pictures`.`path` as `picture_path` FROM `Categories` JOIN `Pictures` ON `Categories`.`picture` = `Pictures`.`id` WHERE `Categories`.`id` = $id;";
    $res = mysqli_query($conn, $sql);

    if ($res != false) {
        $CATEGORY = mysqli_fetch_array($res);
        if (!$CATEGORY) {
            redirect("$PATH/");
        }
    } else {
        redirect("$PATH/");
    }
} else {
    redirect("$PATH/");
}

get_user_info();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <?php load("res/templates/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="<?= res("res/css/category.css") ?>" />
    </head>
    <body>
        <?php load("res/templates/header.php"); ?>

        <div><?= $CATEGORY["name"] ?></div>
        <div><img src="<?= picture($CATEGORY["picture_path"]) ?>"></div>

        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/category.js") ?>"></script>
            
</body>
</html>
