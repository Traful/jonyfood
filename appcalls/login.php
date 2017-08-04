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
	        "id" => array(
	            "required" => true,
	            "numeric" => true
	        ),
	        "grupo" => array(
	            "required" => true,
	            "numeric" => true
	        )
	    ))->passed()) {
	    	$where = false;
	        $xSQL = "SELECT id, name, mail FROM users";
	    	if(Input::get("id") > 0) {
	        	$xSQL .= " WHERE id = " . Input::get("id");
	        	$where = true;
	    	}
	    	if(Input::get("grupo") > 0) {
	    		if($where) {
	    			$xSQL .= " AND grupo = " . Input::get("grupo");
	    		} else {
	    			$xSQL .= " WHERE grupo = " . Input::get("grupo");
	    		}
	    	}
	        $users = DB::getInstance()->query($xSQL);
			if(!$users->error()) {
				$regs_users = $users->results();
				foreach($regs_users as $value) {
					//Buscar los tokens registrados
					$xSQL = "SELECT * FROM tokensu WHERE iduser = " . $value->id;
					$token = DB::getInstance()->query($xSQL);
					if(!$token->error() && $token->count()) {
						$regs_tokens = $token->results();
						foreach($regs_tokens as $valueT) {
							$value->tokens[] = $valueT->token;
						}
					} else {
						//$value->tokens[] = "No";
					}
					$resp->data[] = $value;
				}
			} else {
				$resp->err = 1;
				$resp->msg[] = $users->errMsg();
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