<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../vue/images/favicon.ico">

    <title>Planning par semaines</title>

    <!-- Bootstrap core CSS -->
    <link href="../vue/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../vue/css/dashboard.css" rel="stylesheet">

	<style>
		div.course{
			height:10px;
			width:5px;
			background:red;
			margin:2px;
			display:inline-block;
		}
		.quantity{
			
		}
		div.week{
			height:50px;
			width:50px;
		}
		div.week.over {
			border: 2px dashed #000;
		}
	</style>

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
			else if ($logged){
				echo "<li><a>Responsable Annee des ".$_SESSION["promo"]."</a></li>";
			}
			if($logged){
				echo '
					<li class="dropdown">
              <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Mon Profil<span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="editmdp.php">Editer mon MDP</a></li>
                <li><a href="editmail.php">Editer mon @</a></li>
                <li class="divider"></li>
                <li><a href="logout.php">Se deconnecter</a></li>
              </ul>
            </li>
				';
				echo "<li><a href='charges.php'>Charges Prof</a></li>";
			}
			else{
				echo "<li><a href='login.php'>Se connecter</a></li>";
            }
            ?>
			<li><a href='export.php'>Exporter</a></li>
          </ul>
		  <?php
		  if($edit){
			echo '<input id="commitBtn" class="btn btn-lg btn-default" type="button" name="commit" value="Commit/Update"/> ';
		  }
		  ?>          
          <form class="navbar-form navbar-right" method="post" action="./index.php">
            <select name="promo" onchange="this.form.submit();">
					<?php
					foreach($Tabpromo as $cle => $valeur){
						if($promo ==$valeur)
							echo "<option selected='selected'>".$valeur."</option>";
						else
							echo "<option>".$valeur."</option>";
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
            <table class="table table-striped">
              <thead>
                <tr>                
                  <th>Module</th>
                  <th>Intervenant</th>
                  <th>Nature</th>
                  
                  <?php
					if($edit){
						echo "<th>Quantite</th>";
					}
					foreach($tabSemaine as  $key=>$value){
						echo "<th>".$value["semaine"]."</th>";	
					}
					?>
                </tr>
              </thead>              
              <tbody>
				<?php
				
				// Taux de Remplissage
				if($logged && $edit){
					echo "<tr><th>Taux de Remplissage</th><td/><td/><td/>";
				}
				else {
					echo "<tr><th>Taux de Remplissage</th><td/><td/>";
				}
				foreach($tabSemaine as  $key=>$value){
						$i=$value["semaine"];
						if($value["nombreHeuresMax"]!=0){
							$taux_remplissage=$value["heuresAffectees"]/$value["nombreHeuresMax"]*100;
						}
						else {
							$taux_remplissage = 0;
						}
						echo "<td class='TR' id='TR_".$i."'>".$taux_remplissage."%</td>";						
					}
					
					echo "</tr>";
					
				//	Heures Max 
				if($logged && $edit){
					echo "<tr><th>Heures Maximum</th><td/><td/><td/>";
				}
				else {
					echo "<tr><th>Heures Maximum</th><td/><td/>";
				}
				foreach($tabSemaine as  $key=>$value){
						$i=$value["semaine"];
						echo "<td class='HM' id='HM_".$i."'>".$value["nombreHeuresMax"]."h</td>";						
					}
					
					echo "</tr>";
				
				// Heures Affectées
				
				if($logged && $edit){
					echo "<tr><th>Heures Affectees</th><td/><td/><td/>";
				}
				else {
					echo "<tr><th>Heures Affectees</th><td/><td/>";
				}
				foreach($tabSemaine as  $key=>$value){
						$i=$value["semaine"];
						echo "<td class='HA' id='HA_".$i."'>".$value["heuresAffectees"]."h</td>";						
					}
					
					echo "</tr>";
				
					
			     $finsemaine = end($tabSemaine);
				 $finsemaine =  $finsemaine["semaine"];
				 $once = false;
				 $c1=1;
				 $c2=1;
				 while( $enregistrement = $select2->fetch() )
					{
					echo "<tr>";
					if(!$once){
						$heuresRestantes = $enregistrement->heuresRestantes;
						$libelle=$enregistrement->libelle;
						$r1=$enregistrement->r1;
						$nom=$enregistrement->nom;
						$r2=$enregistrement->r2;
						$partie=$enregistrement->partie;
						$module=$enregistrement->module;
						$enseignant=$enregistrement->enseignant;						
						if($enregistrement->groupe ==1){
							$groupe = "GROUPE";
						}
						else{
							$groupe = "NORMAL";
						}
						
						if($nom ==Null){
							$nom ="#N/A";
							$enseignant = "NULL";
						}
						
						if($r2 ==0){
							$r2=1;
						}
						if($c1==1){
							 echo "<td rowspan='".$r1."'>".$libelle."</td>";
						}
						if($c2==1){
							 echo "<td rowspan='".$r2."'>".$nom."</td>";
						}
						echo "<td class=\"type\">".$partie."</td>";
						if($edit){
							echo "<td id=\"".$module."_".$enseignant."_".$partie."\" class=\"quantity\">";
						
							for($j=1;$j<=$heuresRestantes/2;$j++){
									echo "<div class=\"course\" id=\"".$module."_".$enseignant."_".$partie."_"."item".$j."_".$groupe."\" draggable=\"true\"></div>";
								}
							if($heuresRestantes %2 ==1){
								echo "<div class=\"course\" id=\"".$module."_".$enseignant."_".$partie."_"."half".$j."_".$groupe."\" draggable=\"true\" style=\"background-color:blue\"></div>";
								$j++;
							}
							echo "</td>";
						}
						else{
							$j=0;
						}
						$once=true;
						}
						
					do{
						$semaine=$enregistrement->semaine;
						$nbHeures=$enregistrement->nbHeures;
						echo "<td> <div class='week' id='".$module."_".$enseignant."_".$partie."_".$semaine."'>";
						if($nbHeures != Null){
							for($k=1;$k<=$nbHeures/2;$k++){
								$j++;
								echo "<div class=\"course\" id=\"".$module."_".$enseignant."_".$partie."_"."item".$j."_".$groupe."\" draggable=\"true\"></div>";
							}
							if($nbHeures %2 ==1){
								echo "<div class=\"course\" id=\"".$module."_".$enseignant."_".$partie."_"."half".$j."_".$groupe."\" draggable=\"true\" style=\"background-color:blue\"></div>";
								$j++;
							}
						}
						echo "</div></td>";
						if($semaine < $finsemaine)
							$enregistrement = $select2->fetch();
					}
					
					while($semaine<$finsemaine);
					$once = false;
					$c1++;
					$c2++;
					if($c1>$r1){
						$c1=1;
					}
					if($c2>$r2){
						$c2=1;
					}
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
	<?php
	if($edit){
		require_once("../vue/edit.php");
	}
	?>
  </body>
</html>
