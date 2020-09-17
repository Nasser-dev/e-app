<?php

header('Access-Control-Allow-Origin: *');
require_once('parser/parser.php');

// Récupération de la chaine saisi par l'utilisateur
$string = $_GET['word'];

// Appel à la méthode permettant de récupérer les données de jeuxdemots
$content = getDataFromJDM($string);

// Si les données récupérer ne sont pas valide, on quitte
if ($content === false) {
    return;
}

// Sinon, on appel la méthode qui permet de parser les données
parse($string, $content);