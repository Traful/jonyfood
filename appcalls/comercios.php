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
	        "idrubro" => array(
	            "required" => true,
	            "numeric" => true
	        ),
			"idlocalidad" => array(
	            "required" => true,
	            "numeric" => true
	        ),
	        "iduser" => array(
	            "required" => true,
	            "numeric" => true
	        )
	    ))->passed()) {
	        $xSQL = "SELECT * FROM comercios";
	    	$existewhere = false;
			if(Input::get("idrubro") > 0) {
	        	$xSQL .= " WHERE idrubro = " . Input::get("idrubro");
				$existewhere = true;
	    	}
			if(Input::get("idrubro") > 0) {
				if($existewhere) {
					$xSQL .= " AND idlocalidad = " . Input::get("idlocalidad");
				} else {
					$xSQL .= " WHERE idlocalidad = " . Input::get("idlocalidad");
				}
	        	
	    	}
	    	$xSQL .= " ORDER BY nombre";
	        $comercios = DB::getInstance()->query($xSQL);
			if(!$comercios->error()) {
				if(Input::get("iduser") == 0) {
					$resp->data = $comercios->results();
				} else {
					$regs_comercio = $comercios->results();
					foreach($regs_comercio as $value) {
						$xSQL = "SELECT id FROM favoritos";
						$xSQL .= " WHERE idcomercio = " . $value->id;
						$xSQL .= " AND idcliente = " . Input::get("iduser");
						$favorito = DB::getInstance()->query($xSQL)->count();
						if($favorito > 0) {
							$value->favorito = 1;
						} else {
							$value->favorito = 0;
						}
					}
					$resp->data = $regs_comercio;
				}
			} else {
				$resp->err = 1;
				$resp->msg[] = $comercios->errMsg();
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