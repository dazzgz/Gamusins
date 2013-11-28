<?
/*
30/08/2007 TIPOCODRECURSO: AlbertoP
-----------------------------------------------
RESUMEN: 
	"Codificacion del nombre del archivo dependiendo del valor de la variable 'TIPO_CODIFICACION_RECURSO' definidad en constantes.php"
DESCRIPCION: 
	* Si TIPO_CODIFICACION_RECURSO="SinCodificarID"   ---> Sin codificar, utiliza el ID del recurso
	* Si TIPO_CODIFICACION_RECURSO="CodificadoConMD5" ---> Codificado con md5, utiliza el md5 del ID del recurso
NOTA: 
	Se seleccionado una constante externa para evitar tener que leer de la BD desde la parte pública.
NECESIDADES: 
	Necesita declarar en constantes.php:
	define("TIPO_CODIFICACION_RECURSO","SinCodificarID");		
	//define("TIPO_CODIFICACION_RECURSO","CodificadoConMD5");		


04/09/2007 ADMITIR VIDEO: AlbertoP
-----------------------------------------------
RESUMEN: 
	"Permite a este módulo la incrustación de código HTML"
DESCRIPCION: 
	El objetivo de esta modificacion es la de incrustar videos en una web llamando a las funciones de recursos.
NECESIDADES: 
	Nuevos campos en la base de datos:
	->IMAGEN: 					PanelControl/img/objectEmbed.gif
	->ARCHIVO PHP: 				PanelControl/popUpRecursos.php   (para la parte interna del panel de control)
	->TABLA tbRecurso: 			HTMLembed TEXT NULL
	->TABLA tbSeccionRecurso: 	El cambo bImagen toma los valores 0,1 y 2
	
	//////////////////////////////////////////
	//Valores posibles						//
	//0 -> Es archivo						//
	//1 -> Es imagen						//
	//2 -> Es un objeto endebido(case else)	//
	//////////////////////////////////////////	
NOTA: 
	Si un recurso tiene bThumb=1 y su valor en bImagen=2 => no se tendrá en cuenta el valor de bThumb


18/10/2007 GENERAR IMAGEN PEQUEÑA CON GD: AlbertoP
-----------------------------------------------
Ojo: sólo soporta imágenes de aprox 1.5 MB y de extensiones: JPG, JPEG, GIF, PNG


18/10/2007 GENERAR IMAGEN PEQUEÑA CON GD: AlbertoP
-----------------------------------------------


04/06/2008 ABRIR FOTO GRANDE EN POPUP: AlbertoP
-----------------------------------------------
REQUIERE: 
	Necesita definir la cte MOSTRARGRANDE_ENPOPUP = true | false


11/03/2009 ERROR Recurso sin entidad: AlbertoP
-----------------------------------------------
Se añade mensaje de error y comprobacion


16/04/2009 ANCHO MIN-MAX: AlbertoP
-----------------------------------------------
REQUIERE: 
	tbSeccionRecurso.Tamanio_Ancho_MIN


16/04/2009 PIE text y FIRMA: AlbertoP
-----------------------------------------------
MEJORA: 
	Pie->Text y Añadir Firma para recursos 16/09/2009: Alberto


2010 Incluye mejora transparecia para gif y png: AlbertoP
-----------------------------------------------
MEJORA: 
	Pie->Text y Añadir Firma para recursos 16/09/2009: Alberto

*/
function mostrar_formulario_imagenes($Seccion_Recurso_ID,$Entidad_ID=-1){

	$Seccion_Recurso_ID = intval($Seccion_Recurso_ID);
	$Entidad_ID 		= intval($Entidad_ID);
	
	$strHtml = "";
	$PA  = " SELECT Nombre_Seccion, Nombre_Tabla, Numero_Recursos";
	$PA .= "		,Tamanio_MAX, Tamanio_Ancho_MIN";
	$PA .= " 		,Ancho_Pequenia, Alto_Pequenia, Ancho_Grande, Alto_Grande";
	$PA .= " 		,bActiva, Comentario, bOrden, bPie, bFirma, bThumb";
	$PA .= "		,Extensiones_Permitidas, bImagen, Numero_Minimo, bGenerarPequenia";
	$PA .= " FROM 	tbSeccionRecurso";
	$PA .= " WHERE 	Seccion_Recurso_ID = ".$Seccion_Recurso_ID;
	$PA .= " LIMIT 1 ";

	$resultado 		= ExecPA($PA);
	$NumElementos	= mysql_affected_rows();
	if($NumElementos!=1){
		Desconecta();
		$strHtml .= "Error, no existe la sección de recursos ".$Seccion_Recurso_ID.".<br />\n";
	}else{
		//1.0 Recogemos parámetros de la sección
		$Nombre_Seccion 		= ValorCelda($resultado,0,"Nombre_Seccion");
		$Nombre_Tabla 			= ValorCelda($resultado,0,"Nombre_Tabla");
		$Numero_Recursos 		= ValorCelda($resultado,0,"Numero_Recursos");
		$Tamanio_MAX 			= intval(ValorCelda($resultado,0,"Tamanio_MAX"));
		$Tamanio_Ancho_MIN 		= intval(ValorCelda($resultado,0,"Tamanio_Ancho_MIN"));
		$Ancho_Pequenia 		= ValorCelda($resultado,0,"Ancho_Pequenia");
		$Alto_Pequenia 			= ValorCelda($resultado,0,"Alto_Pequenia");
		$Ancho_Grande 			= ValorCelda($resultado,0,"Ancho_Grande");
		$Alto_Grande 			= ValorCelda($resultado,0,"Alto_Grande");
		$bActiva 				= ValorCelda($resultado,0,"bActiva");
		$Comentario 			= ValorCelda($resultado,0,"Comentario");
		$bOrden 				= ValorCelda($resultado,0,"bOrden");
		$bPie 					= ValorCelda($resultado,0,"bPie");
		$bFirma 				= ValorCelda($resultado,0,"bFirma");
		$bThumb 				= ValorCelda($resultado,0,"bThumb");
		$Extensiones_Permitidas = ValorCelda($resultado,0,"Extensiones_Permitidas");
		$bImagen 				= ValorCelda($resultado,0,"bImagen");
		$Numero_Minimo 			= ValorCelda($resultado,0,"Numero_Minimo");
		$bGenerarPequenia 		= intval(ValorCelda($resultado,0,"bGenerarPequenia"));
		
		
		if($Ancho_Pequenia == "") 	$Ancho_Pequenia = "...";
		if($Alto_Pequenia == "") 	$Alto_Pequenia = "...";
		if($Ancho_Grande == "") 	$Ancho_Grande = "...";
		if($Alto_Grande == "")		$Alto_Grande = "...";

		if($Extensiones_Permitidas==""){
			$arrExtensiones_Permitidas="";
		}else{
			$i=0;
			$arrExtensiones_Permitidas[$i] = trim(strtok($Extensiones_Permitidas," "));
			while($tok=strtok(" ")){
				$i++;
				$arrExtensiones_Permitidas[$i]=trim($tok);
			}
		}
		
		
		//1.1 Recogemos datos de los recursos existentes
		$PA  = " SELECT Recurso_ID, Orden, Pie, Firma, Extension, HTMLembed ";
		$PA .= " FROM 	tbRecurso ";
		if(intval($Entidad_ID)==0){ //11/03/2009 -> Evitamos problemas con filas sin entidad
			$PA .= " WHERE Seccion_Recurso_ID = " . $Seccion_Recurso_ID . " AND Entidad_ID = -1";
		}else{
			$PA .= " WHERE Seccion_Recurso_ID = " . $Seccion_Recurso_ID . " AND Entidad_ID = " . $Entidad_ID;
		}
		$PA .= " ORDER BY Orden ASC, Recurso_ID ASC ";
		
		$resultado 			= ExecPA($PA);
		$Imagenes_Mostradas	= mysql_num_rows($resultado);
		for ($i=0;$i<$Imagenes_Mostradas;$i++){
			$Recurso_ID 	= intval(ValorCelda($resultado,$i,"Recurso_ID"));
			$Extension 		= stripslashes(ValorCelda($resultado,$i,"Extension"));
			$Orden 			= stripslashes(ValorCelda($resultado,$i,"Orden"));
			$Pie 			= stripslashes(ValorCelda($resultado,$i,"Pie"));
			$Firma 			= stripslashes(ValorCelda($resultado,$i,"Firma"));
			$HTMLembed 		= stripslashes(ValorCelda($resultado,$i,"HTMLembed"));
			
			$RecursoCript_ID = $Recurso_ID;
			if( TIPO_CODIFICACION_RECURSO=="CodificadoConMD5" ) $RecursoCript_ID = md5($Recurso_ID);
			
			/*Cabecera*/
			$strHtml .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";
			$strHtml .= "	<tr class=\"fondotablas_b\">\n";
			$strHtml .= "		<td width=\"50\" align=\"center\">\n";
			if($bImagen==1){	
				$strHtml .= "			<img src=\"/PanelControl/img/fotos.gif\" width=\"30\" height=\"33\" alt=\"Foto, sección: ".$Seccion_Recurso_ID."\" border=\"0\" /></td><td> Fotograf&iacute;a";
			}elseif($bImagen==2){
				$strHtml .= "			<img src=\"/PanelControl/img/objectEmbed.gif\" width=\"30\" height=\"33\" alt=\"HTML Embed, sección: ".$Seccion_Recurso_ID."\" border=\"0\" /></td><td> V&iacute;deo";			
			}else{
				$strHtml .= "			<img src=\"/PanelControl/img/archivos.gif\" width=\"30\" height=\"33\" alt=\"Archivo, sección: ".$Seccion_Recurso_ID."\" border=\"0\" /></td><td> Archivo";			
			}
			$strHtml .= "		</td>\n";
			$strHtml .= "	</tr>\n";
			$strHtml .= "</table>\n";
			/*Cuerpo*/
			$strHtml .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";
			$strHtml .= "	<tr>\n";
			$strHtml .= "		<td class=\"fondotablas_b\">\n";
			$strHtml .= "			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n";
			
			if(($bThumb || $bGenerarPequenia) && $bImagen!=2){
				//Se trata de una imagen compuesta por un par o generada de forma automatica
				//Recordar: si bGenerarPequenia = 1 entonces bThumb = 0 por construcción del programer
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
				$strHtml .= "					<td align=\"right\" valign=\"top\" width=\"120\"><b>Imagen peque&ntilde;a ".($i+1).":<br /></b></td>";
				if (MOSTRARGRANDE_ENPOPUP){
					$strHtml .= "					<td><a href=\"".DOC_ROOT.DIR_UPLOAD.$RecursoCript_ID."g".$Extension."\" target=\"_blank\"><img src=\"".DOC_ROOT.DIR_UPLOAD.$RecursoCript_ID."p".$Extension."\"";
				}else{
					$strHtml .= "					<td><img src=\"".DOC_ROOT.DIR_UPLOAD.$RecursoCript_ID."p".$Extension."\"";
				}
				if($Ancho_Pequenia <> "..." && !$bGenerarPequenia) $strHtml .= " width=\"".$Ancho_Pequenia."\"";
				if($Alto_Pequenia <> "..." && !$bGenerarPequenia) $strHtml .= " height=\"".$Alto_Pequenia."\"";
				if (MOSTRARGRANDE_ENPOPUP){
					if($Alto_Pequenia > 0){
						$strHtml .= " /><br /></a> ".$Ancho_Pequenia." X ".$Alto_Pequenia." (".tamanioArchivo($RecursoCript_ID."p".$Extension).", ".tamanioArchivo($RecursoCript_ID."g".$Extension).")</td>";
					}else{
						$strHtml .= " /><br /></a> ".$Ancho_Pequenia." X ...  (".tamanioArchivo($RecursoCript_ID."p".$Extension).", ".tamanioArchivo($RecursoCript_ID."g".$Extension).")</td>";
					}	
				}else{
					$strHtml .= "> ".$Ancho_Pequenia." X ".$Alto_Pequenia."  (".tamanioArchivo($RecursoCript_ID."p".$Extension).", ".tamanioArchivo($RecursoCript_ID."g".$Extension).")</td>";
				}
				$strHtml .= "				</tr>";
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
				$strHtml .= "					<td align=\"right\" valign=\"top\"><b>Imagen grande ".($i+1).":<br /></b></td>";
				if (MOSTRARGRANDE_ENPOPUP){
					$strHtml .= "					<td>Pulse sobre la imagen para visualizar";
					if($Alto_Grande > 0){
						$strHtml .= " (".$Ancho_Grande." X ".$Alto_Grande.")</td>";
					}else{
						$strHtml .= " (".$Ancho_Grande." X ...)</td>";
					}
				}else{
					$strHtml .= "					<td><img src=\"".DOC_ROOT.DIR_UPLOAD.$RecursoCript_ID."g".$Extension."\"";
					if($Ancho_Grande <> "..."){
						$strHtml .= " width=\"".$Ancho_Grande."\"";
					}
					if($Alto_Grande <> "..."){
						$strHtml .= " height=\"".$Alto_Grande."\"";
					}
					$strHtml .= " /> ".$Ancho_Grande." X ".$Alto_Grande."</td>";
				}
				$strHtml .= "				</tr>";
			}else{
				if($bImagen==1){
					$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
					$strHtml .= "					<td align=\"right\"  valign=\"top\" width=\"120\"><b>Imagen ".($i+1).":</b></td>";
					$strHtml .= "					<td><img src=\"".DOC_ROOT.DIR_UPLOAD.$RecursoCript_ID.$Extension."\"";
					if($Ancho_Grande <> "..."){
						if($Ancho_Grande>600){
							$strHtml .= " width=\"600\"";
						}else{
							$strHtml .= " width=\"".$Ancho_Grande."\"";
						}
						
					}
					if($Alto_Grande <> "..."){
						if($Ancho_Grande>600){
							$strHtml .= " ";
						}else{
							$strHtml .= " height=\"".$Alto_Grande."\"";
						}
					}
					$strHtml .= " /> ".$Ancho_Grande." X ".$Alto_Grande." (".tamanioArchivo($RecursoCript_ID."".$Extension).")</td>";
					$strHtml .= "				</tr>";
				}elseif($bImagen==2){
					$strHtml .= "				<script language=\"javascript\" type=\"text/javascript\">\n";
					$strHtml .= "					function abrirPopUp(URL,intAncho,intAlto){\n";
					$strHtml .= "						oNewWindow = window.open(URL,\"new\",'height='+ intAlto +', width='+ intAncho +', toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no');\n";
					$strHtml .= "					}\n";
					$strHtml .= "				</script>\n";
					$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
					$strHtml .= "					<td align=\"right\" valign=\"top\" width=\"120\"><b>V&iacute;deo ".($i+1).":</b></td>";
					$strHtml .= "					<td><textarea name=\"htmlembed_".$Seccion_Recurso_ID."_".$Recurso_ID."\" style=\"width:100%\">".$HTMLembed."</textarea><br /><a href=\"#\" onClick=\"abrirPopUp('".SERVER.DOC_ROOT.DIR_PANELCONTROL."popUpRecursos.php?id=".$Recurso_ID."&sec=".$Seccion_Recurso_ID."',700,500);\">[Previsualizar]</a></td>";
					$strHtml .= "				</tr>";
				}else{
					$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
					$strHtml .= "					<td align=\"right\" width=\"120\"><b>Archivo ".($i+1).":</b></td>";
					$strHtml .= "					<td><b>[ <a href=\"".RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID.$Extension."\"><img src=\"/PanelControl/img/flecha2.gif\" width=\"11\" height=\"10\" border=\"0\" align=\"absmiddle\" /> Ver documento</a> ]</b> ".tamanioArchivo($RecursoCript_ID."".$Extension)."</td>";
					$strHtml .= "				</tr>";
				}
			}
			if($bPie){
				$auxTituloPie = "T&iacute;tulo";
				if($bImagen==1) $auxTituloPie = "Pie de la foto";
				if($bImagen!=2 && $bImagen!=1) $auxTituloPie = "Nombre del documento";
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
				$strHtml .= "					<td align=\"right\" valign=\"top\"><b>".$auxTituloPie." ".($i+1).":</b></td>";
				$strHtml .= "					<td valign=\"top\">";
				//$strHtml .= "						<input type=\"text\" name=\"pie_".$Seccion_Recurso_ID."_".$Recurso_ID."\" size=\"80\" maxlegth=\"255\" value=\"".$Pie."\">";
				$strHtml .= "						<textarea name=\"pie_".$Seccion_Recurso_ID."_".$Recurso_ID."\" id=\"pie_".$Seccion_Recurso_ID."_".$Recurso_ID."\" style=\"width:100%\" >".$Pie."</textarea>";
				$strHtml .= "					</td>";
				$strHtml .= "				</tr>";
			}
			if($bFirma){
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
				$strHtml .= "					<td align=\"right\" valign=\"top\"><b>Fuente ".($i+1).":</b></td>";
				$strHtml .= "					<td valign=\"top\">";
				$strHtml .= "						<input type=\"text\" name=\"firma_".$Seccion_Recurso_ID."_".$Recurso_ID."\" id=\"firma_".$Seccion_Recurso_ID."_".$Recurso_ID."\" size=\"80\" maxlength=\"255\" value=\"".$Firma."\">";
				$strHtml .= "					</td>";
				$strHtml .= "				</tr>";				
			}
			if ($bOrden){
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
				$strHtml .= "					<td align=\"right\" width=\"120\"><b>Orden:</b></td>";
				$strHtml .= "					<td><input type=\"text\" name=\"orden_".$Seccion_Recurso_ID."_".$Recurso_ID."\" size=\"4\" value=\"".$Orden."\" maxlegth=\"2\"></td>";
				$strHtml .= "				</tr>";
			}
			$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
			$strHtml .= "					<td align=\"right\" width=\"120\"><b>Borrar:</b></td>";
			$strHtml .= "					<td><input type=\"checkbox\" name=\"borrar_".$Seccion_Recurso_ID."_".$Recurso_ID."\" value=\"1\"></td>";
			$strHtml .= "				</tr>";
			$strHtml .= "			</table>";
			$strHtml .=	"		</td>";
			$strHtml .= "	</tr>";
			$strHtml .= "</table>&nbsp;";
		}//End For (para cuando ya hay imagenes)
		
		//1.2 Miramos cuantos inputs debemos crear nuevos
		if($Numero_Recursos == 0){
			if($Numero_Minimo == 0){
				$Imagenes_Nuevas = 1;
			}else{
				$Imagenes_Nuevas = $numero_Minimo - $Imagenes_Mostradas;
				if($Imagenes_Nuevas<=0){
					$Imagenes_Nuevas = 1;
				}
			}
		}else{
			$Imagenes_Nuevas = $Numero_Recursos - $Imagenes_Mostradas;
		}
		
		//1.3 Pintamos los inputs grandes
		for($i=0;$i<$Imagenes_Nuevas;$i++){
			$strHtml .= "&nbsp;";
			$strHtml .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";
			$strHtml .= "	<tr class=\"bordetablas\">\n";
			$strHtml .= "		<td width=\"50\" align=\"center\">\n";
			if($bImagen==1){
				$strHtml .= "			<img src=\"/PanelControl/img/fotos.gif\" width=\"30\" height=\"33\" alt=\"Foto, sección: ".$Seccion_Recurso_ID."\" title=\"Foto, sección: ".$Seccion_Recurso_ID."\" border=\"0\" /></td><td> Inserci&oacute;n de Fotograf&iacute;a\n";
			}elseif($bImagen==2){
				$strHtml .= "			<img src=\"/PanelControl/img/objectEmbed.gif\" width=\"30\" height=\"33\" alt=\"Video, sección: ".$Seccion_Recurso_ID."\" title=\"Video, sección: ".$Seccion_Recurso_ID."\" border=\"0\" /></td><td> Nuevo v&iacute;deo\n";			
			}else{
				$strHtml .= "			<img src=\"/PanelControl/img/archivos.gif\" width=\"30\" height=\"33\" alt=\"Archivo, sección: ".$Seccion_Recurso_ID."\" title=\"Archivo, sección: ".$Seccion_Recurso_ID."\" border=\"0\" /></td><td> Inserci&oacute;n de archivo\n";			
			}
			$strHtml .= "		</td>\n";
			$strHtml .= "	</tr>\n";
			$strHtml .= "</table>\n";			
			$strHtml .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">\n";			
			$strHtml .= "	<tr>\n";
			$strHtml .= "		<td class=\"bordetablas\">\n";
			$strHtml .= "			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">\n";
			if($bThumb && $bImagen!=2){
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
				$strHtml .= "					<td align=\"right\" width=\"120\"><b>Imagen peque&ntilde;a ".($i+1+$Imagenes_Mostradas).":<br /></b></td>\n";
				$strHtml .= "					<td><input type=\"file\" size=\"50\" name=\"imgpeq_nue_".$Seccion_Recurso_ID."_".$i."\"> ".$Ancho_Pequenia." X ".$Alto_Pequenia."</td>\n";
				$strHtml .= "				</tr>\n";
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
				$strHtml .= "					<td align=\"right\" width=\"120\"><b>Imagen grande ".($i+1+$Imagenes_Mostradas).":<br /></b></td>\n";
				$strHtml .= "					<td><input type=\"file\"  size=\"50\" name=\"imggran_nue_".$Seccion_Recurso_ID."_".$i."\"> ".$Ancho_Grande." X ".$Alto_Grande."</td>\n";
				$strHtml .= "				</tr>\n";
			}else{
				if($bGenerarPequenia && $bImagen!=2){
					$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
					$strHtml .= "					<td align=\"right\" width=\"120\"><b>Imagen grande ".($i+1+$Imagenes_Mostradas).":<br /></b></td>\n";
					$strHtml .= "					<td><input type=\"file\" size=\"50\" name=\"img_nue_".$Seccion_Recurso_ID."_".$i."\"> ".$Ancho_Grande." X ".$Alto_Grande."</td>\n";
					$strHtml .= "				</tr>\n";
				}else{
					if($bImagen==1){
						$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
						$strHtml .= "					<td align=\"right\" width=\"120\"><b>Imagen ".($i+1+$Imagenes_Mostradas).":</b></td>\n";
						$strHtml .= "					<td><input size=\"50\"  type=\"file\" name=\"img_nue_".$Seccion_Recurso_ID."_".$i."\"> ".$Ancho_Grande." X ".$Alto_Grande."</td>\n";
						$strHtml .= "				</tr>\n";
					}elseif($bImagen==2){
						$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
						$strHtml .= "					<td align=\"right\" width=\"120\"><b>V&iacute;deo ".($i+1+$Imagenes_Mostradas).":</b></td>\n";
						$strHtml .= "					<td><textarea name=\"htmlembed_nue_".$Seccion_Recurso_ID."_".$i."\" style=\"width:100%\"></textarea></td>\n";
						$strHtml .= "				</tr>\n";				
					}else{
						$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
						$strHtml .= "					<td align=\"right\" width=\"120\"><b>Archivo ".($i+1+$Imagenes_Mostradas).":</b></td>\n";
						$strHtml .= "					<td><input type=\"file\" size=\"50\" name=\"img_nue_".$Seccion_Recurso_ID."_".$i."\"></td>\n";
						$strHtml .= "				</tr>\n";
					}
				}
			}
			if($bPie){
				$auxTituloPie = "T&iacute;tulo";
				if($bImagen==1) $auxTituloPie = "Pie";
				if($bImagen!=2 && $bImagen!=1) $auxTituloPie = "Nombre del documento";
				
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
				$strHtml .= "					<td align=\"right\" width=\"120\"  valign=\"top\"><b>".$auxTituloPie." ".($i+1).":</b></td>\n";
				$strHtml .= "					<td valign=\"top\">";
				//$strHtml .= "					<input type=\"text\" name=\"pie_nue_".$Seccion_Recurso_ID."_".$i."\" size=\"80\" maxlegth=\"255\">\n";
				$strHtml .= "						<textarea name=\"pie_nue_".$Seccion_Recurso_ID."_".$i."\" id=\"pie_nue_".$Seccion_Recurso_ID."_".$i."\" style=\"width:100%\"></textarea>";
				$strHtml .= "					</td>";
				$strHtml .= "				</tr>\n";
			}
			if($bFirma){
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
				$strHtml .= "					<td align=\"right\" valign=\"top\"><b>Fuente ".($i+1).":</b></td>";
				$strHtml .= "					<td valign=\"top\">";
				$strHtml .= "						<input type=\"text\" name=\"firma_nue_".$Seccion_Recurso_ID."_".$i."\" id=\"firma_nue_".$Seccion_Recurso_ID."_".$i."\" size=\"80\" maxlength=\"255\" />";
				$strHtml .= "					</td>";
				$strHtml .= "				</tr>";		
			}
			if ($bOrden){
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">\n";
				$strHtml .= "					<td align=\"right\" width=\"120\"><b>Orden ".($i+1).":</b></td>\n";
				$strHtml .= "					<td><input type=\"text\" name=\"orden_nue_".$Seccion_Recurso_ID."_".$i."\" size=\"4\" maxlegth=\"2\" value=\"0\"></td>\n";
				$strHtml .= "				</tr>\n";
			}
			if($Comentario!=""){
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
				$strHtml .= "					<td align=\"right\" valign=\"top\"><span class=\"resalte\">Comentarios:</span></td>";
				$strHtml .= "					<td>".$Comentario."</td>";
				$strHtml .= "				</tr>";
			}
			if($Tamanio_Ancho_MIN>0){
				$strHtml .= "				<tr bgcolor=\"#FFFFFF\">";
				$strHtml .= "					<td align=\"right\" valign=\"top\"><span class=\"resalte\">Requerimientos:</span></td>";
				$strHtml .= "					<td>No se admitirán fotos con menos de ".$Tamanio_Ancho_MIN." px de ancho</td>";
				$strHtml .= "				</tr>";	
			}
			$strHtml .= "			</table>";
			$strHtml .= "		</td>";
			$strHtml .= "	</tr>";
			$strHtml .= "</table>\n";
		}
	}
	return $strHtml;
}

