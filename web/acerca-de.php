<?php
define("RUTA_RAIZ","");
require_once("_SiteLayout.php");

$strHtml = Cabecera(SEC_ACERCA_DE);
echo $strHtml;
?>
	<!-- ABOUT & UPDATES -->
	<div class="row-fluid" id="about">
	
		<div class="span6">
			<h2 id="scroll_up">
				Acerca de AppAlimentar
			</h2>
			
			<p>FlexApp is a fully responsive HTML/CSS template perfect for marketing your mobile application. The template utilizes responsive CSS3 & jQuery technology to provide a consistent and enjoyable viewing experience across multiple devices.</p>
			<p>FlexApp is 100% free to use. You may change, edit or modify the template however you wish, for free or commercial projects.</p> 
			<p><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">FlexApp</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.trippoinc.com" property="cc:attributionName" rel="cc:attributionURL">Trippo</a> is licensed under <a rel="license" href="http://creativecommons.org/licenses/by/3.0/">CC BY 3.0</a>.<br />Built on <a xmlns:dct="http://purl.org/dc/terms/" href="http://twitter.github.com/bootstrap/" rel="dct:source">Bootstrap</a>.</p>
			
		</div>
	
		<div class="span6 updates" id="updates">
			<h2 id="scroll_up">
				Updates
				<a href="#home" class="arrow-top">
				<img src="/img/arrow-top.png">
				</a>
			</h2>
			
			<!-- UPDATES & RELEASE NOTES -->
			
			<h3 class="version">Version 1.2</h3>
			<span class="release-date">Released on April 28th, 2012</span>
			<ul>
				<li><span class="label new">NEW</span>Challenge a Friend</li>
				<li><span class="label fix">FIX</span>Fixed 'Resume Game' Bug</li>
			</ul>
			<hr>
			
			<h3 class="version">Version 1.1</h3>
			<span class="release-date">Released on January 28th, 2012</span>
			<ul>
				<li><span class="label new">NEW</span>Facebook & Twitter Integration</li>
				<li><span class="label fix">FIX</span>Various Bug Fixes</li>
				<li><span class="label new">NEW</span>Graphics for Retina Display</li>
			</ul>
			<hr>
		
			<h3 class="version">Version 1.0</h3>
			<span class="release-date">Released on January 10th, 2012</span>
			<ul>
				<li><span class="label label-info">NEW</span>Initial Release</li>
			</ul>
			
		</div>
	
	</div>
<?php echo Pie();?>