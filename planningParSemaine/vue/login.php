<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	<link rel="icon" href="../vue/images/favicon.ico">

    <title>Connexion</title>
	
	<!-- Bootstrap core CSS -->
    <link href="../vue/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../vue/css/dashboard.css" rel="stylesheet">
	
	<link href="../vue/css/signin.css" rel="stylesheet">
	
	</head>

  <body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
			<a class="navbar-brand" href="#">PlanningParSemaine</a>
		</div>
	  </div>
	 </nav>

    <div class="container">

      <form class="form-signin" method="POST" action="./login.php">
        <center><h2 class="form-signin-heading">Connexion</h2></center>
        <label for="inputLogin" class="sr-only">Login</label>
        <input type="text" name="login" id="inputLogin" maxlength="15" class="form-control" placeholder="Login" required="" autofocus="">
        <label for="inputPassword" class="sr-only">Mot de Passe</label>
        <input type="password" name="pwd" id="inputPassword"  class="form-control" placeholder="Mot de Passe" required="">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="checkbox" value="remember-me"> Maintenir la connexion
          </label>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Se connecter
		</button>
		<button class="btn btn-lg btn-default btn-block" onclick="document.location.href='index.php'" >Retour</button>
      </form>

    </div> 

	</body>

</html>