function recojer_formulario_imagenes($Seccion_Recurso_ID,$Entidad_ID){
	$Seccion_Recurso_ID = intval($Seccion_Recurso_ID);
	$Entidad_ID 		= intval($Entidad_ID);
	
	$strHtml = "";
	$PA = " SELECT 	Nombre_Seccion, Nombre_Tabla, Numero_Recursos, Tamanio_MAX, Tamanio_Ancho_MIN,";
	$PA .= " 		Ancho_Pequenia, Alto_Pequenia, Ancho_Grande, Alto_Grande,";
	$PA .= " 		bActiva, Comentario, bOrden, bPie, bFirma, bThumb, Extensiones_Permitidas, bImagen, Numero_Minimo, bGenerarPequenia";
	$PA .= " FROM tbSeccionRecurso";
	$PA .= " WHERE Seccion_Recurso_ID = ".$Seccion_Recurso_ID;
//echo $PA;
	$resultado = ExecPA($PA);
	$NumElementos=mysql_affected_rows();
	if($NumElementos!=1 || $Entidad_ID==0){
		Desconecta();
		$strHtml .= "Error, no se pudo recoger el recurso ".$Entidad_ID." para la sección de recursos ".$Seccion_Recurso_ID.".<br />\n";
	}else{
		//2.0 Recogemos parámetros
		$Nombre_Seccion 	= ValorCelda($resultado,0,"Nombre_Seccion");
		$Nombre_Tabla 		= ValorCelda($resultado,0,"Nombre_Tabla");
		$Numero_Recursos 	= ValorCelda($resultado,0,"Numero_Recursos");
		$Tamanio_MAX 		= intval(ValorCelda($resultado,0,"Tamanio_MAX"));
		$Tamanio_Ancho_MIN 	= intval(ValorCelda($resultado,0,"Tamanio_Ancho_MIN"));
		$Ancho_Pequenia 	= ValorCelda($resultado,0,"Ancho_Pequenia");
		$Alto_Pequenia 		= ValorCelda($resultado,0,"Alto_Pequenia");
		$Ancho_Grande 		= ValorCelda($resultado,0,"Ancho_Grande");
		$Alto_Grande 		= ValorCelda($resultado,0,"Alto_Grande");
		$bActiva 			= ValorCelda($resultado,0,"bActiva");
		$Comentario 		= ValorCelda($resultado,0,"Comentario");
		$bOrden 			= ValorCelda($resultado,0,"bOrden");
		$bPie 				= ValorCelda($resultado,0,"bPie");
		$bFirma 			= ValorCelda($resultado,0,"bFirma");
		$bThumb 			= ValorCelda($resultado,0,"bThumb");
		$Extensiones_Permitidas = ValorCelda($resultado,0,"Extensiones_Permitidas");
		$bImagen 			= ValorCelda($resultado,0,"bImagen");
		$Numero_Minimo 		= ValorCelda($resultado,0,"Numero_Minimo");
		$bGenerarPequenia 	= ValorCelda($resultado,0,"bGenerarPequenia");
		
		
		if($Ancho_Pequenia == "") 	$Ancho_Pequenia = "...";
		if($Alto_Pequenia == "")	$Alto_Pequenia = "...";
		if($Ancho_Grande == "")		$Ancho_Grande = "...";
		if($Alto_Grande == "")		$Alto_Grande = "...";

		if($Extensiones_Permitidas==""){
			$arrExtensiones_Permitidas="";
		}else{
			$i=0;
			$arrExtensiones_Permitidas[$i] = trim(strtok($Extensiones_Permitidas," "));
			while($tok=strtok(" ")){
				$i++;
				$arrExtensiones_Permitidas[$i]=trim($tok);
			}
		}
		
		//2.1 Recogemos datos de los recursos
		$PA  = " SELECT Recurso_ID,Extension ";
		$PA .= " FROM 	tbRecurso ";
		$PA .= " WHERE 	Seccion_Recurso_ID = " . $Seccion_Recurso_ID . " AND Entidad_ID = " . $Entidad_ID;
		
		$resultado 	= ExecPA($PA);
		$Recursos	= mysql_num_rows($resultado);
		
		for($f=0;$f<$Recursos;$f++){
			$Recurso_ID = ValorCelda($resultado,$f,"Recurso_ID");
			$extension 	= ValorCelda($resultado,$f,"Extension");
			
			if( TIPO_CODIFICACION_RECURSO=="CodificadoConMD5" ){
				$RecursoCript_ID = md5($Recurso_ID);
			}else{
				$RecursoCript_ID = $Recurso_ID;
			}
			
			//2.1.0 Borro los recursos necesarios sólo si no es HMTL($bImagen!=2)
			if(isset($_POST["borrar_".$Seccion_Recurso_ID."_".$Recurso_ID])){
				$PA = "DELETE FROM tbRecurso WHERE Recurso_ID = ".$Recurso_ID;
				$resultado2 = ExecPA($PA);
				
				if($bImagen==1){
					$strHtml .= "Imagen eliminada correctamente.<br />\n";
				}elseif($bImagen==2){
					$strHtml .= "Objeto incrustado eliminado correctamente.<br />\n";
				}else{
					$strHtml .= "Archivo eliminado correctamente.<br />\n";
				}
				if(($bThumb || $bGenerarPequenia) && $bImagen!=2){
					$archivo_pequenio 	= RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID."p".$extension;
					$archivo_grande 	= RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID."g".$extension;
					
					if(file_exists($archivo_pequenio)) unlink($archivo_pequenio);
					if(file_exists($archivo_grande)) unlink($archivo_grande);
				}else{
					if($bImagen!=2){
						$archivo = RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID.$extension;
						if(file_exists($archivo)) unlink($archivo);
					}
				}
			}else{
			//2.1.1 Actualizamos
				$PA = "UPDATE tbRecurso SET ";
				$PA .= "	Orden = ".intval($_POST["orden_".$Seccion_Recurso_ID."_".$Recurso_ID]);
				$PA .= "	,Pie = '".addslashes($_POST["pie_".$Seccion_Recurso_ID."_".$Recurso_ID])."'";
				$PA .= "	,Firma = '".addslashes($_POST["firma_".$Seccion_Recurso_ID."_".$Recurso_ID])."'";
				$PA .= "	,HTMLembed='".addslashes($_POST["htmlembed_".$Seccion_Recurso_ID."_".$Recurso_ID])."'";
				$PA .= " WHERE Recurso_ID = ".$Recurso_ID;
				$resultado2 = ExecPA($PA);
				
				if($bImagen==1){
					$strHtml .= "Imagen actualizada correctamente.<br />\n";
				}elseif($bImagen==2){
					$strHtml .= "Objeto actualizado correctamente.<br />\n";
				}else{
					$strHtml .= "Archivo actualizado correctamente.<br />\n";
				}
			}
		}//End for
		
		//2.1.2 Preparo la subida de los archivos
		if($bThumb && $bImagen!=2){
			//2.1.2.0 Preparo la subida de 2 archivos
			$i = 0;
			while( isset($_FILES["imgpeq_nue_".$Seccion_Recurso_ID."_".$i]) && isset($_FILES["imggran_nue_".$Seccion_Recurso_ID."_".$i]) ){
				$mi_file_p = "imgpeq_nue_".$Seccion_Recurso_ID."_".$i;
				$mi_file_g = "imggran_nue_".$Seccion_Recurso_ID."_".$i;
				
				if($_FILES[$mi_file_p]["name"]!="" && $_FILES[$mi_file_g]["name"]!=""){
					if(  ( $_FILES[$mi_file_p]["size"]<$Tamanio_MAX && $_FILES["imgpeg_nue_".$Seccion_Recurso_ID."_".$i]["size"]<$Tamanio_MAX ) || $Tamanio_MAX ==0  ){

						$cadena 	= strtoupper($_FILES[$mi_file_p]["name"]);
						$pos 		= strrpos($cadena,".");
						$extension 	= substr($cadena,$pos+1);
						
						if(!is_array($arrExtensiones_Permitidas)){
							$OK = true;
						}else{
							$OK = false;
							foreach($arrExtensiones_Permitidas as $value){
								if(strtoupper($value)==$extension){
									$OK = true;
								}
							}
						}
						$cadena = $_FILES[$mi_file_p]["name"];
						if($OK){
							//Comprobar minimo en ancho
							$OK_minimos = false;
							if($Tamanio_Ancho_MIN>0){
								$arra_propsImg 		= getPropsImagen( $_FILES[$mi_file_p]["tmp_name"] );
								if(is_array($arra_propsImg)){
									if($arra_propsImg[0]>=$Tamanio_Ancho_MIN){	
										$OK_minimos = true;	
									}					
								}
							}	
							if($Tamanio_Ancho_MIN>0 && !$OK_minimos){
								$strHtml .= "<font color=\"#FF0000\">La imagen es demasiado pequeña (tiene ".$arra_propsImg[0]."px de ancho y el ancho mínimo es de  ".$Tamanio_Ancho_MIN." px).</font><br />\n";
							}else{
								$Pie 	= (isset($_POST["pie_nue_".$Seccion_Recurso_ID."_".$i]))?$_POST["pie_nue_".$Seccion_Recurso_ID."_".$i]:"";
								$Firma 	= (isset($_POST["firma_nue_".$Seccion_Recurso_ID."_".$i]))?$_POST["firma_nue_".$Seccion_Recurso_ID."_".$i]:"";
								$Orden 	= (isset($_POST["orden_nue_".$Seccion_Recurso_ID."_".$i]))?intval($_POST["orden_nue_".$Seccion_Recurso_ID."_".$i]):0;
								
								$PA 		= "INSERT INTO tbRecurso(Seccion_Recurso_ID,Entidad_ID,Orden,Pie,Firma,Extension,Original_FileName) VALUES(".$Seccion_Recurso_ID.",".$Entidad_ID.",".intval($Orden).",'".addslashes($Pie)."','".addslashes($Firma)."','.".strtoupper($extension)."','".addslashes($cadena)."');";
								$resultado 	= ExecPA($PA);
								$Recurso_ID = mysql_insert_id();
								
								if($Entidad_ID==0){//11/03/2009
									$strHtml .= "<font color=\"#FF0000\">Error (FR1): Un recurso de la sección #".$Seccion_Recurso_ID." no se creó de forma correcta, contacte con el administrador.</font><br />\n";
									$auxERROR = "ERROR (FR1): Error al crear el recurso #".$Entidad_ID." en la seccion #".$Seccion_Recurso_ID;
									@mail(EMAIL_ERROR,"ERROR EN FUNCION Entidad = 0 ".PROJECT_NAME,$auxERROR);
								}
								
								
								if(TIPO_CODIFICACION_RECURSO=="CodificadoConMD5" ){
									$RecursoCript_ID = md5($Recurso_ID);
								}else{
									$RecursoCript_ID = $Recurso_ID;
								}
								if(!copy($_FILES[$mi_file_p]["tmp_name"],RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID."p.".$extension)){
									$strHtml .= "<font color=\"#FF0000\">La imagen peque&ntilde;a ".$i." no ha podido ser copiada.</font><br />\n";	
								}else{
									$strHtml .= "La imagen ".$i." se ha subido correctamente.<br />\n";
									/////////////////////////////////////////////////////////////////////----------------
									//Necesita ser reducida la pequenia??
									if(intval($Ancho_Pequenia)>0 || intval($Alto_Pequenia)>0){
										$auxIDimg		= $RecursoCript_ID;
										$auxEXTimg		= $extension;
										$AnchoMAX 		= $Ancho_Pequenia;
										$AltoMAX 		= $Alto_Pequenia;
										$RUTAOrigen 	= RUTA_RAIZ.DIR_UPLOAD;
										$RUTADestino 	= RUTA_RAIZ.DIR_UPLOAD;
										$sufIJOold		= "p";
										$sufIJOnew		= "p";
										
										if(generar_pequenia($auxIDimg,$auxEXTimg,$AnchoMAX,$AltoMAX,$RUTAOrigen,$RUTADestino,$sufIJOold,$sufIJOnew)){
											//$strHtml .= "La imagen ".$i." se ha subido correctamente.<br />\n";
										}else{
											$auxERROR = "Error al generar la imagen ".$auxIDimg.$sufIJOnew.".".$auxEXTimg." con ".$AnchoMAX."x".$AltoMAX." en ".$RUTADestino;
											@mail(EMAIL_ERROR,"ERROR EN FUNCION generar_pequenia ".PROJECT_NAME,$auxERROR);
										}
									}								
									/////////////////////////////////////////////////////////////////////----------------
									
									if(!copy($_FILES[$mi_file_g]["tmp_name"],RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID."g.".$extension)){
										$strHtml .= "<font color=\"#FF0000\">La imagen grande ".$i." no ha podido ser copiada.</font><br />\n";
									}else{
										$strHtml .= "La imagen grande ".$i." se ha subido correctamente.<br />\n";
										/////////////////////////////////////////////////////////////////////----------------
										//Necesita ser reducida la grande??
										if(intval($Ancho_Grande)>0 || intval($Alto_Grande)>0){
											$auxIDimg		= $RecursoCript_ID;
											$auxEXTimg		= $extension;
											$AnchoMAX 		= $Ancho_Grande;
											$AltoMAX 		= $Alto_Grande;
											$RUTAOrigen 	= RUTA_RAIZ.DIR_UPLOAD;
											$RUTADestino 	= RUTA_RAIZ.DIR_UPLOAD;
											$sufIJOold		= "g";
											$sufIJOnew		= "g";
											if(generar_pequenia($auxIDimg,$auxEXTimg,$AnchoMAX,$AltoMAX,$RUTAOrigen,$RUTADestino,$sufIJOold,$sufIJOnew)){
												//$strHtml .= "La imagen ".$i." se ha subido correctamente.<br />\n";
											}else{
												$auxERROR = "Error al generar la imagen ".$auxIDimg.$sufIJOnew.".".$auxEXTimg." con ".$AnchoMAX."x".$AltoMAX." en ".$RUTADestino;
												@mail(EMAIL_ERROR,"ERROR EN FUNCION generar_pequenia ".PROJECT_NAME,$auxERROR);
											}
										}
										/////////////////////////////////////////////////////////////////////----------------
									}
								}								
							}
						}else{
							$strHtml .= "<font color=\"#FF0000\">La extension del fichero no es valida.</font><br />\n";
						}

					}else{
						$strHtml .= "<font color=\"#FF0000\">El archivo es demasiado grande.</font><br />\n";
						$strHtml .= "<font color=\"#FF0000\">El tama&ntilde;o maximo permitido es de ".$Tamanio_MAX." bytes.</font><br />\n";
					}
				}
				$i++;
			}
		}elseif($bImagen==2){
			//2.1.2.1 No subimos en el caso de que sea un Objeto incrustado 	
			$i = 0;
			while(isset($_POST["htmlembed_nue_".$Seccion_Recurso_ID."_".$i]) && strlen($_POST["htmlembed_nue_".$Seccion_Recurso_ID."_".$i])>0){
				$HTMLembed 	= (isset($_POST["htmlembed_nue_".$Seccion_Recurso_ID."_".$i]))?$_POST["htmlembed_nue_".$Seccion_Recurso_ID."_".$i]:"";
				$Pie 		= (isset($_POST["pie_nue_".$Seccion_Recurso_ID."_".$i]))?$_POST["pie_nue_".$Seccion_Recurso_ID."_".$i]:"";
				$Firma 		= (isset($_POST["firma_nue_".$Seccion_Recurso_ID."_".$i]))?$_POST["firma_nue_".$Seccion_Recurso_ID."_".$i]:"";
				$Orden 		= (isset($_POST["orden_nue_".$Seccion_Recurso_ID."_".$i]))?intval($_POST["orden_nue_".$Seccion_Recurso_ID."_".$i]):0;
				
				$PA = "INSERT INTO tbRecurso(Seccion_Recurso_ID,Entidad_ID,Orden,Pie,Firma,HTMLembed) VALUES(".$Seccion_Recurso_ID.",".$Entidad_ID.",".intval($Orden).",'".addslashes($Pie)."','".addslashes($Firma)."','".addslashes($HTMLembed)."');";
				$resultado = ExecPA($PA);
				
				if($Entidad_ID==0){//11/03/2009
					$strHtml .= "<font color=\"#FF0000\">Error (FR2): Un recurso de la sección #".$Seccion_Recurso_ID." no se creó de forma correcta, contacte con el administrador.</font><br />\n";
					$auxERROR = "ERROR (FR2): Error al crear el recurso #".$Entidad_ID." en la seccion #".$Seccion_Recurso_ID;
					@mail(EMAIL_ERROR,"ERROR EN FUNCION Entidad = 0 ".PROJECT_NAME,$auxERROR);
				}
				
				$i++;
			}
		}else{
			//2.1.2.2 Subida de 1 fichero (es posible que se tenga que generar otro)
			$i = 0;
			while(isset($_FILES["img_nue_".$Seccion_Recurso_ID."_".$i])){
				// miro si hay archivo
				if($_FILES["img_nue_".$Seccion_Recurso_ID."_".$i]["name"]!=""){
					$cadena 	= strtoupper($_FILES["img_nue_".$Seccion_Recurso_ID."_".$i]["name"]);
					$pos 		= strrpos($cadena,".");
					$extension 	= substr($cadena,$pos+1);
					if(!is_array($arrExtensiones_Permitidas)){
						$OK = true;
					}else{
						$OK = false;
						foreach($arrExtensiones_Permitidas as $value){
							if(strtoupper($value)==$extension){
								$OK = true;
							}
						}
					}
					$cadena = $_FILES["img_nue_".$Seccion_Recurso_ID."_".$i]["name"];
					if($OK){
						//Comprobar minimo en ancho
						$OK_minimos = false;
						if($Tamanio_Ancho_MIN>0){
							$arra_propsImg 		= getPropsImagen( $_FILES["img_nue_".$Seccion_Recurso_ID."_".$i]["tmp_name"] );
							if(is_array($arra_propsImg)){
								if($arra_propsImg[0]>=$Tamanio_Ancho_MIN){	
									$OK_minimos = true;	
								}					
							}
						}
						if($Tamanio_Ancho_MIN>0 && !$OK_minimos){
							$strHtml .= "<font color=\"#FF0000\">La imagen es demasiado pequeña (tiene ".$arra_propsImg[0]."px de ancho y el ancho mínimo es de ".$Tamanio_Ancho_MIN." px).</font><br />\n";
						}else{
							if(($_FILES["img_nue_".$Seccion_Recurso_ID."_".$i]["size"]<$Tamanio_MAX AND $_FILES["img_nue_".$Seccion_Recurso_ID."_".$i]["size"]<$Tamanio_MAX) OR $Tamanio_MAX ==0){
								$Pie 	= $_POST["pie_nue_".$Seccion_Recurso_ID."_".$i];
								$Firma 	= $_POST["firma_nue_".$Seccion_Recurso_ID."_".$i];
								$Orden 	= $_POST["orden_nue_".$Seccion_Recurso_ID."_".$i];

								$PA = "INSERT INTO tbRecurso(Seccion_Recurso_ID,Entidad_ID,Orden,Pie,Firma,Extension,Original_FileName) VALUES(".$Seccion_Recurso_ID.",".$Entidad_ID.",".intval($Orden).",'".addslashes($Pie)."','".addslashes($Firma)."','.".strtoupper($extension)."','".addslashes($cadena)."');";
								$resultado = ExecPA($PA);
								$Recurso_ID = mysql_insert_id();
								
								if($Entidad_ID==0){//11/03/2009
									$strHtml .= "<font color=\"#FF0000\">Error (FR3): Un recurso de la sección #".$Seccion_Recurso_ID." no se creó de forma correcta, contacte con el administrador.</font><br />\n";
									$auxERROR = "ERROR (FR3): Error al crear el recurso #".$Entidad_ID." en la seccion #".$Seccion_Recurso_ID;
									@mail(EMAIL_ERROR,"ERROR EN FUNCION Entidad = 0 ".PROJECT_NAME,$auxERROR);
								}
								
								if(TIPO_CODIFICACION_RECURSO=="CodificadoConMD5"){
									$RecursoCript_ID = md5($Recurso_ID);
								}else{
									$RecursoCript_ID = $Recurso_ID;
								}
	
								if($bGenerarPequenia){
									if(!copy($_FILES["img_nue_".$Seccion_Recurso_ID."_".$i]["tmp_name"],RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID."g.".$extension)){
										$strHtml .= "<font color=\"#FF0000\">La imagen ".$i." no ha podido ser copiada.</font><br />\n";
									}else{
										$strHtml .= "La imagen ".$i." se ha subido correctamente.<br />\n";
										/////////////////////////////////////////////////////////////////////----------------
										//Necesita ser reducida la grande??
										if(intval($Ancho_Grande)>0 || intval($Alto_Grande)>0){
											$auxIDimg		= $RecursoCript_ID;
											$auxEXTimg		= $extension;
											$AnchoMAX 		= $Ancho_Grande;
											$AltoMAX 		= $Alto_Grande;
											$RUTAOrigen 	= RUTA_RAIZ.DIR_UPLOAD;
											$RUTADestino 	= RUTA_RAIZ.DIR_UPLOAD;
											$sufIJOold		= "g";
											$sufIJOnew		= "g";
					
											if(generar_pequenia($auxIDimg,$auxEXTimg,$AnchoMAX,$AltoMAX,$RUTAOrigen,$RUTADestino,$sufIJOold,$sufIJOnew)){
												$strHtml .= "La imagen ".$i." se ha subido correctamente.<br />\n";
											}else{
												$auxERROR = "Error al generar la imagen ".$auxIDimg.$sufIJOnew.".".$auxEXTimg." con ".$AnchoMAX."x".$AltoMAX." en ".$RUTADestino;
												@mail(EMAIL_ERROR,"ERROR EN FUNCION generar_pequenia ".PROJECT_NAME,$auxERROR);
											}
										}								
										/////////////////////////////////////////////////////////////////////----------------
										/////////////////////////////////////////////////////////////////////----------------
										//Creemos la pequenia??
										if(intval($Ancho_Pequenia)>0 || intval($Alto_Pequenia)>0){
											$auxIDimg		= $RecursoCript_ID;
											$auxEXTimg		= $extension;
											$AnchoMAX 		= $Ancho_Pequenia;
											$AltoMAX 		= $Alto_Pequenia;
											$RUTAOrigen 	= RUTA_RAIZ.DIR_UPLOAD;
											$RUTADestino 	= RUTA_RAIZ.DIR_UPLOAD;
											$sufIJOold		= "g";
											$sufIJOnew		= "p";
											if(generar_pequenia($auxIDimg,$auxEXTimg,$AnchoMAX,$AltoMAX,$RUTAOrigen,$RUTADestino,$sufIJOold,$sufIJOnew)){
												$strHtml .= "La imagen ".$i." se ha generado correctamente.<br />\n";
											}else{
												$auxERROR = "Error al generar la imagen ".$auxIDimg.$sufIJOnew.".".$auxEXTimg." con ".$AnchoMAX."x".$AltoMAX." en ".$RUTADestino;
												@mail(EMAIL_ERROR,"ERROR EN FUNCION generar_pequenia ".PROJECT_NAME,$auxERROR);
											}
										}else{
											$strHtml .= "<font color=\"#FF0000\">Falta crear la imagen ".$i." en formato pequeño, falta ancho o alto.</font><br />\n";
										}								
										/////////////////////////////////////////////////////////////////////----------------
									}
								
								}else{
									if(!copy($_FILES["img_nue_".$Seccion_Recurso_ID."_".$i]["tmp_name"],RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID.".".$extension)){
										if($bImagen){
											$strHtml .= "<font color=\"#FF0000\">La imagen ".$i." no ha podido ser copiada.</font><br />\n";
										}else{
											$strHtml .= "<font color=\"#FF0000\">El archivo ".$i." no ha podido ser copiado.</font><br />\n";
										}
									}else{
										if($bImagen){
											$strHtml .= "La imagen ".$i." se ha subido correctamente.<br />\n";
											/////////////////////////////////////////////////////////////////////----------------
											//Necesita ser reducida la grande??
											if(intval($Ancho_Grande)>0 || intval($Alto_Grande)>0){
												$auxIDimg		= $RecursoCript_ID;
												$auxEXTimg		= $extension;
												$AnchoMAX 		= $Ancho_Grande;
												$AltoMAX 		= $Alto_Grande;
												$RUTAOrigen 	= RUTA_RAIZ.DIR_UPLOAD;
												$RUTADestino 	= RUTA_RAIZ.DIR_UPLOAD;
												$sufIJOold		= "";
												$sufIJOnew		= "";
												if(generar_pequenia($auxIDimg,$auxEXTimg,$AnchoMAX,$AltoMAX,$RUTAOrigen,$RUTADestino,$sufIJOold,$sufIJOnew)){
													//$strHtml .= "La imagen ".$i." se ha subido correctamente.<br />\n";
												}else{
													$auxERROR = "Error al generar la imagen ".$auxIDimg.$sufIJOnew.".".$auxEXTimg." con ".$AnchoMAX."x".$AltoMAX." en ".$RUTADestino;
													@mail(EMAIL_ERROR,"ERROR EN FUNCION generar_pequenia ".PROJECT_NAME,$auxERROR);
												}
											}						
											/////////////////////////////////////////////////////////////////////----------------
										}else{
											$strHtml .= "El archivo ".$i." se ha subido correctamente.<br />\n";
										}
									}
								}
	
							}else{
								$strHtml .= "<font color=\"#FF0000\">El archivo es demasiado grande.</font><br />\n";
								$strHtml .= "<font color=\"#FF0000\">El tama&ntilde;o maximo permitido es de ".$Tamanio_MAX." bytes.</font><br />\n";
							}						
						}
					}else{
						$strHtml .= "<font color=\"#FF0000\">El tipo de fichero no es v&aacute;lido.</font><br />\n";
					}
				}
				$i++;
			}
		}
	}
	return $strHtml;
}

