<?php
define("RUTA_RAIZ","");
require_once("_SiteLayout.php");

$strHtml = Cabecera(SEC_PEDIDO);
echo $strHtml;

	$PA = "SELECT DISTINCT ";
	$PA .= "		Categoria_ID, Nombre";
	$PA .= "		, Orden, bActivo, Foto1";
	$PA .= "		, CategoriaPadre_ID";
	$PA .= " FROM 	tbCategoria ";
	$PA .= "ORDER BY Orden;";

	//echo $PA;
	$resultado = ExecPA($PA);
	$NumElementos = mysql_affected_rows();
?>
	<!-- PEDIDO -->
	<div class="row-fluid" id="screenshots">
		
		<h2 id="scroll_up">Pedido MAS</h2>
			
				<div class="row-fluid">
					<div class="span12">
						<div class="box">
							<div class="box-head">
								<i class="icon-list-ul"></i>
								<span>Wizard horizontal steps</span>
							</div>
							<div class="box-body box-body-nopadding">
								<form action="#" method="post" class="form-horizontal form-bordered form-validate form-wizard" id="ss">
									<div class="step" id="firstStep">
										<ul class="wizard-steps wizard-style-1 steps-4">
											<li class='active'>
												<div class="single-step">
													<span class="title">
														Step 1
													</span>
													<span class="circle">
														<span class="active"></span>
													</span>
													<span class="description">
														Basic information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 2
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Advanced information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 3
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Additional information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 4
													</span>
													<span class="circle">
														<span class="active"></span>
													</span>
													<span class="description">
														Mapa
													</span>
												</div>
											</li>
										</ul>
										<div class="control-group">
											<label for="firstname" class="control-label">First name</label>
											<div class="controls">
												<input type="text" name="firstname" id="firstname" class="input-xlarge" data-rule-required="true" data-msg-required="Introduzca el nombre">
											</div>
										</div>
										<div class="control-group">
											<label for="anotherelem" class="control-label">Last name</label>
											<div class="controls">
												<input type="text" name="anotherelem" id="anotherelem" class="input-xlarge">
											</div>
										</div>
										<div class="control-group">
											<label for="additionalfield" class="control-label">Additional information</label>
											<div class="controls">
												<input type="text" name="additionalfield" id="additionalfield" class="input-xlarge">
											</div>
										</div>
									</div>
									<div class="step" id="secondStep">
										<ul class="wizard-steps wizard-style-1 steps-4">
											<li>
												<div class="single-step">
													<span class="title">
														Step 1
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Basic information
													</span>
												</div>
											</li>
											<li class='active'>
												<div class="single-step">
													<span class="title">
														Step 2
													</span>
													<span class="circle">
														<span class="active"></span>
													</span>
													<span class="description">
														Advanced information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 3
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Additional information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 4
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Mapa
													</span>
												</div>
											</li>
										</ul>
										<div class="control-group">
											<label for="text" class="control-label">Gender</label>
											<div class="controls">
												<select name="gend" id="gend" class="uniform-me">
													<option value="">-- Chose one --</option>
													<option value="1">Male</option>
													<option value="2">Female</option>
												</select>
											</div>
										</div>
										<div class="control-group">
											<label for="text" class="control-label">Accept policy</label>
											<div class="controls">
												<label class="checkbox"><input type="checkbox" name="policy" value="agree"> I agree the policy.</label>
											</div>
										</div>
									</div>
									<div class="step" id="thirdStep">
										<ul class="wizard-steps wizard-style-1 steps-4">
											<li>
												<div class="single-step">
													<span class="title">
														Step 1
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Basic information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 2
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Advanced information
													</span>
												</div>
											</li>
											<li class='active'>
												<div class="single-step">
													<span class="title">
														Step 3
													</span>
													<span class="circle">
														<span class="active"></span>
													</span>
													<span class="description">
														Additional information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 4
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Mapa
													</span>
												</div>
											</li>
										</ul>
										<div class="control-group">
											<label for="text" class="control-label">Additional information</label>
											<div class="controls">
												<textarea name="textare" id="tt333" class="span12" rows="7" placeholder="You can provide additional information in here..."></textarea>
											</div>
										</div>
									</div>
									
									<div class="step" id="fourthStep">
										<ul class="wizard-steps wizard-style-1 steps-4">
											<li>
												<div class="single-step">
													<span class="title">
														Step 1
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Basic information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 2
													</span>
													<span class="circle">
													</span>
													<span class="description">
														Advanced information
													</span>
												</div>
											</li>
											<li>
												<div class="single-step">
													<span class="title">
														Step 3
													</span>
													<span class="circle">
														<span class="active"></span>
													</span>
													<span class="description">
														Additional information
													</span>
												</div>
											</li>
											<li class='active'>
												<div class="single-step">
													<span class="title">
														Step 4
													</span>
													<span class="circle">
														<span class="active"></span>
													</span>
													<span class="description">
														Mapa
													</span>
												</div>
											</li>
										</ul>
										<div class="control-group">
											<label for="text" class="control-label">Additional information</label>
											<div class="controls">
												<textarea name="textare" id="tt3334" class="span12" rows="7" placeholder="You can provide additional information in here..."></textarea>
											</div>
										</div>
									</div>
									
									<div class="form-actions">
										<button type="reset" class="btn" id="back"><i class="icon-step-backward"></i> Anterior</button>
										<button type="submit" class="btn btn-primary" id="next"><i class="icon-step-forward"></i> Siguiente</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				
				
	</div>
<?php echo Pie();?>