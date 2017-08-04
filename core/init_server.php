<?php
/*
ini_set('session.gc_maxlifetime', 2*60*60);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
session_save_path($_SERVER["DOCUMENT_ROOT"] . 'app/sessions');
session_set_cookie_params(2*60*60);
*/
session_start();
date_default_timezone_set("America/Argentina/San_Luis");
$GLOBALS["config"] = array(
	"database" => array (
		"driver" => "mysql",
		"username" => "u249335311_jf",
		"password" => "quilmes"
	),
	"dns" => array (
		//"host" => "localhost",
		"host" => "127.0.0.1",
		//"port" => "3306",
		"db" => "u249335311_jf"
	),
	"dboptions" => array (
		"PDO::ATTR_PERSISTENT" => true,
		"PDO::MYSQL_ATTR_INIT_COMMAND" => "set names utf8"
	),
	"dbattributes" => array (
		"PDO::ATTR_ERRMODE" => "PDO::ERRMODE_EXCEPTION"
	),
	"remember" => array(
		"cookie_name" => "hash",
		"cookie_expire" => 604800
	),
	"session" => array(
		"session_name" => "user",
		"token_name" => "name"
	)
);

/*
//Fuerza a los nombres de columnas a una capitalización específica
PDO::ATTR_CASE => PDO::CASE_LOWER o PDO::CASE_NATURAL o PDO::CASE_UPPER
//Reporte de errores
PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT o PDO::ERRMODE_WARNING o PDO::ERRMODE_EXCEPTION
//Conversión de NULL y cadenas vacías (disponible para todos los drivers, no sólo Oracle)
PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL o PDO::NULL_EMPTY_STRING o PDO::NULL_TO_STRING
//Convierte los valores numéricos a cadenas cuando se buscan
PDO::ATTR_STRINGIFY_FETCHES => false o true
//Establece la clase de sentencia proporcionada por el usuario derivada de PDOStatement. No puede ser usado con instancias PDO persistentes
PDO::ATTR_STATEMENT_CLASS => array(string classname, array(mixed constructor_args))
//Especifica la duración del tiempo de espera en segundos. No todos los drivers soportan esta opcion, y es diferente dependiendo del driver.
PDO::ATTR_TIMEOUT => int
//Whether to autocommit every single statement (disponible en OCI, Firebird y MySQL).
PDO::ATTR_AUTOCOMMIT => ??? !importante ver
//Enables or disables emulation of prepared statements
PDO::ATTR_EMULATE_PREPARES => true o false !importante ver
//Set default fetch mode
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC o PDO::FETCH_BOTH o PDO::FETCH_BOUND o PDO::FETCH_CLASS o PDO::FETCH_INTO o PDO::FETCH_LAZY o PDO::FETCH_NAMED o PDO::FETCH_NUM o PDO::FETCH_OBJ

// Se debe pasar en el constructor
PDO::MYSQL_ATTR_MAX_BUFFER_SIZE => 1024*1024*50 (50MB)
//Use buffered queries (available in MySQL).
PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => ???
*/


/*
$valid_options = array(
	PDO::ATTR_PERSISTENT => 'PDO::ATTR_PERSISTENT',
	PDO::ATTR_AUTOCOMMIT => 'PDO::ATTR_AUTOCOMMIT',
	PDO::ATTR_TIMEOUT => 'PDO::ATTR_TIMEOUT',
	PDO::ATTR_EMULATE_PREPARES => 'PDO::ATTR_EMULATE_PREPARES',
	PDO::MYSQL_ATTR_USE_BUFFERED_QUERY	=> 'PDO::MYSQL_ATTR_USE_BUFFERED_QUERY',
	PDO::MYSQL_ATTR_LOCAL_INFILE => 'PDO::MYSQL_ATTR_LOCAL_INFILE',
	PDO::MYSQL_ATTR_DIRECT_QUERY => 'PDO::MYSQL_ATTR_DIRECT_QUERY',
	PDO::MYSQL_ATTR_INIT_COMMAND => 'PDO::MYSQL_ATTR_INIT_COMMAND'
);

$defaults = array(
	PDO::ATTR_PERSISTENT => false,
	PDO::ATTR_AUTOCOMMIT => 1,
	PDO::ATTR_TIMEOUT => false,
	PDO::ATTR_EMULATE_PREPARES => 1,
	PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => 1,
	PDO::MYSQL_ATTR_LOCAL_INFILE => false,
	PDO::MYSQL_ATTR_DIRECT_QUERY => 1,
	PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
);
*/

$server_path = $_SERVER["DOCUMENT_ROOT"]; //local
define("SERVER_PATH", $server_path);
spl_autoload_register(function ($class) {
    include_once(SERVER_PATH . "/recursos/classes/" . $class . ".php");
});
include_once(SERVER_PATH . "/recursos/functions/sanitize.php");
if(Cookie::exist(Config::get("remember/cookie_name")) && !Session::exist(Config::get("session/session_name"))) {
	//echo "User asked to be remember";
	$hash = Cookie::get(Config::get("remember/cookie_name"));
	$hashCheck = DB::getInstance()->get("users_session", array("hash", "=", $hash));
	if($hashCheck->count()) {
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}
}
?>