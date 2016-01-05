<?php    	
	
	session_start(); //Demarrage de la session	
	if(isset($_COOKIE["login"])&&isset($_COOKIE["nom"])&&isset($_COOKIE["prenom"])){
		$_SESSION["login"]=$_COOKIE["login"];
		$_SESSION["nom"]=$_COOKIE["nom"];
		$_SESSION["prenom"]=$_COOKIE["prenom"];
		$_SESSION['logged']=true;
		if(isset($_COOKIE["superlogged"])){
			$_SESSION["superlogged"]=$_COOKIE["superlogged"];
		}
		else{
			$_SESSION["promo"]=$_COOKIE["promo"];
		}
	}
	else if(!isset($_SESSION['logged'])){
		$_SESSION['logged']=false;
		$_SESSION['superlogged']=false;
	}
	$superlogged = $_SESSION['superlogged'];
	$logged = $_SESSION['logged'];// variable pour savoir si un utilisateur est connecté
	$edit=false; // variable pour savoir si l'utilisateur connecté peut editer son planning (par defaut : false)
	
	// On recupere toutes les promos
	require_once('../modele/get_promo.php');
	$Tabpromo =get_promo();
	//Promo selectionné par le responsable d'année   (par defaut : TC)
	if(!isset($_POST["promo"]))
		$promo = 'TC';
	else
		$promo = $_POST["promo"];
	
	if($superlogged)
		$edit = true;
	else if($logged){
		$edit = ($promo == $_SESSION['promo']);
	}

	// On envois les requètes 
	
	require_once('../modele/query.php');
	
	$tabSemaine=Array();
	
// Nous traitons les résultats en boucle
while( $enregistrement = $select->fetch() )
{
  $tabSemaine[$enregistrement->semaine]=Array("semaine"=>$enregistrement->semaine,"heuresAffectees"=>$enregistrement->heuresAffectees,"nombreHeuresMax"=>$enregistrement->nombreHeuresMax); 
}
	
	require_once("../vue/index.php");
	?>