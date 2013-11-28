<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");

ComprobarLoginPanel();
$strHtml = CabeceraPanelControl(SEC_ADM_PARAMETROS_CATEGORIAS);

echo $strHtml;

$Categoria_ID = intval(parametro("Categoria_ID"));
if($Categoria_ID == 0){
	$accion = ACCION_NUEVO;
	$required = "true";
	$Orden = intval(ExecQueryValue("SELECT MAX(Orden) FROM tbCategoria")) + 1;
	$claseUpload1 = "new";
}else{
	$accion = ACCION_EDITAR;
	$required = "false";
}
$PA="SELECT Categoria_ID, CategoriaPadre_ID, Nombre, Orden, bActivo, Foto1 FROM tbCategoria WHERE Categoria_ID = ".$Categoria_ID;
$resultado = ExecPA($PA);
$NumElementos = mysql_affected_rows();

if($NumElementos == 0){
	Desconecta();
	$strError = TextoDeIdioma("No_se_ha_encontrado_el_registro_solicitado");
}else{
	$Categoria_ID 			= intval(ValorCelda($resultado,0,"Categoria_ID"));
	$CategoriaPadre_ID 	= intval(ValorCelda($resultado,0,"CategoriaPadre_ID"));
	$Nombre 						= stripslashes(ValorCelda($resultado,0,"Nombre"));
	$Orden							= intval(ValorCelda($resultado,0,"Orden"));
	$bActivo						= intval(ValorCelda($resultado,0,"bActivo"));
	$Foto1						= intval(ValorCelda($resultado,0,"Foto1"));
	if($Foto1 == 0){
		$claseUpload1 = "new";
	}else{
		$claseUpload1 = "exists";
		$FicheroNombre1 = ExecQueryValue("SELECT Nombre FROM tbFichero WHERE Fichero_ID = ".$Foto1);
	}
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
					<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_categorias")?></h4>
				</div>
				<div class="pull-right">
					<ul class="bread">
						<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
		        <li><a href="<?php echo RUTA_ADM?>categorias/index.php"><?php echo TextoDeIdioma("Categorias")?></a><span class="divider">/</span></li>
						<li class="active"><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("categoria")?></li>
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
								<span><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("categoria")?></span>
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
								<form name="frmDatos" id="frmDatos" action="proceso.php" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered form-validate">
									<input type="hidden" name="accion" value="<?php echo $accion;?>" />
									<input type="hidden" name="ID" value="<?php echo $Categoria_ID;?>" />
									<div class="control-group">
										<label class="control-label"><?php echo TextoDeIdioma("Codigo")?></label>
										<div class="controls">
											<span class="uneditable-input input-mini"><?php echo $Categoria_ID?></span>
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
										<label for="Categoria_ID" class="control-label"><?php echo TextoDeIdioma("Padre")?></label>
										<div class="controls">
											<?php
											$queryCombo = "SELECT Categoria_ID valor, Nombre descripcion
														FROM tbCategoria
														WHERE bActivo = 1 
																	AND CategoriaPadre_ID = %1";
											/*if($Tipo_ID > 0){
												$queryCombo .= " AND Tipo_ID =".$Tipo_ID;
											}*/
											$queryCombo .= " ORDER BY Orden";
											$strCombo = generaComboBoxRecursivo(
													$queryCombo
													, 0
													, ""
													, $Categoria_ID
													, "CategoriaPadre_ID"
													, $noSeleccion="-- ".TextoDeIdioma("Seleccione_un_elemento")." --"
													, $CategoriaPadre_ID
													, "class=\"selectpicker\" data-rule-required=\"false\" data-msg-required=\"".TextoDeIdioma("Seleccione_un_elemento_de_la_lista")."\""
											);
											echo $strCombo; ?>
										</div> 
									</div>
									<!-- div class="control-group">
										<label for="Categoria_ID" class="control-label"><?php //echo TextoDeIdioma("Padre")?></label>
										<div class="controls">
											<?php /*
											$strCombo = generaComboBoxRelacionadoRecursivo(
													"SELECT Tipo_ID valor, Nombre descripcion
														FROM tbTipo
														WHERE bActivo = 1
														ORDER BY Orden"
													,
													"SELECT Categoria_ID valor, Nombre descripcion
														FROM tbCategoria
														WHERE bActivo = 1
																	AND Tipo_ID = %1
																	AND CategoriaPadre_ID = %2
																 ORDER BY Orden"
													, 0
													, ""
													, $Categoria_ID
													, "CategoriaPadre_ID"
													, $noSeleccion="-- ".TextoDeIdioma("Seleccione_un_elemento")." --"
													, $CategoriaPadre_ID
													, "class=\"selectpicker\" data-rule-required=\"false\" data-msg-required=\"".TextoDeIdioma("Seleccione_un_elemento_de_la_lista")."\""
											);
											echo $strCombo; */?>
										</div> 
									</div -->	
									<div class="control-group">
										<label for="foto1" class="control-label"><?php echo TextoDeIdioma("Foto").""?></label>
										<div class="controls">
											<div class="fileupload fileupload-<?php echo $claseUpload1?>" data-provides="fileupload" data-name="foto1" data-uploadtype="image">
												<input type="hidden" name="foto1" value="1" />
												<div class="fileupload-new thumbnail" style="width: 50px; height: 50px;"><img src="http://www.placehold.it/50x50/EFEFEF/AAAAAA" /></div>
												<div class="fileupload-preview fileupload-exists thumbnail" style="width: 50px; height: 50px;"><img src="<?php echo DOC_ROOT.DIR_UPLOAD.$FicheroNombre1?>" /></div>
												<span class="btn btn-file"><span class="fileupload-new"><?php echo TextoDeIdioma("Seleccione_archivo")?></span>
												<span class="fileupload-exists"><?php echo TextoDeIdioma("Cambiar")?></span><input type="file" /></span>
												<a href="#" class="btn fileupload-exists" data-dismiss="fileupload"><?php echo TextoDeIdioma("Cancelar")?></a>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label for="chkActivo" class="control-label"><?php echo TextoDeIdioma("Activo")?></label>
										<div class="controls">
											<input type="checkbox" name="chkActivo" id="chkActivo" value="1" <?php echo $strActivo?>>
										</div>
									</div>
									
									<div class="form-actions">
										<button type="submit" class="button button-basic button-basic-blue"><i class="icon-save"></i> <?php echo TextoDeIdioma("Guardar")?></button>
										<button type="button" class="button button-basic" onclick="location.href='index.php'"><?php echo TextoDeIdioma("Cancelar")?></button>
									</div>
								</form>
							</div>
							
						</div>
					</div>
				</div>
							
			</div>
	
<?php echo PiePanelControl(); ?>