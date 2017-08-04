<?php
	class Hash {
		public static function make($password, $salt = "") {
			// Generamos el hash
			$hash = crypt($password, '$2y$10$' . $salt);
			return $hash;
		}
		public static function salt($length) {
			/*
				A partir de php 7.1 hay que
				cambiar mcrypt_create_iv por:
				string random_bytes ( int $length )
				$salt = substr(base64_encode(random_bytes($length)), 0, $length);
			*/
			$salt = substr(base64_encode(mcrypt_create_iv($length)), 0, $length);
			//A Crypt no le gustan los '+' así que los vamos a reemplazar por puntos.
			$salt = strtr($salt, array('+' => '.'));
			return $salt;
		}
		public static function unique() {
			return self::make(uniqid());
		}
	}
?>