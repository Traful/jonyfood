<?php
	function escape($string) {
		return htmlentities($string, ENT_QUOTES, "UTF-8");
	}
	function relleno($nro, $longitud = 4, $relleno = 0) {
		return str_pad($nro, $longitud, $relleno, STR_PAD_LEFT);
	}
	function ffecha($fecha) {
		if(strrpos($fecha, "-")) {
			$x = explode("-", $fecha);
			return $x[2] . "/" . $x[1] . "/" . $x[0];
		} else {
			$x = explode("/", $fecha);
			return $x[2] . "-" . $x[1] . "/" . $x[0];
		}
	}
	function fechahora($fecha) {
		if(strrpos($fecha, " ")) {
			$x = explode(" ", $fecha);
			return ffecha($x[0]) . " " . $x[1];
		} else {
			return $fecha;
		}
	}
	function UFtext($valor) {
		$valor = strtolower($valor);
		$resp = "";
		if(strrpos($valor, " ") === false) {
			$resp = ucfirst($valor);
		} else {
			$resp = explode(" ", $valor);
			$buffer = "";
			for($i=0; $i < count($resp); $i++) { 
				$buffer .= ucfirst($resp[$i]) . " ";
			}
			$resp = $buffer;
		}
		return $resp;
	}
	function nlm($name, $lastname) {
		$nombre = "";
		$apellido = "";
		//Nombre
		if(strrpos($name, " ") === false) {
			$nombre = ucfirst($name);
		} else {
			$nombre = explode(" ", $name);
			$buffer = "";
			for($i=0; $i < count($nombre); $i++) { 
				$buffer .= ucfirst($nombre[$i]) . " ";
			}
			$nombre = $buffer;
		}
		//Apellido
		if(strrpos($lastname, " ") === false) {
			$apellido = ucfirst($lastname);
		} else {
			$apellido = explode(" ", $lastname);
			$buffer = "";
			for($i=0; $i < count($apellido); $i++) { 
				$buffer .= ucfirst($apellido[$i]) . " ";
			}
			$apellido = $buffer;
		}
		return $apellido . " " . $nombre;
	}
	/*
	function nlm($name, $lastname) {
		$primer_nombre = "";
		$primer_apellido = "";
		//Nombre
		if(strrpos($name, " ") === false) {
			$primer_nombre = ucfirst($name);
		} else {
			$primer_nombre = explode(" ", $name);
			$primer_nombre = ucfirst($primer_nombre[0]);
		}
		//Apellido
		if(strrpos($lastname, " ") === false) {
			$primer_apellido = ucfirst($lastname);
		} else {
			$primer_apellido = explode(" ", $lastname);
			$primer_apellido = ucfirst($primer_apellido[0]);
		}
		return $primer_nombre . " " . $primer_apellido;
	}
	*/
	function get_ip_address() {
	    // check for shared internet/ISP IP
	    if (!empty($_SERVER['HTTP_CLIENT_IP']) && validate_ip($_SERVER['HTTP_CLIENT_IP'])) {
	        return $_SERVER['HTTP_CLIENT_IP'];
	    }

	    // check for IPs passing through proxies
	    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	        // check if multiple ips exist in var
	        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
	            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	            foreach ($iplist as $ip) {
	                if (validate_ip($ip))
	                    return $ip;
	            }
	        } else {
	            if (validate_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
	                return $_SERVER['HTTP_X_FORWARDED_FOR'];
	        }
	    }
	    if (!empty($_SERVER['HTTP_X_FORWARDED']) && validate_ip($_SERVER['HTTP_X_FORWARDED']))
	        return $_SERVER['HTTP_X_FORWARDED'];
	    if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']) && validate_ip($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
	        return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
	    if (!empty($_SERVER['HTTP_FORWARDED_FOR']) && validate_ip($_SERVER['HTTP_FORWARDED_FOR']))
	        return $_SERVER['HTTP_FORWARDED_FOR'];
	    if (!empty($_SERVER['HTTP_FORWARDED']) && validate_ip($_SERVER['HTTP_FORWARDED']))
	        return $_SERVER['HTTP_FORWARDED'];

	    // return unreliable ip since all else failed
	    return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Ensures an ip address is both a valid IP and does not fall within
	 * a private network range.
	 */
	function validate_ip($ip) {
	    if (strtolower($ip) === 'unknown')
	        return false;

	    // generate ipv4 network address
	    $ip = ip2long($ip);

	    // if the ip is set and not equivalent to 255.255.255.255
	    if ($ip !== false && $ip !== -1) {
	        // make sure to get unsigned long representation of ip
	        // due to discrepancies between 32 and 64 bit OSes and
	        // signed numbers (ints default to signed in PHP)
	        $ip = sprintf('%u', $ip);
	        // do private network range checking
	        if ($ip >= 0 && $ip <= 50331647) return false;
	        if ($ip >= 167772160 && $ip <= 184549375) return false;
	        if ($ip >= 2130706432 && $ip <= 2147483647) return false;
	        if ($ip >= 2851995648 && $ip <= 2852061183) return false;
	        if ($ip >= 2886729728 && $ip <= 2887778303) return false;
	        if ($ip >= 3221225984 && $ip <= 3221226239) return false;
	        if ($ip >= 3232235520 && $ip <= 3232301055) return false;
	        if ($ip >= 4294967040) return false;
	    }
	    return true;
	}
	function html_stars($x) {
		$buffer = "";
		if($x) {
			for($i=1; $i <= $x ; $i++) { 
				$buffer .= "<i class='fa fa-star' aria-hidden='true'></i> ";
			}
			for($i=($x+1); $i < 6 ; $i++) { 
				$buffer .= "<i class='fa fa-star-o' aria-hidden='true'></i> ";
			}
		}
		return $buffer;
	}
	function ftel($tel) {
		$buffer = $tel;
		//Formato (2657) 33-5514
		$buffer = str_replace("(", "", $buffer);
		$buffer = str_replace(")", "", $buffer);
		$buffer = str_replace("-", "", $buffer);
		$buffer = str_replace(" ", "", $buffer);
		if(is_numeric($buffer)) {
			$buffer = $buffer + 0;
			$buffer = strval($buffer);
			$len = strlen($buffer);
			switch($len) {
				case 10: //Cel con caracteristica 2657335514
					$buffer = substr_replace($buffer, "(", 0, 0);
					$buffer = substr_replace($buffer, ") ", 5, 0);
					$buffer = substr_replace($buffer, "-", 9, 0);
					break;
				case 6: //Cel sin caracteristica 335514
					$buffer = substr_replace($buffer, "-", 2, 0);
					break;
				case 5: //Telefono fijo
					//$buffer = substr_replace($buffer, "-", 3, 0);
					break;
				default:
					# code...
					break;
			}
		} else {
			return $tel;
		}
		return $buffer;
	}
	function CalculaEdad($fechaDB) {
	    list($Y,$m,$d) = explode("-", $fechaDB);
	    return (date("md") < $m.$d ? date("Y")-$Y-1 : date("Y")-$Y);
	}
	function Acortar($cadena, $caracteres = 27) {
		if(strlen($cadena) > $caracteres) {
			return substr($cadena, 0, $caracteres - 3) . "...";
		} else {
			return $cadena;
		}
	}
?>