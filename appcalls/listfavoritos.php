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

	$validate = new Validate();
	if(!$validate->check($_POST, array(
		"idcliente" => array(
			"required" => true,
			"numeric" => true,
			"mayorcero" => true
		)
	))->passed()) {
		$data_err["error"] = 1;
		foreach($validate->errors() as $error) {
			$data_err["msg"] = $data_err["msg"] . $error . " // ";
		}
	} else {
		//$xSQL = "SELECT favoritos.*, comercios.nombre, comercios.costodelib, comercios.pathimg FROM favoritos";

		$xSQL = "SELECT favoritos.*, comercios.* FROM favoritos";
		$xSQL .= " INNER JOIN comercios ON favoritos.idcomercio = comercios.id";
		/*
		$xSQL .= " WHERE favoritos.idcliente = " . Input::get("idcliente");
		$xSQL .= " AND favoritos.idcomercio = " . Input::get("idcomercio");
		*/
		$xSQL .= " WHERE favoritos.idcliente = " . Input::get("idcliente");
		$xSQL .= " ORDER BY comercios.nombre";
		$favoritos = DB::getInstance()->query($xSQL);
		if(!$favoritos->error()) {
			if($favoritos->count()) {

				$resp->data = $favoritos->results();
				/*
				$filas = array();
				//$path_imgs = "http://localhost/alaorden/imgs/";
				$path_imgs = "http://pub.esy.es/zz/admin/imgs/";
				$path_imgs_local = "../../zz/admin/imgs/";
				
				$results = $favoritos->results();
				
				foreach($results as $key) {
					if($key->pathimg != "" && file_exists($path_imgs_local . $key->pathimg)) {
						$key->pathimg = utf8_encode($path_imgs . $key->pathimg);
					} else {
						$key->pathimg = utf8_encode($path_imgs . "icon.jpg");
					}
				}

				$resp->data = $results;
				*/
			} else {
				$resp->err = 1;
				$resp->msg[] = "No hay registros";
			}
		} else {
			$resp->err = 1;
			$resp->msg = $favoritos->errMsg();
		}
	}
	
	echo(json_encode($resp));
?>
