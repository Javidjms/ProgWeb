<?php
// On appelle la session
session_start();

// On écrase le tableau de session
$_SESSION = array();

// On détruit la session
session_destroy();

//Supression des cookies

setcookie('login', null, -1, '/');
setcookie('nom', null, -1, '/');
setcookie('prenom', null, -1, '/');
setcookie('promo', null, -1, '/');
setcookie('superlogged', null, -1, '/');

unset($_COOKIE["superlogged"]);
unset($_COOKIE["promo"]);
unset($_COOKIE["nom"]);
unset($_COOKIE["prenom"]);
unset($_COOKIE["promo"]);

header('Location: index.php');
?>
