<?php
	header('Access-Control-Allow-Origin: *');
	require_once("core/init.php");
	$xSQL = "";
	if(isset($_GET["idcomercio"]) && $_GET["idcomercio"] != "") {
		$xSQL = "SELECT * FROM promo";
		$xSQL .= " WHERE idcomercio = " . $_GET["idcomercio"];
		$xSQL .= " ORDER BY descripcion";
	}
	if(isset($_GET["idlocalidad"]) && $_GET["idlocalidad"] != "") {
		$xSQL = "SELECT promo.*, comercios.idlocalidad FROM promo";
		$xSQL .= " INNER JOIN comercios ON promo.idcomercio = comercios.id";
		$xSQL .= " WHERE comercios.idlocalidad = " . $_GET["idlocalidad"];
		$xSQL .= " ORDER BY promo.descripcion";
	}
	/*
	$xSQL = "SELECT * FROM promo";
	if(isset($_GET["idcomercio"]) && $_GET["idcomercio"] != "") {
		$xSQL .= " WHERE idcomercio = " . $_GET["idcomercio"];
	}
	if(isset($_GET["idlocalidad"]) && $_GET["idlocalidad"] != "") {
		$xSQL .= " WHERE idcomercio = " . $_GET["idcomercio"];
	}
	$xSQL .= " ORDER BY descripcion";
	*/
	$results = DB::getInstance()->query($xSQL)->results();
	$filas = array();
	//$path_imgs = "http://localhost/alaorden/imgs/";
	$path_imgs = "http://pub.esy.es/zz/admin/imgs/";
	$path_imgs_local = "../../zz/admin/imgs/";
	foreach($results as $key) {
		if($key->pathimg != "" && file_exists($path_imgs_local . $key->pathimg)) {
			$key->pathimg = utf8_encode($path_imgs . $key->pathimg);
		} else {
			$key->pathimg = utf8_encode($path_imgs . "icon.jpg");
		}
		$filas[] = $key;
	}
	echo(json_encode($filas));
?>