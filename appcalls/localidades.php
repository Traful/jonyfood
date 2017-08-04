<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();
	$resp->data = array();

	$postdata = @json_decode(file_get_contents("php://input"));
    foreach ($postdata as $key => $value) {
    	$_POST[$key] = $value;
    }

    if(Input::exist()) {
	    $validate = new Validate();
	    if($validate->check($_POST, array(
	        "idprovincia" => array(
	            "required" => true,
	            "numeric" => true,
	            "mayorcero" => true
	        )
	    ))->passed()) {
	    	$xSQL = "SELECT DISTINCT(idlocalidad), localidades.nombre FROM comercios";
			$xSQL .= " INNER JOIN localidades ON comercios.idlocalidad = localidades.id";
			$xSQL .= " WHERE comercios.idprovincia = " . Input::get("idprovincia");
			$xSQL .= " ORDER BY localidades.nombre";
	        $localidades = DB::getInstance()->query($xSQL);
			if(!$localidades->error()) {
				$resp->data = $localidades->results();
			} else {
				$resp->err = 1;
				$resp->msg[] = $localidades->errMsg();
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