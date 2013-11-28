<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");

ComprobarLoginPanel();
$strHtmlFrame = CabeceraPanelControl(SEC_ADM_USUARIOS);
echo $strHtmlFrame;

	
$bBuscar = parametro("bBuscar");
$activo = -1;
$strSQLWhere = " WHERE 1 = 1";
//$strSQLWhere .= " AND UsuarioRol_ID >= ".$_SESSION["sPANEL_UsuarioRol_ID"];
if ($bBuscar){
	$parametros_query = "";
	$parametros_texto = "";

	$texto = parametro("texto");
	$acceso = parametro("acceso");
	$UsuarioRol_ID = intval(parametro("UsuarioRol_ID"));
	
	if($acceso != "") {
		$strSQLWhere.= " AND (L.Acceso = '".$acceso."') ";
		$parametros_query .= "&acceso=".$activo;
		$parametros_texto .= "&nbsp;&gt; ".TextoDeIdioma("Acceso").": ".$acceso."<br />";
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
			$subWHERE[1][$i] = "UPPER(L.Login) LIKE '%".addslashes(strtoupper($tok))."%'";
			$subWHERE[2][$i] = "UPPER(L.IP) LIKE '%".addslashes(strtoupper($tok))."%'";
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

$strSQLCount = "SELECT COUNT(*) ";
$strSQLCount .= " FROM tbLogAccesoPanel L ";
$strSQLCount .= "	LEFT JOIN tbUsuario U ON L.Usuario_ID = U.Usuario_ID ";
$strSQLCount .= $strSQLWhere;
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
$paginaHtml = "accesos.php";
$urlPag = $paginaHtml."?orden=".$orden."&tipoOrden=".$tipoOrden.$paramsFiltro;
?>
	<!-- Migas de pan -->
	<div class="page-header">
		<div class="pull-left">
			<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_accesos")?></h4>
		</div>
		<div class="pull-right">
			<ul class="bread">
				<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
	        <li><?php echo TextoDeIdioma("Usuarios")?><span class="divider">/</span></li>
				<li class="active"><?php echo TextoDeIdioma("Listado_de_accesos")?></li>
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
						
						<form name="frmBuscar" id="frmBuscar" action="accesos.php" method="post" class="form-horizontal form-bordered form-validate">
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
									<select id="acceso" name="acceso" class="input-block">
										<?php $sel = ""; if($acceso == -1) $sel = "selected";?>
										<option value="" <?php echo $sel?>>Todos</option>
										<?php $sel = ""; if($acceso == 'Correcto') $sel = "selected";?>
										<option value="Correcto" <?php echo $sel?>>Correcto</option>
										<?php $sel = ""; if($acceso == 'Incorrecto') $sel = "selected";?>
										<option value="Incorrecto" <?php echo $sel?>>Incorrecto</option>
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
	$PA = "SELECT";
	$PA .= " L.LogAccesoPanel_ID AS LogAccesoPanel_ID";
	$PA .= ", L.Usuario_ID AS Usuario_ID";
	$PA .= ", L.IP AS IP";
	$PA .= ", L.Fecha AS Fecha";
	$PA .= ", L.Login AS Login";
	$PA .= ", L.Password AS Password";
	$PA .= ", L.Acceso AS Acceso";
	$PA .= ", U.Nombre AS Nombre";
	$PA .= ", U.UsuarioRol_ID AS UsuarioRol_ID";
	$PA .= " FROM tbLogAccesoPanel L ";
	$PA .= "	LEFT JOIN tbUsuario U ON L.Usuario_ID = U.Usuario_ID ";
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
						
						<form action="accesos.php" name="frmBorrar" method="post">
							<input type="hidden" name="bBorrar" value="1" />
							<table class="table table-nomargin table-bordered table-striped table-hover table-pagination">
							<!-- table class="table table-nomargin table-bordered table-striped table-hover dataTable-tools"-->
								<thead>
									<tr>
										<?php echo CampoCabeceraTablaListado("Usuario_ID", TextoDeIdioma("Codigo"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "center");?>
										<?php echo CampoCabeceraTablaListado("UsuarioRol_ID", TextoDeIdioma("Rol_de_usuario"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Nombre", TextoDeIdioma("Nombre"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Login", TextoDeIdioma("Login"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Password", TextoDeIdioma("Password"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("IP", TextoDeIdioma("Direccion_IP"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Fecha", TextoDeIdioma("Fecha"), $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										<?php echo CampoCabeceraTablaListado("Acceso", "Acceso", $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, "left");?>
										
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
										$Login 							= stripslashes(ValorCelda($resultado,$i,"Login"));
										$Fecha 							= fechaHoraNormal(ValorCelda($resultado,$i,"Fecha"));
										$IP 								= stripslashes(ValorCelda($resultado,$i,"IP"));
										$Password						= stripslashes(ValorCelda($resultado,$i,"Password"));
										$Acceso							= stripslashes(ValorCelda($resultado,$i,"Acceso"));
										
										$strHtml .= "<tr>";
										$strHtml .= "	<td style=\"text-align:center;vertical-align:middle\">".$Usuario_ID."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".ExecQueryValue("SELECT Nombre FROM tbUsuarioRol WHERE UsuarioRol_ID = ".$UsuarioRol_ID)."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$Nombre."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$Login."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$Password."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$IP."</td>";
										$strHtml .= "	<td style=\"vertical-align:middle\">".$Fecha."</td>";
										$strHtml .= "	<td style=\"text-align:center;vertical-align:middle\">";
										if ($Acceso == 'Correcto')
										{
											//$strHtml .= "		<i class=\"icon-ok\"></i>";
											$strHtml .= "		<img src=\"".RUTA_ADM."/img/icons/check.png\" alt=\"".$Acceso."\" width=\"32\" /></td>";
										}else{
											//$strHtml .= "		<i class=\"icon-remove\"></i>";
											$strHtml .= "		<img src=\"".RUTA_ADM."/img/icons/busy.png\" alt=\"".$Acceso."\" width=\"32\" /></td>";
										}
										$strHtml .= "</td>";
										
										$strHtml .= "</tr>";
									}
									echo $strHtml;
								?>
									</tbody>
								</table>
								<div class="bottom-table">
									<div class="pull-right">
										<?php echo PaginacionListado(4, $totalRegs, $inicio, $final, $pag, $regsPorPag, $urlPag);?>&nbsp;&nbsp;&nbsp;
									</div>
								</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo PiePanelControl(); ?>