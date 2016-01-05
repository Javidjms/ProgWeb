<?php 
	//Reception et Traitement de la requete Asynchrone pour les modifications du plannings par la validation
	require_once('../modele/conf/connexion.php');
	
	$module = $_POST["module"]["module"];
	$responsable = $_POST["responsable"]["responsable"];
	
	$update1 = $connection->prepare("UPDATE module set responsable =:responsable where module=:module");
	$update1->execute(Array('module'=>$module, 'responsable'=>$responsable));
	
	foreach( $_POST["responsablepartie"] as $cle =>$valeur){
		$arr[$cle]=$valeur;
		$partie = $valeur["partie"];
		$enseignant = $valeur["enseignant"];
		$update2 = $connection->prepare("UPDATE contenumodule set enseignant =:enseignant where partie=:partie and module=:module");
		$update2->execute(Array('module'=>$module, 'enseignant'=>$enseignant,'partie'=>$partie));
	}
	
		
	
	
	echo json_encode($arr);

	
?>
