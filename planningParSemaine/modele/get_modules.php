<?php

function get_modules()
{
global $connection;
$req = $connection->query('SELECT module,libelle from module');
$req->setFetchMode(PDO::FETCH_OBJ);
$modules = $req->fetchAll();
return $modules;
}
