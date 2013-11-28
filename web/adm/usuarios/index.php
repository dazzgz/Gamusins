<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");

ComprobarLoginPanel();
$strHtmlFrame = CabeceraPanelControl(SEC_ADM_USUARIOS);
echo $strHtmlFrame;

$bBorrar = parametro("bBorrar");
if($bBorrar){
	// Borro lo que este marcado para ello
	$borrados = 0;
	$strMsg = "";
	foreach($_POST["chkBorrar"] as $ID)
	{
		$PA = "DELETE FROM tbUsuario WHERE Usuario_ID = ".intval($ID);
		//echo $PA;
		//die(); 
		ExecPA($PA);
		$strMsg .= TextoDeIdioma("Borrando") . " " . TextoDeIdioma("usuario") . "... #" . $ID . ".<br />";

    $borrados++;
  }

  if ($borrados > 0)
	{
		if ($borrados == 1)
		{
			$strMsg .= "<br />" . TextoDeIdioma("Se_ha_borrado") . " " . $borrados . " " . TextoDeIdioma("usuario") . ".<br />";
		}else{
			$strMsg .= "<br />" . TextoDeIdioma("Se_han_borrado") . " " . $borrados . " " . TextoDeIdioma("usuarios") . ".<br />";
		}
	}
}

$bBuscar = parametro("bBuscar");
$activo = -1;
$strSQLWhere = " WHERE 1 = 1";
$strSQLWhere .= " AND UsuarioRol_ID >= ".$_SESSION["sPANEL_UsuarioRol_ID"];
if ($bBuscar){
	$parametros_query = "";
	$parametros_texto = "";

	$texto = parametro("texto");
	$activo = parametro("activo");
	if($activo == ""){
		$activo = -1;
	}
	$UsuarioRol_ID = intval(parametro("UsuarioRol_ID"));
	
	if($activo >= 0) {
		$strSQLWhere.= " AND (U.bActivo = ".intval($activo).") ";
		$parametros_query .= "&activo=".$activo;
		$parametros_texto .= "&nbsp;&gt; ".TextoDeIdioma("Activo").": ".int2Bool($activo)."<br />";
	}
	
	if($UsuarioRol_ID > 0) {
		$strSQLWhere.= " AND (U.UsuarioRol_ID = " . $UsuarioRol_ID . ") ";
		$parametros_query .= "&UsuarioRol_ID=".$UsuarioRol_ID;
		$parametros_texto .= "&nbsp;&gt; ".TextoDeIdioma("Rol").": <span class=\"text-warning\">".ExecQueryValue("SELECT Nombre FROM tbUsuarioRol WHERE UsuarioRol_ID = ".$UsuarioRol_ID)."</span><br />";
	}
	
	$strWHERE = "";
	if($texto != "")
	{
		// Corto el texto por los espacios para poder hacer un AND
		$tok = strtok($texto, " ");
		$i = 0;
		$parametros_query .= "&texto=".$texto;
		$parametros_texto .= "&nbsp;&gt; ".TextoDeIdioma("Texto").": <span class=\"text-warning\">'".$texto."'</span><br />";
		while($tok)
		{
			$subWHERE[0][$i] = "UPPER(U.Nombre) LIKE '%".addslashes(strtoupper($tok))."%'";
			$subWHERE[1][$i] = "UPPER(U.Email) LIKE '%".addslashes(strtoupper($tok))."%'";
			$tok = strtok(" ");
			$i++;
		}
		// Ahora tengo que recorrer lo que viene para montar el where
		if(is_array($subWHERE))
		{
			$strWHERE .= " AND ((";
			foreach($subWHERE as $key => $value)
			{
				if(is_array($value))
				{
					if($key != 0)
					{
						$strWHERE .= ") OR (";
					}
					foreach($value as $key2 => $value2)
					{
						if($key2 == 0)
						{
							$strWHERE .= " ".$value2;
						}
						else
						{
							$strWHERE .= " AND ".$value2;
						}
					}
				}
			}
			$strWHERE .= ")";
			$strWHERE .= ")";
		}
		$strSQLWhere .= $strWHERE; 
	}
}

$orden = parametro("orden");
$tipoOrden = parametro("tipoOrden");

$strSQLOrden = "";
if ($orden != ""){
	$strSQLOrden .= " ORDER BY ".$orden." ".$tipoOrden;
}

