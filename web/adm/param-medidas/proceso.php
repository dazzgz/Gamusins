<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");
	ComprobarLoginPanel();
	$strPagina = CabeceraPanelControl(SEC_ADM_PARAMETROS_MEDIDAS);
	echo $strPagina;

	$accion 						= parametro("accion");
	$ID 								= intval(parametro("ID"));
	$CategoriaPadre_ID 	= intval(parametro("CategoriaPadre_ID"));
	$Nombre 						= addslashes(parametro("nombre"));
	$Orden 							= intval(parametro("orden"));
	$bActivo 						= (parametro("chkActivo"))?1:0;
	
	$sqlComboSel = "DELETE FROM tbCategoriaMedida WHERE Medida_ID = ".$ID;
	ExecPA($sqlComboSel);
	$arrCatsSel [] = parametro("categorias_ID[]");
	foreach ($_POST['categorias_ID'] as $CatSel_ID)
	{
		$PACatSel = "INSERT INTO tbCategoriaMedida (";
		$PACatSel .= " Medida_ID";
		$PACatSel .= ", Categoria_ID";
		$PACatSel .= ") VALUES (";
		$PACatSel .= " " . $ID . "";
		$PACatSel .= ", " . $CatSel_ID . "";
		$PACatSel .= ")";
		//echo $PACatSel."<br />";
		ExecPA($PACatSel);
	}
	
	$strHtml="";
	if($accion==ACCION_EDITAR && $ID==0){
		$mensaje .= "<strong>".TextoDeIdioma("No_ha_seleccionado_registro_para_editar").".</strong><br />";
		$strHtml .= maquetarError($mensaje, 0);
		$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
	}else{
		//Validaciones de servidor
		if($Nombre!=""){
			// Compruebo que ese registrono este ya en BD
			$PA = "SELECT COUNT(*) AS Contador FROM tbMedida WHERE Nombre = '".$Nombre."' AND Medida_ID != ".$ID.";";
			$resultado = ExecQueryValue($PA);
			if($resultado==0){
				switch($accion)
				{
					case ACCION_EDITAR:
						$PA = "UPDATE tbMedida SET";
						$PA .= " Nombre = '".$Nombre."'";
						$PA .= ", Orden = ".$Orden;
						$PA .= ", CategoriaPadre_ID = ".$CategoriaPadre_ID;
						$PA .= ", bActivo = ".$bActivo;
						$PA .= " WHERE Medida_ID = ".$ID.";";
						//echo $PA;
						ExecPA($PA);
						$mensaje .= "<strong>".TextoDeIdioma("Registro_actualizado_con_exito").".</strong><br />";
						$strHtml .= maquetarCorrecto($mensaje, 0);
						$strHtml .= "<p class=\"text-center\"><br /><a href=\"index.php\" class=\"btn btn-success\"><i class=\"icon-list-ul\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
						break;
						
					case ACCION_NUEVO:
						$PA = "INSERT INTO tbMedida (";
						$PA .= "Nombre";
						$PA .= ", Orden";
						$PA .= ", CategoriaPadre_ID";
						$PA .= ", bActivo";
						$PA .= ") VALUES (";
						$PA .= "'".$Nombre."'";
						$PA .= ", ".$Orden."";
						$PA .= ", ".$CategoriaPadre_ID."";
						$PA .= ", ".$bActivo.")";
						ExecPA($PA);
						$mensaje .= "<strong>".TextoDeIdioma("Registro_creado_con_exito").".</strong><br />";
						$strHtml .= maquetarCorrecto($mensaje, 0);
						$strHtml .= "<p class=\"text-center\"><br /><a href=\"index.php\" class=\"btn btn-success\"><i class=\"icon-list-ul\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
						break;
				}
			}else{
				$mensaje .= "<strong>".TextoDeIdioma("El_campo_X_ya_esta_registrado_en_la_BD", "Nombre").".</strong><br />";
				$strHtml .= maquetarError($mensaje, 0);
				$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
			}
		}else{
			$mensaje .= "<strong>".TextoDeIdioma("El_campo_X_no_puede_dejarse_en_blanco", "Nombre").".</strong><br />";
			$strHtml .= maquetarError($mensaje, 0);
			$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
		}
	}
?>
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

  <div class="container-fluid" id="content-area">
		<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<div class="box-head">
						<i class="icon-list-ul"></i>
						<span><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("medida")?></span>
					</div>
					<div class="box-body box-body-nopadding">
             <?php echo $strHtml ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<?php echo PiePanelControl(); ?>