<?php
require_once "core/init.php";
/*
$user = new User();
if(!$user->isLoggedIn() || $user->data()->grupo != 1) {
	Redirect::to("index.php");
}
*/
if(Token::check(Input::get("token"))) {
	if(Input::exist()) {
		$validate = new Validate();
		if(!$validate->check($_POST, array(
			"username" => array(
				"required" => true,
				"min" => 2,
				"max" => 20,
				"unique" => "users"
			),
			"password" => array(
				"required" => true,
				"min" => 2,
				"max" => 20
			),
			"rpassword" => array(
				"required" => true,
				"matches" => "password"
			),
			"name" => array(
				"required" => true,
				"min" => 2,
				"max" => 50
			)
		))->passed()) {
			echo("No se paso la validacion <br/>");
			foreach($validate->errors() as $error) {
				echo($error . "<br/>");
			}
		} else {
			$user = new User();
			$salt = Hash::salt(32);
			try {
				$user->create(array(
					"username" => "Admin",
					"password" => Hash::make(Input::get("password"), $salt),
					"salt" => $salt,
					"name" => Input::get("name"),
					"joined" => date("Y-m-d H:i:s"),
					"grupo" => 1,
					"idcomercio" => 0,
					"permissions" => '{"login": 1, "mail": 1}',
					"mail" => Input::get("username"),
					"keyp" => base64_encode(Input::get("password"))
				));
				Session::flash("home", "El registro fue exitoso!");
				//header("Location: index.php");
				Redirect::to("index.php");
			} catch(Exception $e) {
				die($e->getMessage());
			}
		}
	} else {
		echo("NO se setearon las variables");
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Prueba DB Class</title>
<body>

<form name="login" id="login" action="register.php" method="post">
	<div>
		<label for="username">Usuario:</label>
		<input type="text" name="username" id="username" value="" />
	</div>
	<div>
		<label for="password">Password:</label>
		<input type="password" name="password" id="password" value="" />
	</div>
	<div>
		<label for="rpassword">Reenter Password:</label>
		<input type="password" name="rpassword" id="rpassword" value="" />
	</div>
	
	<div>
		<label for="name">Nombre:</label>
		<input type="text" name="name" id="name" value="" />
	</div>
	
	<input type="submit" value="Aceptar" />
	<input type="hidden" name="token" id="token" value="<?php echo(Token::generate()); ?>" />
</form>
</body>
</html>