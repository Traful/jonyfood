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
	        "idcomercio" => array(
	            "required" => true,
	            "numeric" => true,
	            "mayorcero" => true
	        ),
	        "idcategoria" => array(
	            "required" => true,
	            "numeric" => true,
	            "mayorcero" => true
	        )
	    ))->passed()) {
			$comercio = new Comercio(Input::get("idcomercio"));
			if(!$comercio->error()) {
				$resp->data = $comercio->getSubCategorias(Input::get("idcategoria"), true);
			} else {
				$resp->err = 1;
				$resp->msg[] = $comercio->errMsg();
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