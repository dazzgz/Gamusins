<?php
define("RUTA_ADM", "/adm/");
define("RUTA_RAIZ", $_SERVER["DOCUMENT_ROOT"].'/');
require_once(RUTA_RAIZ."_entorno.php");
require_once(RUTA_RAIZ."_constantes.php");
require_once(RUTA_RAIZ."funciones/FuncionesDB.php");
require_once(RUTA_RAIZ."funciones/FuncionesComunes.php");
 
$pais_ID = intval($_GET['pais_ID']);
$db = Conecta();
$strSQL = "SELECT IdProvincia valor, Nombre descripcion FROM tbProvincia WHERE IdPais = ".$pais_ID." ORDER BY Nombre";
$resultado = ExecPA($strSQL, $db);
$NumElementos = mysql_affected_rows();

$strHtml = '<option value="">-- '.TextoDeIdioma("Seleccione_un_elemento").' --</option>';
for($i=0;$i<$NumElementos;$i++)
{
	$valor				= intval(ValorCelda($resultado,$i,"valor"));
	$descripcion 	= stripslashes(ValorCelda($resultado,$i,"descripcion"));
	$strHtml .= '<option value="'.$valor.'">'.$descripcion.'</option>';
}
echo $strHtml;
?>