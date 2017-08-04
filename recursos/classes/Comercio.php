<?php
	class Comercio {
		private $_db,
				$_comercioData = null,
				$_error = false,
				$_errMsg = array();
		
		public function __construct($comercioId = null) {
			$this->_db = DB::getInstance();
			if(!is_null($comercioId)) {
				$xSQL = "SELECT comercios.*, paises.nombre AS pais, provincias.nombre AS provincia, localidades.nombre AS localidad FROM comercios";
				$xSQL .= " INNER JOIN paises ON comercios.idpais = paises.id";
				$xSQL .= " INNER JOIN provincias ON comercios.idprovincia = provincias.id";
				$xSQL .= " INNER JOIN localidades ON comercios.idlocalidad = localidades.id";
				$xSQL .= " WHERE comercios.id = " . $comercioId;
				$comercio = $this->_db->query($xSQL);
				if(!$comercio->error()) {
					if($comercio->count() > 0) {
						$reg = $comercio->first();
						//Datos del Usuario
						$xSQL = "SELECT * FROM users WHERE idcomercio = " . $reg->id;
						$user = $this->_db->query($xSQL);
						if(!$user->error()) {
							if($user->count() > 0) {
								$reg->datosUsuario = $user->first();
							}
							$reg->datosUsuario->keyp = base64_decode($reg->datosUsuario->keyp); //Ojo devuelve el pass del usuario
							//Estrellas
							$xSQL = "SELECT SUM(estrellas) AS suma FROM votos WHERE idcomercio = " . $comercioId;
							$suma = $this->_db->query($xSQL)->first()->suma;
							$xSQL = "SELECT COUNT(id) AS total FROM votos WHERE idcomercio = " . $comercioId;
							$total = $this->_db->query($xSQL)->first()->total;
							$promedio = 0;
							if($suma > 0 && $total > 0) {
								$promedio = intval($suma/$total);
							}
							$reg->votos = array("puntos" => $suma, "usuarios" => $total, "promedio" => $promedio);
						}
						$this->_comercioData = $reg;
					} else {
						$this->_error = true;
						$this->_errMsg[] = "El comercio no existe.";
					}
				} else {
					$this->_error = true;
					$this->_errMsg[] = $comercio->errMsg();
				}
			}
			return $this;
		}

		public function getComercioData() {
			return $this->_comercioData;
		}

		public function getCategorias() {
			if(!is_null($this->_comercioData)) {
				//Categorias del Comercio
				$xSQL = "SELECT * FROM categorias";
				$xSQL .= " WHERE idcomercio = " . $this->_comercioData->id;
				$xSQL .= " AND stock = 1";
				$xSQL .= " ORDER BY descripcion";
				$categorias = $this->_db->query($xSQL);
				if(!$categorias->error()) {
					if($categorias->count() > 0) {
						$regs_categorias = $categorias->results();
						foreach($regs_categorias as $value) {
							//Total subcategorías de la categoría
							$xSQL = "SELECT COUNT(id) AS total FROM subcategoria WHERE idcategoria = " . $value->id;
							$subcategorias = $this->_db->query($xSQL);
							if(!$subcategorias->error()) {
								$value->items = $subcategorias->first()->total;
							} else {
								$value->items = "E";
							}
						}
						return $regs_categorias;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function addCategoria($descripcion) {
			$this->_error = false;
			$this->_errMsg = null;
			if(!is_null($this->_comercioData)) {
				//Verificar que no existe la categoría
				$xSQL = "SELECT id FROM categorias";
				$xSQL .= " WHERE idcomercio = " . $this->_comercioData->id;
				$xSQL .= " AND descripcion = '" . $descripcion . "'";
				$categorias = $this->_db->query($xSQL);
				if(!$categorias->error()) {
					if($categorias->count()) {
						$this->_error = true;
						$this->_errMsg[] = "La categoría " . $descripcion . " ya existe.";
					} else {
						$data = array(
							"idcomercio" => $this->_comercioData->id,
							"descripcion" => UFtext($descripcion)
						);
						if($this->_db->insert("categorias", $data)->error()) {
							$this->_error = true;
							$this->_errMsg[] = $this->_db->errMsg();
						}
					}
				} else {
					$this->_error = true;
					$this->_errMsg[] = $this->_db->errMsg();
				}
			} else {
				$this->_error = true;
				$this->_errMsg[] = "No hay datos del Comercio.";
			}
			return $this;
		}

		public function getSubCategorias($idcategoria, $conItems = false) {
			if(!is_null($this->_comercioData)) {
				//Sub Categorias
				$xSQL = "SELECT * FROM subcategoria";
				$xSQL .= " WHERE idcomercio = " . $this->_comercioData->id;
				$xSQL .= " AND idcategoria = " . $idcategoria;
				$xSQL .= " AND stock = 1";
				$xSQL .= " ORDER BY descripcion";
				$subcategorias = $this->_db->query($xSQL);
				if(!$subcategorias->error()) {
					if($subcategorias->count() > 0) {
						$regs_subcategorias = $subcategorias->results();
						if($conItems) {
							foreach($regs_subcategorias as $value_subcategorias) {
								//Determinar si utiliza un master u opciones
								if($value_subcategorias->idmaster > 0) {
									$value_subcategorias->listaitems = $this->getItemsLista($value_subcategorias->idmaster);
								} else {
									$xSQL = "SELECT descripcion FROM items WHERE idsubcategoria = " . $value_subcategorias->id;
									$items = $this->_db->query($xSQL);
									if(!$items->error()) {
										$reg_items = $items->results();
										$value_subcategorias->listaitems = $reg_items;
									}
								}
							}
						}
						return $regs_subcategorias;
					} else {
						return false;
					}
				}
			} else {
				return false;
			}
		}

		public function getSubCategoriaData($idsubcategoria) {
			//Sub Categoria Data
			$xSQL = "SELECT * FROM subcategoria";
			$xSQL .= " WHERE id = " . $idsubcategoria;
			$subcategoria = $this->_db->query($xSQL);
			if(!$subcategoria->error()) {
				if($subcategoria->count() > 0) {
					$regs_subcategoria = $subcategoria->first();
					//Determinar si utiliza un master u opciones
					if($regs_subcategoria->idmaster > 0) {
						$xSQL = "SELECT id, descripcion FROM masters WHERE id = " . $regs_subcategoria->idmaster;
						$items = $this->_db->query($xSQL);
						if(!$items->error()) {
							$regs_subcategoria->bufferItems = $items->first();
						}
					} else {
						$xSQL = "SELECT descripcion FROM items WHERE idsubcategoria = " . $regs_subcategoria->id;
						$items = $this->_db->query($xSQL);
						if(!$items->error()) {
							$reg_items = $items->results();
							$regs_subcategoria->bufferItems = array();
							foreach($reg_items as $value) {
								$regs_subcategoria->bufferItems[] = $value->descripcion;
							}
						}
					}
					return $regs_subcategoria;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function getItemsSubCategoria($idsubcategoria) {
			//Sub Categorias
			$xSQL = "SELECT idmaster FROM subcategoria";
			$xSQL .= " WHERE id = " . $idcategoria;
			$xSQL .= " AND stock = 1";
			$subcategoria = $this->_db->query($xSQL);
			if(!$subcategoria->error()) {
				if($subcategoria->count() > 0) {
					$regs_subcategoria = $subcategorias->first();
					//Determinar si utiliza un master u opciones
					if($regs_subcategoria->idmaster > 0) {
						return $this->getItemsLista($regs_subcategoria->idmaster);
					} else {
						$xSQL = "SELECT descripcion FROM items WHERE idsubcategoria = " . $regs_subcategoria->id;
						$items = $this->_db->query($xSQL);
						if(!$items->error()) {
							return $items->results();
						}
					}
				} else {
					return false;
				}
			}
		}

		public function getListas() {
			if(!is_null($this->_comercioData)) {
				//Listas
				$xSQL = "SELECT id, descripcion FROM masters";
				$xSQL .= " WHERE idcomercio = " . $this->_comercioData->id;
				$xSQL .= " ORDER BY descripcion";
				$listas = $this->_db->query($xSQL);
				if(!$listas->error()) {
					return $listas->results();
				}
			} else {
				return false;
			}
		}

		public function getItemsLista($idmaster) {
			if(!is_null($this->_comercioData)) {
				//Items Lista
				$xSQL = "SELECT id, descripcion, stock FROM itemsmaster";
				$xSQL .= " WHERE idmaster = " . $idmaster;
				$xSQL .= " ORDER BY descripcion";
				$items = $this->_db->query($xSQL);
				if(!$items->error()) {
					return $items->results();
				}
			} else {
				return false;
			}
		}

		public function addLista($descripcion) {
			$this->_error = false;
			$this->_errMsg = null;
			if(!is_null($this->_comercioData)) {
				//Verificar que no existe en la lista
				$xSQL = "SELECT id FROM masters";
				$xSQL .= " WHERE idcomercio = " . $this->_comercioData->id;
				$xSQL .= " AND descripcion = '" . $descripcion . "'";
				$masters = $this->_db->query($xSQL);
				if(!$masters->error()) {
					if($masters->count()) {
						$this->_error = true;
						$this->_errMsg[] = "La lista " . $descripcion . " ya existe.";
					} else {
						$data = array(
							"idcomercio" => $this->_comercioData->id,
							"descripcion" => UFtext($descripcion)
						);
						if($this->_db->insert("masters", $data)->error()) {
							$this->_error = true;
							$this->_errMsg[] = $this->_db->errMsg();
						}
					}
				} else {
					$this->_error = true;
					$this->_errMsg[] = $this->_db->errMsg();
				}
			} else {
				$this->_error = true;
				$this->_errMsg[] = "No hay datos del Comercio.";
			}
			return $this;
		}

		public function error() {
			return $this->_error;
		}

		public function errMsg() {
			return $this->_errMsg;
		}
	}
?>