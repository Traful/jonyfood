<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();
	$resp->data = array();

	$xSQL = "SELECT DISTINCT(idprovincia), provincias.nombre FROM comercios";
	$xSQL .= " INNER JOIN provincias ON comercios.idprovincia = provincias.id";
	$xSQL .= " ORDER BY provincias.nombre";
	$provincias = DB::getInstance()->query($xSQL);
	if(!$provincias->error()) {
		$regs_provincias = $provincias->results();
		//var_dump($regs_provincias);
		foreach($regs_provincias as $value_provincias) {
			//Localidades de la provincia que tienen comercios
			$xSQL = "SELECT DISTINCT(idlocalidad), localidades.nombre FROM comercios";
			$xSQL .= " INNER JOIN localidades ON comercios.idlocalidad = localidades.id";
			$xSQL .= " WHERE comercios.idprovincia = " . $value_provincias->idprovincia;
			$xSQL .= " ORDER BY localidades.nombre";
			//echo($xSQL . "<br>");
	        $localidades = DB::getInstance()->query($xSQL);
			if(!$localidades->error()) {
				$value_provincias->localidades = $localidades->results();

			} else {
				$value_provincias->localidades = array("0", "Error al obtener los datos de las localidades");
			}
			$resp->data[] = $value_provincias;
		}
	} else {
		$resp->err = 1;
		$resp->msg[] = $provincias->errMsg();
	}
    echo(json_encode($resp));
?>