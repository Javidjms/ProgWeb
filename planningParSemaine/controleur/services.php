<?php    	
	session_start(); //Demarrage de la session
	$logged = $_SESSION["logged"];
	$superlogged = $_SESSION['superlogged'];
	
	//Redirection si le DDE n'est plus connecté
	if(!$superlogged){
		header('Location: index.php');
	}
	
	// On établis la connection
	require_once('../modele/conf/connexion.php');
	
	
	$select1 = $connection->query("select login,nom,Table1.type,ifnull(sum(nbHeures),0) as nbHeures from enseignant cross join (SELECT type FROM `contenumodule` where not type='SCOL' group by type) Table1 left join contenumodule on(enseignant.login = contenumodule.enseignant and Table1.type =contenumodule.type) group by login,Table1.type order by nom,Table1.type");
	$select1->setFetchMode(PDO::FETCH_OBJ);
		
	
	require_once("../vue/services.php");
?>