<?php
require_once("./res/php/common.php");

// code PHP

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

            <br />
                <div class="block-home">
                    <div id="blockpromo">
                    <h1 style="text-align: center">PROMOTIONS</h1>
                    </div>
                    
                    <div class="article">
                    <h1 style="text-align: center">Article 1</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 2</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 3</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 4</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 5</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 6</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 7</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 8</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 9</h1>
                    </div>

                    <div class="article">
                    <h1 style="text-align: center">Article 10</h1>
                    
                   
                    </div>
                </div>
        <?php load("res/templates/footer.php"); ?>
        <script type="text/javascript" src="<?= res("res/js/index.js") ?>"></script>
            
</body>
</html>
