<?php
session_start(); //Demarrage de la session
	$logged = $_SESSION["logged"];
	$superlogged = $_SESSION['superlogged'];
	
	
	// On recupere toutes les promos
	require_once('../modele/get_promo.php');
	$Tabpromo =get_promo();
	
	if(isset($_POST["promo"])&&isset($_POST["format"])){
		$format = $_POST["format"];
		$promo = $_POST["promo"];
		if($format =="CSV"){
			require_once("exportcsv.php");
		}
		
	
	
	}
	else if($_SERVER['REQUEST_METHOD'] === 'POST') {
	  echo '<div class="alert alert-warning" role="alert">
							<strong>Erreur</strong> Mauvaise manipulation.
							</div>';
	}
	else{
		require_once("../vue/export.php");
	}



	?>