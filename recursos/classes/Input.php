<?php
	class Input {
		public static function exist($type = "post") {
			if(!empty($_POST)) {
				return true;
			} elseif (!empty($_GET)) {
				return true;
			} else {
				return false;
			}
			/*
			switch($type) {
				case "post":
					return (!empty($_POST)) ? true : false;
					break;
				case "get":
					return (!empty($_GET)) ? true : false;
					break;
				default:
					return false;
					break;
			}
			*/
		}
		public static function get($item) {
			if(isset($_POST[$item])) {
				return $_POST[$item];
			} else if(isset($_GET[$item])) {
				return $_GET[$item];
			}
			return "";
		}
	}
?>