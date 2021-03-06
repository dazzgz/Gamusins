<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");

ComprobarLoginPanel();
$strHtmlFrame = CabeceraPanelControl(SEC_ADM_PEDIDOS);
echo $strHtmlFrame;

$bBorrar = parametro("bBorrar");
if($bBorrar){
	// Borro lo que este marcado para ello
	$borrados = 0;
	$strMsg = "";
	foreach($_POST["chkBorrar"] as $ID)
	{
		$PA = "DELETE FROM tbPedido WHERE Pedido_ID = ".intval($ID);
		//echo $PA;
		//die(); 
		ExecPA($PA);
		$strMsg .= TextoDeIdioma("Borrando") . " " . TextoDeIdioma("pedido") . "... #" . $ID . ".<br />";

    $borrados++;
  }

  if ($borrados > 0)
	{
		if ($borrados == 1)
		{
			$strMsg .= "<br />" . TextoDeIdioma("Se_ha_borrado") . " " . $borrados . " " . TextoDeIdioma("pedido") . ".<br />";
		}else{
			$strMsg .= "<br />" . TextoDeIdioma("Se_han_borrado") . " " . $borrados . " " . TextoDeIdioma("pedidos") . ".<br />";
		}
	}
}

$bBuscar = parametro("bBuscar");
$activo = -1;
$strSQLWhere = " WHERE 1 = 1";

