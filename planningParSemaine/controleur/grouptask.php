<?php 
	require_once('../modele/conf/connexion.php');
	
	//Reception et Traitement de la requete asynchrone pour savoir si un module de groupe (COMMUN) est libre sur la semaine week pour toutes les promos de ce groupe (EX : RESIMREII)
	
	$grouped_promo =Array();
	$authorization = true;
	
	//Recuperation de la demande 
	foreach( $_POST["groupedcourses"] as $cle =>$valeur){
		$week = $valeur["week"];
		$module = $valeur["module"];
		$hours = $valeur["hours"];
	}
	
	//Recuperation des promos du groupe
	$xml=simplexml_load_file("responsables.xml") or die("Error: Cannot create object");	
	$result = $xml->xpath("//promo");
	foreach($result as $promo) {
		$select = $connection->prepare("SELECT public from module where (module =:module and public like :promo)");
		$select->execute(Array('module'=>$module, 'promo'=>'%'.$promo.'%'));
		if($select->fetch()){
			array_push($grouped_promo,$promo);
		}
		
	}
	
	//Verification si le module a deplacer est libre dans la semaine $week pour toutes les promos de $grouped_promo
	if($grouped_promo!=null){
		foreach($grouped_promo as $promo) {
			$select = $connection->prepare("select semaine.semaine, ifnull(sum(nbHeures),0) AS `heuresAffectees`,public, nombreHeuresMax  from affectationsemaine join module on(affectationsemaine.module = module.module and (public like :public or public ='Tous'))  right join semaine on(affectationsemaine.semaine = semaine.semaine) where semaine.semaine =:week  group by semaine.semaine");
			$select->execute(Array('week'=>$week,'public'=>'%'.$promo.'%'));
			$select->setFetchMode(PDO::FETCH_OBJ);
			$enregistrement =$select->fetch();
			$heuresAffectees = $enregistrement->heuresAffectees;
			$nombreHeuresMax = $enregistrement->nombreHeuresMax;
			if($nombreHeuresMax - $heuresAffectees < $hours){
				$authorization = false;
				break;
			}
			$authorization = true;
			
		}
	
	}
	else{
		$authorization = false;
	}
	
	//Envoie de l'autorisation 
	$arr = array('authorization' => $authorization);
	echo json_encode($arr);	

	
?>
