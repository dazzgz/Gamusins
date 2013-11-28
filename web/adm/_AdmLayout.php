<?
$bCabecera = true;
define("RUTA_ADM", "/adm/");
require_once(RUTA_RAIZ."_entorno.php");
require_once(RUTA_RAIZ."_constantes.php");

require_once(RUTA_RAIZ."funciones/FuncionesDB.php");
require_once(RUTA_RAIZ."funciones/FuncionesError.php");
require_once(RUTA_RAIZ."funciones/FuncionesComunes.php");
require_once(RUTA_RAIZ."funciones/FuncionesUpload.php");
require_once(RUTA_RAIZ."funciones/FuncionesParticulares.php");
//require_once(RUTA_RAIZ."funciones/FuncionesBoletines.php");
//IdiomaEnSesion();
//require_once(RUTA_RAIZ."funciones/lang_".get_idioma_en_session().".php");

function CabeceraHTML(){
	global $bCabecera;

	if($bCabecera){
		$bCabecera = false;

		$strHtml = "<!doctype html>"."\n";
		$strHtml .= "<html>"."\n";
		$strHtml .= "<head>"."\n";
		$strHtml .= "	<title>Panel de Control. ".PROJECT_NAME."</title>"."\n";
		$strHtml .= "	<meta http-equiv=\"Content-Type\" content=\"".CONTENT_TYPE."\" />"."\n";
		$strHtml .= "	<meta charset=\"".CHARSET."\">"."\n";
		//$strHtml .= "	<meta charset=\"UTF-8\">"."\n";

		$strHtml .= "	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" />"."\n";
		$strHtml .= "	<meta name=\"apple-mobile-web-app-capable\" content=\"yes\" />"."\n";
		$strHtml .= "	<meta names=\"apple-mobile-web-app-status-bar-style\" content=\"black-translucent\" />"."\n";

		$strHtml .= "	<!-- Bootstrap -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/bootstrap.min.css\">"."\n";
		$strHtml .= "	<!-- Bootstrap responsive -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/bootstrap-responsive.min.css\">"."\n";
		$strHtml .= "	<!-- Glyphicons large -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/bootstrap.icon-large.min.css\" rel=\"stylesheet\">"."\n";
		
		$strHtml .= "	<!-- small charts plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/jquery.easy-pie-chart.css\">"."\n";
		$strHtml .= "	<!-- calendar plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/fullcalendar.css\">"."\n";
		$strHtml .= "	<!-- Calendar printable -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/fullcalendar.print.css\" media=\"print\">"."\n";
		$strHtml .= "	<!-- chosen plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/chosen.css\">"."\n";
		$strHtml .= "	<!-- CSS for Growl like notifications -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/jquery.gritter.css\">"."\n";

		$strHtml .= "	<!-- Theme CSS -->"."\n";
		$strHtml .= "	<!--[if !IE]> -->"."\n";
		$strHtml .= "		<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/style.css\">"."\n";
		$strHtml .= "	<!-- <![endif]-->"."\n";
		$strHtml .= "	<!--[if IE]>"."\n";
		$strHtml .= "		<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/style_ie.css\">"."\n";
		$strHtml .= "	<![endif]-->"."\n";
			
		$strHtml .= "	<!-- ComboBox -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/bootstrap-combobox.css\">"."\n";
		$strHtml .= "	<!-- SelectPicker -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/bootstrap-select.min.css\">"."\n";
		
		
		$strHtml .= "	<!-- jQuery -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.min.js\"></script>"."\n";
		$strHtml .= "	<!-- Bootstrap -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap.min.js\"></script>"."\n";

		$strHtml .= "	<!-- smoother animations -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.easing.min.js\"></script>"."\n";
		$strHtml .= "	<!-- small charts plugin -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.easy-pie-chart.min.js\"></script>"."\n";
		//$strHtml .= "	<!-- Charts plugin -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.flot.min.js\"></script>"."\n";
		//$strHtml .= "	<!-- Pie charts plugin -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.flot.pie.min.js\"></script>"."\n";
		//$strHtml .= "	<!-- Bar charts plugin -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.flot.bar.order.min.js\"></script>"."\n";
		//$strHtml .= "	<!-- Charts resizable plugin -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.flot.resize.min.js\"></script>"."\n";
		//$strHtml .= "	<!-- calendar plugin -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/fullcalendar.min.js\"></script>"."\n";
		$strHtml .= "	<!-- chosen plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/chosen.jquery.min.js\"></script>"."\n";
		$strHtml .= "	<!-- Scrollable navigation -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.nicescroll.min.js\"></script>"."\n";
		$strHtml .= "	<!-- Growl Like notifications -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.gritter.min.js\"></script>"."\n";


		$strHtml .= " <!-- Edicion de formularios -->"."\n";
		$strHtml .= "	<!-- Form plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.form.min.js\"></script>"."\n";
		$strHtml .= "	<!-- Validation plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.validate.min.js\"></script>"."\n";
		$strHtml .= "	<!-- Additional methods for validation plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/additional-methods.min.js\"></script>"."\n";
		//$strHtml .= "	<!-- tagsinput plugin -->"."\n";
		//$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/jquery.tagsinput.css\">"."\n";
		$strHtml .= "	<!-- bootstrap-tagsinput plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/bootstrap-tagsinput.css\">"."\n";
		
		$strHtml .= "	<!-- jQuery UI -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/jquery-ui.css\">"."\n";
		$strHtml .= "	<!-- jQuery UI Theme -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/jquery.ui.theme.css\">"."\n";
		$strHtml .= "	<!-- multi select plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/multi-select.css\">"."\n";
		$strHtml .= "	<!-- datepicker plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/datepicker.css\">"."\n";
		$strHtml .= "	<!-- timepicker plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/bootstrap-timepicker.min.css\">"."\n";
		$strHtml .= "	<!-- colorpicker plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/colorpicker.css\">"."\n";
		$strHtml .= "	<!-- MultiUpload plupload plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/jquery.plupload.queue.css\">"."\n";

		$strHtml .= "	<!-- WYSIWYG plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/jquery.cleditor.css\">"."\n";
		
		$strHtml .= "	<!-- Uniform plugin -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/uniform.default.min.css\">"."\n";
		$strHtml .= "	<!-- Switch plugin CSS -->"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/bootstrap-switch.css\">"."\n";
		$strHtml .= "	<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/flat-ui-fonts.css\">"."\n";
		
		$strHtml .= "	<!-- Old jquery functions -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.migrate.min.js\"></script>"."\n";
			
		$strHtml .= "	<!-- jQuery UI Core -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.ui.core.min.js\"></script>"."\n";
		$strHtml .= "	<!-- jQuery UI Widget -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.ui.widget.min.js\"></script>"."\n";
		$strHtml .= "	<!-- jQuery UI button -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.ui.button.min.js\"></script>"."\n";
		$strHtml .= "	<!-- jQuery UI Spinner -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.ui.spinner.min.js\"></script>"."\n";
		$strHtml .= "	<!-- jQuery UI Mouse -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.ui.mouse.min.js\"></script>"."\n";
		$strHtml .= "	<!-- jQuery UI slider -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.ui.slider.min.js\"></script>"."\n";
			
		//$strHtml .= "	<!-- tagsinput plugin -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.tagsinput.min.js\" charset=\"UTF-8\"></script>"."\n";
		$strHtml .= "	<!-- bootstrap tagsinput plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-tagsinput.min.js\"></script>"."\n";
		
		$strHtml .= "	<!-- multi select plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.multi-select.min.js\"></script>"."\n";
		
		//$strHtml .= "	<!-- jasny mask plugin -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.bootstrap-inputmask.min.js\"></script>"."\n";
		$strHtml .= "	<!-- maskedInput plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.maskedinput.min.js\"></script>"."\n";
		//http://igorescobar.github.io/jQuery-Mask-Plugin/
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.mask.min.js\"></script>"."\n";
		$strHtml .= "	<!-- timerpicker plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-timepicker.min.js\"></script>"."\n";
		$strHtml .= "	<!-- datepicker plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-datepicker.min.js\"></script>"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-datepicker.es.js\"></script>"."\n";
		$strHtml .= "	<!-- colorpicker plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-colorpicker.min.js\"></script>"."\n";
		$strHtml .= "	<!-- multiUpload plupload plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/plupload.full.min.js\"></script>"."\n";
		$strHtml .= "	<!-- multiUpload plupload plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.plupload.queue.min.js\"></script>"."\n";
		$strHtml .= "	<!-- spanish for plupload plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/plupload.spanish.js\"></script>"."\n";
		
		$strHtml .= "	<!-- WYSIWYG plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.cleditor.min.js\"></script>"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/ckeditor/ckeditor.js\"></script>"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/ckeditor/adapters/jquery.js\"></script>"."\n";
		
		$strHtml .= "	<!-- Uniform plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.uniform.min.js\"></script>"."\n";
		$strHtml .= "	<!-- file upload plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-fileupload.min.js\"></script>"."\n";

		$strHtml .= "<!-- Tablas -->"."\n";
		$strHtml .= "<!-- TableTools for dataTables plugin -->"."\n";
		$strHtml .= "<link rel=\"stylesheet\" href=\"".RUTA_ADM."css/TableTools.css\">"."\n";
		$strHtml .= "	<!-- dataTables plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/jquery.dataTables.min.js\"></script>"."\n";
		$strHtml .= "	<!-- TableTools for dataTables plguin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/TableTools.min.js\"></script>"."\n";

		$strHtml .= "	<!-- VALIDACIONES DE FORMS -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/validaciones.js\"></script>"."\n";
		//$strHtml .= "	<!-- Just for demonstration -->"."\n";
		//$strHtml .= "	<script src=\"js/demonstration.min.js\"></script>"."\n";
		$strHtml .= "	<!-- Theme framework -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/eakroko.min.js\"></script>"."\n";
		//$strHtml .= "	<!-- Just for demonstration -->"."\n";
		//$strHtml .= "	<script src=\"".RUTA_ADM."js/demonstration.min.js\"></script>"."\n";
		$strHtml .= "	<!-- Theme scripts -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/application.js\"></script>"."\n";
		
		$strHtml .= "	<!-- ComboBox plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-combobox.js\"></script>"."\n";
		$strHtml .= "	<!-- SelectPicker plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-select.min.js\"></script>"."\n";
		
		//$strHtml .= "	<script src=\"http://jschr.github.io/bootstrap-modal/js/bootstrap-modal.js\"></script>"."\n";
		//$strHtml .= "	<script src=\"http://jschr.github.io/bootstrap-modal/js/bootstrap-modalmanager.js\"></script>"."\n";
		
		$strHtml .= "	<!-- GRAFICAS GOOGLE -->"."\n";
		$strHtml .= "	<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>"."\n";
		//$strHtml .= "	<script type=\"text/javascript\" src=\"".RUTA_ADM."js/graficos.js\"></script>"."\n";
		
		$strHtml .= "	<!-- Switch plugin -->"."\n";
		$strHtml .= "	<script src=\"".RUTA_ADM."js/bootstrap-switch.min.js\"></script>"."\n";
		
		$strHtml .= "	<link rel=\"shortcut icon\" href=\"".RUTA_ADM."favicon.png\" />"."\n";
		$strHtml .= "	<link rel=\"apple-touch-icon-precomposed\" href=\"".RUTA_ADM."apple-touch-icon-precomposed.png\" />"."\n";
		$strHtml .= "</head>"."\n";

		return $strHtml;
	}
}

