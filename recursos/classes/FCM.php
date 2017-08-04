<?php
	class FCM {
		private $_conn = false,
						$_authKey = "AIzaSyCzMF3ZUH052kXRp4ALLDx9u9ZOw_BuYso",
						$_fcmServer = "https://fcm.googleapis.com/fcm/send",
						$_error = false,
						$_errmsg = "",
						$_respuesta = "";
		
		public function __construct() {
			$this->_conn = DB::getInstance();
		}

		public function sendByToken($token, $titulo, $msg) {
			$tokens = array($token);
			$this->send($tokens, $titulo, $msg);
			return $this;
		}

		public function sendById($id, $titulo, $msg) {
			$tokens = array();
			$this_error = false;
			$this_errmsg = "";
      $this->_respuesta = "";
			$xSQL = "SELECT token FROM tokensu WHERE iduser = " . $id;
			$tokensUser = $this->_conn->query($xSQL);
			if(!$tokensUser->error()) {
				if($tokensUser->count()) {
					$regs_tokens = $tokensUser->results();
					foreach($regs_tokens as $value) {
						$tokens[] = $value->token;
					}
					$this->send($tokens, $titulo, $msg);
				} else {
					$this_error = true;
					$this_errmsg = $tokensUser->errMsg();
				}
			} else {
				$this_error = true;
				$this_errmsg = $tokensUser->errMsg();
			}
			return $this;
		}

		public function sendAll($titulo, $msg) {
			$tokens = array();
			$this_error = false;
			$this_errmsg = "";
      $this->_respuesta = "";
      //Ojo deberia ser un max de 1000
			$xSQL = "SELECT token FROM tokensu";
			$tokensUser = $this->_conn->query($xSQL);
			if(!$tokensUser->error()) {
				if($tokensUser->count()) {
					$regs_tokens = $tokensUser->results();
					foreach($regs_tokens as $value) {
						$tokens[] = $value->token;
					}
					$this->send($tokens, $titulo, $msg);
				} else {
					$this_error = true;
					$this_errmsg = $tokensUser->errMsg();
				}
			} else {
				$this_error = true;
				$this_errmsg = $tokensUser->errMsg();
			}
			return $this;
		}

		private function send($tokens, $titulo, $msg) {
			$headers = array(
				"Content-Type:application/json",
				"Authorization:key=" . $this->_authKey
			);
			$data = null;
			if(is_array($tokens)) {
				$data = array(
					"notification" => array(
						"title" => $titulo,
						"body" => $msg
				  ),
				  "registration_ids" => $tokens
				);
			} else {
				$data = array(
					"notification" => array(
						"title" => $titulo,
						"body" => $msg
				  ),
				  "to" => $tokens
				);
			}
			$ch = curl_init();
			if($ch) {
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_URL, $this->_fcmServer);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          $this->_respuesta = curl_exec($ch);
          curl_close($ch);
      } else {
      		$this_error = true;
          $this->_respuesta = "El servicio CURL no esta habilitado en el servidor PHP";
      }
		}

		public function respuesta() {
			return $this->_respuesta;
		}

		public function error() {
			return $this_error;
		}

		public function errorMsg() {
			return $this_errmsg;
		}
	}
?>