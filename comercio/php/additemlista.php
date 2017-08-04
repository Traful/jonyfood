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
	        "idmaster" => array(
	            "required" => true,
	            "numeric" => true,
	            "mayorcero" => true
	        ),
	        "descripcion" => array(
	            "required" => true,
	            "max" => 50,
	            "min" => 3
	        )
	    ))->passed()) {
	    	//Verificar si ya existe
	    	$xSQL = "SELECT id FROM itemsmaster";
	    	$xSQL .= " WHERE idmaster = " . Input::get("idmaster");
	    	$xSQL .= " AND descripcion = '" . Input::get("descripcion") . "'";
	    	$lista = DB::getInstance()->query($xSQL);
	    	if(!$lista->error()) {
	    		if($lista->count()) {
	    			$resp->err = 1;
					$resp->msg[] = "El item " . Input::get("descripcion") . " ya existe.";
	    		} else {
	    			$registro = array(
	    				"idmaster" => Input::get("idmaster"),
	    				"descripcion" => UFtext(Input::get("descripcion")),
	    				"stock" => 1
	    			);
	    			$insert = DB::getInstance()->insert("itemsmaster", $registro);
	    			if($insert->error()) {
	    				$resp->err = 1;
						$resp->msg[] = $insert->errMsg();
	    			}
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