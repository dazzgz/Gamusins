<?
function ProcesarFicherosUpload(&$strError, $campo, $Fichero_ID, $directorio, $ancho=0, $alto=0, $tamanioMax=0, $arrExtensiones=0)
{
	$Fichero_ID = intval($Fichero_ID);
	if($Fichero_ID > 0)
	{
		//Datos del fichero actual
		$PA="SELECT Nombre, Ruta FROM tbFichero WHERE Fichero_ID = ".$Fichero_ID;
		$resultado = ExecPA($PA);
		$NumElementos = mysql_affected_rows();
		if($NumElementos > 0){
			$Nombre = stripslashes(ValorCelda($resultado,0,"Nombre"));
			$Ruta 	= stripslashes(ValorCelda($resultado,0,"Ruta"));
		}
		//Elimino el fichero actual
		$PA = "DELETE FROM tbFichero WHERE Fichero_ID = ".$Fichero_ID;
		ExecPA($PA);
		//Borro fisicamente
		if(file_exists(RAIZ_FILE_SYSTEM."/".$Ruta.$Nombre))
		{
			unlink(RAIZ_FILE_SYSTEM."/".$Ruta.$Nombre);
		}
		if($ancho > 0 || $alto > 0){
			$arrNombreFichero = explode('.', $Nombre);
			//var_dump_html($arrExtensiones);
			$ficheroExtension = strtolower($arrNombreFichero[count($arrNombreFichero)-1]);
			$ficheroNombre = "";
			for ($i=0;$i<count($arrNombreFichero)-1;$i++)
			{
				if ($i > 0){
					$ficheroNombre .= ".";
				}
				$ficheroNombre .= $arrNombreFichero[$i];
			}
			$ficheroRedim = $ficheroNombre."_".$ancho."x".$alto.".".$ficheroExtension;
			//echo RAIZ_FILE_SYSTEM."/".$Ruta.$ficheroRedim;
			if(file_exists(RAIZ_FILE_SYSTEM."/".$Ruta.$ficheroRedim))
			{
				unlink(RAIZ_FILE_SYSTEM."/".$Ruta.$ficheroRedim);
			}
		}
	}
	
	$logError = '';
	$Tamanio_MAX = $tamanioMax;
	
	if($_FILES[$campo]["name"]!="")
	{
			$Fichero = $_FILES[$campo]["name"];
			$Tamano = $_FILES[$campo]["size"];
			$Formato = $_FILES[$campo]["type"];
			
			if($_FILES[$campo]["error"] != 0){
				$logError .= $_FILES[$campo]["error"]."<br />";
			}
			if($Tamanio_MAX==0 || $Tamano < $Tamanio_MAX){
				$arrNombreFichero = explode('.', $Fichero);
				//var_dump_html($arrExtensiones);
				$ficheroExtension = strtolower($arrNombreFichero[count($arrNombreFichero)-1]);
				if(in_array($ficheroExtension, $arrExtensiones))
				{
					$ficheroNombre = "";
					for ($i=0;$i<count($arrNombreFichero)-1;$i++)
					{
						if ($i > 0){
							$ficheroNombre .= ".";
						}
						$ficheroNombre .= $arrNombreFichero[$i];
					}
					//echo RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio;
					if(!file_exists(RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio)){
						mkdir(RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio, 0777);
						$logError .= "Directorio creado: ".$directorio."<br />";
					}
					//Si existe renombramos...
					/*if($ancho > 0 || $alto > 0){
						$Fichero = $ficheroNombre."_".$ancho."x".$alto.".".$ficheroExtension;
					}else{*/
						$Fichero = $ficheroNombre.".".$ficheroExtension;
					//}
					//echo RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$ficheroRedim;
					if(file_exists(RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$Fichero)){
						//echo RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$ficheroRedim;
						$dado = rand(1, 100);
						$ficheroNombre .= "-".str_pad($dado, 2, "0", STR_PAD_LEFT);
						/*if($ancho > 0 || $alto > 0){
							$Fichero = $ficheroNombre."_".$ancho."x".$alto.".".$ficheroExtension;
						}else{*/
							$Fichero = $ficheroNombre.".".$ficheroExtension;
						//}
					}
					//$ficheroNombre = elimina_especialchars($ficheroNombre);
					//echo $ficheroNombre;
					//echo stripAccents($ficheroNombre); 
					
					//Inicializo la variable de retorno con el nombre del fichero para tenerlo en la pagina llamadora
					$strError = $Fichero.'|'.$logError;
					if(copy($_FILES[$campo]["tmp_name"], RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$Fichero))
					{
						
						//Redimensiono al tamaño requerido. Si procede
						if($ancho > 0 || $alto > 0){
							$ficheroRedim = $ficheroNombre."_".$ancho."x".$alto.".".$ficheroExtension;
							//echo RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$Fichero." a ".RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$ficheroRedim;
							thumbnail(RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$ficheroRedim, RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$Fichero, $ancho, $alto, true);
							$Tamano = filesize(RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$Fichero);
						}
						$PA 				= "INSERT INTO tbFichero(Nombre, Ruta, Tamano, Formato) VALUES('".addslashes($Fichero)."', '".addslashes(DIR_UPLOAD.$directorio)."', ".intval($Tamano).", '".addslashes($Formato)."');";
						$resultado 	= ExecPA($PA);
						$Fichero_ID = mysql_insert_id();
						
						//para devolverla y visualizar
						//$strError .= RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$Fichero."<br />";
					}
				}else{
					$strError = $Fichero.'|';
					$Fichero_ID = 0;
					$strError .= "Extensión no admitida. ".$ficheroExtension."<br />";
				}
			}else{
				$strError = $Fichero.'|';
				$Fichero_ID = 0;
				$strError .= "Tamaño en bytes excedido. ".$Tamano."<br />";
			}
	}
	else
	{
		$strError = $_FILES[$campo]["tmp_name"].'|';
		$Fichero_ID = 0;
		$strError .= " Nombre de fichero vacio. "."<br />";
	}
	
	//SI NO FUNCIONA LA DEVOLUCION POR REFERENCIA....
	//LO SOLUCIONO ACCEDIENDO A LA VARIABLE definida en la pagina llamadora como '$err'...
	//$GLOBALS["err"] = $strError;
	
	return $Fichero_ID;
}

