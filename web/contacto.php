<?php
define("RUTA_RAIZ","");
require_once("_SiteLayout.php");

$strHtml = Cabecera(SEC_CONTACTO);
echo $strHtml;
?>

	<!-- CONTACT -->
	<div class="row-fluid" id="contact">
	
		<h2 id="scroll_up">
			Contacto
			<!-- a href="/index.php" class="arrow-top">
				<i class="icon-large icon-home"></i>
			</a> -->
			<!-- a href="/index.php" class="arrow-top">
				<img src="/img/arrow-top.png">
				</a> -->
		</h2>

		<!-- CONTACT INFO -->
		<div class="span4" id="contact-info">
			<br />
			<h3>Contacta con nosotros</h3>
			<p>Queremos hacer que los Bancos de Alimentos sean más eficaces y solidarios!</p>
			<p>Coméntanos cualquier problema o inquitud que te haya surgido al trabajar con la aplicación</p>
			<p><a href="mailto:contacto@appalimentar.com">contacto@appalimentar.com</a></p>
		</div>
		
		<!-- CONTACT FORM -->
		<div class="span7" id="contact-form">
			<form name="frmDatos" id="frmDatos" action="contacto.php" method="post" class="form-horizontal form-bordered form-validate">
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="nombre">Nombre</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="nombre" placeholder="Jose Luis Garcia" data-rule-required="true" data-msg-required="Introduzca el nombre">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="email">Email</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="email" placeholder="joseluis@gmail.com">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="asunto">Asunto</label>
						<div class="controls">
							<input class="input-xlarge" type="text" id="asunto" placeholder="Consulta general">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="mensaje">Mensaje</label>
						<div class="controls">
							<textarea class="input-xlarge" rows="3" id="mensaje" placeholder="Tu mensaje..."></textarea>
						</div>
					</div>

					<div class="form-actions">
						<!-- button type="submit" class="btn btn-primary">Enviar</button-->
						<button type="submit" class="btn btn-primary"><i class="icon-envelope icon-white"></i> Enviar</button>
						<button type="button" class="btn" onclick="resetear(this);"><i class="icon-trash"></i> Limpiar</button>
					</div>
				</fieldset>
			</form>
		</div>
		
	</div>
<?php echo Pie();?>