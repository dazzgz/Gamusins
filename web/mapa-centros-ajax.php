<?php
define("RUTA_RAIZ","");
require_once("_SiteLayout.php");

function parseToXML($htmlStr)
{
	$xmlStr=str_replace('<','&lt;',$htmlStr);
	$xmlStr=str_replace('>','&gt;',$xmlStr);
	$xmlStr=str_replace('"','&quot;',$xmlStr);
	$xmlStr=str_replace("'",'&#39;',$xmlStr);
	$xmlStr=str_replace("&",'&amp;',$xmlStr);
	return $xmlStr;
}

header("Content-type: text/xml");
	
	$Empleado_ID = intval(parametro("empleado_ID"));
	$FechaIni = stripslashes(parametro("fechaIni"));
	$FechaFin = stripslashes(parametro("fechaFin"));
	//$texto = stripslashes(parametro("texto"));
	
	$strSQLWhere = " WHERE 1 = 1";
	if($Empleado_ID > 0) {
		$strSQLWhere .= " AND Empleado_ID = ".$Empleado_ID;
	}
	if($FechaIni != '') {
		$strSQLWhere .= " AND Fecha >= '".fechaHoraMySQL($FechaIni." 00:00:00")."' ";
	}
	if($FechaFin != '') {
		$strSQLWhere .= " AND Fecha <= '".fechaHoraMySQL($FechaFin." 23:59:59")."' ";
	}
	
	$PA = "SELECT EmpleadoAccion_ID, Empleado_ID, Lat, Lon, Fecha, Titulo, Accion_ID";
	$PA .= " FROM tbEmpleadoAccion";
	$PA .= $strSQLWhere;
	$PA .= " ORDER BY Fecha";
//echo $PA;
	//$resultado = ExecPA($PA);
	$NumElementos = mysql_affected_rows();
	if($NumElementos == 0){
		Desconecta();
		$strError = TextoDeIdioma("No_se_ha_encontrado_el_registro_solicitado");
		
		$strHtml = '<markers>';
		$strHtml .= '<marker ';
		$strHtml .= 'id="1" ';
		$strHtml .= 'titulo="Hola" ';
		$strHtml .= 'fecha="' . parseToXML("2013-06-25 02:15:16") . '" ';
		$strHtml .= 'lat="41.679108" ';
		$strHtml .= 'lng="-0.866335" ';
		$strHtml .= 'accion="1" ';
		$strHtml .= '/>';
		$strHtml .= '</markers>';
	}else{
		// Start XML file, echo parent node
		$strHtml = '<markers>';
		for($i=0;$i<$NumElementos;$i++)
		{
			$EmpleadoAccion_ID 	= intval(ValorCelda($resultado,$i,"EmpleadoAccion_ID"));
			$Empleado_ID 	= intval(ValorCelda($resultado,$i,"Empleado_ID"));
			$Lat 					= floatval(ValorCelda($resultado,$i,"Lat"));
			$Lon 					= floatval(ValorCelda($resultado,$i,"Lon"));
			$Fecha 				= FechaHoraNormal(ValorCelda($resultado,$i,"Fecha"));
			$Titulo 			= stripslashes(ValorCelda($resultado,$i,"Titulo"));
			$Accion_ID 		= intval(ValorCelda($resultado,$i,"Accion_ID"));
			
			// ADD TO XML DOCUMENT NODE
			$strHtml .= '<marker ';
			$strHtml .= 'id="' . $EmpleadoAccion_ID . '" ';
			$strHtml .= 'titulo="' . parseToXML($Titulo) . '" ';
			$strHtml .= 'fecha="' . parseToXML($Fecha) . '" ';
			$strHtml .= 'lat="' . $Lat . '" ';
			$strHtml .= 'lng="' . $Lon . '" ';
			$strHtml .= 'accion="' . $Accion_ID . '" ';
			$strHtml .= '/>';
		}
		// End XML file
		$strHtml .= '</markers>';
	}
	echo $strHtml;
?>