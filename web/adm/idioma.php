<?php
define("RUTA_RAIZ","../");
require_once("_AdmLayout.php");

	ComprobarLoginPanel();
	
	$idioma = parametro("id");
	$_SESSION["sPANEL_Idioma"] = $idioma;
	$_SESSION["sPANEL_Idioma-txt"] = TextoDeIdioma("idioma_txt_".$idioma);
	$_SESSION["sPANEL_Idioma-txt-det"] = TextoDeIdioma("idioma_txt_det_".$idioma);
	
	$enlace = "idioma.php?id=" . $_SESSION["sPANEL_Idioma"];
	switch ($idioma)
	{
		case "es":
			$_SESSION["sPANEL_Idioma_ID"] = 1;
			break;
		case "en":
			$_SESSION["sPANEL_Idioma_ID"] = 2;
			break;
		case "ja":
			$_SESSION["sPANEL_Idioma_ID"] = 3;
			break;
		case "ca":
			$_SESSION["sPANEL_Idioma_ID"] = 4;
			break;
	}

	header("location: ".DOC_ROOT.DIR_PANELCONTROL."index.php");
?>