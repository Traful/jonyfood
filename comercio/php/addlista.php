<?php
	require_once("../../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();

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
	        "descripcion" => array(
	            "required" => true,
	            "min" => 3,
	            "max" => 50
	        )
	    ))->passed()) {
			$lista = new Comercio(Input::get("idcomercio"));
			if(!$lista->error()) {
				$lista->addLista(Input::get("descripcion"));
				if($lista->error()) {
					$resp->err = 1;
					$resp->msg[] = $lista->errMsg();
				}
			} else {
				$resp->err = 1;
				$resp->msg[] = $lista->errMsg();
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