<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();
	$resp->estrellas = 0;

	$postdata = @json_decode(file_get_contents("php://input"));
	foreach($postdata as $key => $value) {
		$_POST[$key] = $value;
	}

	$validate = new Validate();
	if(!$validate->check($_POST, array(
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
		$xSQL = "SELECT * FROM votos";
		$xSQL .= " WHERE idcomercio = " . Input::get("idcomercio");
		$estrellas = DB::getInstance()->query($xSQL);
		if(!$estrellas->error()) {
			$registros = $estrellas->results();
			$total_registros = 0;
			$total = 0;
			foreach($registros as $key) {
				$total_registros = $total_registros + 1;
				$total = $total + intval($key->estrellas);
			}
			$promedio = 0;
			if($total_registros > 0) {
				$promedio = $total / $total_registros;
			}
			$resp->estrellas = intval($promedio);
		}
	}
	echo(json_encode($resp));
?>
