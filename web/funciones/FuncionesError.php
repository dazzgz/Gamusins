<?
/*
1 => 'E_ERROR'
2 => 'E_WARNING'
4 => 'E_PARSE'
8 => 'E_NOTICE'
16 => 'E_CORE_ERROR'
32 => 'E_CORE_WARNING'
64 => 'E_COMPILE_ERROR'
128 => 'E_COMPILE_WARNING'
256 => 'E_USER_ERROR' 
512 => 'E_USER_WARNING'
1024 => 'E_USER_NOTICE'
2047 => 'E_ALL '
*/

// error handler function
function myErrorHandler($errno,$errstr,$errfile, $errline){
	$ERROR = '';
	$ERROR.= "<b>Tipo de error desconocido:</b> $errstr <br />\n en el fichero '$errfile' en la linea '$errline' <br /><br />";
	
	if(isset($_SERVER['SCRIPT_NAME']) && strlen($_SERVER['SCRIPT_NAME'])>0){
		$ERROR.= '<b>Script_name:</b> '.$_SERVER['SCRIPT_NAME'].'<br />';
	}
	if(isset($_SERVER['SCRIPT_FILENAME']) && strlen($_SERVER['SCRIPT_FILENAME'])>0){
		$ERROR.= '<b>Script_filename:</b> '.$_SERVER['SCRIPT_FILENAME'].'<br />';
	}
	if(isset($_SERVER['PHP_SELF']) && strlen($_SERVER['PHP_SELF'])>0){
		$ERROR.= '<b>Php_Self:</b> '.$_SERVER['PHP_SELF'].'<br />';
	}
	if(isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING'])>0){
		$ERROR.= '<b>Query string:</b> '.$_SERVER['QUERY_STRING'].'<br />';
	}
	if(isset($_SERVER['REMOTE_ADDR']) && strlen($_SERVER['REMOTE_ADDR'])>0){
		$ERROR.= '<b>IP:</b> '.$_SERVER['REMOTE_ADDR'].'<br />';
	}
	if(isset($_SERVER['REQUEST_METHOD']) && strlen($_SERVER['REQUEST_METHOD'])>0){
		$ERROR.= '<b>Metodo:</b> '.$_SERVER['REQUEST_METHOD'].'<br />';
	}
	if(isset($_SERVER['HTTP_USER_AGENT']) && strlen($_SERVER['HTTP_USER_AGENT'])>0){
		$ERROR.= '<b>Explorador:</b> '.$_SERVER['HTTP_USER_AGENT'].'<br />';
	}
		
	//if(ENTORNO==0){
		switch ($errno){
			case E_USER_ERROR:
				terminar('<h4>E_USER_ERROR</h4>'.$ERROR);
				break;
			case E_USER_WARNING:
				terminar('<h4>E_USER_WARNING</h4>'.$ERROR);
				break;
			case E_USER_NOTICE;
				terminar('<h4>E_USER_NOTICE</h4>'.$ERROR);
				break;
			case E_NOTICE;
				//terminar($ERROR);
				break;
			default:
				//terminar($ERROR);
				break;
		}
	/*}else{
		switch ($errno){
			case E_USER_ERROR:
				if(enviarPhpMailer(EMAIL_ERROR,EMAIL_ERROR,'E_USER_ERROR EN UNA P�GINA DE '.strtoupper(PROJECT_NAME),$ERROR)){
				}
				terminar('<ul><b>Se ha producido un error en el servidor.</b><br>Se ha enviado una notificacion al administrador.<br>Disculpe las molestias.<br><br><a href=/index.php>Pulse aqu�</a> para volver a la portada del web.</ul><br>');
				break;
				
			case E_USER_WARNING:
				if(enviarPhpMailer(EMAIL_ERROR,EMAIL_ERROR,'E_USER_WARNING EN UNA P�GINA DE '.strtoupper(PROJECT_NAME),$ERROR)){
				}
				break;
			case E_USER_NOTICE;
				break;
				
			case E_NOTICE;
				break;
				
			default:
				break;
		}
	}*/
}

function terminar($strError){
	echo $strError;
	die();
}

// pongo mi manejador de errores y guardo el actual por si las moscas
$old_error_handler = set_error_handler('myErrorHandler');
?>