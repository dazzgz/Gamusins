<?php
define("RUTA_RAIZ","../");
require_once("_AdmLayout.php");

ComprobarLoginPanel();

if($_SESSION["sPANEL_UsuarioRol_ID"] >= ROL_AGENTE_ID ){
	if($_SESSION["sPANEL_UsuarioRol_ID"] == ROL_CLIENTE_ID ){ // Ver CUPS de cliente para seleccionar con cual trabajar
		//header("Location: ".RUTA_ADM."clientes/ficha-cliente.php?Cliente_ID=".$_SESSION["sPANEL_Cliente_ID"]);
		header("Location: ".RUTA_ADM."clientes/cups.php?Cliente_ID=".$_SESSION["sPANEL_Cliente_ID"]);
	}else{ // Ver clientes del agente para seleccionar con cual trabajar
		header("Location: ".RUTA_ADM."clientes/index.php");
	}
}

$strHtmlFrame = CabeceraPanelControl(SEC_ADM_PANELCONTROL);
echo $strHtmlFrame;
?>
	<!-- Migas de pan -->
	<div class="page-header">
		<div class="pull-left">
			<h4><i class="icon-home"></i><?php echo TextoDeIdioma("Panel_de_Control")?></h4>
		</div>
		<div class="pull-right">
			<ul class="bread">
				<li class="active"><?php echo TextoDeIdioma("Inicio")?></li>
			</ul>
		</div>
	</div>
			
	<!-- Destacado -->
	<?php if($_SESSION["sPANEL_UsuarioRol_ID"] <= ROL_AGENTE_ID ){ ?>
	<div class="content-highlighted">
		<h3><?php echo TextoDeIdioma("Accesos_directos")?></h3>
		<!-- ul class="quick" data-collapse="collapse">
			<li>
				<a href="<?php echo RUTA_ADM."empleados/index.php"?>"><img src="img/icons/my-account.png" alt="" /><span><?php echo TextoDeIdioma("Empleados")?></span></a>
			</li>
			<li>
				<a href="<?php echo RUTA_ADM."clientes/index.php"?>"><img src="img/icons/customers.png" alt="" /><span><?php echo TextoDeIdioma("Clientes")?></span></a>
			</li>
			<li>
				<a href="<?php echo RUTA_ADM."trabajos/index.php"?>"><img src="img/icons/project.png" alt="" /><span><?php echo TextoDeIdioma("Trabajos")?></span></a>
			</li>
			<li>
				<a href="<?php echo RUTA_ADM."servicios/index.php"?>"><img src="img/icons/premium.png" alt="" /><span><?php echo TextoDeIdioma("Servicios")?></span></a>
			</li>
			<li>
				<a href="<?php echo RUTA_ADM."tipos/index.php"?>"><img src="img/icons/cost.png" alt="" /><span><?php echo TextoDeIdioma("Tipos")?></span></a>
			</li>
			<li>
				<a href="<?php echo RUTA_ADM."categorias/index.php"?>"><img src="img/icons/tag.png" alt="" /><span><?php echo TextoDeIdioma("Categorias")?></span></a>
			</li>
		</ul> -->
		
		<ul class="quick" data-collapse="collapse">
		<?php if($_SESSION["sPANEL_UsuarioRol_ID"] <= ROL_ADMINISTRADOR_ID ){ ?>
			<li>
				<a href="<?php echo RUTA_ADM."usuarios/index.php"?>"><img src="img/icons/user.png" alt="" /><span><?php echo TextoDeIdioma("Usuarios")?></span></a>
			</li>
			<li>
				<a href="<?php echo RUTA_ADM."roles/index.php"?>"><img src="img/icons/suppliers.png" alt="" /><span><?php echo TextoDeIdioma("Roles_de_usuario")?></span></a>
			</li>
		<?php } ?>
			<li>
				<a href="<?php echo RUTA_ADM."contacto/index.php"?>"><img src="img/icons/email.png" alt="" /><span><?php echo TextoDeIdioma("Mensajes")?></span></a>
			</li>
			<li>
				<a href="<?php echo RUTA_ADM."usuarios/accesos.php"?>"><img src="img/icons/lock.png" alt="" /><span><?php echo TextoDeIdioma("Accesos_al_panel_de_control")?></span></a>
			</li>
			<li>
				<a href="#"><img src="img/icons/consulting.png" alt="" /><span><?php echo TextoDeIdioma("Ayuda")?></span></a>
			</li>
		</ul>
	</div>
	<?php } ?>

	<!-- Contenido -->
	<div class="container-fluid" id="content-area">
		
	</div>
<?php echo PiePanelControl(); ?>