$strSQLCount = "SELECT COUNT(*) FROM tbUsuario U".$strSQLWhere;
//$totalRegs = db.QueryValue(strSQLCount);
//echo $strSQLCount;
$totalRegs = ExecQueryValue($strSQLCount);
$htmlTipoOrdenASC = "<img src=\"".RUTA_ADM."img/ASC.png\" width=\"10\" height=\"10\" title=\"".TextoDeIdioma("Orden_ascendente")."\" />";
$htmlTipoOrdenDESC = "<img src=\"".RUTA_ADM."img/DESC.png\" width=\"10\" height=\"10\" title=\"".TextoDeIdioma("Orden_descendente")."\" />";

$regsPorPag = 10;

$pag = intval(parametro("pag"));
if($pag == 0){
	$pag = 1;
	$inicio = 0;
}else{
		$inicio = ($pag-1) * $regsPorPag;
}

//$inicio = intval(parametro("inicio"));

$final = $inicio + $regsPorPag; 
if($final > $totalRegs)
	$final = $totalRegs;

$resto = ($totalRegs % $regsPorPag);

if($resto > 0)
	$numPags = ceil($totalRegs  / $regsPorPag);
else
	$numPags = $totalRegs  / $regsPorPag;

$paramsFiltro = $parametros_query."&bBuscar=".$bBuscar;
$paginaHtml = "index.php";
$urlPag = $paginaHtml."?orden=".$orden."&tipoOrden=".$tipoOrden.$paramsFiltro;
?>
	<!-- Migas de pan -->
	<div class="page-header">
		<div class="pull-left">
			<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_usuarios")?></h4>
		</div>
		<div class="pull-right">
			<ul class="bread">
				<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
	        <li><?php echo TextoDeIdioma("Usuarios")?><span class="divider">/</span></li>
				<li class="active"><?php echo TextoDeIdioma("Listado_de_usuarios")?></li>
			</ul>
		</div>
	</div>

	<!-- FILTROS -->
	<div class="container-fluid" id="content-area">

		<div class="row-fluid">
			<div class="span12">
				<div class="box">

					<div class="box-head">
						<i class="icon-search"></i>
						<span><?php echo TextoDeIdioma("Filtro_de_registros")?></span>
					</div>

					<div class="box-body box-body-nopadding">
						<div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i>  <?php echo TextoDeIdioma("Puedes_aplicar_filtros_para_reducir_los_resultados_de_busqueda")?>.
						</div>
						
						<form name="frmBuscar" id="frmBuscar" action="index.php" method="post" class="form-horizontal form-bordered form-validate">
							<input type="hidden" name="bBuscar" value="1" />
							<div class="control-group">
								<label for="UsuarioRol_ID" class="control-label"><?php echo TextoDeIdioma("Rol");?></label>
								<div class="controls">
									<?php
									$sql = "SELECT UsuarioRol_ID valor, Nombre descripcion
												FROM tbUsuarioRol
												WHERE bActivo = 1";
									$sql .= " AND UsuarioRol_ID >= ".$_SESSION["sPANEL_UsuarioRol_ID"];
									$sql .= " ORDER BY UsuarioRol_ID";
									$strComboUsuarioRol = generaComboBox($sql
											, "UsuarioRol_ID"
											, $noSeleccion="-- ".TextoDeIdioma("Seleccione_un_elemento")." --"
											, $UsuarioRol_ID
											, ""
									);
									echo $strComboUsuarioRol;?>
								</div>
							</div>
							<div class="control-group">
								<label for="activo" class="control-label"><?php echo TextoDeIdioma("Activo");?></label>
								<div class="controls">
									<select id="activo" name="activo" class="input-block">
										<?php $sel = ""; if($activo == -1) $sel = "selected";?>
										<option value="-1" <?php echo $sel?>>Todos</option>
										<?php $sel = ""; if($activo == 1) $sel = "selected";?>
										<option value="1" <?php echo $sel?>>Si</option>
										<?php $sel = ""; if($activo == 0) $sel = "selected";?>
										<option value="0" <?php echo $sel?>>No</option>
									</select>
								</div>
							</div>
							<div class="control-group">
								<label for="texto" class="control-label"><?php echo TextoDeIdioma("Texto_a_buscar");?></label>
								<div class="controls">
									<input type="text" name="texto" id="texto" placeholder="<?php echo TextoDeIdioma("Texto_a_buscar");?>" value="<?php echo $texto;?>" class="input-block" data-rule-minlength="2" data-rule-required="false" data-msg-required="<?php echo TextoDeIdioma("Texto_a_buscar")?>" data-msg-minlength="<?php echo TextoDeIdioma("Introduzca_al_menos_X_caracteres", "2")?>" />
								</div>
							</div>
							
							<div class="form-actions">
								<input type="submit" class="button button-basic-blue" value="<?php echo TextoDeIdioma("Filtrar_resultados");?>">
								<button type="button" class="button button-basic" onclick="resetear(this);"><?php echo TextoDeIdioma("Limpiar_filtro");?></button>
							</div>
						</form>
					</div>
					
				</div>
			</div>
		</div>
					
	</div>

	<!-- REGISTROS -->
	<?php
	$PA = "SELECT DISTINCT ";
	$PA .= "		U.Usuario_ID, U.Nombre, U.Email";
	$PA .= "		, U.bActivo, U.UsuarioRol_ID";
	$PA .= "		, U.FechaAlta, U.FechaUltimoAcceso ";
	$PA .= " FROM 	tbUsuario U";
	$PA .= $strSQLWhere;
	$PA .= $strSQLOrden;
	$PA .= " LIMIT ".$inicio.", ".$regsPorPag;
	
	//echo $PA;
	$resultado = ExecPA($PA);
	
	$NumElementos = mysql_affected_rows();
	if($NumElementos <= 0){
		$strHtml.= "<strong>".TextoDeIdioma("RESULTADO_DE_LA_BUSQUEDA").": </strong><br />".TextoDeIdioma("No_se_han_encontrado_resultados_que_cumplan_las_condiciones_establecidas").".<br />";
	}else{
		$strHtml.= "<strong>".TextoDeIdioma("RESULTADO_DE_LA_BUSQUEDA").": ".($NumElementos)." ";
		if($NumElementos == 1)
			$strHtml.= TextoDeIdioma("elemento")."</strong><br />";
		else
			$strHtml.= TextoDeIdioma("elementos")."</strong><br />";
		$strHtml.= TextoDeIdioma("mostrando_del")." ".($inicio+1)." ".TextoDeIdioma("al")." ".($final)."<br />";
		
		if ($parametros_query != ""){
			$strHtml.= "<strong>".TextoDeIdioma("Filtro_aplicado").":</strong><br />".$parametros_texto;
		}
	}
	?>
	<div class="container-fluid" id="content-area">
		<div class="row-fluid">
			<div class="span12">
				<div class="box">
			
					<?php echo maquetarCorrecto($strMsg); ?>
						
					<div class="box-head">
						<i class="icon-table"></i>
						<span><?php echo TextoDeIdioma("Usuarios")?></span>
					</div>
					<div class="box-body box-body-nopadding">
						<div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i> <?php echo $strHtml;?>
						</div>
						<div class="highlight-toolbar">
							<div class="pull-left">
								<div class="btn-toolbar">
									<div class="btn-group">
										<a href="editar.php" class="btn btn-primary"><img src="<?php echo RUTA_ADM;?>img/icons/plus.png" width="16" />&nbsp;&nbsp;<?php echo TextoDeIdioma("Nuevo");?></a>
										<!-- a href="#" class='button button-basic button-icon' rel="tooltip" title="Archive"><i class="icon-inbox"></i></a>
										<a href="#" class='button button-basic button-icon' rel="tooltip" title="Mark as spam"><i class="icon-exclamation-sign"></i></a>
										<a href="#" class='button button-basic button-icon' rel="tooltip" title="Delete"><i class="icon-trash"></i></a -->
									</div>
								</div>
							</div>
							<div class="pull-right">
								<!-- div class="btn-toolbar">
									<div class="btn-group">
										<span><strong>1-25</strong> of <strong>348</strong></span>
									</div>
									<div class="btn-group">
										<a href="#" class="button button-basic button-icon" data-toggle="dropdown"><i class="icon-angle-left"></i></a>
										<a href="#" class="button button-basic button-icon" data-toggle="dropdown"><i class="icon-angle-right"></i></a>
									</div>
								</div> -->
							</div>
						</div>
						
						<form action="index.php" name="frmBorrar" method="post">
							<input type="hidden" name="bBorrar" value="1" />
							<table class="table table-nomargin table-bordered table-striped table-hover table-pagination">
							<!-- table class="table table-nomargin table-bordered table-striped table-hover dataTable-tools"-->
								<thead>
									<tr>
										<?php echo CampoCabeceraTablaListado("Usuario_ID", TextoDeIdioma("Codigo"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "center");?>
										<?php echo CampoCabeceraTablaListado("UsuarioRol_ID", TextoDeIdioma("Rol_de_usuario"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Nombre", TextoDeIdioma("Nombre"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Email", TextoDeIdioma("Email"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("FechaAlta", TextoDeIdioma("Fecha_de_alta"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("FechaUltimoAcceso", "Último acceso", $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("bActivo", TextoDeIdioma("Activo"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "center");?>
										<th style="text-align:center"><?php echo TextoDeIdioma("Ficha")?></th>
										<th style="text-align:center"><?php echo TextoDeIdioma("Borrar")?></th>
									</tr>
								</thead>
								<tbody>
								<?php 
									$strHtml = "";
									for($i=0;$i<$NumElementos;$i++)
									{
										$Usuario_ID 				= intval(ValorCelda($resultado,$i,"Usuario_ID"));
										$UsuarioRol_ID 			= intval(ValorCelda($resultado,$i,"UsuarioRol_ID"));
										$Nombre 						= stripslashes(ValorCelda($resultado,$i,"Nombre"));
										$Email 							= stripslashes(ValorCelda($resultado,$i,"Email"));
										$FechaAlta 					= fechaHoraNormal(ValorCelda($resultado,$i,"FechaAlta"));
										$FechaUltimoAcceso	= fechaHoraNormal(ValorCelda($resultado,$i,"FechaUltimoAcceso"));
										$esActivo 					= intval(ValorCelda($resultado,$i,"bActivo"));
										
										$strHtml .= "<tr>";
										$strHtml .= "	<td style=\"text-align:center;vertical-align:middle\">".$Usuario_ID."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".ExecQueryValue("SELECT Nombre FROM tbUsuarioRol WHERE UsuarioRol_ID = ".$UsuarioRol_ID)."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$Nombre."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$Email."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$FechaAlta."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$FechaUltimoAcceso."</td>";
										$strHtml .= "	<td style=\"text-align:center;vertical-align:middle\">";
										if ($esActivo == 1)
										{
											$strHtml .= "		<i class=\"icon-ok\"></i>";
										}else{
											$strHtml .= "		<i class=\"icon-remove\"></i>";
										}
										$strHtml .= "</td>";
										$strHtml .= "	<td style=\"text-align:center;vertical-align:middle\"><a href=\"editar.php?Usuario_ID=".$Usuario_ID."\"><img src=\"".RUTA_ADM."/img/icons/attibutes.png\" alt=\"".TextoDeIdioma("Editar")."\" width=\"32\" /></a></td>";
										$strHtml .= " <td style=\"text-align:center;vertical-align:middle\"><input type=\"checkbox\" name=\"chkBorrar[]\" value=\"".$Usuario_ID."\" /></td>";
										$strHtml .= "</tr>";
									}
									echo $strHtml;
								?>
									</tbody>
								</table>
						
							<div class="bottom-table">
								<div class="pull-left">
									<a href="editar.php" class="btn btn-primary"><img src="<?php echo RUTA_ADM;?>img/icons/plus.png" width="16" />&nbsp;&nbsp;<?php echo TextoDeIdioma("Nuevo");?></a>
								</div>
								<div class="pull-right">
									<?php //echo "totalRegs:". $totalRegs." inicio:".$inicio." final:". $final." pag:". $pag." regsPorPag:". $regsPorPag." urlPag:". $urlPag;?>
									<?php echo PaginacionListado(4, $totalRegs, $inicio, $final, $pag, $regsPorPag, $urlPag);?>&nbsp;&nbsp;&nbsp;
									<!-- input type="submit" value="<?php echo TextoDeIdioma("Borrar")?>" class="btn btn-primary" /-->
									<?php if($totalRegs > 0){?>
										<a href="#confirmarBorrado" role="button" class="btn btn-primary" data-toggle="modal"><?php echo TextoDeIdioma("Borrar")?></a>
									<? }?>
								</div>
							</div>
							
							<!-- Modal para confirmar borrado-->
							<div id="confirmarBorrado" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="confirmarBorrado" aria-hidden="true">
							  <div class="modal-header">
							    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							    <h3 id="confirmarBorrado"><?php echo TextoDeIdioma("Confirmar_borrado_de_registros")?></h3>
							  </div>
							  <div class="modal-body">
							    <p>¿<?php echo TextoDeIdioma("Seguro_que_desea_borrar_los_elementos_seleccionados")?>?</p>
							  </div>
							  <div class="modal-footer">
							    <!-- button class="btn btn-primary"><?php echo TextoDeIdioma("Aceptar")?></button-->
							    <input type="submit" value="<?php echo TextoDeIdioma("Aceptar")?>" class="btn btn-primary" />
							    <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo TextoDeIdioma("Cancelar")?></button>
							  </div>
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php echo PiePanelControl(); ?>