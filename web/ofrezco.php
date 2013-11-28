<?php
define("RUTA_RAIZ","");
require_once("_SiteLayout.php");

	//$texto = addslashes(parametro("texto"));

	//CARGO CATEGORIAS DE PRODUCTO
	$PA = "SELECT DISTINCT ";
	$PA .= "		Categoria_ID, Nombre";
	$PA .= "		, Orden, bActivo, Foto1";
	$PA .= "		, CategoriaPadre_ID";
	$PA .= " FROM 	tbCategoria ";
	$PA .= "ORDER BY Orden;";

	//echo $PA;
	$resultado = ExecPA($PA);
	$NumElementos = mysql_affected_rows();

	$strCats = '<ul class="thumbnails">';
	$k=1;
	for($i=0;$i<$NumElementos;$i++)
	{
		$Categoria_ID 	= intval(ValorCelda($resultado,$i,"Categoria_ID"));
		$Nombre 				= stripslashes(ValorCelda($resultado,$i,"Nombre"));
		$Orden 					= intval(ValorCelda($resultado,$i,"Orden"));
		$CategoriaPadre_ID 	= intval(ValorCelda($resultado,$i,"CategoriaPadre_ID"));
		$esActivo 			= intval(ValorCelda($resultado,$i,"bActivo"));
		$Foto1 					= intval(ValorCelda($resultado,$i,"Foto1"));
		
		if($Foto1 > 0){
			$strFoto1 = DOC_ROOT.DIR_UPLOAD.ExecQueryValue("SELECT Nombre FROM tbFichero WHERE Fichero_ID = ".$Foto1);
		}else{
			$strFoto1 = 'http://www.placehold.it/50x50/EFEFEF/AAAAAA';
		}
		
		$strCats .= '<li class="span3">';
			$strCats .= '<a href="'.$strFoto1.'" rel="gallery" class="thumbnail">';
				$strCats .= '<img class="img-circle" src="'.$strFoto1.'" title="'.$Nombre.'" alt="'.$Nombre.'">';
			$strCats .= '</a>';
			$strCats .= '<span class="">'.$Nombre.'</span>';
		$strCats .= '</li>';
		if($k % 4 == 0){
			$strCats .= '</ul>';
			$strCats .= '<ul class="thumbnails">';
		}
		$k++;
	}
	$strCats .= '</ul>';
	
	//if($Empleado_ID > 0){
		$bMapa = true;
	/*}else{
		$bMapa = false;
	}*/
	
$strHtmlFrame = Cabecera(SEC_PEDIDO, $bMapa, $parametros_query);
echo $strHtmlFrame;
		