if ($bBuscar){
	$parametros_query = "";
	$parametros_texto = "";
	
	$texto = parametro("texto");
	$activo = parametro("activo");
	if($activo == ""){
		$activo = -1;
	}
	
	if($activo >= 0) {
		$strSQLWhere.= " AND (bActivo = ".intval($activo).") ";
		$parametros_query .= "&activo=".$activo;
		$parametros_texto .= "&nbsp;&gt; ".TextoDeIdioma("Activo").": ".int2Bool($activo)."<br />";
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
			$subWHERE[0][$i] = "UPPER(Nombre) LIKE '%".addslashes(strtoupper($tok))."%'";
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

$strSQLCount = "SELECT COUNT(*) FROM tbPedido ".$strSQLWhere;
//$totalRegs = db.QueryValue(strSQLCount);
//echo $strSQLCount;
$totalRegs = ExecQueryValue($strSQLCount);
$htmlTipoOrdenASC = "<img src=\"".RUTA_ADM."img/ASC.png\" width=\"10\" height=\"10\" title=\"".TextoDeIdioma("Orden_ascendente")."\" />";
$htmlTipoOrdenDESC = "<img src=\"".RUTA_ADM."img/DESC.png\" width=\"10\" height=\"10\" title=\"".TextoDeIdioma("Orden_descendente")."\" />";

$regsPorPag = REG_POR_PAG_DEF;

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
			<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_pedidos")?></h4>
		</div>
		<div class="pull-right">
			<ul class="bread">
				<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
	        <li><?php echo TextoDeIdioma("Pedidos")?><span class="divider">/</span></li>
				<li class="active"><?php echo TextoDeIdioma("Listado_de_pedidos")?></li>
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
	$PA .= "		Pedido_ID, Cliente_ID, Proveedor_ID, Fecha, Estado, Lat, Lon";
	$PA .= " FROM 	tbPedido ";
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
						<span><?php echo TextoDeIdioma("Pedidos")?></span>
					</div>
					<div class="box-body box-body-nopadding">
						<div class="alert alert-info">
							<i class="icon-info-sign icon-large"></i> <?php echo $strHtml;?>
						</div>
						<div class="highlight-toolbar">
							<div class="pull-left">
								<div class="btn-toolbar">
									<div class="btn-group">
										<?php if($_SESSION["sPANEL_UsuarioRol_ID"] < ROL_PROVEEDOR_ID ){?>
											<a href="editar.php" class="btn btn-primary"><img src="<?php echo RUTA_ADM;?>img/icons/plus.png" width="16" />&nbsp;&nbsp;<?php echo TextoDeIdioma("Nuevo");?></a>
										<?php }?>
									</div>
								</div>
							</div>
							<div class="pull-right">
							</div>
						</div>
						
						<form action="index.php" name="frmBorrar" method="post">
							<input type="hidden" name="bBorrar" value="1" />
							<table class="table table-nomargin table-bordered table-striped table-hover table-pagination">
								<thead>
									<tr>
										<?php echo CampoCabeceraTablaListado("Pedido_ID", TextoDeIdioma("Codigo"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "center");?>
										<?php echo CampoCabeceraTablaListado("Fecha", TextoDeIdioma("Fecha_de_alta"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "center");?>
										<?php echo CampoCabeceraTablaListado("Cliente_ID", TextoDeIdioma("Cliente"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Proveedor_ID", TextoDeIdioma("Proveedor"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Estado", TextoDeIdioma("Estado"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php if($_SESSION["sPANEL_UsuarioRol_ID"] < ROL_PROVEEDOR_ID ){?>
											<th style="text-align:center"><?php echo TextoDeIdioma("Editar")?></th>
											<th style="text-align:center"><?php echo TextoDeIdioma("Borrar")?></th>
										<?php }?>
									</tr>
								</thead>
								<tbody>
								<?php 
									$strHtml = "";
									for($i=0;$i<$NumElementos;$i++)
									{
										$Pedido_ID 				= intval(ValorCelda($resultado,$i,"Pedido_ID"));
										$Cliente_ID 				= intval(ValorCelda($resultado,$i,"Cliente_ID"));
										$Proveedor_ID 				= intval(ValorCelda($resultado,$i,"Proveedor_ID"));
										$Estado 							= intval(ValorCelda($resultado,$i,"Estado"));
										$Fecha 					= fechaHoraNormal(ValorCelda($resultado,$i,"FechaAlta"));
										
										$strHtml .= "<tr>";
										$strHtml .= "	<td style=\"".$colorFila."vertical-align:middle;text-align:center;\">".$Pedido_ID."</td>";
										$strHtml .= "	<td style=\"".$colorFila."vertical-align:middle;text-align:center;\">".$Fecha."</td>";
										$strHtml .= "	<td style=\"".$colorFila."vertical-align:middle;text-align:left;\">".ExecQueryValue("SELECT CONCAT(RazonSocial, ' [', Nombre, ' ', Apellidos, ']') FROM tbCliente WHERE Cliente_ID = ".$Cliente_ID)."</td>";
										$strHtml .= "	<td style=\"".$colorFila."vertical-align:middle;text-align:left;\">".ExecQueryValue("SELECT CONCAT(RazonSocial, ' [', Nombre, ' ', Apellidos, ']') FROM tbProveedor WHERE Proveedor_ID = ".$Proveedor_ID)."</td>";
										$strHtml .= "	<td style=\"".$colorFila."vertical-align:middle;text-align:center;\">".$Estado."</td>";
										
										if($_SESSION["sPANEL_UsuarioRol_ID"] < ROL_PROVEEDOR_ID ){
											$strHtml .= "	<td style=\"".$colorFila."vertical-align:middle;text-align:center;\"><a href=\"editar.php?Pedido_ID=".$Pedido_ID."\"><img src=\"".RUTA_ADM."/img/icons/attibutes.png\" alt=\"".TextoDeIdioma("Editar")."\" width=\"32\" /></a></td>";
											$strHtml .= " <td style=\"".$colorFila."vertical-align:middle;text-align:center;\"><input type=\"checkbox\" name=\"chkBorrar[]\" value=\"".$Pedido_ID."\" /></td>";
										}
										$strHtml .= "</tr>";
									}
									echo $strHtml;
								?>
									</tbody>
								</table>
						
							<div class="bottom-table">
								<div class="pull-left">
									<?php if($_SESSION["sPANEL_UsuarioRol_ID"] < ROL_PROVEEDOR_ID ){?>
										<a href="editar.php" class="btn btn-primary"><img src="<?php echo RUTA_ADM;?>img/icons/plus.png" width="16" />&nbsp;&nbsp;<?php echo TextoDeIdioma("Nuevo");?></a>
									<? }?>
								</div>
								<div class="pull-right">
									<?php echo PaginacionListado(4, $totalRegs, $inicio, $final, $pag, $regsPorPag, $urlPag);?>&nbsp;&nbsp;&nbsp;
									<?php if($totalRegs > 0){?>
										<?php if($_SESSION["sPANEL_UsuarioRol_ID"] < ROL_PROVEEDOR_ID ){?>
											<a href="#confirmarBorrado" role="button" class="btn btn-primary" data-toggle="modal"><?php echo TextoDeIdioma("Borrar")?></a>
										<? }?>
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