<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
     <link rel="icon" href="../vue/images/favicon.ico">

    <title>Affectation des modules</title>

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
                <li><a href="services.php">Voir le service des enseignant</a></li>
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
		  <form class="navbar-form navbar-right" method="post" action="./affectermodules.php">
            <select id="module" name="module" onchange="this.form.submit();">
					<?php
					foreach($Tabmodules as $cle => $valeur){
						if($module ==$valeur->module)
							echo "<option selected='selected' value=".$valeur->module.">".$valeur->libelle."</option>";
						else
							echo "<option value=".$valeur->module.">".$valeur->libelle."</option>";
					}
					?>
			</select>
          </form>
        </div>
      </div>
    </nav>
	
	<div class="container-fluid">
      <div class="row">     
		<div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr> 
				  <th>Responsable du Module</th>
				  <th>Affectation</th>
                </tr>
              </thead>
              <tbody>
				<?php				
				 $enregistrement = $select1->fetch();
					echo "<tr>";						
					if($enregistrement->responsable!=NULL)
						echo "<td id='oldresponsable'>".$enregistrement->responsable."</td>";
					else
						echo "<td id='oldresponsable'>NULL</td>";
					echo "<td><form  method='post'><select id='responsable' class='selector' name='enseignant'>";
					foreach($Tabenseignants as $cle => $valeur){
						echo "<option value=".$valeur->login.">".$valeur->login."</option>";
					}
					echo "</select></form></td>";
					echo "</tr>";
					
				?>
              </tbody>
			  <thead>
                <tr> 
				  <th>Partie</th>
				  <th>Responsable du cours</th>
				  <th>Affectation</th>
                </tr>
              </thead>
			  <tbody>
				<?php				
				 while($enregistrement = $select2->fetch()){
					echo "<tr>";			
					echo "<td>".$enregistrement->partie."</td>";
					if($enregistrement->enseignant!=NULL)
						echo "<td id=''>".$enregistrement->enseignant."</td>";
					else
						echo "<td>NULL</td>";
					echo "<td><form  method='post'><select id='".$enregistrement->partie."' class='partieselector' name='enseignant'>";
					foreach($Tabenseignants as $cle => $valeur){
						echo "<option value=".$valeur->login.">".$valeur->login."</option>";
					}
					echo "</select></form></td>";
					echo "</tr>";
					}
				?>
              </tbody>
            </table>
          </div>
        </div>
    </div>
	<center><input id="valider" class="btn btn-lg btn-default" type="button" name="valider" value="Valider"/></center>
	
	
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../vue/js/bootstrap.min.js"></script>
	<script src="../vue/js/bootbox.min.js"></script>
	<script>
	var module = document.getElementById('module').value;
	var responsablemodule = document.getElementById('oldresponsable').innerHTML;
	var responsablepartie = {"responsablepartie":[]};
	
	responsablepartie["module"]={"module":module};
	responsablepartie["responsable"]={"responsable":responsablemodule};
	var responsableselector = document.getElementById("responsable");

	responsableselector.addEventListener("change", function() {
		responsablemodule =responsableselector.value;
		responsablepartie["responsable"]={"responsable":responsablemodule};
	});
	
	var responsablepartieselector = document.querySelectorAll('.partieselector');
		[].forEach.call(responsablepartieselector, function(partieselector) {
			partieselector.addEventListener("change", function() {
			addEnseignant(partieselector.id,partieselector.value);
			});
		});
		
	function addEnseignant(partie,enseignant){
		if(responsablepartie["responsablepartie"].length!=0){
		
				for(x in responsablepartie["responsablepartie"]){
					var course = responsablepartie["responsablepartie"][x];
					if(course["partie"]==partie){
							course["enseignant"]=enseignant;
							return 0;
						}	
				}
				
			}			
			responsablepartie["responsablepartie"].push(
					{"partie":partie, "enseignant":enseignant }
				);
		
	}
	
	document.getElementById("valider").addEventListener('click', valider, false);
				
		function valider(e) {
			//console.log("time to commit");
			//send Asynchronous HTTP request to server to commit changes 
			
			$.ajax({
				url : "updateenseignant.php",
				type : 'POST',
				data : responsablepartie,
				dataType : 'json', // On désire recevoir du HTML
				success : function(JsonResponse, statut){ 
					console.log("Success");
					 responsablepartie["responsablepartie"]=[];	
					 bootbox.alert("Modification effectue avec succes - Raffraichissement de la page dans 8 secondes", function(){});
					  setTimeout(function(){document.location.href="affectermodules.php"; }, 8000);
				}
			});
		}

	
	</script>
</body>


</html>
