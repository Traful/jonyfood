<?php
	class User {
		private $_db,
				$_data,
				$_sessionName,
				$_cookieName,
				$_isLoggedIn = false;
		
		public function __construct($user = null) {
			$this->_db = DB::getInstance();
			$this->_sessionName = Config::get("session/session_name");
			$this->_cookieName = Config::get("remember/cookie_name");
			if(!$user) {
				
				if(Session::exist($this->_sessionName)) {
					$user = Session::get($this->_sessionName);
					if($this->find($user)) {
						$this->_isLoggedIn = true;
					} else {
						//Procces Logout
					}
				}
			} else {
				$this->find($user);
			}
		}

		public function create($fields = array()) {
			if(!$this->_db->insert("users", $fields)) {
				//throw new Exception("Error al crear nuevo usuario");
				throw new Exception(print_r($this->_db->errMsg()));
			} else {
				return $this->_db->lastId();
			}
		}
		
		public function update($fields = array(), $id = null) {
			if(!$id && $this->isLoggedIn()) {
				$id = $this->data()->id;
			}
			if(!$this->_db->update("users", $id, $fields)) {
				//throw new Exception("Error al editar datos del usuario");
				throw new Exception(print_r($this->_db->errMsg()));
			}
		}
		
		public function find($user = null) {
			if($user) {
				//El nombre de usuario no puede ser un numero OJO con esto
				//$field = (is_numeric($user)) ? "id" : "username";
				$field = (is_numeric($user)) ? "id" : "mail";
				$data = $this->_db->get("users", array($field, "=", $user));
				if($data->count()) {
					$this->_data = $data->first();
					return true;
				}
			}
			return false;
		}
		
		public function login($username = null, $password = null, $remember = false) {
			if(!$username && !$password && $this->exist()) {
				Session::put($this->_sessionName, $this->data()->id);
			} else {
				$user = $this->find($username);
				if($user) {
					if($this->data()->password === Hash::make($password, $this->data()->salt)) {
						Session::put($this->_sessionName, $this->data()->id);
						if($remember) { //No funca ver porque
							$hash = Hash::unique();
							$hashCheck = $this->_db->get("users_session", array("user_id", "=", $this->data()->id));
							if(!$hashCheck->count()) {
								$this->_db->insert("users_session", array(
									"user_id" => $this->data()->id,
									"hash" => $hash
								));
							} else {
								$hash = $hashCheck->first()->hash;
							}
							Cookie::put($this->_cookieName, $hash, Config::get("remember/cookie_expire"));
						}
						return true;
					} /* else {
						echo("El pass no coincide!<br/>");
						echo("US: " . Hash::make($password, $this->data()->salt) . "<br/>");
						echo("DB: " . $this->data()->password . "<br/>");
					}
					*/
				}
			}
			return false;
		}
		
		public function hasPermission($key, $nivel = 0) {
			$permissions = false;
			if($nivel == 0) { //Se adminstra a nivel de usuario por defecto
				$permissions = json_decode($this->data()->permissions, true);
				if($permissions[$key] == true) {
					return true;
				}
			} else { //Se adminstra a nivel de grupo
				$group = $this->_db->get("groups", array("id", "=", $this->data()->grupo)); //?????
				//print_r($group->first());
				if($group->count()) {
					$permissions = json_decode($group->first()->permissions, true);
					if($permissions[$key] == true) {
						return true;
					}
				}
			}
			return false;
		}
		
		public function exist() {
			return (!empty($this->_data)) ? true : false;
		}
		
		public function logout() {
			$this->_db->delete("users_session", array("user_id", "=", $this->data()->id));
			Session::delete($this->_sessionName);
			Cookie::delete($this->_cookieName);
		}
		
		public function data() {
			return $this->_data;
		}
		
		public function isLoggedIn() {
			return $this->_isLoggedIn;
		}
	}
?>