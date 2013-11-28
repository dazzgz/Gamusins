<?
date_default_timezone_set('Europe/Madrid');
ini_set('arg_separator.output','&amp;');
if(!ini_get('session.auto_start'))	session_start();
	
//-----------------------PARTES_PAGINA------------------------------------------//
//METAS
	//define('CHARSET','ISO-8859-1');
	define('CHARSET',				'UTF-8');
	define('CONTENT_TYPE',			'text/html; charset='.CHARSET);
	
//SERVICIOS
	//define('DOMAIN_GOOGLE_ANALYTICS','www.daruma.com');

//IDIOMA DEFAULT	
	define('ID_IDIOMA_DEF',			1); //Castellano
	define('COD_IDIOMA',				'es');
	define('IDIOMA_PANEL',			'es');

//ROLES PARA PERMISOS
	define('ROL_SUPER_ADMIN_ID',		1);
	define('ROL_BANCO_ID',					2);
	define('ROL_PROVEEDOR_ID',			3);
	define('ROL_CLIENTE_ID',				4);
	
	define('ACCION_NUEVO',			'nuevo');
	define('ACCION_EDITAR',			'editar');
	define('ACCION_BORRAR',			'borrar');

//SECCIONES PANEL CONTROL
	define('SEC_ADM_PANELCONTROL',	0);
	
	define('SEC_ADM_USUARIOS', 	1);
	define('SEC_ADM_ROLES', 		2);
	define('SEC_ADM_MENSAJES', 	3);
	define('SEC_ADM_ACCESOS', 	4);
	define('SEC_ADM_AYUDA', 		5);
	
	define('SEC_ADM_CLIENTES', 				10);
	define('SEC_ADM_PROVEEDORES', 		20);
	define('SEC_ADM_PARAMETROS', 						30);
	define('SEC_ADM_PARAMETROS_CATEGORIAS', 301);
	define('SEC_ADM_PARAMETROS_MEDIDAS', 		302);
	define('SEC_ADM_PEDIDOS', 		40);
	
//SECCIONES FRONT
	define('SEC_INICIO',									1);
	define('SEC_REGISTRO_CLIENTE',				2);
	define('SEC_REGISTRO_PROVEEDOR',			3);
	define('SEC_PEDIDO',									4);
	define('SEC_PEDIDO_MAPA',							5);
	define('SEC_CONTACTO',								6);
	define('SEC_ACERCA_DE',										7);
	

	define ('REMITENTE_APP', 0);
	
//-----------------------RUTAS Y UPLOAD RECURSOS------------------------------------------//
		
// RUTAS Y UPLOAD	
	define('DOC_ROOT',				'/');
	define('DIR_IMAGENES',			'img/');
	define('DIR_JS',				'js/');
	define('DIR_ESTILOS',			'css/');
	define('DIR_PANELCONTROL',		'adm/');
	define('DIR_UPLOAD',			'ficheros/');

// OTROS
	define('REG_POR_PAG_DEF',		25);
	
// TIPOS DE ELECTRICIDAD	
	define ('TIPO_ELECTRICIDAD_ENERGIA', 1);
	define ('TIPO_ELECTRICIDAD_REACTIVA', 2);
	define ('TIPO_ELECTRICIDAD_POTENCIA', 3);
	
//---------------------PERSONALIZADOS------------------------------------------//	
switch(ENTORNO){
	case 0: // Desarrollo
		//define('RAIZ_FILE_SYSTEM',		'C:\Users\desarrollo\Dropbox\Arkinos\AppAlimentar\web');
		define('RAIZ_FILE_SYSTEM',		$_SERVER["DOCUMENT_ROOT"]);
		define('PROJECT_NAME','AppAlimentar');
		define('SERVER','http://localhost:8081');
		
		// Emails
		define('EMAIL_PARA',						'dazzgz@gmail.com');
		define('EMAIL_FROM_BOLETIN',		'dazzgz@gmail.com');
		define('EMAIL_ERROR_SILENCIOSO','dazzgz@gmail.com');
		define('EMAIL_ERROR',						'dazzgz@gmail.com');
		
		// Base de datos
		define('DB_HOST','localhost');
		define('DB_NAME','AppAlimentar');
		define('DB_USER','root');
		define('DB_PASS','12345678');
		//define('DB_PASS','');
		
		// PHPMAILER
		define('PHPMAILER_TIPOHOST',	'smtp');
		define('PHPMAILER_PORT',		'465');
		define('PHPMAILER_HOST',		'ssl://smtp.gmail.com');
		define('PHPMAILER_USER',		'desarrollo.mailer@gmail.com');
		define('PHPMAILER_PWD',			'desarrollo.mailer!@');
		break;
	
	case 1: // PRODUCCION
		//define('RAIZ_FILE_SYSTEM',		'/var/www/vhosts/clientesasicxxi.es/httpdocs');
		define('RAIZ_FILE_SYSTEM',		$_SERVER["DOCUMENT_ROOT"]);
		define('PROJECT_NAME','AppAlimentar');
		define('SERVER','http://appalimentar.aloneintheweb.com');
		
		// Emails
		define('EMAIL_PARA',						'dazzgz@gmail.com');
		define('EMAIL_FROM_BOLETIN',		'dazzgz@gmail.com');
		define('EMAIL_ERROR_SILENCIOSO','dazzgz@gmail.com');
		define('EMAIL_ERROR',						'dazzgz@gmail.com');
		
		// Base de datos
		define('DB_HOST','db501799281.db.1and1.com');
		define('DB_NAME','db501799281');
		define('DB_USER','dbo501799281');
		define('DB_PASS','AppAlimentar!@');
			
		// PHPMAILER
		define('PHPMAILER_TIPOHOST',	'smtp');
		define('PHPMAILER_PORT',		'465');
		define('PHPMAILER_HOST',		'ssl://smtp.gmail.com');
		define('PHPMAILER_USER',		'desarrollo.mailer@gmail.com');
		define('PHPMAILER_PWD',			'desarrollo.mailer!@');
		break;
}
?>