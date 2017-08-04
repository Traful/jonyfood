<?php
class Token {
	public static function generate() {
		return Session::put(Config::get("session/token_name"), md5(uniqid()));
	}
	public static function check($token) {
		$TokenName = Config::get("session/token_name");
		if(Session::exist($TokenName) && $token === Session::get($TokenName)) {
			Session::delete($TokenName);
			return true;
		}
		return false;
	}
}
?>