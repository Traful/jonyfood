<?php
	header('Access-Control-Allow-Origin: *');
	require_once("../core/init.php");
	$resp = new stdClass();
	$resp->err = 0;
	$resp->msg = array();
	$resp->data = null;

	$postdata = @json_decode(file_get_contents("php://input"));
	foreach($postdata as $key => $value) {
		$_POST[$key] = $value;
	}

	$validate = new Validate();
	if(!$validate->check($_POST, array(
		"iduser" => array(
			"required" => true,
			"numeric" => true,
			"mayorcero" => true
		)
	))->passed()) {
		$resp->err = 1;
		foreach($validate->errors() as $error) {
			$resp->msg[] = $error;
		}
	} else {
		//Pedidos del Usuario
		//Obtener los datos del pedido
		$xSQL = "SELECT pedidos.*, comercios.nombre, estados.descripcion, users.name FROM pedidos";
		$xSQL .= " INNER JOIN comercios ON pedidos.idcomercio = comercios.id";
		$xSQL .= " INNER JOIN estados ON pedidos.idestado = estados.id";
		$xSQL .= " INNER JOIN users ON pedidos.idcliente = users.id";
		$xSQL .= " WHERE idcliente = " . Input::get("iduser");
		$xSQL .= " AND idestado < 5"; //5 = Cobrado
		//$xSQL .= " ORDER BY comercios.id DESC";
		$xSQL .= " ORDER BY pedidos.id DESC";
		$xSQL .= " LIMIT 7"; //Últimos 7 pedidos
		$pedidos = DB::getInstance()->query($xSQL);
		if(!$pedidos->error()) {
			if($pedidos->count()) {
				$regs_pedidos = $pedidos->results();
				foreach($regs_pedidos as $val_pedidos) {
					$val_pedidos->fecha = ffecha($val_pedidos->fecha);
					
					//Items del Pedido
					$xSQL = "SELECT * FROM itemspedido";
					$xSQL .= " WHERE idpedido = " . $val_pedidos->id;
					$xSQL .= " ORDER BY id";
					$items_pedido = DB::getInstance()->query($xSQL);
					$total_items = 0;
					if(!$items_pedido->error()) {
						$regs_items_pedido = $items_pedido->results();
						foreach($regs_items_pedido as $val_items) {
							$total_items = $total_items + ($val_items->costo * $val_items->cantidad);
							//SubItems del Pedido
							$xSQL = "SELECT * FROM subitemspedido";
							$xSQL .= " WHERE iditem = " . $val_items->id;
							$xSQL .= " ORDER BY id";
							$subitems_pedido = DB::getInstance()->query($xSQL);
							if(!$subitems_pedido->error()) {
								$val_items->subitems = $subitems_pedido->results();
							}
							$val_pedidos->items[] = $val_items;
						}
					}
					$val_pedidos->total = $total_items + $val_pedidos->costodelibery;
				}
				$resp->data = $regs_pedidos;
			}
		} else {
			$resp->err = 1;
			$resp->msg[] = "Error al obtener los datos del pedido.";
		}
	}
	echo(json_encode($resp));
?>
