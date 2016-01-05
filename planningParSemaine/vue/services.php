<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
     <link rel="icon" href="../vue/images/favicon.ico">

    <title>Services des Enseignants</title>

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
				echo $_SESSION['nom']." ".$_SESSION['prenom'];
			?>
			</a></li>
		  </ul>
          <ul class="nav navbar-nav navbar-right">
			<li><a>Directeur des Etudes</a></li>
			<li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Actions<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="affectermodules.php">Affecter des modules</a></li>
              </ul>
            </li>
		   <li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Mon Profil<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="editmdp.php">Editer mon MDP</a></li>
                <li><a href="editmail.php">Editer mon @</a></li>
                <li class="divider"></li>
                <li><a href="logout.php">Se deconnecter</a></li>
              </ul>
            </li>
			<li><a href='index.php'>Voir Planning</a></li>
          </ul>
        </div>
      </div>
    </nav>
	
	<div class="container-fluid">
      <div class="row">     
		<div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr> 
				  <th>Nom
				  <th>Heures CM</th>
				  <th>Heures Projet</th>
				  <th>Heures TD</th>
				  <th>Heures TP</th>
                </tr>
              </thead>
			  <tbody>
				<?php
				 while($enregistrement = $select1->fetch()){
					$i=0;
					echo "<tr>";			
					echo "<td>".$enregistrement->nom."</td>";
					do{
						echo "<td>".$enregistrement->nbHeures."</td>";
						$i++;
						
						if($i<4){
							$enregistrement = $select1->fetch();
						}
					}
					while($i<4);
					echo "</tr>";
					}
				?>
              </tbody>
            </table>
          </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../vue/js/bootstrap.min.js"></script>
	<script src="../vue/js/bootbox.min.js"></script>
</body>


</html>