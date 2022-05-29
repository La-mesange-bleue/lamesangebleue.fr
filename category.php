<?php
require_once("./res/php/common.php");

if (isset($_GET["id"])) {
    $id = mysqli_escape_string($conn, $_GET["id"]);

    $sql = "SELECT `Categories`.*, `Pictures`.`path` as `picture_path` FROM `Categories` JOIN `Pictures` ON `Categories`.`picture` = `Pictures`.`id` WHERE `Categories`.`id` = $id;";
    $res = mysqli_query($conn, $sql);

    if ($res != false) {
        $CATEGORY = mysqli_fetch_array($res);
        $sqlproduits = "SELECT `Products`.* FROM `Products` WHERE `Products`.`is_valid` = '1' AND `Products`.`category` = '$id'";
        $resproduits = mysqli_query($conn, $sqlproduits); 
        $PRODUCTS = array();
        if ($resproduits != false){
            while ($row = mysqli_fetch_array($resproduits)) {
                $product = $row;
                $pic_sql = "SELECT `Pictures`.`id` AS `picture_id`, `Pictures`.`path` AS `picture_path` FROM `Pictures` WHERE `Pictures`.`set` = '{$row['picture_set']}'";
                $pic_res = mysqli_query($conn, $pic_sql);
                $pictures = array();
                if ($pic_res != false)
                    while ($pic_row = mysqli_fetch_array($pic_res))
                        array_push($pictures, $pic_row);
                $product["pictures"] = $pictures;
                array_push($PRODUCTS, $product);
            }  
        }
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

        <div class="pagebanner">
            <div style="background-image: url('<?= picture($CATEGORY["picture_path"]) ?>');" class="background-image">
            </div>
            <span class="title">
            <?= $CATEGORY["name"] ?>
            </span>
        </div>
        <?php
        if (isset($PRODUCTS)) {
            $c = count($PRODUCTS);
            if ($c > 0) {
                ?>

                <div id="article-list">
                    <?php
                    foreach ($PRODUCTS as $PRODUCT) {
                    ?>
                    <a class="no-link" href="<?= $PATH ?>/product.php?ref=<?= $PRODUCT["reference"] ?>">
                        <div class="article">
                            <div class="article-picture-viewer"><?php
                                $is_first = true;
                                foreach ($PRODUCT["pictures"] as $picture) {
                                ?>
                                <div class="article-picture <?= (! $is_first)? "hidden": "" ?>" style="background-image: url('<?= picture($picture["picture_path"]) ?>');"></div>
                                <?php
                                $is_first = false;
                                }
                            ?></div>
                            <div class="article-name"><?= $PRODUCT["name"] ?></div>
                            <div class="article-price"><?= number_format($PRODUCT["price"], 2, ',', ' ') ?> €</div>
                        </div>
                    </a>
                    <?php
                    }
                    ?>
                </div>
            <?php
            } else {
            ?>
                <h4>Aucun article dans cette catégorie</h4>
            <?php
            }
        }
        ?>
        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/category.js") ?>"></script>
            
</body>
</html>
