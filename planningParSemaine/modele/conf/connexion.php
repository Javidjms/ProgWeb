<?php
try{
  $dns = 'mysql:host=127.0.0.1;dbname=PLANNINGS';
  $utilisateur = 'planningDb';
  $motDePasse = 'PlanningRobot';
  $connection = new PDO( $dns, $utilisateur, $motDePasse );
} catch ( Exception $e ) {
        echo "Connection à MySQL impossible : ", $e->getMessage();
        die();
}
?>

