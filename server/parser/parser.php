<?php

// Récupépration des données retournées par le site jeuxdemots
function getDataFromJDM($string) {
	// Si la chaine contient plusieurs mot, les espaces blancs sont remplacé par le caractère "+" dans le lien.
	// Les caractères avec des accents sont remplacé par les caractères URL
	$stringRequest = str_replace("%2B", "+", rawurlencode(utf8_decode(rawurldecode(urlencode($string)))));

	
	// Récupépration des données retournées par le site jeuxdemots
	$content = curl_get_contents("http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher&gotermrel=".$stringRequest."&charset=utf-8");

	// $content = file_get_contents("http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher&gotermrel=".$stringRequest."&charset=utf-8");
	
	if ($content === FALSE) {
		echo "une erreur est survenu, veuillez réessayer !";
		return false;
	}

	return $content;
}

// Retourne la définition du mot
function getDefinitions($content) {
	return preg_replace('<br />', "def", utf8_encode(explode("</def>", explode("<def>", $content)[1])[0]), 1);
}

// Retourne la liste des entrés et des relations (entrantes et sortantes)
function getEntriesAndRelations($content) {
	$arrayEntriesAndRelations = array();
	preg_match_all("/(e;[0-9]+;\'[\w\'áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ.>:?! -]+\';[0-9]+;[0-9]+)|(r;[0-9]+;[0-9]+;[0-9]+;[0-9]+;[0-9]+)/", $content, $arrayEntriesAndRelations);

	return $arrayEntriesAndRelations[0];
}

// Récupere l'ID du mot rechercher
function getIDWord($firstEntree) {
	return explode(";", $firstEntree)[1];
}

function curl_get_contents($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function parse($string, $content) {
	$arrayEntriesAndRelations = getEntriesAndRelations($content);
	if (empty($arrayEntriesAndRelations)) {
		echo "Le terme '".$string."' est inexistant ou un problème est survenu lors de la récupération des données";
		return false;
	}
	$definition = getDefinitions($content);

	echo $definition;

	echo "<br><br>";

	foreach ($arrayEntriesAndRelations as $value) {
		echo utf8_encode($value."<br>");
	}
}