<?php
	class DB {
		private static $_instance = null;
		private $_conn = false,
				$_query = false,
				$_results = false,
				$_error = false,
				$_count = 0,
				$_lastid = 0,
				$_errMsg = array();
		
		private function __construct() {
			try {
				$dsn = Config::get("database/driver") . ":host=" . Config::get("dns/host") . ";dbname=" . Config::get("dns/db");
				$options = array();
				//Opciones
				foreach(Config::getkey("dboptions") as $k => $v) {
	        		$options[constant("{$k}")] = $v;
	        	}
				$this->_conn = new PDO($dsn, Config::get("database/username"), Config::get("database/password"), $options);
				//Atributos
				foreach(Config::getkey("dbattributes") as $k => $v) {
	        		$this->_conn->setAttribute(constant("{$k}"), constant("{$v}"));
	        	}
			} catch (PDOException $e) {
				$this->_error = true;
		        $this->_errMsg = array("Error Conn" => $e->getMessage());
		        print "¡Error!: " . $e->getMessage() . "<br/>";
		        die();
			}
		}

		public static function getInstance() {
			if(!self::$_instance) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function prepare($sql, $params = array()) {
			/*
			$sql = "INSERT INTO hans (uno, dos) VALUES (?, ?)";
			$consulta = $this->_conn->prepare($sql);
			$gsent->bindParam(1, $color, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 12);


			$color = 'red';
			$gsent = $gbd->prepare('CALL puree_fruit(?)');
			$gsent->bindParam(1, $color, PDO::PARAM_STR|PDO::PARAM_INPUT_OUTPUT, 12);
			$gsent->execute();
			*/
		}

		public function query($xSQL) {
			$this->_error = false;
			unset($this->_errMsg);
			unset($this->_results);
			$this->_count = 0;
			if($this->_conn) {
				try {
					//$this->_results = $this->_conn->query($xSQL)->fetchAll(PDO::FETCH_OBJ);
					$inicio_sql = strtoupper(substr($xSQL, 0, 6)); //DELETE
					if($inicio_sql == "DELETE") {
						$this->_conn->query($xSQL);
					} else {
						$this->_results = $this->_conn->query($xSQL);
						$this->_count = $this->_results->rowCount();
						$this->_results = $this->_results->fetchAll(PDO::FETCH_OBJ);
					}
				} catch(PDOException $e) {
					$this->_error = true;
		         	$this->_errMsg = array("Error Query" => $e->getMessage());
				}
			} else {
				$this->_error = true;
				$this->_errMsg =  array("Error Query" => "No se ha estabecido ninguna conexión a base de datos.");
			}
			return $this;
		}

		public function insert($table, $fields = array()) {
			$this->_error = false;
			unset($this->_errMsg);
			unset($this->_results);
			$this->_lastid = 0;
			$this->_count = 0;
			if(count($fields)) {
				$keys = array_keys($fields);
				$values = "";
				$x = 1;
				foreach($fields as $field) {
					$values .= "'" . $field . "'";
					if($x < count($fields)) {
						$values .= ", ";
					}
					$x++;
				}
				$sql = "INSERT INTO " . $table . " (" . implode(", ", $keys) . ") VALUES (" . $values . ")";
				$this->_conn->query($sql);
				if(!$this->error()) {
					$this->_lastid = $this->_conn->lastInsertId();
				}
			} else {
				$this->_error = true;
		        $this->_errMsg = array("Error Insert" => "No se han establecido los campos y sus valores.");
			}
			return $this;
		}

		public function update($table, $id, $fields = array()) {
			$this->_error = false;
			unset($this->_errMsg);
			unset($this->_results);
			$this->_count = 0;
			if(count($fields)) {
				$values = "";
				$x = 1;
				foreach($fields as $key => $value) {
					$values .= $key . " = '" . $value . "'";
					if($x < count($fields)) {
						$values .= ", ";
					}
					$x++;
				}
				$sql = "UPDATE " . $table . " SET " . $values . " WHERE id = " . $id;
				$this->_conn->query($sql);
			} else {
				$this->_error = true;
		        $this->_errMsg = array("Error Update" => "No se han establecido los campos y sus valores.");
			}
			return $this;
		}

		public function delete($table, $filter = array()) {
			$sql = "DELETE FROM " . $table . " WHERE " . $filter[0] . " " . $filter[1] . " '" . $filter[2] . "'";
			$this->_conn->query($sql);
			return $this;
		}

		public function get($table, $filter = array()) {
			if(count($filter) == 3 ) {
				$sql = "SELECT * FROM " . $table . " WHERE " . $filter[0] . " " . $filter[1] . " '" . $filter[2] . "'";
				$this->query($sql);
			} else {
				$this->_error = true;
		        $this->_errMsg = array("Error Get" => "Datos insuficientes.");
			}
			return $this;
		}

		public function first() {
			if($this->_count > 0) {
				return $this->_results[0];
			} else {
				$this->_error = true;
	        	$this->_errMsg = array("Error First" => "No hay registros.");
			}
			return false;
		}

		public function results() {
			if(isset($this->_results)) {
				return $this->_results;
			} else {
				$this->_error = true;
	        	$this->_errMsg = array("Error Results" => "No hay registros.");
			}
			return false; //Ver que conviene devolver cuando no hay consulta
		}

		public function lastId() {
			return $this->_lastid;
		}

		public function count() {
			return $this->_count;
		}

		public function error() {
			return $this->_error;
		}

		public function errMsg() {
			return $this->_errMsg;
		}

		public function conn_attr() {
			$attributes = array(
			    "PDO::ATTR_AUTOCOMMIT",
			    "PDO::ATTR_ERRMODE",
			    "PDO::ATTR_CASE", 
			    "PDO::ATTR_CLIENT_VERSION", 
			    "PDO::ATTR_CONNECTION_STATUS",
			    "PDO::ATTR_ORACLE_NULLS", 
			    "PDO::ATTR_PERSISTENT", 
			    "PDO::ATTR_PREFETCH", 
			    "PDO::ATTR_SERVER_INFO", 
			    "PDO::ATTR_SERVER_VERSION",
			    "PDO::ATTR_TIMEOUT", 
			    "PDO::MYSQL_ATTR_INIT_COMMAND"
			);
			foreach ($attributes as $val) {
			    echo $val . "-:  ";
			    try {
			    	echo $this->_conn->getAttribute(constant($val)) . ".</br>\n";
			    } catch (Exception $e) {
			    	//echo "Atributo no soportado por la conexion mysql.</br>\n";
			    	echo $e->getMessage() . "</br>\n";
			    }
			    
			}
		}
	}
?>