<?php
require_once("./res/php/common.php");

get_user_info();

if (isset($USER_INFO)) {
    if (isset($_GET['remove'])) { //supp un produit de la wishlist
        $rm_sql = "DELETE FROM `Wishlist` WHERE `Wishlist`.`product` = '{$_GET['remove']}' AND `Wishlist`.`user` = '{$USER_INFO['id']}'";
        $rm_res = mysqli_query($conn, $rm_sql); //supprime correspondant de la wishlist
        if ($rm_res == false) {
            $_SESSION['WISHLIST_error_msg'] = "Le produit n'a pas pu être retiré de votre liste d'envies";
        }
    } elseif (isset($_GET['add'])) { //ajouter produit a la wishlist
        $add_sql = "INSERT INTO `Wishlist` (`Wishlist`.`product`, `Wishlist`.`user`) VALUES ('{$_GET['add']}', '{$USER_INFO['id']}')";
        $add_res = mysqli_query($conn, $add_sql); //ajoute produit dans wishlist user co
        if ($add_res == false) {
            $_SESSION['WISHLIST_error_msg'] = "Le produit n'a pas pu être ajouté à votre liste d'envies";
        }
    }

    $sql = "SELECT `Wishlist`.*, `Products`.* FROM `Wishlist` JOIN `Products` ON `Wishlist`.`product` = `Products`.`id` WHERE `Wishlist`.`user` = '{$USER_INFO['id']}'";
    $res = mysqli_query($conn, $sql); //recupere tous les produits de la wishlist 
    $PRODUCTS = array();
    if ($res != false) {
        while ($row = mysqli_fetch_array($res)){
            $product = $row;
            $pic_sql = "SELECT `Pictures`.`id` AS `picture_id`, `Pictures`.`path` AS `picture_path` FROM `Pictures` WHERE `Pictures`.`set` = '{$row['picture_set']}'";
            $pic_res = mysqli_query($conn, $pic_sql); //pour chaque produit recupere image associé 
            $pictures = array();
            if ($pic_res != false)
                while ($pic_row = mysqli_fetch_array($pic_res))
                    array_push($pictures, $pic_row);
            $product["pictures"] = $pictures;
            array_push($PRODUCTS, $product);
        }
    } else {
        redirect("$PATH/");
    }
} else {
    redirect("$PATH/");
}

?>


<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <title>Ma liste d'envies — <?= $WEBSITE_NAME ?></title>
    <?php load("res/templates/head.php"); ?>
    <link rel="stylesheet" type="text/css" href="<?= res("res/css/wishlist.css") ?>" />
</head>
<body>
    <?php load("res/templates/header.php"); ?>
        <div class="pagebanner">
            <span class="title">
                Ma liste d'envie
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
                            <div class="article-remove" style="background-image: url('<?= res('res/img/icons/trash.fill.svg') ?>');" onclick="event.preventDefault(); window.location.href = '<?= $PATH ?>/wishlist.php?remove=<?= $PRODUCT['id'] ?>';"></div>
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
                            <div class="article-to-cart">
                                <button class="buttonls"> Ajouter au panier </button>
                            </div>
                        </div>
                    </a>
                    <?php
                    }
                    ?>
                </div>
            <?php
            } else {
            ?>
                <h4>Aucun article dans votre liste d'envie :(</h4>
            <?php
            }
        }
        ?>


    <?php load("res/templates/footer.php"); ?>
    <script type="text/javascript" src="<?= res("res/js/wishlist.js") ?>"></script>
</body>
</html>
