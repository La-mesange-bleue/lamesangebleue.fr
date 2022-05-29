<?php
require_once("./res/php/common.php");

$sql = "SELECT `Products`.* FROM `Products` WHERE `Products`.`is_valid` = '1' ORDER BY RAND() LIMIT 10"; 
$res = mysqli_query($conn, $sql); //requete recupere 10 produits aleatoires validés 
$PRODUCTS = array();
if ($res != false) {
    while ($row = mysqli_fetch_array($res)) {
        $product = $row;
        $pic_sql = "SELECT `Pictures`.`id` AS `picture_id`, `Pictures`.`path` AS `picture_path` FROM `Pictures` WHERE `Pictures`.`set` = '{$row['picture_set']}'";
        $pic_res = mysqli_query($conn, $pic_sql); //recupere img associé a chaque produit 
        $pictures = array();
        if ($pic_res != false)
            while ($pic_row = mysqli_fetch_array($pic_res))
                array_push($pictures, $pic_row);
        $product["pictures"] = $pictures;
        array_push($PRODUCTS, $product);
    }
}

get_user_info();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <?php load("res/templates/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="<?= res("res/css/index.css") ?>" />
    </head>
    <body>
        <?php load("res/templates/header.php"); ?>
                <div class="block-home">
                    <div id="promo-block" class="pagebanner">
                        <span class="title">PROMOTIONS !</span>
                        <span>Retrouvez certains de nos articles à des prix imbattables.</span>
                    </div>
                    <!--<div id="blockpromo">
                        <h1 style="text-align: center">PROMOTIONS</h1>
                    </div>-->
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
                </div>
        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/index.js") ?>"></script>
    </body>
</html>
