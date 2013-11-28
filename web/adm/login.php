<?php
//phpinfo();
//exit;
define("RUTA_RAIZ","../");
include_once("_AdmLayout.php");

unsession_setPanel();
$_SESSION["sPANEL_Idioma"] = "es";
$_SESSION["sPANEL_Idioma_ID"] = 1;

$strError = "";
if(parametro("usuario")!=""){// Si viene del formulario
	$IP 			= $_SERVER['REMOTE_ADDR'];
	$Fecha 		= date('Y-m-d H:i:s', time());
	$login 	  = parametro("usuario");
	$password = parametro("password");
	$idioma 	= parametro("idioma");
	// Compruebo el usuario y la password
	if($login!=""){
		if($password!=""){
			// Compruebo los datos del usuario
			$PA="SELECT Usuario_ID, Login, Nombre, Email, UsuarioRol_ID, bActivo FROM tbUsuario WHERE Login = '".addslashes($login)."' AND Password = '".sha1($password)."' LIMIT 1";
			$resultado = ExecPA($PA);
			$NumElementos=mysql_affected_rows();
				
			if($NumElementos!=1){
				$strError = TextoDeIdioma("Intento_acceso_incorrecto");

				$Usuario_ID = -1;
				$Acceso = TextoDeIdioma("Incorrecto");
				InsertLog($Usuario_ID,$IP,$Fecha,$login,$password,$Acceso);
			}else{
				if(intval(ValorCelda($resultado,0,"bActivo"))){
					// Usuario autenticado con exito
					$_SESSION["sPANEL_Usuario_ID"] 	   	= intval(ValorCelda($resultado,0,"Usuario_ID"));
					$_SESSION["sPANEL_UsuarioRol_ID"] 	= intval(ValorCelda($resultado,0,"UsuarioRol_ID"));
					$_SESSION["sPANEL_Usuario_Nombre"] 	= stripslashes(ValorCelda($resultado,0,"Nombre"));
					$_SESSION["sPANEL_Usuario_Login"]	 	= stripslashes(ValorCelda($resultado,0,"Login"));
					$_SESSION["sPANEL_Usuario_Email"]	 	= stripslashes(ValorCelda($resultado,0,"Email"));
					//CLIENTE/PROVEEDOR VINCULADO AL USUARIO LOGUEADO
					//echo $_SESSION["sPANEL_Usuario_ID"].'<br />';
					if($_SESSION["sPANEL_UsuarioRol_ID"] == ROL_PROVEEDOR_ID){
						$_SESSION["sPANEL_Proveedor_ID"] 	  = intval(ExecQueryValue("SELECT Cliente_ID FROM tbCliente WHERE Usuario_ID = ".$_SESSION["sPANEL_Usuario_ID"]));
					}else{
						$_SESSION["sPANEL_Cliente_ID"] 	   	= intval(ExecQueryValue("SELECT Cliente_ID FROM tbCliente WHERE Usuario_ID = ".$_SESSION["sPANEL_Usuario_ID"]));
					}
					//echo $_SESSION["sPANEL_Cliente_ID"]; echo $_SESSION["sPANEL_Proveedor_ID"];
					$_SESSION["sPANEL_Idioma"] 					= $idioma;
					switch ($idioma)
					{
						case "es":
							$_SESSION["sPANEL_Idioma_ID"] = 1;
							$_SESSION["sPANEL_Idioma-txt"] = TextoDeIdioma("idioma_txt_es");
							$_SESSION["sPANEL_Idioma-txt-det"] = TextoDeIdioma("idioma_txt_det_es");
							break;
						case "en":
							$_SESSION["sPANEL_Idioma_ID"] = 2;
							$_SESSION["sPANEL_Idioma-txt"] = TextoDeIdioma("idioma_txt_en");
							$_SESSION["sPANEL_Idioma-txt-det"] = TextoDeIdioma("idioma_txt_det_en");
							break;
						case "ja":
							$_SESSION["sPANEL_Idioma_ID"] = 3;
							$_SESSION["sPANEL_Idioma-txt"] = TextoDeIdioma("idioma_txt_ja");
							$_SESSION["sPANEL_Idioma-txt-det"] = TextoDeIdioma("idioma_txt_det_ja");
							break;
						case "ca":
							$_SESSION["sPANEL_Idioma_ID"] = 4;
							$_SESSION["sPANEL_Idioma-txt"] = TextoDeIdioma("idioma_txt_ca");
							$_SESSION["sPANEL_Idioma-txt-det"] = TextoDeIdioma("idioma_txt_det_ca");
							break;
					}
					
					//Inserto registro en el log.
					$Acceso = TextoDeIdioma("Correcto");
					InsertLog ($_SESSION["sPANEL_Usuario_ID"],$IP,$Fecha,$login,$password, $Acceso);

					ExecPA("UPDATE tbUsuario SET FechaUltimoAcceso = '".date("Y-m-d H:i:s")."' WHERE Usuario_ID = ".$_SESSION["sPANEL_Usuario_ID"]);
					
					// Redirijo
					header("location: ".SERVER.DOC_ROOT.DIR_PANELCONTROL."index.php");
					die();
				}else{
					$strError = TextoDeIdioma("Lo_sentimos_pero_usted_no_tiene_acceso_a_este_apartado_del_web");
					$Usuario_ID = ValorCelda($resultado,0,"Usuario_ID");
					$Acceso = TextoDeIdioma("Incorrecto");
					InsertLog($Usuario_ID,$IP,$Fecha,$login,$password,$Acceso);
				}

			}
		}else{
			$strError = TextoDeIdioma("Debe_introducir_una_contrasena");
			$Usuario_ID = -1;
			$Acceso = TextoDeIdioma("Incorrecto");
			InsertLog ($Usuario_ID,$IP,$Fecha,$login,$password,$Acceso);
		}
	}else{
		$strError = TextoDeIdioma("Debe_introducir_login_de_usuario");
		$Usuario_ID = -1;
		$Acceso = TextoDeIdioma("Incorrecto");
		InsertLog ($Usuario_ID,$IP,$Fecha,$login,$password,$Acceso);
	}
}

