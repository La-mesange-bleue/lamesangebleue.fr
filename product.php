<?php
require_once("./res/php/common.php");

if (isset($_GET['ref']) && str_length($_GET['ref']) > 0) {
    $ref = $_GET['ref'];
    $sql = "SELECT `Products`.`name`, `Products`.`price`, `Products`.`description`, EXTRACT(YEAR FROM `Products`.`release_date`) AS `release_year`, `Products`.`picture_set` FROM `Products` WHERE `Products`.`reference` = '$ref' AND `Products`.`is_valid` = '1'";
    $res = mysqli_query($conn, $sql);
    if ($res != false) {
        $row = mysqli_fetch_array($res);
        if ($row) {
            $PRODUCT = $row;
            $pic_sql = "SELECT `Pictures`.`id` AS `picture_id`, `Pictures`.`path` AS `picture_path` FROM `Pictures` WHERE `Pictures`.`set` = '{$row['picture_set']}'";
            $pic_res = mysqli_query($conn, $pic_sql);
            $pictures = array();
            if ($pic_res != false)
                while ($pic_row = mysqli_fetch_array($pic_res))
                    array_push($pictures, $pic_row);
            $PRODUCT['pictures'] = $pictures;
        }
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
    <link rel="stylesheet" type="text/css" href="<?= res("res/css/product.css") ?>" />
</head>

<body>
    <?php load("res/templates/header.php"); ?>

    <div class="article">
        <div class="article-name"><?= $PRODUCT['name'] ?></div>
        <div class="article-release-year"><?= $PRODUCT['release_year'] ?></div>
        <div class="article-picture-viewer"><?php
                $is_first = true;
                foreach ($PRODUCT['pictures'] as $picture) {
                ?>
                <div class="article-picture <?= (!$is_first) ? 'hidden' : '' ?>" style="background-image: url('<?= picture($picture['picture_path']) ?>');"></div>
                <?php
                $is_first = false;
            }
            ?>
        </div>
        <div class="row">
            <div class="article-price"><?= number_format($PRODUCT['price'], 2, ',', ' ') ?> €</div>
            <div class="article-add-to-cart-button"><button class="add-to-cart buttonls">Ajouter au panier</button></div>
        </div>
        <div class="article-description"><?= $PRODUCT['description'] ?></div>
    </div>

    <?php load("res/templates/footer.php"); ?>
    <script type="text/javascript" src="<?= res("res/js/product.js") ?>"></script>
</body>

</html>