function borrar_imagenes($Seccion_Recurso_ID,$Entidad_ID){
	$Seccion_Recurso_ID = intval($Seccion_Recurso_ID);//11/03/2009
	$Entidad_ID 		= intval($Entidad_ID);//11/03/2009
	
	$strHtml = "";
	// Saco los datos relativos a la seccion
	$PA = " SELECT 	Nombre_Seccion, Nombre_Tabla, Numero_Recursos, Tamanio_MAX,";
	$PA .= " 		Ancho_Pequenia, Alto_Pequenia, Ancho_Grande, Alto_Grande,";
	$PA .= " 		bActiva, Comentario, bOrden, bPie, bThumb, Extensiones_Permitidas, bImagen, Numero_Minimo, bGenerarPequenia";
	$PA .= " FROM tbSeccionRecurso";
	$PA .= " WHERE Seccion_Recurso_ID = " . $Seccion_Recurso_ID;

	$resultado = ExecPA($PA);
	$NumElementos=mysql_affected_rows();
	if($NumElementos!=1 || $Entidad_ID==0){
		Desconecta();
		$strHtml .= "Error, no se pudo borrar el recurso ".$Entidad_ID." para la sección de recursos ".$Seccion_Recurso_ID.".<br />\n";
	}else{
		$Nombre_Seccion 	= ValorCelda($resultado,0,"Nombre_Seccion");
		$Nombre_Tabla 		= ValorCelda($resultado,0,"Nombre_Tabla");
		$Numero_Recursos 	= ValorCelda($resultado,0,"Numero_Recursos");
		$Tamanio_MAX 		= ValorCelda($resultado,0,"Tamanio_MAX");
		$Ancho_Pequenia 	= ValorCelda($resultado,0,"Ancho_Pequenia");
		$Alto_Pequenia 		= ValorCelda($resultado,0,"Alto_Pequenia");
		$Ancho_Grande 		= ValorCelda($resultado,0,"Ancho_Grande");
		$Alto_Grande 		= ValorCelda($resultado,0,"Alto_Grande");
		$bActiva 			= ValorCelda($resultado,0,"bActiva");
		$Comentario 		= ValorCelda($resultado,0,"Comentario");
		$bOrden 			= ValorCelda($resultado,0,"bOrden");
		$bPie 				= ValorCelda($resultado,0,"bPie");
		$bThumb 			= intval(ValorCelda($resultado,0,"bThumb"));
		$bImagen 			= intval(ValorCelda($resultado,0,"bImagen"));
		$bGenerarPequenia 	= intval(ValorCelda($resultado,0,"bGenerarPequenia"));
		
		// Ya tengo los datos generales
		
		// Borro las imagenes de la entidad
		$PA = "SELECT Recurso_ID, Extension FROM tbRecurso WHERE Seccion_Recurso_ID = " . $Seccion_Recurso_ID . " AND Entidad_ID = " . $Entidad_ID;
		$resultado = ExecPA($PA);
		$Recursos=mysql_num_rows($resultado);
		for($f=0;$f<$Recursos;$f++){
			$Recurso_ID = ValorCelda($resultado,$f,"Recurso_ID");
			if(TIPO_CODIFICACION_RECURSO=="CodificadoConMD5"){
				$RecursoCript_ID = md5($Recurso_ID);
			}else{
				$RecursoCript_ID = $Recurso_ID;
			}
			$extension = ValorCelda($resultado,$f,"Extension");
			// borro
			$PA = "DELETE FROM tbRecurso WHERE Recurso_ID = ".$Recurso_ID;
			$resultado2 = ExecPA($PA);
			// Me cargo las imagenes si las hay
			$strHtml .= "Recurso eliminado correctamente.<br />\n";
			// Borro fisicamente la o las imagenes (sólo si no es HMTL)
			if(($bThumb || $bGenerarPequenia) && $bImagen!=2){
				// Hay que borrar las dos
				$archivo_pequenio = RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID."p".$extension;
				$archivo_grande = RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID."g".$extension;
				
				if(file_exists($archivo_pequenio)) unlink($archivo_pequenio);
				if(file_exists($archivo_grande)) unlink($archivo_grande);
			}elseif($bImagen!=2){
				// Solo hay que borrar una
				$archivo = RUTA_RAIZ.DIR_UPLOAD.$RecursoCript_ID.$extension;
				
				if(file_exists($archivo)) unlink($archivo);
			}
		}
	}
}