function thumbnail($rutanew, $image, $width, $height, $borrarOrigen=true) {

	/*if($image[0] != "/") { // Decide where to look for the image if a full path is not given
		if(!isset($_SERVER["HTTP_REFERER"])) { // Try to find image if accessed directly from this script in a browser
			$image = $_SERVER["DOCUMENT_ROOT"].implode("/", (explode('/', $_SERVER["PHP_SELF"], -1)))."/".$image;
		} else {
			$image = implode("/", (explode('/', $_SERVER["HTTP_REFERER"], -1)))."/".$image;
		}
	} else {
		$image = $_SERVER["DOCUMENT_ROOT"].$image;
	}*/
	$image_properties = getimagesize($image);
	$image_width = $image_properties[0];
	$image_height = $image_properties[1];
	$image_ratio = $image_width / $image_height;
	$type = $image_properties["mime"];

	if(!$width && !$height) {
		$width = $image_width;
		$height = $image_height;
	}
	if(!$width) {
		$width = round($height * $image_ratio);
	}
	if(!$height) {
		$height = round($width / $image_ratio);
	}

	if($type == "image/jpeg") {
		//header('Content-type: image/jpeg');
		$thumb = imagecreatefromjpeg($image);
	} elseif($type == "image/png") {
		//header('Content-type: image/png');
		$thumb = imagecreatefrompng($image);
	} else {
		return false;
	}

	$temp_image = imagecreatetruecolor($width, $height);
	imagecopyresampled($temp_image, $thumb, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
	$thumbnail = imagecreatetruecolor($width, $height);
	imagecopyresampled($thumbnail, $temp_image, 0, 0, 0, 0, $width, $height, $width, $height);

	if($type == "image/jpeg") {
		imagejpeg($thumbnail, $rutanew, 100);
	} else {
		imagealphablending($thumbnail, false);
		imagesavealpha($thumbnail, true);

		imagepng($thumbnail, $rutanew);
	}

	imagedestroy($temp_image);
	imagedestroy($thumbnail);

	if($borrarOrigen){
		unlink($image);
		rename($rutanew, $image);
	}
}
?>