function CabeceraPanelControl($opMenu=SEC_ADM_PANELCONTROL)
{
	$strHtml = CabeceraHTML($bKeyGoogle);
	if($strHtml != ""){
		$numMensajes = 0;
		$strHtml .= "<body data-layout=\"fixed\">"."\n";
		$strHtml .= "	<div id=\"top\">"."\n";
		$strHtml .= "		<div class=\"container-fluid\">"."\n";
		
		$strHtml .= "			<div class=\"pull-left\">"."\n";
		$strHtml .= "				<a href=\"".RUTA_ADM."index.php\" id=\"brand\"><img src=\"/adm/img/logo-trans-cab.png\" alt=\"".PROJECT_NAME."\" height=\"24\" /></a>"."\n";
		/*$strHtml .= "				<div class=\"collapse-me\">"."\n";
		$strHtml .= "					<div class=\"btn-group\">"."\n";
		
		$enlace = RUTA_ADM."idioma.php?id=" . $_SESSION["sPANEL_Idioma"];
		$txt = $_SESSION["sPANEL_Idioma-txt"];
		$idioma = $_SESSION["sPANEL_Idioma"];
		$imgBandera = "/adm/img/" . $idioma . ".png";
		
		$strHtml .= "						<a href=\"".$enlace."\" class=\"button dropdown-toggle\" data-toggle=\"dropdown\">&nbsp;".$txt."<span class=\"caret\"></span></a>"."\n";
		$strHtml .= "						<div class=\"dropdown-menu pull-right\">"."\n";
		$strHtml .= "							<div class=\"right-details\">"."\n";
		$strHtml .= "								<h6>".TextoDeIdioma("Selecciona_idioma").":</h6>"."\n";
		$strHtml .= "									<ul>"."\n";
		$strHtml .= "										<li>"."\n";
		$strHtml .= "											<a href=\"".RUTA_ADM."idioma.php?id=es\">&nbsp;".TextoDeIdioma("idioma_txt_es")."</a>"."\n";
		$strHtml .= "										</li>"."\n";
		$strHtml .= "										<li>"."\n";
		$strHtml .= "											<a href=\"".RUTA_ADM."idioma.php?id=en\">&nbsp;".TextoDeIdioma("idioma_txt_en")."</a>"."\n";
		$strHtml .= "										</li>"."\n";
		$strHtml .= "										<li>"."\n";
		$strHtml .= "											<a href=\"".RUTA_ADM."idioma.php?id=ja\">&nbsp;".TextoDeIdioma("idioma_txt_ja")."</a>"."\n";
		$strHtml .= "										</li>"."\n";
		$strHtml .= "										<li>"."\n";
		$strHtml .= "											<a href=\"".RUTA_ADM."idioma.php?id=ca\">&nbsp;".TextoDeIdioma("idioma_txt_ca")."</a>"."\n";
		$strHtml .= "										</li>"."\n";
		$strHtml .= "									</ul>"."\n";
		$strHtml .= "								</div>"."\n";
		$strHtml .= "							</div>"."\n";
		$strHtml .= "						</div>"."\n";
		$strHtml .= "					</div>"."\n";
		$strHtml .= "					<img style=\"margin-top:0px;\" src=\"".$imgBandera."\" width=\"29\" title=\"".$txt."\" alt=\"".$txt."\" />"."\n";
		*/
		
		//$strHtml .= "				<div class=\"collapse-me\">"."\n";
		/*$strHtml .= "					<a href=\"".RUTA_ADM."contacto/index.php\" class=\"button\">"."\n";
		$strHtml .= "						<i class=\"icon-comment icon-white\"></i>"."\n";
		$strHtml .= "						".TextoDeIdioma("Mensajes").""."\n";
		$strHtml .= "						<span class=\"badge badge-important\">".$numMensajes."</span>"."\n";
		$strHtml .= "					</a>"."\n";
		//$strHtml .= "idioma: ".$_SESSION["sPANEL_Idioma"]." - ".$_SESSION["sPANEL_Idioma_ID"];
		
		$strHtml .= "					<a href=\"#\" class=\"button\">"."\n";
		$strHtml .= "						<i class=\"icon-question-sign icon-white\"></i>"."\n";
		$strHtml .= "						Tickets de soporte"."\n";
		$strHtml .= "						<span class=\"badge badge-info\">3</span>"."\n";
		$strHtml .= "					</a>"."\n";
		$strHtml .= "					<a href=\"#\" class=\"button\">"."\n";
		$strHtml .= "						<i class=\"icon-truck icon-white\"></i>"."\n";
		$strHtml .= "						Pedidos"."\n";
		$strHtml .= "						<span class=\"badge badge-default\">5</span>"."\n";
		$strHtml .= "					</a>"."\n";*/
		//$strHtml .= "				</div>"."\n";
		
		$strHtml .= "			</div>"."\n";
		
		$strHtml .= "			<div class=\"pull-right\">"."\n";
		$strHtml .= "				<div class=\"btn-group\">"."\n";
		$strHtml .= "					<a href=\"#\" class=\"button dropdown-toggle\" data-toggle=\"dropdown\"><i class=\"icon-white icon-user\"></i>".$_SESSION["sPANEL_Usuario_Nombre"]."<span class=\"caret\"></span></a>"."\n";
		$strHtml .= "					<div class=\"dropdown-menu pull-right\">"."\n";
		$strHtml .= "						<div class=\"right-details\">"."\n";
		$strHtml .= "								<h6>".TextoDeIdioma("Conectado_como")."</h6>"."\n";
		$strHtml .= "								<span class=\"name\"><i class=\"icon-group\"></i> ".ExecQueryValue("SELECT Nombre FROM tbUsuarioRol WHERE UsuarioRol_ID = ".$_SESSION["sPANEL_UsuarioRol_ID"])."</span>"."\n";
		$strHtml .= "								<span class=\"name\"><i class=\"icon-user\"></i> ".$_SESSION["sPANEL_Usuario_Nombre"]."</span>"."\n";
		$strHtml .= "								<span class=\"email\"><i class=\"icon-envelope\"></i> ".$_SESSION["sPANEL_Usuario_Email"]."</span>"."\n";
		if($_SESSION["sPANEL_Cliente_ID"] != ""){
			$strHtml .= "								<span class=\"name\"><i class=\"icon-user\"></i> Cliente: ".$_SESSION["sPANEL_Cliente_ID"]."</span>"."\n";
		}
		
		
		//$strHtml .= "								<a href=\"#\" class=\"highlighted-link\"><i class=\"icon-question-sign\"></i> ".TextoDeIdioma("Ayuda")."</a>"."\n";
		/*$strHtml .= "								<ul>"."\n";
		$strHtml .= "									<li>"."\n";
		$strHtml .= "										<a href=\"#\">Getting started</a>"."\n";
		$strHtml .= "									</li>"."\n";
		$strHtml .= "									<li>"."\n";
		$strHtml .= "										<a href=\"#\">Account settings</a>"."\n";
		$strHtml .= "									</li>"."\n";
		$strHtml .= "								</ul>"."\n";*/
		$strHtml .= "								</div>"."\n";
		$strHtml .= "							</div>"."\n";
		$strHtml .= "						</div>"."\n";
		$strHtml .= "						<a href=\"".RUTA_ADM."login.php\" class=\"button\">"."\n";
		$strHtml .= "							<i class=\"icon-off\"></i>"."\n";
		//$strHtml .= "							<i class=\"icon-signout\"></i>"."\n";
		$strHtml .= "							".TextoDeIdioma("Desconectar").""."\n";
		$strHtml .= "						</a>"."\n";
		$strHtml .= "				</div>"."\n";
		$strHtml .= "			</div>"."\n";
		$strHtml .= "		</div>"."\n";
				
		$strHtml .= "		<div id=\"main\">"."\n";
		$strHtml .= "			<div id=\"navigation\">"."\n";
		/*
		$strHtml .= "				<div class=\"search\">"."\n";
		$strHtml .= "					<i class=\"icon-search icon-white\"></i>"."\n";
		$strHtml .= "					<form action=\"search-page.html\" method=\"get\">"."\n";
		$strHtml .= "						<input type=\"text\" placeholder=\"Search here\" />"."\n";
		$strHtml .= "					</form>"."\n";
		$strHtml .= "					<div class=\"dropdown\">"."\n";
		$strHtml .= "						<a href=\"#\" class='search-settings dropdown-toggle' data-toggle=\"dropdown\"><i class=\"icon-cog icon-white\"></i></a>"."\n";
		$strHtml .= "						<ul class=\"dropdown-menu\">"."\n";
		$strHtml .= "							<li class=\"sort-by\">"."\n";
		$strHtml .= "								Sort by <span class='filter'>Categories</span> <span class=\"order\">A-Z</span>"."\n";
		$strHtml .= "							</li>"."\n";
		$strHtml .= "							<li class=\"heading\">"."\n";
		$strHtml .= "								Filter categories"."\n";
		$strHtml .= "							</li>"."\n";
		$strHtml .= "							<li class=\"filter active\">"."\n";
		$strHtml .= "								Categories"."\n";
		$strHtml .= "							</li>"."\n";
		$strHtml .= "							<li class=\"filter\">"."\n";
		$strHtml .= "								Countries"."\n";
		$strHtml .= "							</li>"."\n";
		$strHtml .= "							<li class=\"filter\">"."\n";
		$strHtml .= "								Likes"."\n";
		$strHtml .= "							</li>"."\n";
		$strHtml .= "							<li class=\"heading\">"."\n";
		$strHtml .= "								Sorting order"."\n";
		$strHtml .= "							</li>"."\n";
		$strHtml .= "							<li class=\"order active\">"."\n";
		$strHtml .= "								Ascending"."\n";
		$strHtml .= "							</li>"."\n";
		$strHtml .= "							<li class=\"order\">"."\n";
		$strHtml .= "								Descending"."\n";
		$strHtml .= "							</li>"."\n";
		$strHtml .= "						</ul>"."\n";
		$strHtml .= "					</div>"."\n";
		$strHtml .= "				</div>"."\n";*/
		
		//Añadir visibilidad mediante roles de acceso al panel
		$strHtml .= "				<ul class=\"mainNav\" data-open-subnavs=\"multi\">"."\n";
		if($opMenu == SEC_ADM_PANELCONTROL) $opActiva = "class='active'"; else $opActiva = "";
		$strHtml .= "					<li ".$opActiva.">"."\n";
		$strHtml .= "						<a href=\"".RUTA_ADM."index.php\"><i class=\"icon-home icon-white\"></i><span>".TextoDeIdioma("Panel_de_Control")."</span></a>"."\n";
		$strHtml .= "					</li>"."\n";
		
		$numOpciones = 3;
		if($opMenu == SEC_ADM_USUARIOS || $opMenu == SEC_ADM_ROLES || $opMenu == SEC_ADM_MENSAJES || $opMenu == SEC_ADM_ACCESOS || $opMenu == SEC_ADM_AYUDA) $opActiva = "class='active'"; else $opActiva = "";
		$strHtml .= "					<li ".$opActiva.">"."\n";
		$strHtml .= "						<a href=\"#\"><i class=\"icon-edit icon-white\"></i><span>".TextoDeIdioma("Administracion")."</span><span class=\"label\">".$numOpciones."</span></a>"."\n";
		$strHtml .= "						<ul class=\"subnav\">"."\n";
		
		if($_SESSION["sPANEL_UsuarioRol_ID"] <= ROL_BANCO_ID )
		{
			if($opMenu == SEC_ADM_USUARIOS) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "							<li ".$opActiva.">"."\n";
			$strHtml .= "								<a href=\"".RUTA_ADM."usuarios/index.php\">".TextoDeIdioma("Usuarios")."</a>"."\n";
			$strHtml .= "							</li>"."\n";
			if($opMenu == SEC_ADM_ROLES) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "							<li ".$opActiva.">"."\n";
			$strHtml .= "								<a href=\"".RUTA_ADM."roles/index.php\">".TextoDeIdioma("Roles")."</a>"."\n";
			$strHtml .= "							</li>"."\n";
			if($opMenu == SEC_ADM_ACCESOS) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "							<li ".$opActiva.">"."\n";
			$strHtml .= "								<a href=\"".RUTA_ADM."usuarios/accesos.php\">".TextoDeIdioma("Accesos")."</a>"."\n";
			$strHtml .= "							</li>"."\n";
		}
		/*
		if($opMenu == SEC_ADM_MENSAJES) $opActiva = "class='active'"; else $opActiva = "";
		$strHtml .= "							<li ".$opActiva.">"."\n";
		$strHtml .= "								<a href=\"".RUTA_ADM."contacto/index.php\">".TextoDeIdioma("Mensajes")."</a>"."\n";
		$strHtml .= "							</li>"."\n";
		if($opMenu == SEC_ADM_AYUDA) $opActiva = "class='active'"; else $opActiva = "";
		$strHtml .= "							<li ".$opActiva.">"."\n";
		$strHtml .= "								<a href=\"#\">".TextoDeIdioma("Ayuda")."</a>"."\n";
		$strHtml .= "							</li>"."\n";
		*/
		$strHtml .= "						</ul>"."\n";
		$strHtml .= "					</li>"."\n";
		
		if($_SESSION["sPANEL_UsuarioRol_ID"] <= ROL_BANCO_ID )
		{
			$numOpciones = 3;
			if($opMenu == SEC_ADM_CLIENTES || $opMenu == SEC_ADM_PROVEEDORES || $opMenu == SEC_ADM_PEDIDOS) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "					<li ".$opActiva.">"."\n";
			$strHtml .= "						<a href=\"#\"><i class=\"icon-edit icon-white\"></i><span>".TextoDeIdioma("Informacion")."</span><span class=\"label\">".$numOpciones."</span></a>"."\n";
			$strHtml .= "						<ul class=\"subnav\">"."\n";
			if($opMenu == SEC_ADM_CLIENTES) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "							<li ".$opActiva.">"."\n";
			$strHtml .= "								<a href=\"".RUTA_ADM."clientes/index.php\">".TextoDeIdioma("Clientes")."</a>"."\n";
			$strHtml .= "							</li>"."\n";
			if($opMenu == SEC_ADM_PROVEEDORES) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "							<li ".$opActiva.">"."\n";
			$strHtml .= "								<a href=\"".RUTA_ADM."proveedores/index.php\">".TextoDeIdioma("Proveedores")."</a>"."\n";
			$strHtml .= "							</li>"."\n";
			if($opMenu == SEC_ADM_PEDIDOS) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "							<li ".$opActiva.">"."\n";
			$strHtml .= "								<a href=\"".RUTA_ADM."pedidos/index.php\">".TextoDeIdioma("Pedidos")."</a>"."\n";
			$strHtml .= "							</li>"."\n";
			$strHtml .= "						</ul>"."\n";
			$strHtml .= "					</li>"."\n";
		}
		
		if($_SESSION["sPANEL_UsuarioRol_ID"] <= ROL_BANCO_ID )
		{
			$numOpciones = 2;
			if($opMenu == SEC_ADM_PARAMETROS_CATEGORIAS || $opMenu == SEC_ADM_PARAMETROS_MEDIDAS) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "					<li ".$opActiva.">"."\n";
			$strHtml .= "						<a href=\"#\"><i class=\"icon-edit icon-white\"></i><span>".TextoDeIdioma("Parametrizacion")."</span><span class=\"label\">".$numOpciones."</span></a>"."\n";
			$strHtml .= "						<ul class=\"subnav\">"."\n";
			if($opMenu == SEC_ADM_PARAMETROS_CATEGORIAS) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "							<li ".$opActiva.">"."\n";
			$strHtml .= "								<a href=\"".RUTA_ADM."param-categorias/index.php\">".TextoDeIdioma("Categorias")."</a>"."\n";
			$strHtml .= "							</li>"."\n";
			if($opMenu == SEC_ADM_PARAMETROS_MEDIDAS) $opActiva = "class='active'"; else $opActiva = "";
			$strHtml .= "							<li ".$opActiva.">"."\n";
			$strHtml .= "								<a href=\"".RUTA_ADM."param-medidas/index.php\">".TextoDeIdioma("Medidas")."</a>"."\n";
			$strHtml .= "							</li>"."\n";
			$strHtml .= "						</ul>"."\n";
			$strHtml .= "					</li>"."\n";
		}

		$strHtml .= "				</ul>"."\n";

		/*$strHtml .= "				<div class=\"status button\">"."\n";
		$strHtml .= "					<div class=\"status-top\">"."\n";
		$strHtml .= "						<div class=\"left\">"."\n";
		$strHtml .= "							Saving changes..."."\n";
		$strHtml .= "						</div>"."\n";
		$strHtml .= "					</div>"."\n";
		$strHtml .= "					<div class=\"status-bottom\">"."\n";
		$strHtml .= "						<div class=\"progress\">"."\n";
		$strHtml .= "							<div class=\"bar\" style=\"width:60%\">60%</div>"."\n";
		$strHtml .= "						</div>"."\n";
		$strHtml .= "					</div>"."\n";
		$strHtml .= "				</div>"."\n";*/
						
		$strHtml .= "			</div>"."\n";
		
		$strHtml .= "			<div id=\"content\">"."\n";
	}
	
	return $strHtml;
}

