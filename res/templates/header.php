<?php
global $USER_INFO;
?>

<header role="header">
    <nav id="menu" role="navigation">
        <div id="inner">
            <div id="m-left">
                <a href="<?= $PATH ?>/"><img id="logo" src="<?= res("res/img/icons/mesangebleue.png") ?>" alt="photo"></a>
                <div class="dropdown">
                    <img id="products" src="<?= res("res/img/icons/menu.svg") ?>" alt="photo">
                    <span id="produits">Produits</span>
                    <div class="dropdown-child">
                        <a id="promotions" href="<?= $PATH ?>/"><img src="<?= res("res/img/icons/promotions.svg") ?>" alt="photo">Promotions</a>
                        <a id="book" href="<?= $PATH ?>/category.php?id=1"><img src="<?= res("res/img/icons/book.svg") ?>" alt="photo">Livre</a>
                        <a id="ebook" href="<?= $PATH ?>/category.php?id=3"><img src="<?= res("res/img/icons/ebook.svg") ?>" alt="photo">Ebook</a>
                        <a id="music" href="<?= $PATH ?>/category.php?id=4"><img src="<?= res("res/img/icons/musique.svg") ?>" alt="photo">Musique</a>
                        <a id="film" href="<?= $PATH ?>/category.php?id=2"><img src="<?= res("res/img/icons/film.svg") ?>" alt="photo">Film</a>
                        <a id="ticketing" href="<?= $PATH ?>/category.php?id=5"><img src="<?= res("res/img/icons/billetterie.svg") ?>" alt="photo">Billetterie</a>
                    </div>
                </div>
            </div>
            <div id="m-middle">
                <form id="search-form" action="<?= $PATH ?>/search.php" method="GET">
                    <label hidden for="site-search"></label>
                    <input type="search" id="site-search" name="q" placeholder="Ici tapez ce que vous recherchez..." value="<?= (isset($_GET["q"])) ? $_GET["q"] : "" ?>">
                    <button type="submit" id="searchbutton"><img src="<?= res("res/img/icons/magnifying.svg") ?>" alt="photo"></button>
                </form>
            </div>
            <div id="m-right">
                <a id="connexioninscription" href="<?= $PATH ?>/login.php"><img src="<?=
                (isset($USER_INFO)) // if user is logged in,
                    ? picture($USER_INFO["profile_picture_path"]) // show their profile picture
                    : res("res/img/icons/man.svg") // else, show default user icon
                ?>" alt="photo"></a>
                <a id="heart" href="<?= $PATH ?>/wishlist.php"><img src="<?= res("res/img/icons/heart.svg") ?>" alt="photo"></a>
                <a id="cart" href="#"><img src="<?= res("res/img/icons/cart.svg") ?>" alt="photo"></a>
            </div>
        </div>
        <div>

        </div>
    </nav>
    <div class="secondbar">Vous trouverez tout ce dont vous avez besoin, c'est certain !</div>
</header>
