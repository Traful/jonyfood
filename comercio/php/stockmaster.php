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
	        "id" => array(
	            "required" => true,
	            "numeric" => true,
	            "mayorcero" => true
	        )
	    ))->passed()) {
	    	$xSQL = "SELECT stock FROM itemsmaster WHERE id = " . Input::get("id");
	    	$stock = DB::getInstance()->query($xSQL);
	    	if(!$stock->error()) {
	    		if($stock->count()) {
	    			$valor = 1;
	    			$reg_stock = $stock->first();
	    			if($reg_stock->stock == 1) {
	    				$valor = 0;
	    			} else {
	    				$valor = 1;
	    			}
	    			$nuevo_stock = array(
	    				"stock" => $valor
	    			);
	    			DB::getInstance()->update("itemsmaster", Input::get("id"), $nuevo_stock);
	    		}
	    	} else {
	    		$resp->err = 1;
				$resp->msg[] = $stock->errMsg();
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