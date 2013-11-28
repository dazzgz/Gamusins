<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");

ComprobarLoginPanel();
$strHtml = CabeceraPanelControl(SEC_ADM_ROLES);

echo $strHtml;

$UsuarioRol_ID = intval(parametro("UsuarioRol_ID"));
if($UsuarioRol_ID == 0){
	$accion = ACCION_NUEVO;
	$required = "true";
}else{
	$accion = ACCION_EDITAR;
	$required = "false";
}
$PA="SELECT UsuarioRol_ID, Nombre, bActivo FROM tbUsuarioRol WHERE UsuarioRol_ID = ".$UsuarioRol_ID;
$resultado = ExecPA($PA);
$NumElementos = mysql_affected_rows();

if($NumElementos == 0){
	Desconecta();
	$strError = TextoDeIdioma("No_se_ha_encontrado_el_registro_solicitado");
}else{
	$UsuarioRol_ID 			= intval(ValorCelda($resultado,0,"UsuarioRol_ID"));
	$Nombre 						= stripslashes(ValorCelda($resultado,0,"Nombre"));
	$bActivo						= intval(ValorCelda($resultado,0,"bActivo"));
	if($bActivo==1){
		$strActivo = "checked";
	}else{
		$strActivo = "";
	}
}
?>
			<!-- Migas de pan -->
			<div class="page-header">
				<div class="pull-left">
					<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_roles")?></h4>
				</div>
				<div class="pull-right">
					<ul class="bread">
						<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
		        <li><a href="<?php echo RUTA_ADM?>roles/index.php"><?php echo TextoDeIdioma("Roles")?></a><span class="divider">/</span></li>
						<li class="active"><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("rol_de_usuario")?></li>
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
								<span><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("rol_de_usuario")?></span>
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
									<input type="hidden" name="ID" value="<?php echo $UsuarioRol_ID;?>" />
									<div class="control-group">
										<label class="control-label"><?php echo TextoDeIdioma("Numero_de_rol")?></label>
										<div class="controls">
											<span class="uneditable-input input-mini"><?php echo $UsuarioRol_ID?></span>
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