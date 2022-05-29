<?php
setlocale(LC_MONETARY, "fr_FR");

// joindre tables :
// $sql = "select p.nom as prodnom, p.prix as prodprix, m.nom marquenom from produits p join marques m on p.marque = m.ID";
// $sql = "select produits.nom as nom_produit, produits.prix, produits.capacite, produits.frequence, marque.nom as nom_marque from produits inner join marque on produits.marque = marque.ID";

function str_length($str) {
    return strlen($str);
} //retourne longueur d'une string 

function format_str($str, $variables = []) {
    foreach ($variables as $variable => $value) $str = str_replace("%$variable%", $value, $str);
    return $str;
} //remplace des variables dans une chaine de caractere

function format_price($price) {
    return number_format($price, 2, ",", " ");
} //mettre 2 chiffres apres virgule 

function pluralize($singular, $plural, $var) {
    return ($var > 1) ? $plural : $singular;
} //mettre au pluriel ou singulier des mots, si sup a 1 pluriel 

function remove_prefix($str, $prefix) {
    while (substr($str, 0, str_length($prefix)) === $prefix) $str = substr($str, str_length($prefix));
    return $str;
} //

function remove_suffix($str, $suffix) {
    while (substr($str, str_length($str) - str_length($suffix)) === $suffix) $str = substr($str, 0, str_length($str) - str_length($suffix));
    return $str;
} //


$DEBUG = true;
$tmp_path = $_SERVER["CONTEXT_PREFIX"];
$ROOT = remove_suffix($_SERVER["CONTEXT_DOCUMENT_ROOT"], $tmp_path); // FOR ME: "/var/www";
// if the line above doesn't work, set it manually to your server root path
// ex: $ROOT = "C:/wamp64";
$PATH = remove_suffix($tmp_path, "/"); // FOR ME: "/ppe";
// if the line above doesn't work, set it manually to website path from server root
// ex: $PATH = "/website";
$WEBSITE_NAME = "La mésange bleue";


function read($path) {
    global $ROOT, $PATH;
    return file_get_contents("$ROOT$PATH/$path");
} //lire le contenu fichier

function redirect($path) {
    header("Location: $path");
    exit();
} //rediriger vers une autre page 

function res($path, $disable_cache = true) {
    global $DEBUG, $PATH;
    return ($DEBUG && $disable_cache) ? "$PATH/$path?v=" . time() : "$PATH/$path";
} //charger une ressource (css ou javascript)

function load($path) {
    global $ROOT, $PATH;
    include_once("$ROOT$PATH/$path");
} //charger fichier php

function picture($path) {
    global $PATH;
    $PIC_PATH = "data/pictures";
    return "$PATH/$PIC_PATH/$path";
} //recuperer chemin image 

function get_user_info() {
    global $conn, $USER_INFO;
    if (isset($_SESSION["user_id"])) {
        $id = $_SESSION["user_id"];
        $sql = "SELECT `Users`.*, `Pictures`.`id` AS `profile_picture_id`, `Pictures`.`path` AS `profile_picture_path` FROM `Users` JOIN `Pictures` ON `Users`.`profile_picture` = `Pictures`.`id` WHERE `Users`.`id` = $id;";
        
        $res = mysqli_query($conn, $sql);
        if ($res != false)
            $USER_INFO = mysqli_fetch_array($res);
        else
            unset($USER_INFO);
    }
} //recuperer les infos d'un utilisateur connecté 

function random_code($str_length = 8, $chars = "0123456789abcdefghijklmnopqrstuvwxyz") {
    $chars_str_length = strlen($chars);
    $code = "";
    for ($i = 0; $i < $str_length; $i++) $code .= $chars[rand(0, $chars_str_length - 1)];
    return $code;
} //genere un code aléatoire

$conn = mysqli_connect("localhost", "www", read("res/top_secret/db_password"), "ppe"); //cree connexion server mysql
$conn->query("SET CHARACTER SET utf8"); //passer en jeu de caractere utf8 (gere caractere speciaux)
session_start(); //demarrer session php (variables persistantes d'une page à l'autre)

?>
