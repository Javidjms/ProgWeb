<?php

//Fonction qui retourne toutes les promos
function get_promo()
{
	$xml=simplexml_load_file("../modele/responsables.xml") or die("Error: Cannot create object");	//Chargement du fichier XML
	$result = $xml->xpath('//promo'); // Requete XPATH
	foreach($result as $value){
		$promo[] = (String) $value;
	}
	return $promo;
}
