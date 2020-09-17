<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
require_once('parser/parser.php');

// Récupération de la chaine saisi par l'utilisateur
$string = $_GET['word'];
$flag = "";

// Appel à la méthode permettant de récupérer les données puis le cache
$content = getDataFromCache($string);

// Si la récupération depuis le cache a échoué, on quitte
if ($content === "GET_DATA_ERROR") {
    return;
}

// Si le cache n'existe pas, on le récupere depuis jeuxdemots
if ($content === "FILE_NOT_FOUND") {
    // Appel à la méthode permettant de récupérer les données de jeuxdemots
    $content = getDataFromJDM($string);
    
    // Si la récupération depuis jeuxdemots a échoué, on quitte
    if ($content === "GET_DATA_ERROR") {
        return;
    }

    $flag = "DATA_GET_FROM_JDM";
} else {
    $flag = "DATA_GET_FROM_CACHE";
}

// Sinon, on appel la méthode qui permet de parser les données
$response = parse($string, $content);

if ($flag === "DATA_GET_FROM_JDM" && $response !== "DATA_IS_EMPTY") {
    createNewCache($string, $response);
}