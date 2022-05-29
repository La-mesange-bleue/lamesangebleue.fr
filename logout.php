<?php
require_once("./res/php/common.php");

session_destroy();
redirect(
    isset($_GET["redirect"])
    ? urldecode($_GET["redirect"])
    : "$PATH/"
);
?>