function PiePanelControl(){
	$strHtml = "";
	$strHtml .= "		<div class=\"navi-functions no-print\">"."\n";
	$strHtml .= "			<div class=\"btn-group btn-group-custom\">"."\n";
	$strHtml .= "				<a href=\"#\" class=\"button button-square layout-not-fixed notify\" rel=\"tooltip\" title=\"Toggle fixed-nav\" data-notify-message=\"Fixed nav is now {{state}}\" data-notify-title=\"Toggled fixed nav\">"."\n";
	$strHtml .= "					<i class=\"icon-unlock\"></i>"."\n";
	$strHtml .= "				</a>"."\n";
	$strHtml .= "				<a href=\"#\" class=\"button button-square layout-not-fluid notify button-active\" rel=\"tooltip\" title=\"Toggle fixed-layout\" data-notify-message=\"Fixed layout is now {{state}}\" data-notify-title=\"Toggled fixed layout\">"."\n";
	$strHtml .= "					<i class=\"icon-exchange\"></i>"."\n";
	$strHtml .= "				</a>"."\n";
	$strHtml .= "				<a href=\"#\" class=\"button button-square layout-no-nav notify notify-inverse \" rel=\"tooltip\" title=\"Toggle navigation\" data-notify-message=\"Navigation is now {{state}}\" data-notify-title=\"Toggled navigation\">"."\n";
	$strHtml .= "					<i class=\"icon-arrow-left\"></i>"."\n";
	$strHtml .= "				</a>"."\n";
	//$strHtml .= "				<a href=\"#\" class=\"button button-square button-active force-last notify-toggle toggle-active notify\" rel=\"tooltip\" title=\"Toggle notification\"  data-notify-message=\"Notifications turned {{state}}\" data-notify-title=\"Toggled notifications\">"."\n";
	//$strHtml .= "					<i class=\"icon-bullhorn\"></i>"."\n";
	//$strHtml .= "				</a>"."\n";
	$strHtml .= "			</div>"."\n";
	$strHtml .= "		</div>"."\n";
	$strHtml .= "	</body>"."\n";
	$strHtml .= "</html>"."\n";
	
	return $strHtml;
}
?>