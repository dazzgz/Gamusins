<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");

ComprobarLoginPanel();
$strHtml = CabeceraPanelControl(SEC_ADM_PARAMETROS_MEDIDAS);

echo $strHtml;

$Medida_ID = intval(parametro("Medida_ID"));
if($Medida_ID == 0){
	$accion = ACCION_NUEVO;
	$required = "true";
	$Orden = intval(ExecQueryValue("SELECT MAX(Orden) FROM tbMedida")) + 1;
}else{
	$accion = ACCION_EDITAR;
	$required = "false";
}
$PA="SELECT Medida_ID, Nombre, Orden, bActivo FROM tbMedida WHERE Medida_ID = ".$Medida_ID;
$resultado = ExecPA($PA);
$NumElementos = mysql_affected_rows();

if($NumElementos == 0){
	Desconecta();
	$strError = TextoDeIdioma("No_se_ha_encontrado_el_registro_solicitado");
}else{
	$Medida_ID 			= intval(ValorCelda($resultado,0,"Medida_ID"));
	$Nombre 						= stripslashes(ValorCelda($resultado,0,"Nombre"));
	$Orden							= intval(ValorCelda($resultado,0,"Orden"));
	$bActivo						= intval(ValorCelda($resultado,0,"bActivo"));
	if($bActivo==1){
		$strActivo = "checked";
	}else{
		$strActivo = "";
	}
}
?>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.combobox').combobox();
			$('.selectpicker').selectpicker({
				//style: 'btn-info',
				size: 5
		  });
		});
	</script>
	
			<!-- Migas de pan -->
			<div class="page-header">
				<div class="pull-left">
					<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_medidas")?></h4>
				</div>
				<div class="pull-right">
					<ul class="bread">
						<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
		        <li><a href="<?php echo RUTA_ADM?>medidas/index.php"><?php echo TextoDeIdioma("Medidas")?></a><span class="divider">/</span></li>
						<li class="active"><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("medida")?></li>
					</ul>
				</div>
			</div>
			
			<!-- EDICION -->
			
			<div class="container-fluid" id="content-area">

				<div class="row-fluid">
					<div class="span12">
						<div class="box">
							<div class="box-head">
								<i class="icon-list-ul"></i>
								<span><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("medida")?></span>
							</div>
							<div class="box-body box-body-nopadding">
								<?php if($strError != "" && $accion==ACCION_EDITAR){ ?>
								<div class="alert alert-error">
									<i class=" icon-exclamation-sign icon-large"></i> <strong><?php echo $strError?></strong>
								</div>
								<?php }else{ ?>
								<div class="alert alert-info">
									<i class="icon-info-sign icon-large"></i> <strong><?php echo TextoDeIdioma("Introduzca_la_informacion_requerida"); ?></strong>
								</div>
								
								<?php } ?>
								<form name="frmDatos" id="frmDatos" action="proceso.php" method="post" class="form-horizontal form-bordered form-validate">
									<input type="hidden" name="accion" value="<?php echo $accion;?>" />
									<input type="hidden" name="ID" value="<?php echo $Medida_ID;?>" />
									<div class="control-group">
										<label class="control-label"><?php echo TextoDeIdioma("Codigo")?></label>
										<div class="controls">
											<span class="uneditable-input input-mini"><?php echo $Medida_ID?></span>
											<span class="help-block">*<?php echo TextoDeIdioma("El_codigo_no_se_puede_cambiar")?></span>
										</div>
									</div>
									
									<div class="control-group">
										<label for="nombre" class="control-label"><?php echo TextoDeIdioma("Nombre")?></label>
										<div class="controls">
											<input type="text" name="nombre" id="nombre" value="<?php echo $Nombre?>" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_nombre")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="nombre" class="control-label"><?php echo TextoDeIdioma("Orden")?></label>
										<div class="controls">
											<input type="text" name="orden" id="orden" value="<?php echo $Orden?>" class="input-mini" data-rule-required="false" />
										</div>
									</div>
									
									<div class="control-group">
										<label for="categorias_ID" class="control-label"><?php echo TextoDeIdioma("Categorias");?></label>
										<div class="controls">
											<select multiple="multiple" id="categorias_ID" name="categorias_ID[]" class="multiselect" data-selectionheader="Disponible" data-selectableheader="Seleccionado" size="4">
												<?php
													//Cargo las medidas seleccionadas para esta categoria 
													$sqlComboSel = "SELECT Categoria_ID, Medida_ID
																				FROM tbCategoriaMedida
																				WHERE Medida_ID = ".$Medida_ID;
													//echo $sqlComboSel;
													$resComboSel = ExecPA($sqlComboSel);
													$NumElComboSel = mysql_affected_rows();
													for($m=0;$m<$NumElComboSel;$m++)
													{
														$CatSel_ID 			= intval(ValorCelda($resComboSel,$m,"Categoria_ID"));
														$arrCatsSel[$m] = $CatSel_ID;
													}
													//Cargo categorias
													$sqlCombo = "SELECT Categoria_ID, Nombre 
																			FROM tbCategoria
																			WHERE bActivo = 1
																			ORDER BY Orden";
													$resCombo = ExecPA($sqlCombo);
													//echo $sqlCombo;
													$NumElCombo = mysql_affected_rows();
													$strHtmlCombo = "";
													for($k=0;$k<$NumElCombo;$k++)
													{
														$Categoria_ID 		= intval(ValorCelda($resCombo,$k,"Categoria_ID"));
														$NombreCategoria 	= stripslashes(ValorCelda($resCombo,$k,"Nombre"));
														
														$sel = "";
														for($m=0;$m<count($arrCatsSel);$m++)
														{
															if ($Categoria_ID == $arrCatsSel[$m])
															{
																$sel = " selected";
															}
														}
														$strHtmlCombo .= "<option value=\"".$Categoria_ID."\" ".$sel.">".$NombreCategoria."</option>";
													}
													
													echo $strHtmlCombo;
												?>
									    </select>
										</div>
									</div>
									
									<div class="control-group">
										<label for="chkActivo" class="control-label"><?php echo TextoDeIdioma("Activo")?></label>
										<div class="controls">
											<input type="checkbox" name="chkActivo" id="chkActivo" value="1" <?php echo $strActivo?>>
										</div>
									</div>
									
									<div class="form-actions">
										<button type="submit" class="button button-basic-blue"><?php echo TextoDeIdioma("Guardar")?></button>
										<button type="button" class="button button-basic" onclick="location.href='index.php'"><?php echo TextoDeIdioma("Cancelar")?></button>
									</div>
								</form>
							</div>
							
						</div>
					</div>
				</div>
							
			</div>
	
<?php echo PiePanelControl(); ?>