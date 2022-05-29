<?php
require_once("./res/php/common.php");

if (isset($_GET["q"]) && str_length($_GET["q"]) > 0) {
    $order_arr = [
        "default" => ["`Products`.`id` ASC", "Afficher d'abord :"],
        "asc-price" => ["`Products`.`price` ASC", "le moins cher"],
        "desc-price" => ["`Products`.`price` DESC", "le plus cher"],
        "asc-rel-date" => ["`Products`.`release_date` ASC", "le premier sorti"],
        "desc-rel-date" => ["`Products`.`release_date` DESC", "le dernier sorti"],
        "asc-sale-date" => ["`Products`.`sale_date` ASC", "le premier mis en vente"],
        "desc-sale-date" => ["`Products`.`sale_date` DESC", "le dernier mis en vente"]
    ];
    if (isset($_GET["order-by"]) && array_key_exists(mysqli_escape_string($conn, $_GET["order-by"]), $order_arr))
        $order_by = $order_arr[mysqli_escape_string($conn, $_GET["order-by"])][0];
    else
        $order_by = $order_arr["default"][0];
    
    $SEARCH_QUERY = mysqli_escape_string($conn, $_GET["q"]);
    $SEARCH_QUERY = trim($SEARCH_QUERY);
    $words = explode(" ", $SEARCH_QUERY);
    $filters_arr = [
    ];
    foreach ($words as $word)
        if (str_length($word) > 0)
            array_push($filters_arr, "(`Products`.`name` LIKE '%$word%' OR `Products`.`description` LIKE '%$word%')");
    $filters = join(" AND ", $filters_arr);

    // add `Categories`.`picture` to $sql?
    $sql = "SELECT `Products`.*, `Categories`.`name` AS `category_name` FROM `Products` JOIN `Categories` ON `Products`.`category` = `Categories`.`id` WHERE " . (($filters) ? "($filters) AND " : "") . "`Products`.`is_valid` = 1 ORDER BY $order_by;";
    $res = mysqli_query($conn, $sql);
    if ($res != false) {
        $PRODUCTS = array();
        while ($row = mysqli_fetch_array($res)) {
            $product = $row;
            $pic_sql = "SELECT `Pictures`.`id` AS `picture_id`, `Pictures`.`path` AS `picture_path` FROM `Pictures` WHERE `Pictures`.`set` = " . $row["picture_set"] . ";";
            $pic_res = mysqli_query($conn, $pic_sql);
            $pictures = array();
            if ($pic_res != false)
                while ($pic_row = mysqli_fetch_array($pic_res))
                    array_push($pictures, $pic_row);
            $product["pictures"] = $pictures;
            array_push($PRODUCTS, $product);
        }
    } else {
        $_SESSION["SEARCH_error_msg"] = "La connexion au serveur a échoué";
    }
} else {
    redirect("$PATH/");
}

get_user_info();
?>

<!DOCTYPE html>
<html lang="fr-FR">
    <head>
        <title>Recherche : <?= $SEARCH_QUERY ?> — <?= $WEBSITE_NAME ?></title>
        <?php load("res/templates/head.php"); ?>
        <link rel="stylesheet" type="text/css" href="<?= res("res/css/search.css") ?>" />
    </head>
    <body>
        <?php load("res/templates/header.php"); ?>

        <?php
        if (isset($PRODUCTS)) {
            $c = count($PRODUCTS);
            if ($c > 0) {
                ?>
                <h4><?= $c ?> <?= pluralize("article", "articles", $c) ?> <?= pluralize("trouvé", "trouvés", $c) ?></h4>
                <select id="order-by" oninput="order_by();">
                    <?php
                    foreach ($order_arr as $k => $v) {
                    ?>
                        <option value="<?= $k ?>" <?php
                        if (isset($_GET["order-by"]) && $_GET["order-by"] == $k) echo "selected";
                        ?>><?= $v[1] ?></option>
                    <?php
                    }
                    ?>
                </select>

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
                <h4>Aucun résultat</h4>
            <?php
            }
        }
        ?>

        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/search.js") ?>"></script>
    </body>
</html>
