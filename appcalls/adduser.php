<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();
	$resp->id = 0;

	$postdata = @json_decode(file_get_contents("php://input"));
	foreach($postdata as $key => $value) {
		$_POST[$key] = $value;
	}

	function cargar_token($id, $tokenV) {
		//Verificacion previa (Si el token ya esta en la DB pero en diferente usuario se elimina)
		$xSQL = "SELECT id FROM tokensu WHERE iduser != " . $id . " AND token = '" . $tokenV . "'";
		$token = DB::getInstance()->query($xSQL);
		if(!$token->error() && $token->count()) {
			$regs_token = $token->results();
			foreach($regs_token as $value) {
				if(DB::getInstance()->delete("tokensu", array("id", "=", $value->id))->error()) {
					$resp->err = 1;
					$resp->msg[] = "Error al eliminar Tokens duplicados.";
					break;
				}
			}
		}
		$xSQL = "SELECT id FROM tokensu WHERE iduser = " . $id . " AND token = '" . $tokenV . "'";
		$token = DB::getInstance()->query($xSQL);
		if(!$token->error()) {
			if($token->count()) {
				//El token ya existe
			} else {
				//Dar de alta el Token
				$data = array(
					"iduser" => $id,
					"token" => $tokenV
				);
				$ins = DB::getInstance();
				$ins->insert("tokensu", $data);
				if($ins->error()) {
					$resp->err = 1;
					$resp->msg[] = "Error al ingresar el token.";
				}
			}
		}
	}

	if(Input::exist()) {
		$validate = new Validate();
		if($validate->check($_POST, array(
			"mail" => array(
				"required" => true,
				"max" => 150
			),
			"password" => array(
				"required" => true,
				"max" => 50
			),
			"name" => array(
				"required" => true,
				"max" => 50
			),
			"token" => array(
				"required" => true,
				"min" => 10
			)
		))->passed()) {
			$iduser = 0;
			//Verificar si el usuario ya existe
			$xSQL = "SELECT id FROM users WHERE mail = '" . Input::get("mail") . "'";
			$user = DB::getInstance()->query($xSQL);
			if(!$user->error() && $user->count()) {
				$resp->id = $user->first()->id;
				//El usuario ya existe, verificar el token
				if(isset($_POST["token"])) {
					cargar_token($resp->id, Input::get("token"));
				}
			} else {
				//Alta de usuario
				$iduser = 0;
				$salt = Hash::salt(32);
				$data = array(
					"username" => "",
					"mail" => Input::get("mail"),
					"name" => Input::get("name"),
					"password" => Hash::make(Input::get("password"), $salt),
					"salt" => $salt,
					"joined" => date("Y-m-d H:i:s"),
					"grupo" => 3, //Smartphones
					"idcomercio" => 0,
					"permissions" => '{"login": 1}'
				);
				try {
					$user = new User();
					$iduser = $user->create($data);
					$resp->id = $iduser;
					if($iduser) {
						if(isset($_POST["token"])) {
							cargar_token($iduser, Input::get("token"));
						}
					}
				} catch(Exception $e) {
					$resp->err = 1;
					$resp->msg[] = $e->getMessage();
				}
			}
		} else {
			$resp->err = 1;
			foreach($validate->errors() as $value) {
				$resp->msg[] = $value;
			}
		}
	} else {
		$resp->err = 1;
		$resp->msg[] = "Datos insuficientes";
	}
	echo(json_encode($resp));
?>