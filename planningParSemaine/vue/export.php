<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
     <link rel="icon" href="../vue/images/favicon.ico">

    <title>Export</title>

    <!-- Bootstrap core CSS -->
    <link href="../vue/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../vue/css/dashboard.css" rel="stylesheet">

  </head>

  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">PlanningParSemaine </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
		  <ul class="nav navbar-nav navbar-left">
			<li><a>
			<?php
			 if($logged){
				echo $_SESSION['nom']." ".$_SESSION['prenom'];
			}
			else{
				echo "Visiteur";
			}
			?>
			</a></li>
		  </ul>
          <ul class="nav navbar-nav navbar-right">
		  <?php
		   if($superlogged){
					echo "<li><a>Directeur des Etudes</a></li>";
					echo '
					<li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Actions<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="affectermodules.php">Affecter des modules</a></li>
                <li><a href="services.php">Voir le service des enseignant</a></li>
              </ul>
            </li>
				';
				}
			else if($logged){
				echo "<li><a>Responsable Annee des ".$_SESSION["promo"]."</a></li>";
			}
			if($logged){
			?>
		   <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Mon Profil<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="editmdp.php">Editer mon MDP</a></li>
                <li><a href="editmail.php">Editer mon @</a></li>
                <li class="divider"></li>
                <li><a href="logout.php">Se deconnecter</a></li>
              </ul>
            </li>
			<?php
			}
			?>
			<li><a href='index.php'>Voir Planning</a></li>
          </ul>
        </div>
      </div>
    </nav>
	<div class="container">
	<center>
    <form  method="post" action="./export.php">
        <h2>Choissisez la promo et le format sous lequel vous voulez exporter</h2>
		<select name="promo">
			<?php
				foreach($Tabpromo as $cle => $valeur){
					if($promo ==$valeur)
						echo "<option selected='selected'>".$valeur."</option>";
					else
						echo "<option>".$valeur."</option>";
					}
					?>
	</select>
	<select name="format">
			<option>CSV</option>
	</select>
	<button class="btn  btn-primary" type="submit">Exporter</button>
    </form>
	</center>
	</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../vue/js/bootstrap.min.js"></script>
  </body>
</html>