echo CabeceraHTML();
//echo "eeee".$strError;
//phpinfo();
?>
    
<script type="text/javascript">
		function validar() {
			var ok = true;
			var mensaje = "";
			var user = frmLogin.usuario.value;
			var pass = frmLogin.password.value;
			if (EnBlanco(user)) {
				mensaje += "<?php TextoDeIdioma("Debe_introducir_login_de_usuario")?>\n";
				ok = false;
			}
			if (EnBlanco(pass)) {
				mensaje += "<?php TextoDeIdioma("Debe_introducir_una_contrasena")?>\n";
				ok = false;
			} else {
				if (pass.length < 4) {
					mensaje += "<?php TextoDeIdioma("contrasena_debe_tener_al_menos_4_caracteres")?>\n";
					ok = false;
				}
			}
			if (!ok)
				alert(mensaje);
			return ok;
		}
</script>

<body class="login-body">
	<div class="login-wrap">
		<h2 style="color:grey;"><img src="img/logo-trans.png" alt="<?php echo TextoDeIdioma("Panel_de_Control")?>" width="207" height="58" /><br /> <?php echo TextoDeIdioma("Panel_de_Control")?></h2>
		<div class="login">
			<form name="frmLogin" method="post" action="login.php" class="form-validate"><!--onsubmit="return validar()"-->
				<h5 style="color: #DDD"><?php echo TextoDeIdioma("Introduzca_Login_y_Password_para_acceder")?>:</h5>
				<br />
				<div style="text-align:center">
					<div class="control-group">
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-user"></i></span>
								<!--  input class="input-block" type="text" id="usuario" name="usuario" required="required" placeholder="Login">-->
								<input type="text" id="usuario" name="usuario" required="required" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Debe_introducir_login_de_usuario")?>" class="input-block" maxlength="50" />
							</div>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-lock"></i></span>
									<!-- input class="input-block" type="password" id="password" name="password" required="required" placeholder="Password"> -->
									<input type="password" id="password" name="password" required="required" data-rule-required="true" data-msg-required="<?php echo TextoDeIdioma("Debe_introducir_una_contrasena")?>" class="input-block" maxlength="50" />
							</div>
						</div>
					</div>
					<div class="control-group">
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-globe icon-blue"></i></span>
								<select id="idioma" name="idioma" class="input-block">
									<option value="es"><?php echo TextoDeIdioma("idioma_txt_es");?></option>
									<!-- option value="en"><?php echo TextoDeIdioma("idioma_txt_en");?></option>
									<option value="ja"><?php echo TextoDeIdioma("idioma_txt_ja");?></option>
									<option value="ca"><?php echo TextoDeIdioma("idioma_txt_ca");?></option> -->
								</select>
							</div>
						</div>
					</div>
				</div>
				<?php echo maquetarError($strError);?>
				<input type="submit" class="btn btn-primary btn-block" value="<?php echo TextoDeIdioma("Acceder")?> >>">
				<!-- button type="submit" class="btn btn-large btn-primary btn-block"><?php echo TextoDeIdioma("Acceder")?> >></button-->
			</form>
		</div>
	</div>
</body>
</html>