function EtapaDePedido($etapa){

	switch($etapa){
		case 1:
			$esActiva1 = ' class=\'active\'';
			$selec1 = '<span class=\'active\'></span>';
			$esActiva2 = '';
			$selec2 = '';
			$esActiva3 = '';
			$selec3 = '';
			$esActiva4 = '';
			$selec4 = '';
			break;
		case 2:
			$esActiva1 = '';
			$selec1 = '';
			$esActiva2 = ' class=\'active\'';
			$selec2 = '<span class=\'active\'></span>';
			$esActiva3 = '';
			$selec3 = '';
			$esActiva4 = '';
			$selec4 = '';
			break;
		case 3:
			$esActiva1 = '';
			$selec1 = '';
			$esActiva2 = '';
			$selec2 = '';
			$esActiva3 = ' class=\'active\'';
			$selec3 = '<span class=\'active\'></span>';
			$esActiva4 = '';
			$selec4 = '';
			break;
		case 4:
			$esActiva1 = '';
			$selec1 = '';
			$esActiva2 = '';
			$selec2 = '';
			$esActiva3 = '';
			$selec3 = '';
			$esActiva4 = ' class=\'active\'';
			$selec4 = '<span class=\'active\'></span>';
			break;					
	}
	
	$strEtapa = '
										<ul class="wizard-steps wizard-style-1 steps-4">
						
											<li'.$esActiva1.'>
												<div class="single-step">
													<span class="title">
														Paso 1
													</span>
													<span class="circle">
														'.$selec1.'
													</span>
													<span class="description">
														Elige los productos
													</span>
												</div>
											</li>
											<li'.$esActiva2.'>
												<div class="single-step">
													<span class="title">
														Paso 2
													</span>
													<span class="circle">
														'.$selec2.'
													</span>
													<span class="description">
														Introduce las cantidades
													</span>
												</div>
											</li>
											<li'.$esActiva3.'>
												<div class="single-step">
													<span class="title">
														Paso 3
													</span>
													<span class="circle">
														'.$selec3.'
													</span>
													<span class="description">
														Selecciona el centro mas cercano
													</span>
												</div>
											</li>
											<li'.$esActiva4.'>
												<div class="single-step">
													<span class="title">
														Paso 4
													</span>
													<span class="circle">
														'.$selec4.'
													</span>
													<span class="description">
														Enviar el pedido
													</span>
												</div>
											</li>

										</ul>
						';
	
	return $strEtapa;
}
?>
	<div class="row-fluid" id="screenshots">
		<h2 id="scroll_up">Pedido</h2>
		
				<div class="row-fluid">
					<div class="span12">
						<div class="box">
							<div class="box-head">
								<i class="icon-list-ul"></i>
								<span>Seleccione los tipos de alimentos a suministrar</span>
							</div>
							<div class="box-body box-body-nopadding">
								<form id="ss" action="#" method="post" class="form-horizontal form-bordered form-validate form-wizard">
									
									<div class="step" id="firstStep">
										<?php echo EtapaDePedido(1);?>
										<div style="background:white; padding: 20px 20px 20px 20px;">
												<?php echo $strCats;?>
										</div>
									</div>
									
									<div class="step" id="secondStep">
										<?php echo EtapaDePedido(2);?>
										<div class="control-group">
											<label for="cantidad" class="control-label">Cantidades</label>
											<div class="controls">
												<input type="text" name="cantidad" id="cantidad" class="input-mini" data-rule-required="true" data-msg-required="Introduzca cantidad">
											</div>
										</div>
										<div class="control-group">
											<label for="medida" class="control-label">Medida</label>
											<div class="controls">
												<?php
												$sql = "SELECT Medida_ID valor, Nombre descripcion
													FROM tbMedida";
												$sql .= " WHERE bActivo = 1";
												$sql .= " ORDER BY Orden";
												$strCombo = generaComboBox( $sql
														, "medida_ID"
														, $noSeleccion="-- Seleccione --"
														, $Medida_ID
														, "class=\"input-small\" data-rule-required=\"true\" data-msg-required=\"Seleccione un elemento de la lista\""
												);
												echo $strCombo;?>
											</div>
										</div>
									</div>
									
									<div class="step" id="thirdStep">
										<?php echo EtapaDePedido(3);?>
										<div class="control-group">
											<label for="map" class="control-label">Seleccionar centro mas cercano</label>
											<div class="controls">
												<div id="map-canvas" style="width:600px;height:400px;"></div>
											</div>
										</div>
									</div>
									
									<div class="step" id="fourthStep">
										<?php echo EtapaDePedido(4);?>
										<div class="control-group">
											<label for="text" class="control-label">Observaciones</label>
											<div class="controls">
												<textarea name="observaciones" id="observaciones" class="span12" rows="7" placeholder="Introduzca cualquier información relevante para el pedido..."></textarea>
											</div>
										</div>
										<div class="control-group">
											<label for="text" class="control-label">Acepto la politica</label>
											<div class="controls">
												<div class="make-switch switch-mini" data-on="success" data-off="danger" data-text-label="" data-on-label="SI" data-off-label="NO">
														<input type="checkbox" name="chkProveedor" id="chkProveedor" value="1" >
												</div>
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