<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");

ComprobarLoginPanel();
$strHtml = CabeceraPanelControl(SEC_ADM_PEDIDOS);

echo $strHtml;

	$Pedido_ID = intval(parametro("Pedido_ID"));
	if($Pedido_ID == 0){
		$accion = ACCION_NUEVO;
		$required = "true";
		
		$FechaAlta = date("d/m/Y");
		$HoraAlta = date("h:i A");
		$FechaBaja = "";
		$HoraBaja = "00:00";
		$FechaUltimoAcceso = date("d/m/Y");
		$HoraUltimoAcceso = date("h:i A");
		$IP = $_SERVER['REMOTE_ADDR'];
		$Pais_ID = 195;
	}else{
		$accion = ACCION_EDITAR;
		$required = "false";
	
		$PA="SELECT * FROM tbPedido WHERE Pedido_ID = ".$Pedido_ID;
		$resultado = ExecPA($PA);
		$NumElementos = mysql_affected_rows();
		
		if($NumElementos == 0){
			Desconecta();
			$strError = TextoDeIdioma("No_se_ha_encontrado_el_registro_solicitado");
		}else{
			
			$Pedido_ID 				= intval(ValorCelda($resultado,0,"Pedido_ID"));
			$Cliente_ID 			= intval(ValorCelda($resultado,0,"Cliente_ID"));
			$Proveedor_ID 		= intval(ValorCelda($resultado,0,"Proveedor_ID"));
			
			$Fecha	 					= fechaHoraNormal(ValorCelda($resultado,0,"Fecha"));
			$arrFechaAlta 		= split(" ", $FechaAlta);
			$FechaAlta				= $arrFechaAlta[0];
			$HoraAlta 				= $arrFechaAlta[1];
			
			$Lat							= floatval(ValorCelda($resultado,0,"Lat"));
			$Lon 							= floatval(ValorCelda($resultado,0,"Lon"));
			
			$Estado				= intval(ValorCelda($resultado,0,"Estado"));
			
			$Observaciones			= stripslashes(ValorCelda($resultado,0,"Observaciones"));
		}
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

			//Donde haya '~' solo podrá introducir + o -
			//$.mask.definitions['~']='[+-]';
			//$("#ip").mask("~9.99.~9.99 999");
		});
	</script>
			<!-- Migas de pan -->
			<div class="page-header">
				<div class="pull-left">
					<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_pedidos")?></h4>
				</div>
				<div class="pull-right">
					<ul class="bread">
						<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
		        <li><a href="<?php echo RUTA_ADM?>pedidos/index.php"><?php echo TextoDeIdioma("Pedidos")?></a><span class="divider">/</span></li>
						<li class="active"><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("pedido")?></li>
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
								<span><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("pedido")?></span>
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
									<input type="hidden" name="ID" value="<?php echo $Pedido_ID;?>" />
									<div class="control-group">
										<label class="control-label"><?php echo TextoDeIdioma("Codigo")?></label>
										<div class="controls">
											<span class="uneditable-input input-mini"><?php echo $Pedido_ID?></span>
											<span class="help-block">*<?php echo TextoDeIdioma("El_codigo_no_se_puede_cambiar")?></span>
										</div>
									</div>
									<div class="control-group">
										<label for="email" class="control-label"><?php echo TextoDeIdioma("Email")?></label>
										<div class="controls">
											<div class="input-prepend">
												<span class="add-on"><i class="icon-envelope"></i></span>
												<input type="text" name="email" id="email" value="<?php echo $Email?>" data-rule-required="true" data-rule-email="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_una_direccion_de_email_valida")?>" data-msg-email="<?php echo TextoDeIdioma("Introduzca_una_direccion_de_email_valida")?>" />
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
										<label for="nombre" class="control-label"><?php echo TextoDeIdioma("Nombre")?></label>
										<div class="controls">
											<input type="text" name="nombre" id="nombre" value="<?php echo $Nombre?>" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_nombre")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="apellidos" class="control-label"><?php echo TextoDeIdioma("Apellidos")?></label>
										<div class="controls">
											<input type="text" name="apellidos" id="apellidos" value="<?php echo $Apellidos?>" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "apellidos")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="nif" class="control-label"><?php echo TextoDeIdioma("NIF_CIF")?></label>
										<div class="controls">
											<input type="text" name="nif" id="nif" value="<?php echo $NIF?>" data-rule-required="false" class="input-small" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "NIF")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="razonSocial" class="control-label"><?php echo TextoDeIdioma("Razon_social")?></label>
										<div class="controls">
											<input type="text" name="razonSocial" id="razonSocial" value="<?php echo $RazonSocial?>" data-rule-required="false" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "Razon_social")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="telefono" class="control-label"><?php echo TextoDeIdioma("Telefono")?></label>
										<div class="controls">
											<input type="text" name="telefono" id="telefono" value="<?php echo $Telefono?>" data-rule-required="false" class="input-small" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "Telefono")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="movil" class="control-label"><?php echo TextoDeIdioma("Movil")?></label>
										<div class="controls">
											<input type="text" name="movil" id="movil" value="<?php echo $Movil?>" data-rule-required="false" class="input-small" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "Movil")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="direccion" class="control-label"><?php echo TextoDeIdioma("Direccion")?></label>
										<div class="controls">
											<input type="text" name="direccion" id="direccion" value="<?php echo $Direccion?>" data-rule-required="false" class="input-normal" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "Direccion")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="poblacion" class="control-label"><?php echo TextoDeIdioma("Poblacion")?></label>
										<div class="controls">
											<input type="text" name="poblacion" id="poblacion" value="<?php echo $Poblacion?>" data-rule-required="false" class="input-normal" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "Poblacion")?>" />
										</div>
									</div>

									<div class="control-group">
										<label for="pais_ID" class="control-label"><?php echo TextoDeIdioma("Pais")?></label>
										<div class="controls">
											<?php
											$sql = "SELECT IdPais valor, Nombre descripcion
												FROM tbPais";
											$sql .= " ORDER BY Nombre";
											$strCombo = generaComboBox( $sql
													, "pais_ID"
													, $noSeleccion="-- ".TextoDeIdioma("Seleccione_un_elemento")." --"
													, $Pais_ID
													, "onChange=\"return pais_IDOnChange()\" data-rule-required=\"true\" data-msg-required=\"".TextoDeIdioma("Seleccione_un_elemento_de_la_lista")."\""
											);
											echo $strCombo;?>
										</div>	
									</div>
									<div class="control-group">
										<label for="provincia_ID" class="control-label"><?php echo TextoDeIdioma("Provincia")?></label>
										<div class="controls">
											<?php
											$sql = "SELECT IdProvincia valor, Nombre descripcion
												FROM tbProvincia";
											$sql .= " WHERE IdPais = ".$Pais_ID;
											$sql .= " ORDER BY Nombre";
											$strCombo = generaComboBox( $sql
													, "provincia_ID"
													, $noSeleccion="-- ".TextoDeIdioma("Seleccione_un_elemento")." --"
													, $Provincia_ID
													, "data-rule-required=\"false\" data-msg-required=\"".TextoDeIdioma("Seleccione_un_elemento_de_la_lista")."\""
											);
											echo $strCombo;?>
										</div>
									</div>
									
									<span id="advice"> </span>
									<div class="control-group">
										<label for="cp" class="control-label"><?php echo TextoDeIdioma("CP")?></label>
										<div class="controls">
											<input type="text" name="cp" id="cp" value="<?php echo $CP?>" data-rule-required="false" class="input-small" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "CP")?>" />
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
						    	<?php 
		              if($accion==ACCION_EDITAR){?>
						    	<div class="control-group">
						        <label for="fechaBaja" class="control-label"><?php echo TextoDeIdioma("Fecha_de_baja")?> <small>[dd/mm/yyyy hh:mm]</small></label>
						        <div class="controls">
						        	<div class="input-append">
												<input type="text" placeholder="<?php echo TextoDeIdioma("Introduzca_solo_numeros")?>" name="fechaBaja" id="fechaBaja" value="<?php echo $FechaBaja?>" class="input-small" data-rule-required="false" data-msg-required="<?php echo TextoDeIdioma("Introduzca_una_fecha_de_baja")?>">
												<span class="add-on"><i class="icon-calendar"></i></span>
											</div>
											<div class="input-append bootstrap-timepicker">
												<input type="text" placeholder="<?php echo TextoDeIdioma("Introduzca_solo_numeros")?>" name="horaBaja" id="horaBaja" value="<?php echo $HoraBaja?>" class="input-mini timepick" data-show-meridian="false">
		                    <span class="add-on"><i class="icon-time"></i></span>
											</div>
						        </div>
						    	</div>
						    	<?php } ?>
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
										<label for="nombre" class="control-label"><?php echo TextoDeIdioma("Direccion_IP")?></label>
										<div class="controls">
											<input type="text" name="ip" id="ip" value="<?php echo $IP?>" data-rule-required="false" data-msg-required="<?php echo TextoDeIdioma("Introduzca_el_campo_X", "IP")?>" />
										</div>
									</div>
									<div class="control-group">
										<label for="observaciones" class="control-label"><?php echo TextoDeIdioma("Observaciones")?> <small></small></label>
										<div class="controls">
											<div>
												<textarea name="observaciones" id="observaciones" class="cleditor" rows="5"><?php echo $Observaciones?></textarea>
											</div>
										</div>
									</div>
									
									<div class="control-group">
										<label for="chkBoletin" class="control-label"><?php echo TextoDeIdioma("Boletin")?></label>
										<div class="controls">
											<input type="checkbox" name="chkBoletin" id="chkBoletin" value="1" <?php echo $strBoletin?>>
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