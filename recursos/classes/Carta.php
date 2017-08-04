<?php
	class Carta {
		private $_db,
				$_comercioData = null,
				$_error = false,
				$_errMsg = null;
		
		public function __construct($comercioId) {
			$this->_db = DB::getInstance();
			$comercio = $this->_db->query("SELECT * FROM comercios WHERE id = " . $comercioId);
		}
	}
}