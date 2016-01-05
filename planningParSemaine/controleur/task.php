<?php 
	//Reception et Traitement de la requete Asynchrone pour les modifications du plannings par le commit
	require_once('../modele/conf/connexion.php');
	
	foreach( $_POST["newcourses"] as $cle =>$valeur){
		$arr[$cle]=$valeur;
		$week = $valeur["week"];
		$module = $valeur["module"];
		$partie = $valeur["partie"];
		$hours = $valeur["hours"];
		$insert = $connection->prepare("INSERT INTO `affectationsemaine` VALUES (:module,:partie,:semaine,:hours,NULL) ON DUPLICATE KEY UPDATE nbHeures = (nbHeures + :hours)");
		$insert->execute(Array('module'=>$module, 'partie'=>$partie, 'semaine'=>$week ,'hours'=>$hours  ));
	}
	
	if(($delete = $connection->query("DELETE FROM `affectationsemaine` where nbHeures = 0 "))==null) exit(1);

	
	
	
	
	echo json_encode($arr);

	
?>
