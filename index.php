<?php
	include_once("core/init.php");
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
	$default->username = "hansjal@gmail.com";
	$default->password = "quilmes";
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
	<!-- <link rel="stylesheet" href="recursos/css/inicio.css"> -->
</head>
<body>

	<main>
		<p>Esta debe ser la página de inicio de la empresa (acá deben estar las opciones para darse de alta como comercio, o acceder a la cuenta si ya la posee, las publicidades, datos de la empresa, etc etc etc).</p>

		<p>De momento este formulario es para que puedas acceder al panel administrativo, con los users y pass de las cuentas tipo administrativas en la DB. (te dejo los datos precargados de un usuario administrativo que cree)</p>
		
		<form method="post" action="index.php" autocomplete="off">
			<div>
				<label for="username">E-Mail</label>
				<input name="username" id="username" type="email" value="<?php echo($default->username); ?>">
			</div>
			<div>
				<label for="password">Password</label>
				<input name="password" id="password" type="password" value="<?php echo($default->password); ?>">
			</div>
			<div>
				<label for="remember">Recordar mi cuenta en este equipo.</label>
				<input type="checkbox" name="remember" id="remember" value="on" checked="checked">
			</div>
			<div>
				<input type="hidden" name="token" id="token" value="<?php echo(Token::generate()); ?>">
				<input type="submit" value="Submit">
			</div>
		</form>
	</main>

	<div id="msg_err" class="hide"><?php echo($errMsg); ?></div>

	<!-- Compiled and minified JavaScript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			if(!$("#msg_err").html() == "") {
				$("#msg_err").removeClass("hide");
			}
		});
	</script>
</body>
</html>