<?php
define("RUTA_RAIZ","");
require_once("_SiteLayout.php");

$strHtml = Cabecera(SEC_INICIO);
echo $strHtml;
?>
	<!-- HOME -->
	<div class="row-fluid">
  
		<!-- PHONES IMAGE FOR DESKTOP MEDIA QUERY -->
		<div class="span5 visible-desktop">
			<img src="/img/banco-de-alimentos.png">
		</div>
	
		<!-- APP DETAILS -->
		<div class="span7">
	
			<!-- ICON -->
			<div class="visible-desktop" id="icon">
				<img src="/img/banco-de-alimentos-icon.png" />
			</div>
			
			<!-- APP NAME -->
			<div id="app-name">
				<h1>Bienvenido</h1>
			</div>
			
			<!-- VERSION -->
			<div id="version">
				<span class="version-top label label-inverse">Version 1.0</span>
			</div>
            
			<!-- TAGLINE -->
			<div id="tagline">
			Queremos hacer que los Bancos de Alimentos sean más eficaces y solidarios!
			</div>
		
			<!-- PHONES IMAGE FOR TABLET MEDIA QUERY -->
			<div class="hidden-desktop" id="phones">
				<img src="/img/banco-de-alimentos.png">
			</div>
            
			<!-- DESCRIPTION -->
			<div id="description">
				AppAlimentar es gratis y permite ir acercándonos cada vez más al lema: Un mundo sin despilfarro es un mundo sin hambre.
			</div>
            
			<!-- FEATURES -->
			<ul id="features">
				<li>Fully Responsive HTML/CSS3 Template</li>
				<li>Built on Bootstrap by Twitter</li>
				<li>Images and Photoshop Files Included</li>
				<li>Completely Free!</li>
			</ul>
		
			<!-- DOWNLOAD & REQUIREMENT BOX -->
			<div class="download-box">
				<a href="#"><img src="/img/available-on-the-app-store.png"></a>
			</div>
			<div class="download-box">
				<a href="#"><img src="/img/android_app_on_play_logo_large.png"></a>
			</div>
			<div class="download-box">
				<strong>Requirements:</strong><br>
				Compatible with iPhone and iPod touch. Requires iPhone OS 2.2 or later. WiFi, Edge, or 3G network connection sometimes required.
			</div>
			<div class="download-box">
				<strong>Requirements:</strong><br>
				Requires Android 2.3 and higher. WiFi, Edge, or 3G network connection sometimes required.
			</div>
			
		</div>
	</div>

<?php echo Pie();?>