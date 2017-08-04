<?php
	require_once "../core/init.php";
	$user = new User();
	if(!$user->isLoggedIn() || $user->data()->grupo != 2) { //Comercios
		Redirect::to("../index.php");
	}
	$comercio = new Comercio($user->data()->idcomercio);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Jony Food</title>
	<link rel="shortcut icon" href="../recursos/favicon.ico" type="image/x-icon">
	<link rel="icon" href="../recursos/favicon.ico" type="image/x-icon">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
	<link rel="stylesheet" href="../recursos/css/estilo.css">
</head>
<body ng-app="App">
	<header>
		<!-- Dropdown Structure -->
		<ul id="dropdowncarta" class="dropdown-content">
			<li><a href="#categorias">Categorías</a></li>
			<li class="divider"></li>
			<li><a href="#listas">Listas</a></li>
		</ul>

		<div class="navbar-fixed">
			<nav class="light-blue lighten-1">
				<div class="nav-wrapper">
					<a href="#" class="brand-logo left"><img class="responsive-img" width="32px" height="32px" style="margin: 8px 0px 0px 8px;" src="../recursos/imgs/iconjf.png"> <?php echo($comercio->getComercioData()->nombre); ?><input type="hidden" id="idUser" value="<?php echo($user->data()->idcomercio) ?>"></a>
					<a href="#" data-activates="mobile-demo" class="button-collapse right" data-sidenav="left" data-menuwidth="300" data-closeonclick="true"><i class="material-icons">menu</i></a>
					<ul class="right hide-on-med-and-down">
						<li><a class="dropdown-button" data-activates="dropdowncarta" dropdown href="javascript:void(0);">Carta <i class="material-icons right">arrow_drop_down</i></a></li>
						<li><a href="#"><i class="material-icons left">message</i> Contacto</a></li>
						<!-- <li><a href="../logout.php"><i class="material-icons left">message</i> Perfil</a></li> -->
						<li><a href="#"><i class="material-icons left">message</i> Perfil</a></li>
					</ul>
					<ul class="side-nav" id="mobile-demo">
						<li><a href="#categorias"><i class="material-icons left">message</i> Categorías</a></li>
						<li><a href="#listas"><i class="material-icons left">message</i> Listas</a></li>
						<li><a href="#"><i class="material-icons left">message</i> Contacto</a></li>
						<li><a href="#"><i class="material-icons left">message</i> Perfil</a></li>
					</ul>
				</div>
			</nav>
		</div>
	</header>
	<br><br>

	<main><div ng-view></div></main>

	<footer class="page-footer light-blue lighten-2">
		<div class="footer-copyright">
			<div class="container">
				© 2017 Jony Food
				<a class="grey-text text-lighten-4 right" href="#!">Acerca de...</a>
			</div>
		</div>
	</footer>

	<!-- Compiled and minified JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!-- AngularJS -->  
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
	<script src="../recursos/js/libs/angular-route.min.js"></script>
	<!-- Materialize -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
	<!-- Angular Materialize -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/angular-materialize/0.2.2/angular-materialize.min.js"></script>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB16sGmIekuGIvYOfNoW9T44377IU2d2Es"></script>

	<!-- App -->
	<script src="js/app.js"></script>
	<script src="js/services.js"></script>
	<script src="js/controllers.js"></script>
	<script src="js/directives.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$(".button-collapse").sideNav();
			$(".dropdown-button").dropdown();
			$(".tooltipped").tooltip({delay: 50});
			$(".modal").modal({
				dismissible: true, // Modal can be dismissed by clicking outside of the modal
				opacity: .5, // Opacity of modal background
				inDuration: 300, // Transition in duration
				outDuration: 200, // Transition out duration
				startingTop: '4%', // Starting top style attribute
				endingTop: '10%', // Ending top style attribute
				ready: function(modal, trigger) { // Callback for Modal open. Modal and trigger parameters available.
					//alert("Ready");
					//console.log(modal, trigger);
				},
				complete: function() { /* alert('Closed'); */ } // Callback for Modal close
			});
		});
	</script>
</body>
</html>