// DEVUELVO UN ARRAY ORDENADO POR ORDEN
function SacarRecursos($Seccion_Recurso_ID,$Entidad_ID){
	$Seccion_Recurso_ID = intval($Seccion_Recurso_ID);
	$Entidad_ID 		= intval($Entidad_ID);


	
	// Sacar el recurso
	$Recursos = "";
	$PA ="SELECT Recurso_ID, Seccion_Recurso_ID, Entidad_ID, Orden, Pie, Extension, HTMLembed, Original_FileName, Firma ";
	$PA.="FROM tbRecurso ";
	$PA.="WHERE Seccion_Recurso_ID = ".$Seccion_Recurso_ID." AND Entidad_ID = ".$Entidad_ID." ORDER BY Orden ASC, Recurso_ID ASC";
	$resultado = ExecPA($PA);
	
	$NumElementos = NumeroFilas($resultado);
	for($Fila=0;$Fila<$NumElementos;$Fila++){
		$Recursos[$Fila]["Recurso_ID"] 			= ValorCelda($resultado,$Fila,"Recurso_ID");
		$Recursos[$Fila]["Seccion_Recurso_ID"]	= ValorCelda($resultado,$Fila,"Seccion_Recurso_ID");
		$Recursos[$Fila]["Entidad_ID"]			= ValorCelda($resultado,$Fila,"Entidad_ID");
		$Recursos[$Fila]["Orden"]				= ValorCelda($resultado,$Fila,"Orden");
		$Recursos[$Fila]["Pie"]					= stripslashes(ValorCelda($resultado,$Fila,"Pie"));
		$Recursos[$Fila]["Extension"]			= ValorCelda($resultado,$Fila,"Extension");
		$Recursos[$Fila]["HTMLembed"]			= stripslashes(ValorCelda($resultado,$Fila,"HTMLembed"));
		$Recursos[$Fila]["Original_FileName"]	= stripslashes(ValorCelda($resultado,$Fila,"Original_FileName"));
		$Recursos[$Fila]["Firma"]				= stripslashes(ValorCelda($resultado,$Fila,"Firma"));
		$Recursos[$Fila]["Firma_IDS"]			= getFirmaFichero($resultado, $Fila);
	}
	return $Recursos;
}

