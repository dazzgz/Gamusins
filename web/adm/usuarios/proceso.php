<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");
	ComprobarLoginPanel();
	$strPagina = CabeceraPanelControl(SEC_ADM_USUARIOS);
	echo $strPagina;

	$accion 						= parametro("accion");
	$ID 								= intval(parametro("ID"));
	//$Usuario_ID 				= intval(parametro("Usuario_ID"));
	$UsuarioRol_ID 			= intval(parametro("UsuarioRol_ID"));
	$Login 							= addslashes(parametro("login"));
	$Password						= addslashes(parametro("password"));
	$Nombre 						= addslashes(parametro("nombre"));
	$Email 							= addslashes(parametro("email"));
	$bActivo 						= (parametro("chkActivo"))?1:0;
	$horaAlta 					= parametro("horaAlta");
	$FechaAlta 					= fechaHoraMySQL(parametro("fechaAlta")." ".$horaAlta);
	$horaUltimoAcceso		= parametro("horaUltimoAcceso");
	$FechaUltimoAcceso 	= fechaHoraMySQL(parametro("fechaUltimoAcceso")." ".$horaUltimoAcceso);
	
	$strHtml="";
	if($accion==ACCION_EDITAR && $ID==0){
		$mensaje .= "<strong>".TextoDeIdioma("No_ha_seleccionado_registro_para_editar").".</strong><br />";
		$strHtml .= maquetarError($mensaje, 0);
		$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
	}else{
		
		//Validaciones de servidor
		if($Login!=""){
			if(($accion==ACCION_NUEVO && $Password!="") || $accion==ACCION_EDITAR){
				if($Nombre!=""){
					if(ComprobarEmail($Email) || $Email==""){
						// Compruebo que ese login no este cogido por otro usuario
						$PA = "SELECT COUNT(*) AS Contador FROM tbUsuario WHERE Usuario_ID != ".$ID." AND Login = '".$Login."';";
						$resultado = ExecQueryValue($PA);
						if($resultado==0){
							switch($accion)
							{
								case ACCION_EDITAR:
									$PA = "UPDATE tbUsuario SET";
									$PA .= " Login = '".$Login."'";
									$PA .= ", UsuarioRol_ID = ".$UsuarioRol_ID."";
									if($Password != "")
										$PA .= ", Password = '".sha1($Password)."'";
									$PA .= ", Nombre = '".$Nombre."'";
									$PA .= ", Email = '".$Email."'";
									$PA .= ", bActivo = ".$bActivo;
									//$PA .= ", FechaAlta = '".$FechaAlta."'";
									//$PA .= ", FechaUltimoAcceso = '".$FechaUltimoAcceso."'";
									$PA .= " WHERE Usuario_ID = ".$ID.";";
									//echo $PA;
									ExecPA($PA);
									$mensaje .= "<strong>".TextoDeIdioma("Registro_actualizado_con_exito").".</strong><br />";
									$strHtml .= maquetarCorrecto($mensaje, 0);
									$strHtml .= "<p class=\"text-center\"><br /><a href=\"index.php\" class=\"btn btn-success\"><i class=\"icon-list-ul\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
									break;
									
								case ACCION_NUEVO:
									$PA = "INSERT INTO tbUsuario (";
									$PA .= " Login";
									$PA .= ", UsuarioRol_ID";
									$PA .= ", Password";
									$PA .= ", Nombre";
									$PA .= ", Email";
									$PA .= ", FechaAlta";
									$PA .= ", FechaUltimoAcceso";
									$PA .= ", bActivo)";
									$PA .= " VALUES (";
									$PA .= "'".$Login."'";
									$PA .= ", ".$UsuarioRol_ID;
									$PA .= ", '".sha1($Password)."'";
									$PA .= ", '".$Nombre."'";
									$PA .= ", '".$Email."'";
									$PA .= ", '".$FechaAlta."'";
									$PA .= ", '".$FechaUltimoAcceso."'";
									$PA .= ", ".$bActivo.")";
									ExecPA($PA);
									$mensaje .= "<strong>".TextoDeIdioma("Registro_creado_con_exito").".</strong><br />";
									$strHtml .= maquetarCorrecto($mensaje, 0);
									$strHtml .= "<p class=\"text-center\"><br /><a href=\"index.php\" class=\"btn btn-success\"><i class=\"icon-list-ul\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
									break;
							}
						}else{
							$mensaje .= "<strong>".TextoDeIdioma("El_campo_X_ya_esta_registrado_en_la_BD", "Login").".</strong><br />";
							$strHtml .= maquetarError($mensaje, 0);
				$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
						}
					}else{
						$mensaje .= "<strong>".TextoDeIdioma("El_campo_X_no_puede_dejarse_en_blanco", "Email").".</strong><br />";
						$strHtml .= maquetarError($mensaje, 0);
				$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
					}
				}else{
					$mensaje .= "<strong>".TextoDeIdioma("El_campo_X_no_puede_dejarse_en_blanco", "Nombre").".</strong><br />";
					$strHtml .= maquetarError($mensaje, 0);
				$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
				}
			}else{
				$mensaje .= "<strong>".TextoDeIdioma("El_campo_X_no_puede_dejarse_en_blanco", "Password").".</strong><br />";
				$strHtml .= maquetarError($mensaje, 0);
				$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
			}
		}else{
			$mensaje .= "<strong>".TextoDeIdioma("El_campo_X_no_puede_dejarse_en_blanco", "Login").".</strong><br />";
			$strHtml .= maquetarError($mensaje, 0);
			$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
		}
	}
?>

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

  <div class="container-fluid" id="content-area">
		<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<div class="box-head">
						<i class="icon-user"></i>
						<span><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("usuario")?></span>
					</div>
					<div class="box-body box-body-nopadding">
               <?php echo $strHtml ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<?php echo PiePanelControl(); ?>