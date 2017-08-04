<?php
	require_once("../../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();

	$postdata = @json_decode(file_get_contents("php://input"));
    foreach ($postdata as $key => $value) {
    	$_POST[$key] = $value;
    }
    
    /*
	["idcomercio"]=> string(1) "2"
	["idcategoria"]=> string(2) "48"
	["descripcion"]=> string(7) "kkkkkkk"
	["importe"]=> int(50)
	["items"]=> int(1)
	["master"]=> int(0)
	["bufferItems"]=> array(3) {
		[0]=> string(4) "pppp"
		[1]=> string(4) "llll"
		[2]=> string(3) "mmm"
	}
	["idmaster"]=> string(1) "1"
	*/

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
	        ),
	        "descripcion" => array(
	            "required" => true,
	            "min" => 3,
	            "max" => 50,
	            "unique" => "subcategoria"
	        ),
	        "importe" => array(
	            "required" => true,
	            "numeric" => true
	        ),
	        "items" => array(
	            "required" => true,
	            "numeric" => true,
	            "mayorcero" => true
	        ),
	        "master" => array(
	            "required" => true,
	            "numeric" => true
	        ),
	        "idmaster" => array( //Si master es 1 e idmaster es 0 es un error
	            "required" => true,
	            "numeric" => true
	        )
	    ))->passed()) {
	    	//Verificar que el id de la categoria corresponda al comercio
	    	$xSQL = "SELECT id FROM categorias";
	    	$xSQL .= " WHERE idcomercio = " . Input::get("idcomercio");
	    	$xSQL .= " AND id = " . Input::get("idcategoria");
	    	$existe = DB::getInstance()->query($xSQL)->count();
	    	if($existe > 0) {
	    		//Agregar la nueva subcategoria
	    		$idmaster = 0;
	    		if(Input::get("master")) {
	    			$idmaster = Input::get("idmaster");
	    		}
	    		$data = array(
	    			"idcomercio" => Input::get("idcomercio"),
	    			"idcategoria" => Input::get("idcategoria"),
	    			"descripcion" => UFtext(Input::get("descripcion")),
	    			"costo" => Input::get("importe"),
	    			"items" => Input::get("items"),
	    			"idmaster" => $idmaster
	    		);
	    		$subcategoria = DB::getInstance()->insert("subcategoria", $data);
	    		if(!$subcategoria->error()) {
	    			$id_subcategria = $subcategoria->lastId();
	    			if(!Input::get("master")) { //Si no utiliza un master cargar el buffer en la tabla items
	    				if(isset($_POST["bufferItems"]) && count($_POST["bufferItems"])) {
	    					foreach(Input::get("bufferItems") as $value) {
		    					$data = array(
					    			"idsubcategoria" => $id_subcategria,
					    			"descripcion" => UFtext($value)
					    		);
					    		if(DB::getInstance()->insert("items", $data)->error()) {
					    			//RollBack
					    			DB::getInstance()->delete("subcategoria", array("id", "=", $id_subcategria));
					    			DB::getInstance()->delete("items", array("idsubcategoria", "=", $id_subcategria));
					    			break;
					    		}
		    				}
	    				} else {
	    					$resp->err = 1;
							$resp->msg[] = "No se especificaron las opciones de la subcategoria.";
							//RollBack
							DB::getInstance()->delete("subcategoria", array("id", "=", $id_subcategria));
	    				}
	    			}
	    		} else {
	    			$resp->err = 1;
					$resp->msg[] = $subcategoria->errMsg();
	    		}
	    	} else {
	    		//Manejar con cuidado este error, el ususario navega donde no debe
	    		$resp->err = 1;
				$resp->msg[] = "Datos incongruentes.";
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