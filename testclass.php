<?php
	require_once("core/init.php");
	$comercio = new Comercio(1);
	if(!$comercio->error()) {
		$data = $comercio->getComercioData();
		foreach($data as $key => $value) {
			echo($key . ": " . $value . "<br>");
		}
		//var_dump($comercio->getComercioData());
	} else {
		echo($comercio->errMsg());
	}
?>