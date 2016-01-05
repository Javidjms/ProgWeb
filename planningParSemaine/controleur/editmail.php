
	<?php
	session_start();
	$superlogged=$_SESSION["superlogged"];
	$logged = $_SESSION["logged"];
	// Redirection si pas connecté
	if(!$logged){
		header('location:index.php');
	}
		
	$enseignant = $_SESSION['login'];
	
	//Traitement du formulaire POST avec les champs email1 et email2 compléte
	if (isset($_POST['email1']) && isset($_POST['email2'])){
		//Cas où les emails complétés sont identiques
		if(($_POST['email1'])==($_POST['email2']) ){
			
			$xml=simplexml_load_file("../modele/responsables.xml") or die("Error: Cannot create object");	
			
			if($superlogged){
				$result1 = $xml->xpath("//personne[directeurdesetudes='".$enseignant."']");
			}
			else{
				$result1 = $xml->xpath("//personne[responsable='".$enseignant."']");
			}
			$result1[0]->adressemail = $_POST['email1'];
			$xml->asXML('../modele/responsables.xml');
			header('location:index.php');
		}
		//Cas où les emails complétés sont différents
		else{
				echo '<div class="alert alert-warning" role="alert">
							<strong>Erreur</strong> Adresse Mail différentes.
				
				</div>';		
		}
	}
	//Traitement du formulaire POST avec les champs email1 et email2 non compléte
	else if($_SERVER['REQUEST_METHOD'] === 'POST'){
			echo '<div class="alert alert-warning" role="alert">
								<strong>Erreur</strong> Mauvaise saisie.
								</div>';
		
	}
	require_once('../vue/editmail.php');
	?>
