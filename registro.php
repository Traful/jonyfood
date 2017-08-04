<?php
	include_once("core/init.php");
	/*
	$errMsg = "";
	$user = new User();
	if($user->isLoggedIn()) {
		switch($user->data()->grupo) {
			case 1: //Administracion
				Redirect::to("admin/index.php");
				break;
			case 2: //Comercios
				Redirect::to("comercio/index.php");
				break;
			case 3: //Clientes
				$errMsg = true;
				$errMsg = "No se reconoce el tipo de usuario, consulte con el administrador del sistema.";
				break;
			default:
				$errMsg = true;
				$errMsg = "No se reconoce el tipo de usuario, consulte con el administrador del sistema.";
				break;
		}
	}
	$default = new stdClass();
	$default->username = "";
	$default->password = "";
	if(Input::exist()) {
		if(Token::check(Input::get("token"))) {
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				"username" => array(
					"required" => true,
					"msgerror" => "Ingrese el nombre de usuario"
				),
				"password" => array(
					"required" => true,
					"msgerror" => "Ingrese la contraseña"
				)
			));
			$default->username = Input::get("username");
			$default->password = Input::get("password");
			if($validation->passed()) {
				//$user = new User();
				$remember = (Input::get("remember") === "on") ? true : false;
				$login = $user->login(Input::get("username"), Input::get("password"), $remember);
				if($login) {
					switch($user->data()->grupo) {
						case 1: //Administracion
							Redirect::to("admin/index.php");
							break;
						case 2: //Comercios
							Redirect::to("comercio/index.php");
							break;
						case 3: //Clientes
							$errMsg = "No se reconoce el tipo de usuario, consulte con el administrador del sistema.";
							break;
						default:
							$errMsg = "No se reconoce el tipo de usuario, consulte con el administrador del sistema.";
							break;
					}
				} else {
					$errMsg = "Nombre de usuario o contraseña incorrecto!";
				}
			} else {
				foreach($validation->errors() as $error) {
					$errMsg .= "<p>" . $error . "</p>";
				}
			}
		}
	}
	*/
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Jony Food</title>
	<link rel="shortcut icon" href="recursos/favicon.ico" type="image/x-icon">
	<link rel="icon" href="recursos/favicon.ico" type="image/x-icon">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<!-- Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
	<link rel="stylesheet" href="recursos/css/inicio.css">
</head>
<body>
	<header>
		<div class="container">
			<div class="row">
				<div class="col s4">
					<img class="responsive-img" src="recursos/imgs/logojf.png">
				</div>
				<div class="col s8">
					<nav>
						<div class="nav-wrapper">
							<a href="#!" class="brand-logo right"><img class="responsive-img" width="32px" height="32px" style="margin: 8px 8px 0px 0px;" src="recursos/imgs/iconjf.png"></a>
							<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
							<ul class="left hide-on-med-and-down">
								<li><a href="index.php"><i class="material-icons left">home</i> Inicio</a></li>
								<li><a href="login.php"><i class="material-icons left">people</i> Soy Miembro</a></li>
								<li><a href="#"><i class="material-icons left">message</i> Contacto</a></li>
							</ul>
							<ul class="side-nav" id="mobile-demo">
								<li><a href="index.php"><i class="material-icons left">home</i> Inicio</a></li>
								<li><a href="login.php"><i class="material-icons left">people</i> Soy Miembro</a></li>
								<li><a href="#"><i class="material-icons left">message</i> Contacto</a></li>
							</ul>
						</div>
					</nav>
					
				</div>
			</div>
		</div>
	</header>

	<main>
		<div class="container">
			<div class="row">
				<form class="col s12 m8 offset-m2 l6 offset-l3" method="post" action="registro.php" autocomplete="off">
					<div class="row">
						<br>
						<div class="input-field col s12">
							<i class="material-icons prefix">contact_mail</i>
							<input name="username" id="username" type="email" class="validate" value="<?php echo($default->username); ?>">
							<label for="username">E-Mail</label>
						</div>
						<div class="input-field col s12">
							<i class="material-icons prefix">more_horiz</i>
							<input name="password" id="password" type="password" class="validate" value="<?php echo($default->password); ?>">
							<label for="password">Password</label>
						</div>
						<div class="input-field col s12">
							<i class="material-icons prefix">more_horiz</i>
							<input name="rpassword" id="rpassword" type="password" class="validate" value="<?php echo($default->password); ?>">
							<label for="rpassword">Reingrese Password</label>
						</div>

						<div class="col s12 center-align">
							<input type="hidden" name="token" id="token" value="<?php echo(Token::generate()); ?>" />
							<button class="btn waves-effect waves-light" type="submit" name="action">Continuar
								<i class="material-icons right">send</i>
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</main>

	<!-- <div id="msg_err" class="hide"><?php echo($errMsg); ?></div> -->

	<footer class="page-footer">
		<div class="container">
			<div class="row">
				<div class="col s12 center-align">
					<a href="https://www.facebook.com/" target="_blank"><img src="recursos/imgs/social/flat-social-icons_0002_facebook.png" width="64px" height="64px" alt=""></a>
					<a href="https://twitter.com/?lang=es" target="_blank"><img src="recursos/imgs/social/flat-social-icons_0008_twitter.png" width="64px" height="64px" alt=""></a>
					<a href="https://plus.google.com/" target="_blank"><img src="recursos/imgs/social/flat-social-icons_0001_google-plus.png" width="64px" height="64px" alt=""></a>
				</div>
			</div>
			<div class="row">
				<div class="col s12 center-align">
					<p class="creditos center-align">
						© 2017 Jony Food // <a href="#">Diseño</a>
					</p>
				</div>
			</div>
		</div>
    </footer>

	<!-- Compiled and minified JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<!-- Materialize -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			$(".button-collapse").sideNav();
			/*
			if(!$("#msg_err").html() == "") {
				Materialize.toast($("#msg_err").html(), 8000) // 4000 is the duration of the toast
			}
			*/
		});
	</script>
</body>
</html>