<?php
	require_once("../../core/init.php");
	$comercios = DB::getInstance()->query("SELECT * FROM comercios ORDER BY nombre")->results();
    echo(json_encode($comercios));
?>