// DEVUELVO UN ARRAY ORDENADO POR ORDEN
function SacarRecursosPorID($Recurso_ID){
	$Recurso_ID = intval($Recurso_ID);
	$Seccion_Recurso_ID = intval($Seccion_Recurso_ID);
	$Entidad_ID 		= intval($Entidad_ID);


	
	// Sacar el recurso
	$Recursos = "";
	$PA ="SELECT Recurso_ID, Seccion_Recurso_ID, Entidad_ID, Entidad_ID, Orden, Pie, Extension, HTMLembed, Original_FileName, Firma ";
	$PA.="FROM tbRecurso ";
	$PA.="WHERE Recurso_ID = ".$Recurso_ID." ORDER BY Orden ASC, Recurso_ID ASC";
	$resultado = ExecPA($PA);
	
	$NumElementos = NumeroFilas($resultado);
	for($Fila=0;$Fila<$NumElementos;$Fila++){
		$Recursos[$Fila]["Recurso_ID"]			= ValorCelda($resultado,$Fila,"Recurso_ID");
		$Recursos[$Fila]["Seccion_Recurso_ID"]	= ValorCelda($resultado,$Fila,"Seccion_Recurso_ID");
		$Recursos[$Fila]["Entidad_ID"]			= ValorCelda($resultado,$Fila,"Entidad_ID");
		$Recursos[$Fila]["Orden"]				= ValorCelda($resultado,$Fila,"Orden");
		$Recursos[$Fila]["Pie"]					= stripslashes(ValorCelda($resultado,$Fila,"Pie"));
		$Recursos[$Fila]["Extension"]			= ValorCelda($resultado,$Fila,"Extension");
		$Recursos[$Fila]["HTMLembed"]			= stripslashes(ValorCelda($resultado,$Fila,"HTMLembed"));
		$Recursos[$Fila]["Original_FileName"]	= stripslashes(ValorCelda($resultado,$Fila,"Original_FileName"));
		$Recursos[$Fila]["Firma"]				= stripslashes(ValorCelda($resultado,$Fila,"Firma"));
		$Recursos[$Fila]["Firma_IDS"]			= getFirmaFichero($resultado, $Fila);
		
		
	}
	return $Recursos;
}

