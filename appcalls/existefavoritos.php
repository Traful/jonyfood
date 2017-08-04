<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();
	$resp->sino = false;

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
			$resp->sino = true;
		}
	}
	echo(json_encode($resp));
?>
