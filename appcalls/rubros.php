<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	echo(json_encode(DB::getInstance()->query("select * from rubros order by descripcion")->results()));
?>