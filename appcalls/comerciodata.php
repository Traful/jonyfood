<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();
	$resp->data = array();
	$resp->categorias = array();

	$postdata = @json_decode(file_get_contents("php://input"));
    foreach ($postdata as $key => $value) {
    	$_POST[$key] = $value;
    }

    //$_POST["idcomercio"] = 2;

    if(Input::exist()) {
	    $validate = new Validate();
	    if($validate->check($_POST, array(
	        "idcomercio" => array(
	            "required" => true,
	            "numeric" => true,
	            "mayorcero" => true
	        )
	    ))->passed()) {
	        $comercio = new Comercio(Input::get("idcomercio"));
	    	$resp->data = $comercio->getComercioData();
	    	$categorias = $comercio->getCategorias();
	    	foreach($categorias as $value) {
	    		$value->subcategorias = $comercio->getSubCategorias($value->id, true); //Subcategoria con Items (Items o Master)
	    		//$value->subcategorias = $comercio->getSubCategorias($value->id);
	    	}
	    	$resp->categorias = $categorias;
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