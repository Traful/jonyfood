<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();

	$postdata = @json_decode(file_get_contents("php://input"));
	foreach($postdata as $key => $value) {
		$_POST[$key] = $value;
	}

	$validate = new Validate();
	if(!$validate->check($_POST, array(
		"idcliente" => array(
			"required" => true,
			"numeric" => true,
			"mayorcero" => true
		),
		"idcomercio" => array(
			"required" => true,
			"numeric" => true,
			"mayorcero" => true
		),
		"estrellas" => array( // 1 a 5
			"required" => true,
			"numeric" => true,
			"mayorcero" => true
		)
	))->passed()) {
		$resp->err = 1;
		foreach($validate->errors() as $error) {
			$resp->msg[] = $error;
		}
	} else {
		//Verificar si ya existe el voto
		$xSQL = "SELECT * FROM votos";
		$xSQL .= " WHERE idcliente = " . Input::get("idcliente");
		$xSQL .= " AND idcomercio = " . Input::get("idcomercio");
		if(DB::getInstance()->query($xSQL)->count()) {
			$resp->err = 1;
			$resp->msg[] = "El usuario ya voto este comercio";
		} else {
			//Agregar a votos
			$estrellas = intval(Input::get("estrellas"), 10);
			if($estrellas > 5) {
				$estrellas = 5;
			}
			$data = array(
				"idcliente" => Input::get("idcliente"),
				"idcomercio" => Input::get("idcomercio"),
				"estrellas" => $estrellas
			);
			$votos = DB::getInstance()->insert("votos", $data);
			if($votos->error()) {
				$resp->err = 1;
				$resp->msg[] = $votos->errMsg();
			}
		}
	}
	echo(json_encode($resp));
?>
