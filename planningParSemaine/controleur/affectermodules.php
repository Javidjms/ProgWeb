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
	
	// On recupere toutes les modules
	require_once('../modele/get_modules.php');
	$Tabmodules =get_modules();
	// On recupere toutes les enseignants
	require_once('../modele/get_enseignants.php');
	$Tabenseignants =get_enseignants();
	
	if(!isset($_POST["module"]))
		$module = 'ALGOC1';
	else
		$module = $_POST["module"];
	// Requete 1 : Pour obtenir tous le responsable du module choisi	
	$select1 = $connection->prepare("select responsable from module where module=:module ");
	$select1->execute(Array('module'=>$module));
	$select1->setFetchMode(PDO::FETCH_OBJ);
	// Requete 2 : Pour obtenir tous les enseignants de tous les cours du module choisi
	$select2 = $connection->prepare("select partie,enseignant from contenumodule where module =:module");
	$select2->execute(Array('module'=>$module));
	$select2->setFetchMode(PDO::FETCH_OBJ);
	
	
	
	require_once("../vue/affectermodules.php");
?>