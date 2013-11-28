<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");

ComprobarLoginPanel();
$strHtml = CabeceraPanelControl(SEC_ADM_USUARIOS);

echo $strHtml;

$Usuario_ID = intval(parametro("Usuario_ID"));
if($Usuario_ID == 0){
	$accion = ACCION_NUEVO;
	$required = "true";
}else{
	$accion = ACCION_EDITAR;
	$required = "false";
}
$PA="SELECT Usuario_ID, Login, Password, Nombre, Email, UsuarioRol_ID, bActivo, FechaAlta, FechaUltimoAcceso FROM tbUsuario WHERE Usuario_ID = ".$Usuario_ID;
$resultado = ExecPA($PA);
$NumElementos = mysql_affected_rows();

if($NumElementos == 0){
	Desconecta();
	$strError = TextoDeIdioma("No_se_ha_encontrado_el_registro_solicitado");
}else{
	$Usuario_ID 				= intval(ValorCelda($resultado,0,"Usuario_ID"));
	$UsuarioRol_ID 			= intval(ValorCelda($resultado,0,"UsuarioRol_ID"));
	$Login 							= stripslashes(ValorCelda($resultado,0,"Login"));
	$Password 					= stripslashes(ValorCelda($resultado,0,"Password"));
	$Nombre 						= stripslashes(ValorCelda($resultado,0,"Nombre"));
	$Email 							= stripslashes(ValorCelda($resultado,0,"Email"));
	$bActivo						= intval(ValorCelda($resultado,0,"bActivo"));
	$FechaAlta 					= fechaHoraNormal(ValorCelda($resultado,0,"FechaAlta"));
	$arrFechaAlta 			= split(" ", $FechaAlta);
	$FechaAlta					= $arrFechaAlta[0];
	$HoraAlta 					= $arrFechaAlta[1];
	$FechaUltimoAcceso 	= fechaHoraNormal(ValorCelda($resultado,0,"FechaUltimoAcceso"));
	$arrFechaUltimoAcceso	= split(" ", $FechaUltimoAcceso);
	$FechaUltimoAcceso	= $arrFechaUltimoAcceso[0];
	$HoraUltimoAcceso		= $arrFechaUltimoAcceso[1];
	if($bActivo==1){
		$strActivo = "checked";
	}else{
		$strActivo = "";
	}
}

