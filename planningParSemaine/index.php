<!DOCTYPE html>
<html lang="en">

  <head>
    <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
     <link rel="icon" href="vue/images/favicon.ico">

    <title>Planning par semaines</title>

    <!-- Bootstrap core CSS -->
    <link href="vue/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="vue/css/dashboard.css" rel="stylesheet">
	
	<style>
	
	body {
		background-color:#E0FFFF;
	
	}
	
	</style>
	
	</head>
	
	<body>
	
	
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
			<a class="navbar-brand" href="#">Projet Programmation Web </a>
		</div>
		 <div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right">
				<li><a> Cree par D.Fromont,S.Kalwant,R.Ben Khala et J.Mougamadou </a></li>
			</ul>
		</div>	
	  </div>
	 </nav>
	<center>
	<h2 style="font-size:120px; line-height: 1; letter-spacing: -2px;">Planning Par Semaine</h2>
	<input id="connectbutton" class="btn btn-lg btn-primary" style="font-size:50px" type="button" name="commit" value="Se connecter" onclick="document.location.href='controleur/login.php'"/> 
	<input id="planningviewbutton" style="font-size:50px" class="btn btn-lg btn-primary" type="button" name="commit" value="Voir le planning" onclick="document.location.href='controleur/index.php'"/> 
	</center>
	
	</body>
	
</html>