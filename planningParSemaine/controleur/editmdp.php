
	<?php
	session_start();
	$logged = $_SESSION["logged"];
	// Redirection si pas connectés
	if(!$logged){
		header('location:index.php');
	}
		
	$enseignant = $_SESSION['login'];
	
	if (isset($_POST['oldpassword']) && isset($_POST['password1']) && isset($_POST['password2'])){
	
		if ($_POST['oldpassword']!='' && $_POST['password1']!='' && $_POST['password2']!=''){

	require_once('../modele/conf/connexion.php');
	// On envois la requète
	$select = $connection->prepare("SELECT login,pwd FROM enseignant WHERE login= :enseignant  ");
	$select->execute(Array('enseignant'=>$enseignant));

	$select->setFetchMode(PDO::FETCH_OBJ);
	$enregistrement = $select->fetch();
		
		if( ($_POST['oldpassword'])==$enregistrement->pwd && ($_POST['password1'])==($_POST['password2']) ){
			
			$maj = $connection->prepare("UPDATE enseignant SET pwd=:pwd WHERE login=:enseignant");
			$maj->execute(Array('pwd'=>$_POST['password1'],'enseignant'=>$enseignant));

			header('location:index.php');
		}
		else{
				echo '<div class="alert alert-warning" role="alert">
							<strong>Erreur</strong> Ancien ou Nouveau Mot de passe incorrect.
							</div>';		}
}
		else if($_SERVER['REQUEST_METHOD'] === 'POST'){
			echo '<div class="alert alert-warning" role="alert">
								<strong>Erreur</strong> Mauvaise saisie.
								</div>';
		}
	}
	require_once('../vue/editmdp.php');
	?>
