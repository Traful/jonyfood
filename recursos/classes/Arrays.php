<?php
	class Arrays {
		function __construct() { }

		public static function ordenar($array_datos, $key_array, $orden = "ASC") {
			uasort($array_datos, self::ordenar_array($key_array));
			return $array_datos;
		}

		private static function isValidDateTimeString($str_dt, $str_dateformat = "Y-m-d", $str_timezone = "America/Argentina/San_Luis") {
			$date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));
			return $date && DateTime::getLastErrors()["warning_count"] == 0 && DateTime::getLastErrors()["error_count"] == 0;
		}
		
		private static function ordenar_array($clave) {
		    return function ($a, $b) use ($clave) {
		        if(self::isValidDateTimeString($a[$clave], "d/m/Y", "America/Argentina/San_Luis") && self::isValidDateTimeString($b[$clave], "d/m/Y", "America/Argentina/San_Luis")) {
		        	$retVal = 0;
		        	//Si ambas fechas son válidas
		        	$fecha1 = strtotime($a[$clave]);
		        	$fecha2 = strtotime($b[$clave]);
		        	if(!$fecha1 == $fecha2) {
		        		$retVal = ($fecha1 > $fecha2) ? 1 : -1;
		        	}
		        	return $retVal;
		        } elseif(is_numeric($a[$clave]) && is_numeric($b[$clave])) {
		        	if(!$a[$clave] == $b[$clave]) {
		        		$retVal = ($a[$clave] > $b[$clave]) ? 1 : -1;
		        	}
		        } else {
	        		$retVal = strnatcmp($a[$clave], $b[$clave]);
		        }
		        return $retVal;
		    };
		}
	}
?>