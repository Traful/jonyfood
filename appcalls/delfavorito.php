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
			$resp->msg = $error;
		}
	} else {
		$xSQL = "DELETE FROM favoritos";
		$xSQL .= " WHERE idcomercio = " . Input::get("idcomercio");
		$xSQL .= " AND idcliente = " . Input::get("idcliente");
		$borrar = DB::getInstance()->query($xSQL);
		if($borrar->error()) {
			$resp->err = 1;
			$resp->msg[] = $borrar->errMsg();
		}
	}
	echo(json_encode($resp));
?>
