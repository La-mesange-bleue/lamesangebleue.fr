<?php
require_once("./res/php/common.php");

session_destroy(); //supprime session
redirect(
    isset($_GET["redirect"])
    ? urldecode($_GET["redirect"]) //redirige vers autre page 
    : "$PATH/"
);
?>
