<?php
class Config {
	public static function get($path = null) {
		if($path) {
			$config = $GLOBALS["config"];
			$path = explode("/", $path);
			foreach($path as $bit) {
				if(isset($config[$bit])) {
					$config = $config[$bit];
				} else {
					return "Error: el valor <strong>" . $bit . "</strong> no existe en la configuraci&oacute;n.";
				}
			}
			return $config;
		}
		return false;
	}
	public static function getkey($key = null) {
		if($key) {
			$config = $GLOBALS["config"];
			if(isset($config[$key])) {
				return $config[$key];
			} else {
				return false;
			}
		}
	}
	public static function set($key, $field, $value) {
		if(isset($GLOBALS["config"][$key][$field])) {
			$GLOBALS["config"][$key][$field] = $value;
			return true;
		}
		return false;
	}
}
?>