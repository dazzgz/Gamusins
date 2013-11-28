<?
$bCabecera = true;
require_once(RUTA_RAIZ."_entorno.php");
require_once(RUTA_RAIZ."_constantes.php");

require_once(RUTA_RAIZ."funciones/FuncionesDB.php");
require_once(RUTA_RAIZ."funciones/FuncionesError.php");
require_once(RUTA_RAIZ."funciones/FuncionesComunes.php");
require_once(RUTA_RAIZ."funciones/FuncionesParticulares.php");

require_once(RUTA_RAIZ."map-script.php");

function Cabecera($opMenu=SEC_INICIO, $bMaps=false, $parametros_query="")
{
	$strHtml = CabeceraHTML($bMaps, $parametros_query);

	if($strHtml != ""){
		$strHtml .= '
			<body data-spy="scroll">

				<!-- TOP MENU NAVIGATION -->
				<div class="navbar navbar-fixed-top">
					<div class="navbar-inner">
						<div class="container">
					
							<a class="brand pull-left" href="/index.php">
								<img src="/img/logo-trans-cab.png">
								<!--span class="white">AppAlimentar</span-->
							</a>
					
							<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</a>
						
							<div class="nav-collapse collapse">
								<ul id="nav-list" class="nav pull-right">
									<li><a href="/index.php">Inicio</a></li>
									<li class="dropdown">
								    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
								      <i class="icon-lock icon-white"></i>&nbsp;Acceso
								      <b class="caret"></b>
								    </a>
								    <ul class="dropdown-menu">
								      <li><a href="/registro.php"><i class="icon-pencil"></i>&nbsp;Registro</a></li>
											<li><a href="/mis-datos.php"><i class="icon-user"></i>&nbsp;Mis datos</a></li>
											<li><a href="/logout.php"><i class="icon-off"></i>&nbsp;Salir</a></li>
								    </ul>
								  </li>
									<li><a href="/ofrezco.php">Ofrezco</a></li>
									<li><a href="/contacto.php">Contacto</a></li>
									<li><a href="/acerca-de.php">Acerca de...</a></li>
									<li>
										<div class="make-switch switch-mini" data-on="success" data-off="warning" data-text-label="SOY" data-on-label="Proveedor" data-off-label="Cliente">
												<input type="checkbox" name="chkProveedor" id="chkProveedor" value="1" >
										</div>
									</li>
								</ul>
							</div>
						
						</div>
					</div>
				</div>
				
				
				<!-- MAIN CONTENT -->
				<div class="container content container-fluid" id="home">
  			'; 
	}
	
	return $strHtml;
}

function CabeceraHTML($bMaps, $parametros_query=""){
	global $bCabecera;
	$strHtml = "";
	if($bCabecera){
		$bCabecera = false;
		$strHtml = '
			<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta http-equiv="Content-Type" content="text/html; charset='.CHARSET.'" />
				
				<!-- CHANGE THIS TITLE TAG -->
				<title>'.PROJECT_NAME.'</title>
				
				<!-- media-queries.js -->
				<!--[if lt IE 9]>
					<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
				<![endif]-->
				<!-- html5.js -->
				<!--[if lt IE 9]>
					<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
				<![endif]-->';
		
		if($bMaps){
			$strHtml .= "	<!-- MAPAS GOOGLE -->"."\n";
			$strHtml .= "	<script type=\"text/javascript\" src=\"http://maps.googleapis.com/maps/api/js?sensor=false\"></script>"."\n";
			$strHtml .= cargaHTMLMapa($parametros_query);
		}
				$strHtml .= '
				<link href="/font/stylesheet.css" rel="stylesheet" type="text/css" />	
				<link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
				<link href="/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
				<link href="/css/styles.css" rel="stylesheet" type="text/css" />
				<link href="/css/media-queries.css" rel="stylesheet" type="text/css" />
				<link href="/css/bootstrap.icon-large.min.css" rel="stylesheet" type="text/css" />
				<link href="/fancybox/jquery.fancybox-1.3.4.css" rel="stylesheet" type="text/css" media="screen" />
				
				<!-- Theme CSS -->
				<!--[if !IE]> -->
				<!--<link rel="stylesheet" href="/css/style.css">-->
				<!-- <![endif]-->
				<!--[if IE]>
				<!--<link rel="stylesheet" href="/css/style_ie.css">-->
				<![endif]-->

				<!-- Uniform plugin -->
				<link rel="stylesheet" href="/css/uniform.default.min.css">
				<!-- Switch plugin CSS -->
				<link rel="stylesheet" href="/css/bootstrap-switch.css">		
						
				<meta name="viewport" content="width=device-width" />
				 
				<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
				
				<link href="http://fonts.googleapis.com/css?family=Exo:400,800" rel="stylesheet" type="text/css">
			
						<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
						<script src="/js/bootstrap.min.js"></script>
						<script src="/js/bootstrap-collapse.js"></script>
						<script src="/js/bootstrap-scrollspy.js"></script>
						<script src="/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
						<script src="/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
						
						<!-- Edicion de formularios -->
						<!-- Form plugin -->
						<script src="/js/jquery.form.min.js"></script>
						<!-- jQuery UI Widget -->
						<script src="/js/jquery.ui.widget.min.js"></script>
						<!-- Form wizard plugin -->
						<script src="/js/jquery.form.wizard.min.js"></script>
						<!-- Uniform plugin -->
						<script src="/js/jquery.uniform.min.js"></script>
						<!-- Switch plugin -->
						<script src="/js/bootstrap-switch.min.js"></script>
						<!-- Validation plugin -->
						<script src="/js/jquery.validate.min.js"></script>
						<!-- Additional methods for validation plugin -->
						<script src="/js/additional-methods.min.js"></script>
						<script type="text/javascript" src="/js/jquery.cook.js"></script>
										
						<script src="/js/init.js"></script>
			</head>';
	}
	return $strHtml;
}

function Pie(){
	$anio = date("Y");
	$strAnio = $anio;
	if($anio < date("Y"))
	{
		$strAnio .= " - ".year(date());
	} 
	$strHtml = '
							</div>
			
			  		<!-- FOOTER -->
						<div class="footer container container-fluid">
						
							<!-- COPYRIGHT - EDIT HOWEVER YOU WANT! -->
							<div id="copyright">
								Copyright &copy; '.$anio.' AppAlimentar.<br />
							</div>
							
							<div id="credits">
								<a href="http://www.alfonsotorres-arquitectos.eu/" target="_blank">Arkinos</a> powered by <a href="http://www.aloneintheweb.com/" target="_blank">AloneInTheWeb</a>.
							</div>
						
						</div>
						
						
					</body>
					</html>';
	
	return $strHtml;
}
?>