function renombrarArchivo($nombreyRutaAntiguo, $nombreyRutaNuevo){
	$resultado=false;
	
	if(file_exists($nombreyRutaAntiguo)){
		if(rename($nombreyRutaAntiguo, $nombreyRutaNuevo)){
			$resultado=true;
		}
	}
	return $resultado;
}

function getAnchoOrAltoRecurso_p($idSeccionRecurso,$anchoORalto){
	if(intval($idSeccionRecurso)>0){
		$strSQL  =" SELECT 	Ancho_Pequenia, Alto_Pequenia";
		$strSQL.= " FROM 	tbSeccionRecurso";
		$strSQL.= " WHERE 	tbSeccionRecurso.Seccion_Recurso_ID=".$idSeccionRecurso;
		
		$resultado = ExecPA($strSQL);
		$NumElementos=mysql_affected_rows();
		
		if($NumElementos==1){
			if($anchoORalto=="ancho"){
				return intval(ValorCelda($resultado,0,"Ancho_Pequenia"));
			}else{
				return intval(ValorCelda($resultado,0,"Alto_Pequenia"));
			}
		}else{
			return 0;
		}
	}else{
		return 0;
	}

}
function getAnchoOrAltoRecurso_g($idSeccionRecurso,$anchoORalto=""){
	if(intval($idSeccionRecurso)>0){
		$strSQL  =" SELECT 	Ancho_Grande, Alto_Grande";
		$strSQL.= " FROM 	tbSeccionRecurso";
		$strSQL.= " WHERE 	tbSeccionRecurso.Seccion_Recurso_ID=".$idSeccionRecurso;
		
		$resultado = ExecPA($strSQL);
		$NumElementos=mysql_affected_rows();
		
		if($NumElementos==1){
			if($anchoORalto=="ancho"){
				return intval(ValorCelda($resultado,0,"Ancho_Grande"));
			}else{
				return intval(ValorCelda($resultado,0,"Alto_Grande"));
			}
		}else{
			return 0;
		}
	}else{
		return 0;
	}

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//ALBERTO ADD 18/10/2007: LIBRERIA GD PARA REESCALAR IMGS														/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function generar_pequenia( $recurso_id, $exten, $width = 0, $height = 0, $ruta_origen, $ruta_destino, $img_sufijo_origen='', $img_sufijo_destino='' ) {
	/*
		Smart Image Resizing while Preserving Transparency With PHP and GD Library
		http://mediumexposure.com/smart-image-resizing-while-preserving-transparency-php-and-gd-library/
		http://github.com/maxim/smart_resize_image
	*/
	
	#Param						  
	$recurso_id			= trim($recurso_id);		  
	$exten				= trim($exten);
	$ruta_origen		= trim($ruta_origen);
	$ruta_destino		= trim($ruta_destino);
	$img_sufijo_origen	= trim($img_sufijo_origen);
	$img_sufijo_destino	= trim($img_sufijo_destino);
	
	$file_origen 	= $ruta_origen.$recurso_id.$img_sufijo_origen.'.'.$exten;
	$file_destino 	= $ruta_origen.$recurso_id.$img_sufijo_destino.'.'.$exten;
	
	$width 		= intval($width);
	$height 	= intval($height);
	
	#Config
	$proportional 		= true; 
    $output 			= 'file'; 
    $delete_original 	= false; 
    $use_linux_commands = false ; 
	$quality 			= 90; 		//from 0 (worst quality) to 100 (best quality). The default is the 75. 
	
							  					    
    if ( $height < 0 && $width < 0 ) return false;
	if (!file_exists($file_origen))  return false;
	
    # Setting defaults and meta
    $info 			= getimagesize($file_origen);
    $image 			= '';
    $final_width 	= 0;
    $final_height 	= 0;
    list($width_old, $height_old) = $info;

    # Calculating proportionality
    if ($proportional) {
      if ($width == 0) $factor = $height/$height_old;
      elseif ($height == 0) $factor = $width/$width_old;
      else $factor = min( $width / $width_old, $height / $height_old );

      $final_width = round( $width_old * $factor );
      $final_height = round( $height_old * $factor );
    }
    else {
      $final_width = ( $width <= 0 ) ? $width_old : $width;
      $final_height = ( $height <= 0 ) ? $height_old : $height;
    }	
	
	# Memory limit
	switch ( $info[2] ) {
		case IMAGETYPE_GIF: 
			$memory_require = round($width_old * $height_old * $info['bits']);
			if (function_exists('memory_get_usage') && memory_get_usage() + $memory_require > (integer) ini_get('memory_limit') * pow(1024, 2)) return false;
			break;
		case IMAGETYPE_JPEG: 
			$memory_require = round(($width_old * $height_old * $info['bits'] * $info['channels'] / 8 + Pow(2, 16)) * 1.65);
			if (function_exists('memory_get_usage') && memory_get_usage() + $memory_require > (integer) ini_get('memory_limit') * pow(1024, 2)) return false;
			break;
		case IMAGETYPE_PNG: 
			$memory_require = round($width_old * $height_old * $info['bits']);
			if (function_exists('memory_get_usage') && memory_get_usage() + $memory_require > (integer) ini_get('memory_limit') * pow(1024, 2)) return false;
			break;
		default: return false;
	}
		
    # Loading image to memory according to type
    switch ( $info[2] ) {
      	case IMAGETYPE_GIF: 	
	  		$image = imagecreatefromgif($file_origen); 	
			if (!$image) return false;
			
			// Convert into a PNG image
			imagepng($image, $ruta_origen.'temp_img_gif_to_png.png');
			imagedestroy($image);
			$image = imagecreatefrompng($ruta_origen.'temp_img_gif_to_png.png');
			$info[2] = IMAGETYPE_PNG;
			
			break;
      	case IMAGETYPE_JPEG: 	
			$image = imagecreatefromjpeg($file_origen); 
			break;
     	case IMAGETYPE_PNG: 	
			$image = imagecreatefrompng($file_origen); 	
			break;
      	default: 
			return false;
    }
	
	if (!$image) return false;
    
    # This is the resizing/resampling/transparency-preserving magic
    $image_resized = imagecreatetruecolor( $final_width, $final_height );
	if ( $info[2] == IMAGETYPE_GIF ) {
		$transparency = imagecolortransparent($image);
		// If we have a specific transparent color
		if ($transparency >= 0) {
			// Get the original image's transparent color's RGB values
			$transparent_color 	= imagecolorsforindex($image, $trnprt_indx);
			// Allocate the same color in the new image resource
			$transparency = imagecolorallocate($image_resized, $trnprt_color['red'], $trnprt_color['green'], $trnprt_color['blue']);
			// Completely fill the background of the new image with allocated color.
			imagefill($image_resized, 0, 0, $transparency);
			// Set the background color for new image to transparent
			imagecolortransparent($image_resized, $transparency);
		}	
	}elseif( $info[2] == IMAGETYPE_PNG ) {
		// Always make a transparent background color for PNGs that don't have one allocated already
		
		// Turn off transparency blending (temporarily)
		imagealphablending($image_resized, false);
		// Create a new transparent color for image
		$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
		// Completely fill the background of the new image with allocated color.
		imagefill($image_resized, 0, 0, $color);
		// Restore transparency blending
		imagesavealpha($image_resized, true);
    }
    imagecopyresampled($image_resized, $image, 0, 0, 0, 0, $final_width, $final_height, $width_old, $height_old);
    
    # Taking care of original, if needed
	if ( $delete_original ) {
	  	@unlink($file_origen);
		//if ( $use_linux_commands ) exec('rm '.$file_origen);
	}

    # Preparing a method of providing result
    switch ( strtolower($output) ) {
		case 'browser':
			$mime = image_type_to_mime_type($info[2]);
			header("Content-type: $mime");
			$output = NULL;
			break;
		case 'file':
			$output = $file_destino;
			break;
		case 'return':
			return $image_resized;
			break;
		default:
			break;
    }
    
    # Writing image according to type to the output destination
	switch ( $info[2] ) {
		case IMAGETYPE_GIF: 	
			imagegif($image_resized, $output); 	
			unlink($ruta_origen.'temp_img_gif_to_png.png'); 	
			break;
		case IMAGETYPE_JPEG: 	
			imagejpeg($image_resized, $output,$quality); 	
			break;
		case IMAGETYPE_PNG: 	
			imagepng($image_resized, $output); 				
			break;
		default: 
		return false;
	}
	
	
	ini_restore ("memory_limit"); 
	
    return true;
}

function getPropsImagen($RutaCompleta){
	//Retorna array con:
	//$imageInfo[0] -> Ancho
	//$imageInfo[1] -> Alto
	//$imageInfo[2] -> Tipo gif, jpg...
	//$imageInfo['bits'] -> Peso en bits...
	//$imageInfo['channels']
			
	if(file_exists($RutaCompleta)){
		$imageInfo = getimagesize($RutaCompleta);	
		return $imageInfo;
	}else{
		//echo "getPropsImagen dice: NO FICHERO...";
	}
}

function getFirmaFichero($rsFichero, $fila)
{
	$reg_file 	= mysql_num_rows($rsFichero);
	if($reg_file > $fila)
	{
		$Recurso_ID 		= intval(ValorCelda($rsFichero, $fila, "Recurso_ID"));
		$Seccion_Recurso_ID = intval(ValorCelda($rsFichero, $fila, "Seccion_Recurso_ID"));
		$Entidad_ID 		= intval(ValorCelda($rsFichero, $fila, "Entidad_ID"));
		$Firma 				= md5($Recurso_ID.$Seccion_Recurso_ID.$Entidad_ID);
		return $Firma;
	}
	return "";
}

?>