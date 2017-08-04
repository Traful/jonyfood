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
	    	$xSQL = "SELECT pedidos.*, users.name FROM pedidos";
	    	$xSQL .= " INNER JOIN users ON pedidos.idcliente = users.id";
	    	$xSQL .= " WHERE pedidos.id = " . Input::get("id");
			$pedido = DB::getInstance()->query($xSQL);
			if(!$pedido->error() && $pedido->count()) {
				$resp->data = $pedido->results();
				/*
				$resp->data->telefono = ftel($resp->data->telefono);
				$resp->data->fecha = ffecha($resp->data->fecha);
				*/
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