if($accion == ACCION_NUEVO){
	$FechaAlta = date("d/m/Y");
	$HoraAlta = date("H:i");
	$FechaUltimoAcceso = date("d/m/Y");
	$HoraUltimoAcceso = date("H:i");
}
?>
	<script type="text/javascript">
		$(function () {
			$("#fechaAlta").mask("99/99/9999", {placeholder:"_"});
			$('#fechaAlta').datepicker({
					format: 'dd/mm/yyyy'
					, weekStart: 1
					, todayHighlight: true
					, autoclose: false
					, language: 'es'
			});
			$("#horaAlta").mask("99:99", {placeholder:"_"});
			$("#fechaUltimoAcceso").mask("99/99/9999", {placeholder:"_"});
			$('#fechaUltimoAcceso').datepicker({
					format: 'dd/mm/yyyy'
					, weekStart: 1
					, todayHighlight: true
					, autoclose: false
					, language: 'es'
			});
			$("#horaUltimoAcceso").mask("99:99", {placeholder:"_"});
		});
	</script>
	
			<!-- Migas de pan -->
			<div class="page-header">
				<div class="pull-left">
					<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_usuarios")?></h4>
				</div>
				<div class="pull-right">
					<ul class="bread">
						<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
		        <li><a href="<?php echo RUTA_ADM?>usuarios/index.php"><?php echo TextoDeIdioma("Usuarios")?></a><span class="divider">/</span></li>
						<li class="active"><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("usuario")?></li>
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
								<span><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("usuario")?></span>
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
									<input type="hidden" name="ID" value="<?php echo $Usuario_ID;?>" />
									<div class="control-group">
										<label class="control-label"><?php echo TextoDeIdioma("Numero_de_usuario")?></label>
										<div class="controls">
											<span class="uneditable-input input-mini"><?php echo $Usuario_ID?></span>
											<span class="help-block">*<?php echo TextoDeIdioma("El_codigo_no_se_puede_cambiar")?></span>
										</div>
									</div>
									<div class="control-group">
										<label for="login" class="control-label"><?php echo TextoDeIdioma("Login")?> <small><?php echo TextoDeIdioma("minimo")?>: 5 <?php echo TextoDeIdioma("caracteres")?></small></label>
										<div class="controls">
											<div class="input-prepend">
												<span class="add-on">@</span>
												<input type="text" placeholder="<?php echo TextoDeIdioma("Al_menos_X_caracteres", "5")?>" name="login" id="login" value="<?php echo $Login?>" class="input-medium" data-rule-minlength="5" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_un_login_de_usuario")?>" data-msg-minlength="<?php echo TextoDeIdioma("Introduzca_al_menos_X_caracteres", "5")?>">
											</div>
										</div>
									</div>
		              
		              <?php 
		              if($accion==ACCION_EDITAR){?>
	                  <div class="alert alert-warning">
									    <i class=" icon-warning-sign icon-large"></i> <strong><?php echo TextoDeIdioma("Atencion_Introduzca_el_campo")?> <code><?php echo TextoDeIdioma("Password")?></code> <?php echo TextoDeIdioma("solo_en_caso_de_querer_modificarlo")?></strong>
								    </div>
                  <?php }?>
									<div class="control-group">
										<label for="password" class="control-label"><?php echo TextoDeIdioma("Password")?> <small><?php echo TextoDeIdioma("recomendado_letras_y_numeros")?></small></label>
										<div class="controls">
											<div class="input-append">
												<input type="text" placeholder="<?php echo TextoDeIdioma("Al_menos_8_caracteres")?>" name="password" id="password" class="input-medium" data-rule-required="<?php echo $required?>" data-rule-minlength="8" data-msg-required="<?php echo TextoDeIdioma("Introduzca_un_password")?>" data-msg-minlength="<?php echo TextoDeIdioma("Introduzca_al_menos_8_caracteres")?>">
												<span class="add-on"><i class="icon-key"></i></span>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label for="password-confirm" class="control-label"><?php echo TextoDeIdioma("Confirmar_password")?> <small><?php echo TextoDeIdioma("recomendado_letras_y_numeros")?></small></label>
										<div class="controls">
											<div class="input-append">
												<input type="text" placeholder="<?php echo TextoDeIdioma("Al_menos_8_caracteres")?>" name="password-confirm" id="password-confirm" class="input-medium" data-rule-required="<?php echo $required?>" data-rule-minlength="8" data-rule-equalto="#password" data-msg-required="<?php echo TextoDeIdioma("Introduzca_un_password")?>" data-msg-minlength="<?php echo TextoDeIdioma("Introduzca_al_menos_8_caracteres")?>" data-msg-equalto="<?php echo TextoDeIdioma("Los_passwords_deben_coincidir")?>">
												<span class="add-on"><i class="icon-key"></i></span>
											</div>
										</div>
									</div>
                  <div class="control-group">
										<label for="email" class="control-label"><?php echo TextoDeIdioma("Email")?></label>
										<div class="controls">
											<div class="input-prepend">
												<span class="add-on"><icon class="icon-envelope"></icon></span>
												<input type="text" name="email" id="email" value="<?php echo $Email?>" data-rule-required="true" data-rule-email="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_una_direccion_de_email_valida")?>" data-msg-email="<?php echo TextoDeIdioma("Introduzca_una_direccion_de_email_valida")?>" />
											</div>
										</div>
									</div>
									<div class="control-group">
										<label for="nombre" class="control-label"><?php echo TextoDeIdioma("Nombre")?></label>
										<div class="controls">
											<input type="text" name="nombre" id="nombre" value="<?php echo $Nombre?>" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_nombre")?>" />
										</div>
									</div>
									
									<div class="control-group">
										<label for="UsuarioRol_ID" class="control-label"><?php echo TextoDeIdioma("Rol")?></label>
										<div class="controls">
											<?php
											$sql = "SELECT UsuarioRol_ID valor, Nombre descripcion
												FROM tbUsuarioRol
												WHERE bActivo = 1";
											$sql .= " AND UsuarioRol_ID >= ".$_SESSION["sPANEL_UsuarioRol_ID"];
											$sql .= " ORDER BY UsuarioRol_ID";
											$strComboUsuarioRol = generaComboBox( $sql
													, "UsuarioRol_ID"
													, $noSeleccion="-- ".TextoDeIdioma("Seleccione_un_elemento")." --"
													, $UsuarioRol_ID
													, "data-rule-required=\"true\" data-msg-required=\"".TextoDeIdioma("Seleccione_un_elemento_de_la_lista")."\""
											);
											echo $strComboUsuarioRol;?>
										</div>
									</div>
									
									<div class="control-group">
						        <label for="fechaAlta" class="control-label"><?php echo TextoDeIdioma("Fecha_de_alta")?> <small>[dd/mm/yyyy hh:mm]</small></label>
						        <div class="controls">
						        	<div class="input-append">
												<input type="text" placeholder="<?php echo TextoDeIdioma("Introduzca_solo_numeros")?>" name="fechaAlta" id="fechaAlta" value="<?php echo $FechaAlta?>" class="input-small" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_una_fecha_de_alta")?>">
												<span class="add-on"><i class="icon-calendar"></i></span>
											</div>
											<div class="input-append bootstrap-timepicker">
												<input type="text" placeholder="<?php echo TextoDeIdioma("Introduzca_solo_numeros")?>" name="horaAlta" id="horaAlta" value="<?php echo $HoraAlta?>" class="input-mini timepick" data-show-meridian="false">
		                    <span class="add-on"><i class="icon-time"></i></span>
											</div>
						        </div>
						    	</div>
									<div class="control-group">
										<label for="fechaUltimoAcceso" class="control-label"><?php echo TextoDeIdioma("Ultimo_acceso")?> <small>[dd/mm/yyyy hh:mm]</small></label>
										<div class="controls">
											<div class="input-append">
												<input type="text" placeholder="<?php echo TextoDeIdioma("Introduzca_solo_numeros")?>" name="fechaUltimoAcceso" id="fechaUltimoAcceso" value="<?php echo $FechaUltimoAcceso?>" class="input-small" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_una_fecha_de_ultimo_acceso")?>">
												<span class="add-on"><i class="icon-calendar"></i></span>
											</div>
											<div class="input-append bootstrap-timepicker">
												<input type="text" placeholder="<?php echo TextoDeIdioma("Introduzca_solo_numeros")?>" name="horaUltimoAcceso" id="horaUltimoAcceso" value="<?php echo $HoraUltimoAcceso?>" class="input-mini timepick" data-show-meridian="false">
												<span class="add-on"><i class="icon-time"></i></span>
											</div>
										</div>
									</div>
									<div class="control-group">
										<label for="chkActivo" class="control-label"><?php echo TextoDeIdioma("Activo")?></label>
										<div class="controls">
											<input type="checkbox" name="chkActivo" id="chkActivo" value="1" <?php echo $strActivo?>>
										</div>
									</div>
									
									<!-- div class="control-group">
										<label for="urlfield" class="control-label">URL </label>
										<div class="controls">
											<input type="text" placeholder="http://" name="url" data-rule-required="true" data-rule-url="true" data-msg-required="Introduzca una url" data-msg-url="Introduzca una url v�lida, comenzando con http://" />
										</div>
									</div> -->
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