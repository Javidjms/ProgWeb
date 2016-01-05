<?php

function get_enseignants()
{
global $connection;
$req = $connection->query('SELECT login from enseignant');
$req->setFetchMode(PDO::FETCH_OBJ);
$enseignants = $req->fetchAll();
return $enseignants;
}
