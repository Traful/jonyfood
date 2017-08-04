<?php
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();
	$resp->data = null;

	$postdata = @json_decode(file_get_contents("php://input"));
  	foreach ($postdata as $key => $value) {
  		$_POST[$key] = $value;
  	}

  	if(Input::exist()) {
	  	$validate = new Validate();
	  	if($validate->check($_POST, array(
	    	"id" => array(
	        	"required" => true
	      	),
	      	"token" => array(
	        	"required" => true
	      	),
	      	"titulo" => array(
	        	"required" => true
	      	),
	      	"mensaje" => array(
	        	"required" => true
	      	)
	   	))->passed()) {
	    	$msg = new FCM();

	    	if(Input::get("id") == 0 && Input::get("token") == "") {
	    		$msg->sendAll(Input::get("titulo"), Input::get("mensaje"));
	    		if(!$msg->error()) {
	    			$resp->data = $msg->respuesta();
	    		} else {
	    			$resp->err = 1;
						$resp->msg[] = $msg->errorMsg();
	    		}
	    	} else {
	    		if(Input::get("id") > 0) {
	    			$msg->sendById(Input::get("id"), Input::get("titulo"), Input::get("mensaje"));
		    		if(!$msg->error()) {
		    			$resp->data = $msg->respuesta();
		    		} else {
		    			$resp->err = 1;
						$resp->msg[] = $msg->errorMsg();
		    		}
	    		} else {
	    			$msg->sendByToken(Input::get("token"), Input::get("titulo"), Input::get("mensaje"));
		    		if(!$msg->error()) {
		    			$resp->data = $msg->respuesta();
		    		} else {
		    			$resp->err = 1;
						$resp->msg[] = $msg->errorMsg();
		    		}
	    		}
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