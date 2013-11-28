<?php
define("RUTA_RAIZ","../../");
require_once("../_AdmLayout.php");
ComprobarLoginPanel();
$strPagina = CabeceraPanelControl(SEC_ADM_PROVEEDORES);
echo $strPagina;

	$accion 						= parametro("accion");
	$ID 								= intval(parametro("ID"));
	$Email 							= addslashes(parametro("email"));
	$Password						= addslashes(parametro("password"));
	$Email 							= addslashes(parametro("email"));
	
	$horaAlta 					= parametro("horaAlta");
	$FechaAlta 					= fechaHoraMySQL(parametro("fechaAlta")." ".$horaAlta);
	
	$FechaBaja 					= parametro("fechaBaja");
	
	if($FechaBaja != '//' && $FechaBaja != ''){
		$horaBaja 				= parametro("horaBaja");
		$FechaBaja 				= fechaHoraMySQL(parametro("fechaBaja")." ".$horaBaja);
	}else{
		$FechaBaja 				= "";
	}
	//echo $FechaBaja;
	$horaUltimoAcceso		= parametro("horaUltimoAcceso");
	$FechaUltimoAcceso 	= fechaHoraMySQL(parametro("fechaUltimoAcceso")." ".$horaUltimoAcceso);
	$IP									= addslashes(parametro("ip"));
	$Nombre 						= addslashes(parametro("nombre"));
	$Apellidos 					= addslashes(parametro("apellidos"));
	$NIF 								= addslashes(parametro("nif"));
	$RazonSocial				= addslashes(parametro("razonSocial"));
	$Telefono						= addslashes(parametro("telefono"));
	$Movil							= addslashes(parametro("movil"));
	$Direccion					= addslashes(parametro("direccion"));
	$Poblacion					= addslashes(parametro("poblacion"));
	$Provincia_ID				= intval(parametro("provincia_ID"));
	$Pais_ID						= intval(parametro("pais_ID"));
	$CP									= addslashes(parametro("cp"));
	$bBoletin						= (parametro("chkBoletin"))?1:0;
	$bActivo 						= (parametro("chkActivo"))?1:0;
	$Observaciones			= addslashes(parametro("observaciones"));
	$Lat								= floatval(parametro("lat"));
	$Lon								= floatval(parametro("lon"));
	$Ubicacion					= addslashes(parametro("ubicacion"));
	
	$strHtml="";
	if($accion==ACCION_EDITAR && $ID==0){
		$mensaje .= "<strong>".TextoDeIdioma("No_ha_seleccionado_registro_para_editar").".</strong><br />";
		$strHtml .= maquetarError($mensaje, 0);
		$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
	}else{
		//Validaciones de servidor
		if($Email != ""){
			// Compruebo que ese registrono este ya en BD
			$PA = "SELECT COUNT(*) AS Contador FROM tbProveedor WHERE Email = '".$Email."' AND Proveedor_ID != ".$ID.";";
			$resultado = ExecQueryValue($PA);
			if($resultado==0){
				switch($accion)
				{
					case ACCION_EDITAR:
						$PA = "UPDATE tbProveedor SET";
						$PA .= " Email = '".$Email."'";
						if($Password != "")
							$PA .= ", Password = '".sha1($Password)."'";
						$PA .= ", Nombre = '".$Nombre."'";
						$PA .= ", Apellidos = '".$Apellidos."'";
						$PA .= ", FechaAlta = '".$FechaAlta."'";
						if($FechaBaja != '')
							$PA .= ", FechaBaja = '".$FechaBaja."'";
						$PA .= ", IP = '".$IP."'";
						$PA .= ", FechaUltimoAcceso = '".$FechaUltimoAcceso."'";
						$PA .= ", NIF = '".$NIF."'";
						$PA .= ", RazonSocial = '".$RazonSocial."'";
						$PA .= ", Telefono = '".$Telefono."'";
						$PA .= ", Movil = '".$Movil."'";
						$PA .= ", Direccion = '".$Direccion."'";
						$PA .= ", Poblacion = '".$Poblacion."'";
						$PA .= ", Provincia_ID = ".$Provincia_ID."";
						$PA .= ", Pais_ID = '".$Pais_ID."'";
						$PA .= ", CP = '".$CP."'";
						$PA .= ", bActivo = ".$bActivo;
						$PA .= ", bBoletin = ".$bBoletin;
						$PA .= ", Observaciones = '".$Observaciones."'";
						$PA .= ", Lat = ".$Lat."";
						$PA .= ", Lon = ".$Lon."";
						$PA .= ", Ubicacion = '".$Ubicacion."'";
						$PA .= " WHERE Proveedor_ID = ".$ID.";";
						//echo $PA;
						ExecPA($PA);
						$mensaje .= "<strong>".TextoDeIdioma("Registro_actualizado_con_exito").".</strong><br />";
						$strHtml .= maquetarCorrecto($mensaje, 0);
						$strHtml .= "<p class=\"text-center\"><br /><a href=\"index.php\" class=\"btn btn-success\"><i class=\"icon-list-ul\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
						break;
						
					case ACCION_NUEVO:
						$PA = "INSERT INTO tbProveedor (";
						$PA .= " Email";
						$PA .= ", Password";
						$PA .= ", Nombre";
						$PA .= ", Apellidos";
						$PA .= ", FechaAlta";
						//$PA .= ", FechaBaja";
						$PA .= ", IP";
						$PA .= ", FechaUltimoAcceso";
						$PA .= ", NIF";
						$PA .= ", RazonSocial";
						$PA .= ", Telefono";
						$PA .= ", Movil";
						$PA .= ", Direccion";
						$PA .= ", Poblacion";
						$PA .= ", Provincia_ID";
						$PA .= ", Pais_ID";
						$PA .= ", CP";
						$PA .= ", bActivo";
						$PA .= ", bBoletin";
						$PA .= ", Observaciones";
						$PA .= ", Lat";
						$PA .= ", Lon";
						$PA .= ", Ubicacion";
						$PA .= ") VALUES (";
						$PA .= "'".$Email."'";
						$PA .= ", '".sha1($Password)."'";
						$PA .= ", '".$Nombre."'";
						$PA .= ", '".$Apellidos."'";
						$PA .= ", '".$FechaAlta."'";
						//$PA .= ", '".$FechaBaja."'";
						$PA .= ", '".$IP."'";
						$PA .= ", '".$FechaUltimoAcceso."'";
						$PA .= ", '".$NIF."'";
						$PA .= ", '".$RazonSocial."'";
						$PA .= ", '".$Telefono."'";
						$PA .= ", '".$Movil."'";
						$PA .= ", '".$Direccion."'";
						$PA .= ", '".$Poblacion."'";
						$PA .= ", ".$Provincia_ID."";
						$PA .= ", '".$Pais_ID."'";
						$PA .= ", '".$CP."'";
						$PA .= ", ".$bActivo;
						$PA .= ", ".$bBoletin;
						$PA .= ", '".$Observaciones."'";
						$PA .= ", ".$Lat."";
						$PA .= ", ".$Lon."";
						$PA .= ", '".$Ubicacion."'";
						$PA .= ")";
						ExecPA($PA);
						$Proveedor_ID = mysql_insert_id();
						
						//CREO UN USUARIO EN EL SISTEMA ASOCIADO AL PROVEEDOR PARA QUE PUEDA TENER ACCESO
						
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
						$PA .= "'".$Email."'";
						$PA .= ", ".ROL_PROVEEDOR_ID;
						$PA .= ", '".sha1($Password)."'";
						//$PA .= ", '".$Nombre." ".$Apellidos."'";
						$PA .= ", '".$RazonSocial."'";
						$PA .= ", '".$Email."'";
						$PA .= ", '".$FechaAlta."'";
						$PA .= ", '".$FechaUltimoAcceso."'";
						$PA .= ", ".$bActivo.")";
						ExecPA($PA);
						$Usuario_ID = mysql_insert_id();
						
						//ACTUALIZO PROVEEDOR CON EL USUARIO CREADO
						$PA = "UPDATE tbProveedor SET Usuario_ID = ".$Usuario_ID." WHERE Proveedor_ID = ".$Proveedor_ID;
						ExecPA($PA);
						
						$mensaje .= "<strong>".TextoDeIdioma("Registro_creado_con_exito").".</strong><br />";
						$strHtml .= maquetarCorrecto($mensaje, 0);
						$strHtml .= "<p class=\"text-center\"><br /><a href=\"index.php\" class=\"btn btn-success\"><i class=\"icon-list-ul\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
						break;
				}
			}else{
				$mensaje .= "<strong>".TextoDeIdioma("El_campo_X_ya_esta_registrado_en_la_BD", "Email").".</strong><br />";
				$strHtml .= maquetarError($mensaje, 0);
				$strHtml .= "<p class=\"text-center\"><br /><a href=\"javascript:history.back();\" class=\"btn btn-danger\"><i class=\"icon-backward\"></i>&nbsp;&nbsp;".TextoDeIdioma("Volver")."</a></p>";
			}
		}
	}

?>
	<!-- Migas de pan -->
	<div class="page-header">
		<div class="pull-left">
			<h4><i class="icon-table"></i><?php echo TextoDeIdioma("Listado_de_proveedores")?></h4>
		</div>
		<div class="pull-right">
			<ul class="bread">
				<li><a href="<?php echo RUTA_ADM?>index.php"><?php echo TextoDeIdioma("Inicio")?></a><span class="divider">/</span></li>
        <li><a href="<?php echo RUTA_ADM?>proveedores/index.php"><?php echo TextoDeIdioma("Proveedores")?></a><span class="divider">/</span></li>
				<li class="active"><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("proveedor")?></li>
			</ul>
		</div>
	</div>

  <div class="container-fluid" id="content-area">
		<div class="row-fluid">
			<div class="span12">
				<div class="box">
					<div class="box-head">
						<i class="icon-list-ul"></i>
						<span><?php echo TextoDeAccionForm($accion)." ".TextoDeIdioma("proveedor")?></span>
					</div>
					<div class="box-body box-body-nopadding">
             <?php echo $strHtml ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
<?php echo PiePanelControl(); ?>