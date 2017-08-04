<?php
	require_once("../../core/init.php");
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
	        "id" => array(
	            "required" => true,
	            "numeric" => true,
	            "mayorcero" => true
	        )
	    ))->passed()) {
	    	$xSQL = "SELECT id FROM pedidos";
	    	$xSQL .= " WHERE idcomercio = " . Input::get("id");
	    	$xSQL .= " AND idestado < 4";
	    	$xSQL .= " ORDER BY id DESC";
			$pedidos = DB::getInstance()->query($xSQL);
			if(!$pedidos->error() && $pedidos->count()) {
				$resp->data = $pedidos->results();
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