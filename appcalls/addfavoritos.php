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
		)
	))->passed()) {
		$resp->err = 1;
		foreach($validate->errors() as $error) {
			$resp->msg[] = $error;
		}
	} else {
		//Verificar si ya existe en Favoritos
		$xSQL = "SELECT id FROM favoritos";
		$xSQL .= " WHERE idcliente = " . Input::get("idcliente");
		$xSQL .= " AND idcomercio = " . Input::get("idcomercio");
		if(DB::getInstance()->query($xSQL)->count()) {
			$resp->err = 1;
			$resp->msg[] = "Ya existe en favoritos";
		} else {
			//Agregar a Favoritos
			$data = array(
				"idcliente" => Input::get("idcliente"),
				"idcomercio" => Input::get("idcomercio")
			);
			$favoritos = DB::getInstance()->insert("favoritos", $data);
			if($favoritos->error()) {
				$resp->err = 1;
				$resp->msg[] = $favoritos->errMsg();
			}
		}
	}
	echo(json_encode($resp));
?>
