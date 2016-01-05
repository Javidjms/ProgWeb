<?php    	
	session_start(); //Demarrage de la session
	$logged = $_SESSION["logged"];
	$superlogged = $_SESSION['superlogged'];
	
	//Redirection si le Responsable d'année n'est plus connecté
	if(!$logged){
		header('Location: index.php');
	}
	
	// On établis la connection
	require_once('../modele/conf/connexion.php');
	

	// On envois les requètes
	
	//Requete 1 pour le label des semaines
if(($select = $connection->query("SELECT semaine FROM semaine"))==null) exit(1);

	//Requete 2 pour la charge des enseignants
if(($select2 = $connection->query("select nom,prenom,semaine.semaine,ifnull(nbHeures,0) as nbHeures from enseignant cross join semaine left join serviceenseignantparsemaine on (enseignant.login = serviceenseignantparsemaine.enseignant and serviceenseignantparsemaine.semaine = semaine.semaine) order by nom,semaine.semaine"))==null) exit(1);


// On indique que nous utiliserons les résultats en tant qu'objet
$select->setFetchMode(PDO::FETCH_OBJ);
$select2->setFetchMode(PDO::FETCH_OBJ);
$tabSemaine=Array();
// Nous traitons les résultats en boucle
while( $enregistrement = $select->fetch() )
{
  $tabSemaine[$enregistrement->semaine]=$enregistrement->semaine; 
}
	
	require_once("../vue/charges.php");
	?>
