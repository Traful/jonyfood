<?php
	require_once "../core/init.php";
	$user = new User();
	if(!$user->isLoggedIn() || $user->data()->grupo != 1) { //Administracion
		Redirect::to("../index.php");
	}
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
</head>
<body ng-app="App">
	<a href="../logout.php">LogOut</a>
	<main><div ng-view></div></main>
	<!-- Compiled and minified JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!-- AngularJS -->  
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.1/angular.min.js"></script>
	<script src="../recursos/js/libs/angular-route.min.js"></script>
	<!-- App -->
	<script src="js/app.js"></script>
	<script src="js/services.js"></script>
	<script src="js/controllers.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		});
	</script>
</body>
</html>