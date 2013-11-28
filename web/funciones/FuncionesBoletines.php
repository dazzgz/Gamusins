<?
function sincabecera(){
	$strHtml = "<html>\n";
	$strHtml .= "<head>\n";
	$strHtml .= "	<title>Boletín. ".PROJECT_NAME."</title>\n";
	$strHtml .= "	<meta http-equiv=\"Content-Type\" content=\"".CONTENT_TYPE."\" />\n";
	$strHtml .= "	<meta http-equiv=\"Expires\" content=\"0\">\n";
	$strHtml .= "	<meta http-equiv=\"Pragma\" content=\"no-cache\">\n";
	$strHtml .= "	<meta http-equiv=\"Cache-Control\" content=\"no-cache, no-store,must-revalidate\">\n";
	$strHtml .= "</head>\n";
	$strHtml .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".DOC_ROOT.DIR_ESTILOS."boletin.css\">\n";
	return $strHtml;
}
function sinpie(){
	$strHtml = "</body>\n";
	$strHtml .= "</html>\n";
	return $strHtml;
}
function componerBoletin($Boletin_ID, &$html_body, &$strError, &$Lista_ID, &$bolet_Titulo=''){
	$bError = false;
	
	$html_body 		= '';
	$strError 		= '';
	$Lista_ID 		= 0;
	$bolet_Titulo	= '';
	
	$PA  = "SELECT  tbLista.Cabecera, tbLista.Pie, ";
	$PA .= "		tbBoletin.Lista_ID, tbBoletin.Titulo, tbBoletin.Fecha ";
	$PA .= " FROM 	tbBoletin, tbLista ";
	$PA .= " WHERE 	tbBoletin.Lista_ID = tbLista.Lista_ID ";
	$PA .= "		AND tbBoletin.Boletin_ID = ".$Boletin_ID;
	$resultado = ExecPA($PA);
	$NumElementos=mysql_affected_rows();
	if($NumElementos<=0){
		$strError .= TXT_No_existe_boletin."<br />";
		$bError = true;
	}else{
		$bolet_Cabecera   	= ValorCelda($resultado,0,"Cabecera");
		$bolet_Pie   		= ValorCelda($resultado,0,"Pie");
		$Lista_ID 		 	= intval(ValorCelda($resultado,0,"Lista_ID"));
		$bolet_Titulo   	= ValorCelda($resultado,0,"Titulo");
		$bolet_Fecha   		= ValorCelda($resultado,0,"Fecha");
		
		$html_body = "	<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">\n";
		$html_body .= "	<html lang=\"es\" xmlns=\"http://www.w3.org/1999/xhtml\">\n";
		$html_body .= "		<head>\n";
		$html_body .= "			<title>".codeToAlternative($bolet_Titulo)." ".PROJECT_NAME."</title>\n";
		$html_body .= "			<meta http-equiv=\"Content-Type\" content=\"".CONTENT_TYPE."\" />\n";
		$html_body .= "			<link rel=\"stylesheet\" type=\"text/css\" href=\"".SERVER."/css/boletin.css\">\n";		
		$html_body .= "		</head>\n";	
		$html_body .= "		<body>";
		$html_body .= "			<p align=\"center\" class=\"NoLoVe\">".TXT_Si_no_ves.", <a href=\"".SERVER."/usuarios/ver_boletin.php?Boletin_ID=".$Boletin_ID."\">".strtolower(TXT_Pulsa_aqui)."</a>.</p>\n";
		$html_body .= "			<div id=\"Principal\">\n";
		$html_body .= $bolet_Cabecera;
		$html_body .= "				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
		$html_body .= "					<tr>";
		$html_body .= "						<td class=\"TituloBoletin\">\n";
		$html_body .= $bolet_Titulo;
		$html_body .= "						</td>";
		$html_body .= "						<td class=\"FechaBoletin\">\n";
		$html_body .= fechaNormal($bolet_Fecha);
		$html_body .= "						</td>\n";
		$html_body .= "					</tr>\n";
		$html_body .= "				</table>\n";
		//----------------------------------------------------	
			//--> PARRAFOS
			$PA = "SELECT Parrafo_ID, Antetitulo, Titulo, Texto ";
			$PA .= " FROM tbParrafo ";
			$PA .= " WHERE Boletin_ID = ".$Boletin_ID." ";
			$PA .= " ORDER BY Orden;";
			$resultado = ExecPA($PA);
			$NumElementos=mysql_affected_rows();
			$html_body .= "				<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" class=\"Cuerpo\">\n";
			$html_body .= "					<tr>\n";
			$html_body .= "						<td>\n";
			If($NumElementos<=0){
				$strError .= "						<p>".TXT_Err_sin_parrafos."</p>";
				$bError = true;
			}Else{
				For($i=0;$i<$NumElementos;$i++)
				{
					$Parrafo_ID = ValorCelda($resultado,$i,"Parrafo_ID");
					$Antetitulo = stripslashes(ValorCelda($resultado,$i,"Antetitulo"));
					$Titulo 	= stripslashes(ValorCelda($resultado,$i,"Titulo"));
					$Texto 		= stripslashes(ValorCelda($resultado,$i,"Texto"));
					$Recursos = "";
					$Recursos = SacarRecursos(SECREC_PARRAFO_FOTO,$Parrafo_ID);
					if(strlen($Antetitulo)>0) {
						$html_body .= "					<h5>".$Antetitulo."</h5>\n";
					}
					$html_body .= "					<h1>".stripslashes(ValorCelda($resultado,$i,"Titulo"))."</h1>\n";
					$html_body .= "					<table width=\"100%\" cellspacing=\"0\" border=\"0\" class=\"Parrafo\">\n";
					$html_body .= "						<tr>\n";
					$html_body .= "							<td valign=\"top\" class=\"Texto\">\n";
					If($Texto!="")
					{
						// Pongo el texto con las im&aacute;genes a la derecha
						$html_body .=			$Texto;
						If(is_array($Recursos))
						{
							$html_body .= "					</td>\n";
							$html_body .= "					<td valign=\"top\" width=\"220\" class=\"Recurso\">\n";
							Foreach($Recursos as $Recurso)
							{
								$html_body .= "					<img src=\"".SERVER.DOC_ROOT.DIR_UPLOAD.$Recurso["Recurso_ID"].$Recurso["Extension"]."\" width=\"220\" class=\"Foto\" />\n";
								If($Recurso["Pie"]!="")
								{
									$html_body .= $Recurso["Pie"];
								}
							}
						}
					}
					Else
					{
						// Para que esten en el centro o de dos en dos
						If(is_array($Recursos))
						{
							Foreach($Recursos as $Recurso)
							{
								$html_body .= "					<img src=\"".SERVER.DOC_ROOT.DIR_UPLOAD.$Recurso["Recurso_ID"].$Recurso["Extension"]."\" class=\"Foto\" />\n";
							}
						}
					}
					$html_body .= "							</td>\n";
					$html_body .= "						</tr>\n";
					$html_body .= "					</table>\n";
				}
				$html_body .= "					</td>\n";
				$html_body .= "				</tr>\n";
				$html_body .= "			</table>\n";
	
				$html_body .= "				<table width=\"100%\" cellspacing=\"0\" border=\"0\" class=\"Pie\">\n";
				$html_body .= "					<tr>\n";
				$html_body .= "						<td>\n";
				$html_body .= "							".$bolet_Pie."\n";
				$html_body .= "						</td>\n";
				$html_body .= "					</tr>\n";
				$html_body .= "				</table>\n";
			}
			//--> FIN PARRAFOS
		//----------------------------------------------------	
		$html_body .= "			</div>\n";
		$html_body .= "		</body>\n";
		$html_body .= "</html>\n";
	}
	return !$bError;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function HistoricoBoletines($Privado){
	$contador=0;
	
	// Hist&oacute;rico de los boletines enviados.
	$PA =  " SELECT Lista_ID, Nombre";
	$PA .= " FROM 	tbLista ";
	$PA .= " WHERE 	bActivo = 1 ";
	$Resultado = ExecPA($PA);
	$NumElementos = mysql_affected_rows();
	
	$strHtml = "";
	$strHtml.= "&nbsp;<br />";
	$strHtml.= "<h5>Hist&oacute;rico de boletines</h5>";

	for($Fila=0;$Fila<$NumElementos;$Fila++){
		$Lista_ID = IntVal(ValorCelda($Resultado,$Fila,"Lista_ID"));
		$NombreLista = StripSlashes(ValorCelda($Resultado,$Fila,"Nombre"));
		
		$PABoletin = "SELECT Boletin_ID, Titulo FROM tbBoletin WHERE Lista_ID = ".$Lista_ID." AND FechaEnvio>0 ";
		$ResultadoBoletin = ExecPA($PABoletin);
		$NumElementosBoletin = mysql_affected_rows();
		
		if($NumElementosBoletin>0){
			$strHtml .= "<p>Aqui tiene un hist&oacute;rico de los &uacute;ltimos boletines que se han enviado:</p>";
			$strHtml .= "	<p><b>".$NombreLista.":</b></p>";
			$strHtml .= "		<ul>";
			for($Row=0;$Row<$NumElementosBoletin;$Row++){
				$Boletin_ID = IntVal(ValorCelda($ResultadoBoletin,$Row,"Boletin_ID"));
				$Titulo = StripSlashes(ValorCelda($ResultadoBoletin,$Row,"Titulo"));
				if(componerBoletin($Boletin_ID,$strCorreo,$strError,$Lista_ID)){
					$contador++;
					$strHtml .= "			<li><a href=\"".SERVER.DOC_ROOT.DIR_BOLETIN."ver_boletin.php?Boletin_ID=".$Boletin_ID."\" target=\"_BLANK\">".$Titulo."</a></li>";
				}
			}
			$strHtml .= "		</ul>";
		}
	}
	if($contador>0){
		echo $strHtml;
	}else{
		echo "";
	}
}
function get_defaultBoletin($Idioma_ID,&$Lista_ID,&$Lista_nombre){
	$Idioma_ID		= intval($Idioma_ID);
	$Lista_nombre 	= '';
	$Lista_ID		= 0;
	
	if($Idioma_ID>0){
		$PA_lista =  " SELECT 	Lista_ID, Nombre";
		$PA_lista .= " FROM 	tbLista ";
		$PA_lista .= " WHERE 	bActivo = 1 ";
		$PA_lista .= " AND 	bPrivado = 0 ";
		$PA_lista .= " AND 	Idioma_ID = ".$Idioma_ID;
		$PA_lista .= " ORDER BY Lista_ID DESC ";
		$PA_lista .= " LIMIT 1 ";
		$rs_lista = ExecPA($PA_lista);
		$num_lista = mysql_affected_rows();
		if($num_lista>0){
			$Lista_ID 		= intval(ValorCelda($rs_lista,0,"Lista_ID"));
			$Lista_nombre 	= stripslashes(ValorCelda($rs_lista,0,"Nombre"));
		}
	}
}
?>