<?php
	session_start(); //démarrer la session

	require_once('../modele/conf/connexion.php');
	
	//On vérifie si les informations on été postées
		
	if ((isset($_POST)) && (!empty ($_POST['login'])) && (!empty($_POST['pwd']))) {
			$login = $_POST['login'];
			$pwd = $_POST['pwd'];
		
		$select = $connection->prepare('SELECT login, nom , prenom, pwd FROM enseignant where login =:login');
		$select->execute(Array('login'=>$login));
		$select->setFetchMode(PDO::FETCH_OBJ);
		$enregistrement = $select->fetch();
		
		 //On cherche le mot de passe pour le login 
		$xml=simplexml_load_file("../modele/responsables.xml") or die("Error: Cannot create object");	
		
		// On verifie si c'est un DDE
		$resultdde = $xml->xpath('//directeurdesetudes');
		if($resultdde[0] == $login){
			$pwd2 = $enregistrement->pwd;
			if ($pwd == $pwd2){
				$_SESSION['superlogged']=true;
				$_SESSION['logged']=true;
				$_SESSION['login']=$login;
				$_SESSION['nom'] = $enregistrement->nom;
				$_SESSION['prenom'] = $enregistrement->prenom;
				if(isset($_POST["checkbox"])){
						setcookie("superlogged",true, time() + (86400 * 30*62), "/"); // 86400 = 1 day
						setcookie("login",$login, time() + (86400 * 30*62), "/"); // 86400 = 1 day
						setcookie("nom",$enregistrement->nom, time() + (86400 * 30*62), "/"); // 86400 = 1 day
						setcookie("prenom",$enregistrement->prenom, time() + (86400 * 30*62), "/"); // 86400 = 1 day
					}
					header('Location: index.php');
			}
			else {
					echo '<div class="alert alert-warning" role="alert">
							<strong>Erreur</strong> Mot de passe non reconnu.
							</div>';
				}
		}
		
		else {
		$result1 = $xml->xpath('//responsable');
		
		foreach($result1 as $key=>$responsable){		
			if($responsable == $login){//on vérifie si le login du responsable existe				
				$pwd2 = $enregistrement->pwd;
				
				if ($pwd == $pwd2) {  //On vérifie que c'est bien le mot de passe
						$result2 = $xml->xpath("//personne[responsable='".$login."']/promo"); //on 		enregistre la filiere dans la session
						foreach($result2 as $promo) {
							$_SESSION['promo'] = (String) $promo;
						}
					$_SESSION['logged']=true;
					$_SESSION['login']=$login;
					$_SESSION['nom'] = $enregistrement->nom;
					$_SESSION['prenom'] = $enregistrement->prenom;
					if(isset($_POST["checkbox"])){
						setcookie("login",$login, time() + (86400 * 30*62), "/"); // 86400 = 1 day
						setcookie("nom",$enregistrement->nom, time() + (86400 * 30*62), "/"); // 86400 = 1 day
						setcookie("prenom",$enregistrement->prenom, time() + (86400 * 30*62), "/"); // 86400 = 1 day
						setcookie("promo",$promo, time() + (86400 * 30*62), "/"); // 86400 = 1 day
					}
					header('Location: index.php');
				}
				else {
					echo '<div class="alert alert-warning" role="alert">
							<strong>Erreur</strong> Mot de passe non reconnu.
							</div>';
				}
				break;
			}
			else if ($responsable === end($result1)){
			echo '<div class="alert alert-warning" role="alert">
							<strong>Erreur</strong> Utilisateur non reconnu.
							</div>';
			}
		}
		
	  }
	}	
	else if($_SERVER['REQUEST_METHOD'] === 'POST') {
	  echo '<div class="alert alert-warning" role="alert">
							<strong>Erreur</strong> Mauvaise saisie.
							</div>';
	}
	require_once("../vue/login.php");
    ?>
