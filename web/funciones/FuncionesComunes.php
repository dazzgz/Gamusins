<?
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//SEGURIDAD: PANEL CONTROL																							/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ComprobarLoginPanel(){
	if( intval(session_get('sPANEL_Usuario_ID'))==0 ){
		//Si no se ha hecho login redirijo a login.php
		header("location: ".DOC_ROOT.DIR_PANELCONTROL."login.php");
		die();
	}
}
function EsAdministradorPanel(){
	return intval(session_get('sPANEL_Usuario_bAdmin'));
}
function getUserIDPanel(){
	return intval(session_get('sPANEL_Usuario_ID'));
}
function unsession_setPanel(){
	/*Personalizada*/

	session_set('sPANEL_Usuario_ID',		0);
	session_set('sPANEL_Usuario_Nombre',	'');
	session_set('sPANEL_Usuario_Email',	'');
			
	
	setcookie('sPANEL_Usuario_ID',0,0);	
	setcookie('sPANEL_Usuario_Nombre','',0);
	setcookie('sPANEL_Usuario_Email','',0);

	//session_destroy();
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//SEGURIDAD: ZONA DE USUARIO																							/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ComprobarLoginCliente($info,$AuxString=""){
	//Funcion que verifica dentro de la zona de usuarios si existe sesion.
	session_set('PAGINA_ACTUAL',SERVER.$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].$AuxString);
	
	if(!isset($_SESSION) || !isset($_SESSION["sFRONT_Cliente_ID"]) || intval($_SESSION["sFRONT_Cliente_ID"])==0){
		if(strlen($info)>0){
			header('Location: /usuarios/index.php?info='.$info);
		}else{
			header('Location: /usuarios/index.php');
		}
		die();
	}	
}
function EstaLogadoElCliente(){
	//Devuelve true si existe el usuario en sesion.
	if(isset($_SESSION) && intval($_SESSION["sFRONT_Cliente_ID"])>0){
		return true;
	}else{
		return false;
	}	
}
function siLogadoClienteIrAIndex(){
	//Miremos si ya esta validado, entonces redireccionamos
	if(isset($_SESSION["sFRONT_Cliente_ID"]) && $_SESSION["sFRONT_Cliente_ID"]!="" && intval($_SESSION["sFRONT_Cliente_ID"])>0){
		header('Location: /usuarios/cuenta/index.php');
		die();	
	}
	
	//Los siguientes sentencias sirven para recordar la direcion de donde viene y redirecionarle tras validarse
	//Se asigna en function ComprobarLoginCliente()
	if( !isset($_SESSION['PAGINA_ACTUAL']) || $_SESSION['PAGINA_ACTUAL']=="" ){
		//Valor de defecto
		$_SESSION['PAGINA_ACTUAL']=SERVER.DOC_ROOT.DIR_USUARIOS_CUENTA."index.php";
	}
}
function siLogadoClienteIrAPagina(){
	if(!isset($_SESSION["PAGINA_ACTUAL"]) || $_SESSION['PAGINA_ACTUAL']==""){
		session_set('PAGINA_ACTUAL',SERVER.DOC_ROOT.DIR_USUARIOS_CUENTA."index.php");
	}	
	
	header('Location: '.$_SESSION['PAGINA_ACTUAL']);
	unset($_SESSION['PAGINA_ACTUAL']);
	die();
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES SESION																						/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function session_get($strConst,$strtipo=''){
	$resultado = '';
	switch(strtolower($strtipo)){
		case 'nocookie':
			if(isset($_SESSION[$strConst]) && strlen($_SESSION[$strConst])>0){
				$resultado = $_SESSION[$strConst];
			}	
			break;
		default:
			if(isset($_SESSION[$strConst]) && strlen($_SESSION[$strConst])>0){
				$resultado = $_SESSION[$strConst];
			}elseif(isset($_COOKIE[$strConst]) && strlen($_COOKIE[$strConst])>0){
				$resultado = $_COOKIE[$strConst];
				
				//Reestablecemos sessi�n
				session_set($strConst,$resultado);
			}
			break;	
	}
	return $resultado;
}
function session_set($strConst,$strValue){
	$_SESSION[$strConst] = $strValue;
}
function cookie_get($strConst){
	return $_COOKIE[$strConst];
}
function cookie_set($strConst,$strValue){
	//echo 'SETEANDO COOKIES '.$strConst.'='.$strValue.'<br />';
	setcookie($strConst, $strValue, time()+(3600*24*14)); //Caduca a las dos semanas
}
function unsession_set(){
	session_set('sFRONT_Cliente_ID',		0);
	session_set('sFRONT_Cliente_Nombre',	'');
	session_set('sFRONT_Cliente_Email'	,	'');
	
	
	session_set('sFRONT_ClienteRol_ID'	,	0);
	
	session_set('sFRONT_ClienteRol'	,		'');
	
	
	setcookie('sFRONT_Cliente_ID',			0,	time() - 3600 	,'/');	
	setcookie('sFRONT_Cliente_Nombre',		'',	time() - 3600 	,'/');	
	setcookie('sFRONT_Cliente_Email',		'',	time() - 3600 	,'/');	
		
	setcookie('sFRONT_ClienteRol_ID',		0,	time() - 3600 	,'/');
	setcookie('sFRONT_ClienteRol',		'',	time() - 3600 	,'/');
	
	session_destroy();
	
	flush();
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//IDIOMAS PUBLICO																							/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function IdiomaEnSesion(){
	$lang = strtoupper(addslashes(parametro("lang")));
	if( strlen($lang)!=2 ){
		if( strlen(session_get('sFRONT_Idioma'))!=2 ){
			$lang = strtoupper(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));
		}
	}
	if( strlen($lang)>0 ){
		switch ($lang){
			case "es":
			case "en":
			case "ja":
			case "ca":
				session_set('sFRONT_Idioma_ID',$lang);
				break;
			default:
				session_set('sFRONT_Idioma_ID',COD_IDIOMA);	
				break;
		}
	}else{		
		if(strlen(session_get('sFRONT_Idioma_ID'))!=2 ){	
			session_set('sFRONT_Idioma_ID',COD_IDIOMA);		
		}
	}
}

function get_idioma_en_session(){
	$returnvalue = COD_IDIOMA;
	if( isset($_SESSION["sFRONT_Idioma"]) && strlen(session_get('sFRONT_Idioma'))==2 ){
		$returnvalue = session_get('sFRONT_Idioma');
	}
	return $returnvalue;
}

function get_id_idioma_en_session(){
	//Retorna  el ID del idioma en db
	switch (session_get('sFRONT_Idioma')){
		case "en":
			//Inglés
			return 2;
		case "ja":
			//Japones
			return 3;
			break;
		case "ca":
			//Catalán
			return 4;
			break;
		default:
			//Castellano
			return 1;
			break;
	}
}

function get_str_idioma($Idioma_ID){
	$Idioma_ID = intval($Idioma_ID);
	
	switch ($Idioma_ID){
		case 2:
			//Inglés
			return 'en';
		case 3:
			//Japones
			return 'ja';
			break;
		case 4:
			//Catalán
			return 'ca';
		default:
			//Castellano
			return 'es';
			break;
	}
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES: LOG PANEL																							/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function InsertLog ($Usuario_ID,$IP,$Fecha,$Login,$Password,$Acceso){
	$PA = "INSERT INTO tbLogAccesoPanel( Usuario_ID, IP, Fecha, Login, Password, Acceso) VALUES (";
	$PA .= "".intval($Usuario_ID).", ";
	$PA .= "'".addslashes($IP)."', ";
	$PA .= "'".$Fecha."', ";
	$PA .= "'".addslashes($Login)."', ";
	$PA .= "'".addslashes($Password)."', ";
	$PA .= "'".addslashes($Acceso)."') ";
	ExecPA($PA);
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES GET SET																						/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getConstante($strConst){
	return constant($strConst);
}
function getMoneda($strConst){
	return constant($strConst);
}
function getPost($strConst){
	$salida = '';
	if (array_key_exists($strConst,$_POST)){
		$salida = $_POST[$strConst];
	}
	return $salida;
}
function sparametro($strConst){
	$salida = '';
	if (array_key_exists($strConst,$_POST)){
		$salida = $_POST[$strConst];
	}
	return $salida;
}
function parametro($dato){
	$salida="";
	if (array_key_exists($dato,$_POST)){
		$salida=$_POST[$dato];
	}elseif (array_key_exists($dato,$_GET)){
		$salida=$_GET[$dato];
	}
	return $salida;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES: UTILES TEXTO																									/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function eliminaUltimaPalabra($Texto){
	//Quitamos la ultima palabra
	$arrayPalabras = split(" ",$Texto);
	$auxTexto="";

	for($j=0;$j<sizeof($arrayPalabras)-1;$j++){
		$auxTexto.= $arrayPalabras[$j]." ";
	}

	if(strlen($auxTexto)>0){
		$salida=$auxTexto;
	}else{
		$salida=$Texto;
	}
	return $salida;
}

function recortarTexto($strTexto, $numChar){
	/*if(strlen($strTexto)>$numChar){
		$strTexto = substr($strTexto,0,$numChar);
		$strTexto = eliminaUltimaPalabra($strTexto);
		return $strTexto."...";
	}else{
		return $strTexto;
	}*/
	
	if(strlen($strTexto)>$numChar){
		return preg_replace('/\s+?(\S+)?$/', '', substr($strTexto, 0, $numChar)).'...'; 
	}else{
		return preg_replace('/\s+?(\S+)?$/', '', substr($strTexto, 0, $numChar));
	}
}

function purgeHTML($cadenaHTML){
	//Definiciones
	$strOpenTag   = "<";
	$strCloseTag  = ">";
	$iniPosition  = 0;
	$endPosition  = 0;
	$cadenaHTMLcopia = $cadenaHTML;
	$iniPosition   = strpos($cadenaHTMLcopia, $strOpenTag,  $iniPosition);
	
	while($iniPosition>=0 && strlen($iniPosition)>0){
		$iniPosition   = strpos($cadenaHTMLcopia, $strOpenTag,  $iniPosition);
		$endPosition   = strpos($cadenaHTMLcopia, $strCloseTag, $iniPosition);
		
		if ($iniPosition >=0 && strlen($iniPosition)>0 && intval($endPosition)>0) {
			//Elimina del mensaje la porcoin del tag de HTML.
			$auxcadenaDcha = substr($cadenaHTMLcopia, 0, $iniPosition);
			$auxcadenaIzq  = substr($cadenaHTMLcopia, $endPosition+1);
			$cadenaHTMLcopia = $auxcadenaDcha."".$auxcadenaIzq;	
			
			$iniPosition = 0; //Volvemos a mirarla por completo
			
		}else{
			$iniPosition = false;
		}
	}
	
	///
	if(strlen($cadenaHTMLcopia)>0){
		$cadenaHTMLcopia=str_replace("<","&lt;",$cadenaHTMLcopia);
		$cadenaHTMLcopia=str_replace(">","&gt;",$cadenaHTMLcopia);
		return $cadenaHTMLcopia;
	}else{
		$cadenaHTML=str_replace("<","&lt;",$cadenaHTML);
		$cadenaHTML=str_replace(">","&gt;",$cadenaHTML);
		return $cadenaHTML;
	}
}
function sustituirTagPorDiv($strHTML,$strOpenTag,$strCloseTag,$strDivClass="")	{	
	//Ejemplo: $strDivClass = "class=\"Cita\""
	if(strlen($strHTML)>0 && strlen($strOpenTag)>0 && strlen($strCloseTag)>0){
		$array_tagOpen 	= $strHTML.split($strOpenTag);
		$array_tagClose = $strHTML.split($strCloseTag);
	
		$numTagOpen 	= sizeof($array_tagOpen);
		$numTagClose 	= sizeof($array_tagClose);
			
		if(intval($numTagOpen)== intval($numTagOpen) && $numTagOpen>=0){
			$strHTML= str_replace($strOpenTag,"<div ".$strDivClass.">",$strHTML);
			$strHTML= str_replace($strCloseTag,"</div>",$strHTML);	
		}else{
			//Los vamos a juntar par a par, el resto se eliminan. El procedimiento es secuencial

			$iniPosition  = 0;
			$endPosition  = 0;
			
			$iniPosition   = strpos($strHTML, $strOpenTag,  $iniPosition);
			$endPosition   = strpos($strHTML, $strCloseTag, $iniPosition);
			
			while($iniPosition && $endPosition > $iniPosition){
				$auxcadenaDcha 	= substr($strHTML, 0, $iniPosition);
				$auxcadenaCentro= substr($strHTML, $iniPosition+1, $endPosition);
				$auxcadenaIzq  	= substr($strHTML, $endPosition+1);
					
				$auxcadenaCentro= str_replace($strOpenTag,"<div ".$strDivClass.">",$strHTML);
				$auxcadenaCentro= str_replace($strCloseTag,"</div>",$strHTML);	
					
				$strHTML = $auxcadenaDcha.$auxcadenaCentro.$auxcadenaIzq;	
					
				$iniPosition   = strpos($strHTML, $strOpenTag,  $iniPosition);
				$endPosition   = strpos($strHTML, $strCloseTag, $iniPosition);
				
			}//End While
			
			$strHTML= str_replace($strOpenTag,"",$strHTML);
			$strHTML= str_replace($strCloseTag,"",$strHTML);	
			
		}
	}
	return $strHTML;
}
function var_dump_html($variable, $height="9em") {
	echo "<pre style=\"border: 1px solid #000; height: {$height}; overflow: auto; margin: 1em;\">";
	var_dump($variable);
	echo "</pre>\n";
}
function codeToAlternative($strHTML){
	$strHTML = trim($strHTML);
	if(strlen($strHTML)>0){	
		$strHTML = purgeHTML($strHTML);
		$strHTML = str_replace("\"","'",$strHTML);	
	}
	return $strHTML;
}
function codeToAlt($strHTML){
	/*Alias de codeToAlternative*/
	return codeToAlternative($strHTML);
}
function codeToFieldValue($strHTML){
	$strHTML = trim($strHTML);
	if(strlen($strHTML)>0){	
		$strHTML = str_replace("\"","&#034",$strHTML);	
	}
	return $strHTML;
}	
function codeToJavaScript($strHTML,$bSaltos=true){
	$strHTML = trim($strHTML);
	if(strlen($strHTML)>0){	
		if($bSaltos){
			//Util cuando el código es texto plano
			$strHTML = str_replace("\n","<br/>",$strHTML);
			$strHTML = str_replace("\r","<br/>",$strHTML);
		}else{
			//Util cuando el código es html
			$strHTML = str_replace("\n","",$strHTML);
			$strHTML = str_replace("\r","",$strHTML);
		}
		$strHTML = str_replace("\t"," ",$strHTML);
		$strHTML = str_replace("  "," ",$strHTML);
		$strHTML = str_replace("  "," ",$strHTML);
		$strHTML = str_replace("  "," ",$strHTML);
		$strHTML = str_replace("'","\\'",$strHTML);
	}
	return $strHTML;
}
function codeToJs($strHTML,$bSaltos=true){
	/*Alias de codeToJavaScript*/
	return codeToJavaScript($strHTML,$bSaltos);
}
function codeToFile($cadena){
	$cadena = rtrim($cadena);
	$cadena = trim($cadena);
	$cadena = eliminaAcentos($cadena);
	//$cadena = quitarAcutes($cadena);
	$cadena = purgeHTML($cadena);
	$cadena = str_replace(" ","-",$cadena);
	
	return ereg_replace("[^A-Za-z0-9\-]", "", $cadena);
}
function codeToNodoXML($strHTML){
	$strHTML = purgeHTML($strHTML);
	$strHTML = trim($strHTML);
	if(strlen($strHTML)>0){	
		$strHTML 	= str_replace("\n","",$strHTML);	
		$strHTML 	= str_replace("\r","",$strHTML);	
		$strHTML 	= str_replace("<","&lt;",$strHTML);	
		$strHTML 	= str_replace(">","&gt;",$strHTML);	
		$strHTML 	= str_replace("'","",$strHTML);
		$strHTML 	= str_replace("\"","&quot;",$strHTML);
		$strHTML 	= str_replace("&","&amp;",$strHTML);
	}
	return $strHTML;
}

function floatvalue($value) {
	return floatval(preg_replace('#^([-]*[0-9\.,\' ]+?)((\.|,){1}([0-9-]{1,2}))*$#e', "str_replace(array('.', ',', \"'\", ' '), '', '\\1') . '.\\4'", $value));
}

function int2Bool($Entero){
	// transforma un entero en una cadena que expresa Si ó No:
	// $entero=0 -> No
	// $entero<>0 -> Si
	if(intval($Entero)==1){
		$Salida= "<span class=\"text-success\">".TextoDeIdioma("Si")."</span>";
	}else{
		$Salida= "<span class=\"text-error\">".TextoDeIdioma("No")."</span>";
	}
	return $Salida;
}
function eliminaComillas($dato){
	$salida	= '';
	if ($dato<>''){
		$salida=str_replace("\\","",str_replace("\"","&#34;",$dato));
	}
	return $salida;
}
function obfuscate($text) {   
	$length = strlen($text);   
	$scrambled = '';   
	for ($i = 0; $i < $length; ++$i) {   
		$scrambled .= '&#' . ord(substr($text, $i, 1)) . ';';   
	}   
	return $scrambled;   
} 
function adaptarTamanoVideo($strEmbed='', $finalAncho=0, $finalAlto=0){

	$finalAncho = intval($finalAncho);
	$finalAlto 	= intval($finalAlto);
		
	if( $finalAncho==0 ) 	$finalAncho = REESCALAR_VIDEO_WIDTH;
	if( $finalAlto==0 ) 	$finalAlto	= REESCALAR_VIDEO_HEIGHT;
		
	if( $finalAncho==0 && $finalAlto==0 ) 			return $strEmbed;
	if( strlen($strEmbed)==0 || !REESCALAR_VIDEO ) 	return $strEmbed;

	//------------------------------------
	$strHtmlResultado 	= $strEmbed;
	$strEmbed_aux		= '';
	$strEmbed_uri 		= '';
	$posComienzo 		= 0;
	$posFin		 		= 0;
		
		
	if( strpos(strtolower($strEmbed),"javascript")>0 ){
		/*
		<script language='javascript' src='http://flash.it2.com/GetMediaJs.ashx?tokenid=b0e99bc3-a6ed-438e-8c41-398849e8a573'>
		< /script> 
		*/
	}else{
		/*
		A. YOUTUBE:
		--------------------
		<object width="560" height="340">
			<param name="movie" value="http://www.youtube.com/v/9tYmT2B8Xz8&hl=es&fs=1"></param>
			<param name="allowFullScreen" value="true"></param>
			<param name="allowscriptaccess" value="always"></param>
			<embed src="http://www.youtube.com/v/9tYmT2B8Xz8&hl=es&fs=1" 
				   type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="560" height="340">
			</embed>
		</object>
		
		<iframe title="YouTube video player" width="640" height="390" 
			src="http://www.youtube.com/embed/24Tc4Qie5Y8" frameborder="0" allowfullscreen>
		</iframe>
		
		
		B. VIMEO:
		--------------------	
		<object width="400" height="225">
			<param name="allowfullscreen" value="true" />
			<param name="allowscriptaccess" value="always" />
			<param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id=4240369&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" />
			<embed src="http://vimeo.com/moogaloop.swf?clip_id=4240369&amp;server=vimeo.com&amp;show_title=1&amp;show_byline=1&amp;show_portrait=0&amp;color=&amp;fullscreen=1" 
					type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" width="400" height="225">
			</embed>
		</object>
		<br />
		<a href="http://vimeo.com/4240369">subprime</a> from <a href="http://vimeo.com/beeple">beeple</a> on <a href="http://vimeo.com">Vimeo</a>.
		
		<iframe src="http://player.vimeo.com/video/21110707" width="400" height="225" frameborder="0">
		</iframe>
		<p><a href="http://vimeo.com/21110707">Wagon Christ Chunkothy Promo (official music video)</a> from <a href="http://vimeo.com/celyn">celyn Brazier</a> on <a href="http://vimeo.com">Vimeo</a>.</p>
		
		
		C. GOOGLE VIDEO:
		--------------------	
		<embed id="VideoPlayback" 
			src="http://video.google.es/googleplayer.swf?docid=1177176280845952069&hl=es&fs=true" 
			style="width:400px;height:326px" allowFullScreen="true" allowScriptAccess="always" 
			type="application/x-shockwave-flash"> 
		</embed>
		
		
		
		D. DAILYMOTION:
		--------------------	
		<object width="480" height="256">
			<param name="movie" value="http://www.dailymotion.com/swf/video/xcnzv0?theme=none"></param>
			<param name="allowFullScreen" value="true"></param><param name="allowScriptAccess" value="always"></param>
			<param name="wmode" value="transparent"></param>
			<embed type="application/x-shockwave-flash" src="http://www.dailymotion.com/swf/video/xcnzv0?theme=none" width="480" height="256" wmode="direct" allowfullscreen="true" allowscriptaccess="always"></embed>
		</object>
		<br /><a href="http://www.dailymotion.com/video/xcnzv0_zaragoza-2-barca-4_sport" target="_blank">Zaragoza 2 Bar&ccedil;a 4</a> 
		<i>por <a href="http://www.dailymotion.com/jordixana" target="_blank">jordixana</a></i>	
	
		E. OTROS:
		--------------------		
		<embed type="application/x-shockwave-flash" src="/dissenys/serveis/videos2/flvplayer.swf" style="" id="single" name="single" quality="high" allowfullscreen="true" flashvars="file=http://grupozeta.ondemand.flumotion.com/grupozeta/ondemand/sport/cas/euforia_españa_final1852.flv&image=aficion_mallorca.jpg&overstretch=fit" height="205" width="300">

		*/
		
		//Obtener el URI (gracias al atributo src=""):
		$posComienzo = strpos( strtolower($strEmbed), ' src="');
		if($posComienzo && $posComienzo>0){
			$posComienzo += 6;
			$strEmbed_aux = substr($strEmbed, $posComienzo);
			if(strlen($strEmbed_aux)>0){
				$posFin 	  = strpos( strtolower($strEmbed_aux),' ')-1;
				$strEmbed_uri = substr($strEmbed_aux, 0, $posFin);				
			}
		}
		if(strlen($strEmbed_uri)>0){
			$strEmbed_uri = str_replace('http://www.youtube.com/embed/','http://www.youtube.com/v/',$strEmbed_uri); //Correcion youtube 2011
			
			$strHtmlResultado = '';
			$strHtmlResultado .= '<object width="'.$finalAncho.'" height="'.$finalAlto.'">';
			$strHtmlResultado .= '<param name="movie" value="'.codeToAlternative($strEmbed_uri).'"></param>';
			$strHtmlResultado .= '	<param name="wmode" value="transparent"></param>';
			$strHtmlResultado .= '	<embed src="'.codeToAlternative($strEmbed_uri).'" type="application/x-shockwave-flash" width="'.$finalAncho.'" height="'.$finalAlto.'" wmode="transparent">';
			$strHtmlResultado .= '	</embed>';
			$strHtmlResultado .= '</object>';
		}
	}
	return $strHtmlResultado;
}
function zerofill($string,$cantidad,$char=0,$position='left') {
	$string 	= trim($string);
	$cantidad 	= intval($cantidad);
	$char 		= trim($char);
	$position 	= strtolower(trim($position));
	
	if(strlen($char)==0) $char = 0;
	
    while (strlen($string)<$cantidad) {
		if($position=='right'){
			$string = $string.$char;
		}else{
			$string = $char.$string;
		}
    }
    return $string;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES: SEGURIDAD																								/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function ComprobarEmail($Email){
	$mail_correcto = 0;
	//compruebo unas cosas primeras
	if ((strlen($Email) >= 6) && (substr_count($Email,"@") == 1) && (substr($Email,0,1) != "@") && (substr($Email,strlen($Email)-1,1) != "@")){
    	if ((!strstr($Email,"'")) && (!strstr($Email,"\"")) && (!strstr($Email,"\\")) && (!strstr($Email,"\$")) && (!strstr($Email," "))) {
        	//miro si tiene caracter .
			if (substr_count($Email,".")>= 1){
				//obtengo la terminacion del dominio
				$term_dom = substr(strrchr ($Email, '.'),1);
				//compruebo que la terminación del dominio sea correcta
				if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
					//compruebo que lo de antes del dominio sea correcto
					$antes_dom = substr($Email,0,strlen($Email) - strlen($term_dom) - 1);
					$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
					if($caracter_ult != "@" && $caracter_ult != "."){
						$mail_correcto = 1;
					}
				}
			}
		}
	}
	if($mail_correcto){
		return 1;
	}else{
		return 0;
	}
}

function check_email_dns($email) {
	if(preg_match('/^\w[-.\w]*@(\w[-._\w]*\.[a-zA-Z]{2,}.*)$/', $email, $matches))
	{
		if(function_exists('checkdnsrr'))
		{
			if(checkdnsrr($matches[1] . '.', 'MX')) return true;
			if(checkdnsrr($matches[1] . '.', 'A')) return true;
		}else{
			if(!empty($hostName))
			{
				if( $recType == '' ) $recType = "MX";
				exec("nslookup -type=$recType $hostName", $result);
				foreach ($result as $line)
				{
					if(eregi("^$hostName",$line))
					{
						return true;
					}
				}
				return false;
			}
			return false;
		}
	}
	return false;
}
    
function f_genera_psw($num=8){ 
	// By Kernellover
	$voc = array (a,e,i,o,u,A,E,I,U);
	$con = array (b,c,d,f,g,h,j,k,m,n,p,q,r,s,t,w,x,y,z,1,2,3,4,5,6,7,8,9);
	$psw = ""; // cadena que contendrá el password.
	
	$vc = mt_rand(0,1); // definde si empieza por vocal o consonante.
	for ($n=0; $n<$num; $n++){
	if ($vc==1){
	$vc=0;
	$psw .= $con[mt_rand(0,count($con)-1)];
	}
	$psw .= $voc[mt_rand(0,count($voc)-1)];
	$psw .= $con[mt_rand(0,count($con)-1)];
	}
	$psw = ereg_replace (q,qu,$psw);
	$psw = ereg_replace (quu,que,$psw);
	$psw = ereg_replace (yi,ya,$psw);
	$psw = ereg_replace (iy,ay,$psw);
	$psw = substr($psw,0,$num);
	return $psw;
}

function getRealIP(){ 
	$ip = '';
	if (isset($_SERVER["X_FORWARDED_FOR"])) { 
		$ip = $_SERVER["X_FORWARDED_FOR"]; 
	} else { 
		$ip = $_SERVER["REMOTE_ADDR"]; 
	}
	$ip    = strtolower(trim($ip));
	return $ip; 
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES: FICHERO																								/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function tamanioArchivo($Archivo){
	$Archivo = RUTA_RAIZ.DIR_UPLOAD.$Archivo;
	if(ENTORNO==0){
		$Archivo = str_replace("/","\\",$Archivo);
	}
	$extension 	= substr($Archivo,strrpos($Archivo,".")+1);					
	if(file_exists($Archivo)){
		//return "<nobr>".$extension." ".(round(filesize($Archivo)/1024))." KB</nobr>";
		return "<nobr> ".(round(filesize($Archivo)/1024))." KB</nobr>";
	}
}
function get_include_file($filename){
	if( !@file_exists($filename) ) {
		return false; 
	} else {
	    if( (@include_once $filename) !== false){ 
			return true; 
		}else{
			return false; 
		}
	}
}
function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include_once $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}
function leer_fichero($nombre_archivo){
	if( !@file_exists($nombre_archivo) ) {
		return false; 
	} else {
		$gestor 	= fopen($nombre_archivo, 'r');
		$contenido  = fread($gestor, filesize($nombre_archivo));
		fclose($gestor);
		
		return $contenido;
	}
}
function escribir_fichero($nombre_archivo,$contenido,&$strResultado=''){
	$strResultado 		= '';
	$bResultadoOk 	= false;
	
	if (!$gestor = fopen($nombre_archivo, 'w')) {
		$strResultado = 'No se puede abrir el archivo '.$nombre_archivo.'.'; 
	}else{
		if (fwrite($gestor, $contenido) === FALSE) {
			$strResultado = 'No se puede escribir al archivo '.$nombre_archivo.'.';
		}else{
			//Ok, se escribe
			$strResultado = 'El contenido se almaceno correctamente en '.$nombre_archivo.'.';
			$bResultadoOk 	= true;
		}
	}
	fclose($gestor);
	return $bResultadoOk;
}
function sobreescribir_fichero($nombre_archivo,$contenido,&$strResultado=''){
	$strResultado 		= '';
	$bResultadoOk 	= false;
	
	if( !@file_exists($nombre_archivo) ) {
		$strResultado = 'El fichero '.$nombre_archivo.' no existe.'; 
	}else{
		if (!is_writable($nombre_archivo)) {
			$strResultado = 'El fichero '.$nombre_archivo.' no tiene permisos de escritura.'; 
		}else{
			if (!$gestor = fopen($nombre_archivo, 'w')) {
				$strResultado = 'No se puede abrir el archivo '.$nombre_archivo.'.'; 
			}else{
			    if (fwrite($gestor, $contenido) === FALSE) {
					$strResultado = 'No se puede escribir al archivo '.$nombre_archivo.'.';
					
					
				}else{
					//Ok, se escribe
					$strResultado = 'El contenido se almaceno correctamente en '.$nombre_archivo.'.';
					$bResultadoOk 	= true;
				}
			}
			fclose($gestor);
		}
	}	
	return $bResultadoOk;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES: VARIOS																								/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function enviar_error_al_admin($error_titulo,$error_detalle){
	$Error_subject = 'ERROR EN '.PROJECT_NAME;
	
	$Error_text = "<h3>Información:</h3>";
	$Error_text.= "<p>";
	$Error_text.= "		<b>Título del error:</b> ".$error_titulo."<br />";
	$Error_text.= "		<b>Descripción:</b> ".$error_detalle."<br />";
	$Error_text.= "		<b>Fecha:</b> ".fechaHoraNormal(time())."<br />";
	$Error_text.= "</p>";
	
	$Error_text.= "<h3>Variables de servidor:</h3>";
	$Error_text.= "<p>";
	foreach($_SERVER as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	$Error_text.= "</p>";
	
	$Error_text.= "<h3>Variables de sesión:</h3>";
	$Error_text.= "<p>";
	foreach($_SESSION as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	$Error_text.= "</p>";
	
	$Error_text.= "<h3>Cookies:</h3>";
	$Error_text.= "<p>";
	foreach($_COOKIE as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	$Error_text.= "</p>";
	
	$Error_text.= "<h3>Variables post/get:</h3>";
	$Error_text.= "<p>";
	foreach($_POST as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	foreach($_GET as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	$Error_text.= "</p>";
	
	@enviarPhpMailer(EMAIL_ERROR_SILENCIOSO,EMAIL_ERROR,$Error_subject,$Error_text);
}
function enviar_ok_al_admin($error_titulo,$error_detalle){
	$Error_subject = 'MENSAJE DE '.PROJECT_NAME;
	
	$Error_text = "<h3>Información:</h3>";
	$Error_text.= "<p>";
	$Error_text.= "		<b>Título:</b> ".$error_titulo."<br />";
	$Error_text.= "		<b>Descripción:</b> ".$error_detalle."<br />";
	$Error_text.= "		<b>Fecha:</b> ".fechaHoraNormal(time())."<br />";
	$Error_text.= "</p>";
	
	$Error_text.= "<h3>Variables de servidor:</h3>";
	$Error_text.= "<p>";
	foreach($_SERVER as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	$Error_text.= "</p>";
	
	$Error_text.= "<h3>Variables de sesión:</h3>";
	$Error_text.= "<p>";
	foreach($_SESSION as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	$Error_text.= "</p>";
	
	$Error_text.= "<h3>Cookies:</h3>";
	$Error_text.= "<p>";
	foreach($_COOKIE as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	$Error_text.= "</p>";
	
	$Error_text.= "<h3>Variables post/get:</h3>";
	$Error_text.= "<p>";
	foreach($_POST as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	foreach($_GET as $key => $value){
		$Error_text.= '<b>'.$key.'</b>= "'.$value.'" <br />';
	}
	$Error_text.= "</p>";
	
	@enviarPhpMailer(EMAIL_ERROR_SILENCIOSO,EMAIL_ERROR,$Error_subject,$Error_text);
	@enviarPhpMailer(EMAIL_PARA,EMAIL_ERROR,$Error_subject,$Error_text);
}
function generaComboBox($PA,$comboBox,$noSeleccion="",$Seleccion="",$Otros=""){
	// Genera el código HTML para una determinada combobox
	//	Parámetros:
	// 		$comboBox
	//		$noSeleccion -> Identificador que aparece cuando no hay nada seleccionado
	// 		$Seleccion -> Valor del elemento que debe aparecer seleccionado
	$salida="";
	$Listado = ExecPA($PA);
	
	if(NumeroFilas($Listado)>0){
		$contador = 0;
		$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
		if($noSeleccion!="")
		{
			$salida .= "<option value=\"\">".$noSeleccion."</option>";
		}
		//echo $PA;
		for($Fila=0;$Fila<NumeroFilas($Listado);$Fila++)
		{
			$contador = $contador + 1;
			$valor=ValorCelda($Listado,$Fila,"valor");
			$descripcion=stripslashes(ValorCelda($Listado,$Fila,"descripcion"));
			if($valor==$Seleccion)
			{
				$salida .= "<option value=\"".$valor."\" selected>".$descripcion."</option>";
			}else
			{
				$salida .= "<option value=\"".$valor."\">".$descripcion."</option>";
			}
		}
		$salida .= "</select>";
	}else{
		$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
		if($noSeleccion!=""){
			$salida .= "<option value=\"\">".$noSeleccion."</option>";
		}
		$salida .= "</select>";
	}
	return $salida;
}

function generaComboBoxRelacionado($PAPadre, $PA, $comboBox, $noSeleccion="", $Seleccion="", $Otros=""){
// Genera el código HTML para una determinada combobox en lugar de crear combox dependientes
//	Parámetros:
// 		$comboBox
//		$noSeleccion -> Identificador que aparece cuando no hay nada seleccionado
// 		$Seleccion -> Valor del elemento que debe aparecer seleccionado
	$salida="";
	$ListadoPadre = ExecPA($PAPadre);
	if(NumeroFilas($ListadoPadre)>0){
		$contadorPadre = 0;
		$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
		
		if($noSeleccion!="")
		{
			$salida .= "<option value=\"\">".$noSeleccion."</option>";
		}
		for($FilaPadre=0;$FilaPadre < NumeroFilas($ListadoPadre);$FilaPadre++)
		{
			$contadorPadre++;
			$valor = ValorCelda($ListadoPadre, $FilaPadre, "valor");
			$descripcion = stripslashes(ValorCelda($ListadoPadre, $FilaPadre, "descripcion"));
			
			$salida .= "<optgroup label=\"".$descripcion."\">";
			
			//Cargo hijos de cada padre
			$PANew = str_replace("%1", $valor, $PA);
			$Listado = ExecPA($PANew);
			if(NumeroFilas($Listado)>0){
				
				//echo $PA;
				for($Fila=0;$Fila < NumeroFilas($Listado);$Fila++)
				{
					$contador++;
					$valor=ValorCelda($Listado,$Fila,"valor");
					$descripcion=stripslashes(ValorCelda($Listado,$Fila,"descripcion"));
					if($valor==$Seleccion){
						$salida .= "<option value=\"".$valor."\" selected>".$descripcion."</option>";
					}else{
						$salida .= "<option value=\"".$valor."\">".$descripcion."</option>";
					}
				}
			}
			$salida .= "</optgroup>";
		}
		$salida .= "</select>";
	}
	else
	{
		$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
		if($noSeleccion!=""){
			$salida .= "<option value=\"\">".$noSeleccion."</option>";
		}
		$salida .= "</select>";
	}
	return $salida;
}

function generaComboBoxRecursivo($PA, $Padre_ID, $indentado, $Principal, $comboBox, $noSeleccion="", $Seleccion="", $Otros=""){
	// Genera el código HTML para una determinada combobox en lugar de crear combox dependientes
	//	Parámetros:
	// 		$comboBox
	//		$noSeleccion -> Identificador que aparece cuando no hay nada seleccionado
	// 		$Seleccion -> Valor del elemento que debe aparecer seleccionado
	$salida="";
	//Cargo hijos de cada padre
	$PANew = str_replace("%1", $Padre_ID, $PA);
	$Listado = ExecPA($PANew);
	if(NumeroFilas($Listado) > 0){
		if($Padre_ID == 0)
		{
			$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
			if($noSeleccion!="")
			{
				$salida .= "<option value=\"\">".$noSeleccion."</option>";
			}
		}
		//echo $PA;
		
		for($Fila=0;$Fila < NumeroFilas($Listado);$Fila++)
		{
			$contador++;
			$valor = ValorCelda($Listado, $Fila, "valor");
			$descripcion = stripslashes(ValorCelda($Listado, $Fila, "descripcion"));
			if($valor == $Seleccion)
			{
				$salida .= "<option value=\"".$valor."\" selected>".$indentado.$descripcion."</option>";
			}else{
				if($valor == $Principal){
					$salida .= "<option value=\"".$valor."\" disabled=\"disabled\">".$indentado.$descripcion."</option>";
				}else{
					$salida .= "<option value=\"".$valor."\">".$indentado.$descripcion."</option>";
				}
			}
			
			$salida .= generaComboBoxRecursivo($PA, $valor, "&nbsp;&nbsp;&nbsp;".$indentado, $Principal, $comboBox, $noSeleccion, $Seleccion, $Otros);
		}
		if($Padre_ID == 0)
		{
			$salida .= "</select>";
		}
	}
	else
	{
		if($Padre_ID == 0)
		{
			$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
			if($noSeleccion!=""){
				$salida .= "<option value=\"\">".$noSeleccion."</option>";
			}
			$salida .= "</select>";
		}
	}
	return $salida;
}


function generaComboBoxRelacionadoRecursivo($PAPadre, $PA, $Padre_ID, $indentado, $Principal, $comboBox, $noSeleccion="", $Seleccion="", $Otros=""){
	// Genera el código HTML para una determinada combobox en lugar de crear combox dependientes
	//	Parámetros:
	// 		$comboBox
	//		$noSeleccion -> Identificador que aparece cuando no hay nada seleccionado
	// 		$Seleccion -> Valor del elemento que debe aparecer seleccionado
	$salida="";
	if($Padre_ID == 0)
	{
		$ListadoPadre = ExecPA($PAPadre);
		if(NumeroFilas($ListadoPadre)>0){
			$contadorPadre = 0;
			$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
			if($noSeleccion!="")
			{
				$salida .= "<option value=\"\">".$noSeleccion."</option>";
			}
			for($FilaPadre=0;$FilaPadre < NumeroFilas($ListadoPadre);$FilaPadre++)
			{
				$contadorPadre++;
				$valor = ValorCelda($ListadoPadre, $FilaPadre, "valor");
				$descripcion = stripslashes(ValorCelda($ListadoPadre, $FilaPadre, "descripcion"));
				$salida .= "<optgroup label=\"".$descripcion."\">";
						
				//Cargo hijos de cada tipo
				$PANew = str_replace("%1", $valor, $PA);
				//Cargo hijos de cada categoria
				$PANew = str_replace("%2", $Padre_ID, $PANew);
				$Listado = ExecPA($PANew);
				
				if(NumeroFilas($Listado) > 0){
					if($Padre_ID == 0)
					{
						$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
						if($noSeleccion!="")
						{
							$salida .= "<option value=\"\">".$noSeleccion."</option>";
						}
					}
					//echo $PA;
				
					for($Fila=0;$Fila < NumeroFilas($Listado);$Fila++)
					{
						$contador++;
						$valor = ValorCelda($Listado, $Fila, "valor");
						$descripcion = stripslashes(ValorCelda($Listado, $Fila, "descripcion"));
						if($valor == $Seleccion)
						{
							$salida .= "<option value=\"".$valor."\" selected>".$indentado.$descripcion."</option>";
						}else{
							if($valor == $Principal)
							{
								$salida .= "<option value=\"".$valor."\" disabled=\"disabled\">".$indentado.$descripcion."</option>";
							}else{
								$salida .= "<option value=\"".$valor."\">".$indentado.$descripcion."</option>";
							}
						}
			
						$salida .= generaComboBoxRecursivo($PA, $valor, "----".$indentado, $Principal, $comboBox, $noSeleccion, $Seleccion, $Otros);
					}
					if($Padre_ID == 0)
					{
						$salida .= "</select>";
					}
				}
				
				$salida .= "</optgroup>";
			}
		}
	}
	else
	{
		if($Padre_ID == 0)
		{
			$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" ".$Otros.">";
			if($noSeleccion!=""){
				$salida .= "<option value=\"\">".$noSeleccion."</option>";
			}
			$salida .= "</select>";
		}
	}
	return $salida;
}

function generaComboBoxMultiple($PA, $PADatos, $idComboBox, $noSeleccion="",$Multiple, $Otros=""){
	$salida="";
	$Multiple = intval($Multiple);
	
	//SQL para obtener los valores ya marcados
	if(strlen($PADatos)>0){
		$ListadoSeleccionados = ExecPA($PADatos);
		if(NumeroFilas($ListadoSeleccionados)>0){
			for($Fila=0;$Fila<NumeroFilas($ListadoSeleccionados);$Fila++){
				$DatosSeleccionados[$Fila]= intval(ValorCelda($ListadoSeleccionados,$Fila,"valor"));
			}
		}
	}		
	//Combo
	$Listado = ExecPA($PA);
	if(NumeroFilas($Listado)>0){
		$contador = 0;
		$salida = "<select name=\"".$idComboBox."\" id=\"".$idComboBox."\" class=\"Formulario\" size=\"".$Multiple."\" multiple=\"multiple\" ".$Otros." >";
		
		if($noSeleccion!=""){
			$salida .= "<option>".$noSeleccion."</option>";
		}
		for($Fila=0;$Fila<NumeroFilas($Listado);$Fila++){
			$contador 		= $contador + 1;
			$valor			= ValorCelda($Listado,$Fila,"valor");
			$descripcion	= stripslashes(ValorCelda($Listado,$Fila,"descripcion"));
			
			if(is_array($DatosSeleccionados)){
				$encontrado = 0;
				foreach($DatosSeleccionados as $ID){
					if($valor==intval($ID)){
						$encontrado = true;
					}
				}
				if($encontrado){
					$salida .= "<option value=\"".$valor."\" selected=\"selected\">".$descripcion."</option>";
				}else{
					$salida .= "<option value=\"".$valor."\">".$descripcion."</option>";
				}
			}else{
				$salida .= "<option value=\"".$valor."\">".$descripcion."</option>";
			}
		}
		$salida .= "</select>";
	}else{
		$salida = "<select name=\"".$comboBox."\" id=\"".$comboBox."\" class=\"Formulario\" size=\"".$Multiple."\" multiple=\"multiple\" ".$Otros.">";
		if($noSeleccion!=""){
			$salida .= "<option>".$noSeleccion."</option>";
		}
		$salida .= "</select>";
	}
	return $salida;
}
function generaComboBoxMovimiento($PA, $PADatos, $idComboBox,$Multiple, $Otros=""){
	$salida="";
	$opt_disbled_color = "#FF0000";
	$opt_disbled_font_weight = "bold";
	$opt_disbled_value = "-1";
	$opt_disbled_text = "No hay elementos.";
	$Multiple = intval($Multiple);
	
	//SQL para obtener los valores ya marcados
	if(strlen($PADatos)>0){
		$ListadoSeleccionados = ExecPA($PADatos);
		if(NumeroFilas($ListadoSeleccionados)>0){
			for($Fila=0;$Fila<NumeroFilas($ListadoSeleccionados);$Fila++){
				$DatosSeleccionados[$Fila]= intval(ValorCelda($ListadoSeleccionados,$Fila,"valor"));
			}
		}
	}		
	$Listado = ExecPA($PA);
	$lista_izq = "";
	$lista_dch = "";
	//Combos
	for($Fila=0;$Fila<NumeroFilas($Listado);$Fila++){
		$contador 		= $contador + 1;
		$valor			= ValorCelda($Listado,$Fila,"valor");
		$descripcion	= stripslashes(ValorCelda($Listado,$Fila,"descripcion"));
		
		if(is_array($DatosSeleccionados)){
			$encontrado = 0;
			foreach($DatosSeleccionados as $ID){
				if($valor==intval($ID)){
					$encontrado = true;
				}
			}
			if($encontrado){
				$lista_dch .= "<option value=\"".$valor."\">".$descripcion."</option>";
			}else{
				$lista_izq .= "<option value=\"".$valor."\">".$descripcion."</option>";
			}
		}else{
			$lista_izq .= "<option value=\"".$valor."\">".$descripcion."</option>";
		}
	}
	
	if (strlen($lista_izq) == 0) $lista_izq .= "<option value=\"".$opt_disbled_value."\" disabled style='color:".$opt_disbled_color.";font-weight:".$opt_disbled_font_weight.";' >".$opt_disbled_text."</option>";
	if (strlen($lista_dch) == 0) $lista_dch .= "<option value=\"".$opt_disbled_value."\" disabled style='color:".$opt_disbled_color.";font-weight:".$opt_disbled_font_weight.";' >".$opt_disbled_text."</option>";
	
	$salida = "";
	
	
	$salida .= "<script language=\"JavaScript\" type=\"text/javascript\">";
	$salida .= "var NS4 = (navigator.appName == \"Netscape\" && parseInt(navigator.appVersion) < 5);";
	
	$salida .= "function addOption(theSel, theText, theValue)";
	$salida .= "{";
	$salida .= "	var newOpt = new Option(theText, theValue);";
	$salida .= "	var selLength = theSel.length;";
	$salida .= "	theSel.options[selLength] = newOpt;";
	$salida .= "}";
	
	$salida .= "function addOptionEmpty(theSel)";
	$salida .= "{";
	$salida .= "	var newOpt = new Option(\"".$opt_disbled_text."\", \"".$opt_disbled_value."\");";
	$salida .= "	newOpt.disabled = true;";
	$salida .= "	newOpt.style.color='".$opt_disbled_color."';";
	$salida .= "	newOpt.style.fontWeight='".$opt_disbled_font_weight."';";
	
	$salida .= "	var selLength = theSel.length;";
	$salida .= "	theSel.options[selLength] = newOpt;";
	$salida .= "}";
	
	$salida .= "function deleteOption(theSel, theIndex)";
	$salida .= "{	";
	$salida .= "	var selLength = theSel.length;";
	$salida .= "	if(selLength>0)";
	$salida .= "	{";
	$salida .= "		theSel.options[theIndex] = null;";
	$salida .= "	}";
	$salida .= "}";
	
	$salida .= "function moveOptions(theSelFrom, theSelTo)";
	$salida .= "{";
		
	$salida .= "	var selLength = theSelFrom.length;";
	$salida .= "	var selectedText = new Array();";
	$salida .= "	var selectedValues = new Array();";
	$salida .= "	var selectedCount = 0;";
	
	$salida .= "	var i;";
	
	$salida .= "	if (theSelFrom.length == 1) {";
	$salida .= "		if (theSelFrom.options[0].value == \"-1\") return;";
	$salida .= "	}";
	
		
	$salida .= "	for(i=selLength-1; i>=0; i--)";
	$salida .= "	{";
	$salida .= "		if(theSelFrom.options[i].selected)";
	$salida .= "		{";
	$salida .= "			if (theSelTo.length == 1) {";
	$salida .= "				if (theSelTo.options[0].value == \"-1\") deleteOption(theSelTo, 0);";
	$salida .= "			}";
	$salida .= "			selectedText[selectedCount] = theSelFrom.options[i].text;";
	$salida .= "			selectedValues[selectedCount] = theSelFrom.options[i].value;";
	$salida .= "			deleteOption(theSelFrom, i);";
	$salida .= "			selectedCount++;";
	$salida .= "		}";
	$salida .= "	}";
		
	
	$salida .= "	for(i=selectedCount-1; i>=0; i--)";
	$salida .= "	{";
	$salida .= "		addOption(theSelTo, selectedText[i], selectedValues[i]);";
	$salida .= "	}";
		
	$salida .= "	if (theSelFrom.length == 0) addOptionEmpty(theSelFrom);";
	
	$salida .= "	if(NS4) history.go(0);";
	$salida .= "}";
	
	$salida .= "function selectAllOptions(selStr)";
	$salida .= "{";
	$salida .= "	var selObj = document.getElementById(selStr);";
	$salida .= "	for (var i=0; i<selObj.options.length; i++) {";
	$salida .= "		if (selObj.options[i].value != \"-1\"){";
	$salida .= "	    	selObj.options[i].selected = true;";
	$salida .= "		}";
	$salida .= "	}";
	$salida .= "}";

	
	$salida .= "</script>";


	$salida .= "<table border=\"0\">";
	$salida .= "	<tr><td align=\"center\">No seleccionados:</td><td width=\"75\">&nbsp;</td><td align=\"center\">Seleccionados:</td></tr>";
	$salida .= "	<tr>";
	$salida .= "		<td>";
	$salida .= "			<select name=\"fuera_".$idComboBox."\" id=\"fuera_".$idComboBox."\" class=\"Formulario\" size=\"".$Multiple."\" multiple=\"multiple\" style='width:160px'>";
	$salida .= $lista_izq;
	$salida .= "			</select>";
	$salida .= "		</td>";
	$salida .= "		<td align=\"center\" valign=\"middle\">";
	$salida .= "			<input type=\"button\" value=\"&gt;&gt;\"";
	$salida .= "				onclick=\"moveOptions(document.getElementById('fuera_".$idComboBox."'), document.getElementById('".$idComboBox."'));".$Otros."\" /><br />";
	$salida .= "			<input type=\"button\" value=\"&lt;&lt;\"";
	$salida .= "				onclick=\"moveOptions(document.getElementById('".$idComboBox."'), document.getElementById('fuera_".$idComboBox."'));".$Otros."\" />";
	$salida .= "		</td>";
	$salida .= "		<td>";
	$salida .= "			<select name=\"".$idComboBox."\" id=\"".$idComboBox."\" class=\"Formulario\" size=\"".$Multiple."\" multiple=\"multiple\" style='width:160px'>";
	$salida .= $lista_dch;
	$salida .= "			</select>";
	$salida .= "		</td>";
	$salida .= "	</tr>";
	$salida .= "</table>";

	return $salida;
}
function contieneBasuraWord($strHtml){
	if( 
		!(strpos(strtolower($strHtml), strtolower('class="MsoNormal"'))=== false) 
		|| 
		!(strpos(strtolower($strHtml), strtolower('Microsoft Word'))=== false)
	){
		return true;
	}else{
		return false;
	}
}
function construirURL($url){
	$resultado = $url;
	if( strpos(strtolower($url), strtolower("http"))=== false ){
		if( strpos($url,'/')=== false ){
			$resultado = 'http://'.$url;
		}
	}
	return $resultado;
}
function comprobar_en_directorio($directorio){
	$resultado = false;
	if(strlen($directorio)>0){
		$directorio = strtolower($directorio);
		if( isset($_SERVER['PHP_SELF']) ){
			if ( strpos(strtolower($_SERVER['PHP_SELF']), $directorio) === false) {
			}else{
				$resultado = true;
			}
		}elseif( isset($_SERVER['REQUEST_URI']) ){
			if( strtolower($_SERVER['REQUEST_URI'])== $directorio ){
				$resultado = true;
			}
		}	
	}
	return $resultado;
}
function iff ($condicion,$true,$false){ 
	if ($condicion) { 
		return $true; 
	} else { 
		return $false; 
	}
}
function iif($condicion,$true,$false){
	/*Alias de iff*/
	return iff($condicion,$true,$false);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES: CHARSET																								/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function eliminaAcentos($cadena){
	$tofind = "ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
	$replac = "AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
	return(strtr($cadena,$tofind,$replac));
}

function stripAccents($String)
{
	$String = str_replace(array('á','à','â','ã','ª','ä'),'a',$String);
	$String = str_replace(array('Á','À','Â','Ã','Ä'),'A',$String);
	$String = str_replace(array('Í','Ì','Î','Ï'),'I',$String);
	$String = str_replace(array('í','ì','î','ï'),'i',$String);
	$String = str_replace(array('é','è','ê','ë'),'e',$String);
	$String = str_replace(array('É','È','Ê','Ë'),'E',$String);
	$String = str_replace(array('ó','ò','ô','õ','ö','º'),'o',$String);
	$String = str_replace(array('Ó','Ò','Ô','Õ','Ö'),'O',$String);
	$String = str_replace(array('ú','ù','û','ü'),'u',$String);
	$String = str_replace(array('Ú','Ù','Û','Ü'),'U',$String);
	$String = str_replace(array('{','}','[','^','´','`','¨','~',']'),'',$String);
	$String = str_replace('ç','c',$String);
	$String = str_replace('Ç','C',$String);
	$String = str_replace('ñ','n',$String);
	$String = str_replace('Ñ','N',$String);
	$String = str_replace('Ý','Y',$String);
	$String = str_replace('ý','y',$String);
		
	return $String;
}

function elimina_especialchars($cadena){
	$cadena = rtrim($cadena);
	$cadena = trim($cadena);
	//$cadena = stripAccents($cadena);
	$cadena = eliminaAcentos($cadena);
	//$cadena = quitarAcutes($cadena);
	$cadena = purgeHTML($cadena);
	$cadena = str_replace(" ","_",$cadena);
	
	//return preg_replace("/[^A-Za-z0-9\-]/", "", $cadena);
	return preg_replace("/[^A-Za-z0-9]/", "", $cadena);
}
function elimina_lang_del_query($server_query_string){
	$server_query_string = str_replace('&amp;lang=ES','',$server_query_string);
	$server_query_string = str_replace('&amp;lang=EN','',$server_query_string);
	$server_query_string = str_replace('&amp;lang=FR','',$server_query_string);
	$server_query_string = str_replace('&amp;lang=DE','',$server_query_string);
	
	$server_query_string = str_replace('&lang=ES','',$server_query_string);
	$server_query_string = str_replace('&lang=EN','',$server_query_string);
	$server_query_string = str_replace('&lang=FR','',$server_query_string);		
	$server_query_string = str_replace('&lang=DE','',$server_query_string);	
		
	$server_query_string = str_replace('?lang=ES','',$server_query_string);
	$server_query_string = str_replace('?lang=EN','',$server_query_string);
	$server_query_string = str_replace('?lang=FR','',$server_query_string);
	$server_query_string = str_replace('?lang=DE','',$server_query_string);
	
	$server_query_string = str_replace('lang=ES','',$server_query_string);
	$server_query_string = str_replace('lang=EN','',$server_query_string);
	$server_query_string = str_replace('lang=FR','',$server_query_string);	
	$server_query_string = str_replace('lang=DE','',$server_query_string);	
	
	return $server_query_string;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES: FECHAS																					/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function comprobarFecha($strFecha){
	$i=0;
	$arrFecha[$i] = intval(strtok($strFecha,"/"));
	while($tok=strtok("/")){
		$i++;
		$arrFecha[$i] = intval($tok);
	}
	$bFecha = true;
	if($i==2){
		$dia = $arrFecha[0];
		$mes = $arrFecha[1];
		$anio = $arrFecha[2];

		// Compruebo el año
		if($anio<0 OR $anio>4000){
			$bFecha = false;
		}else{
			if($mes>0 AND $mes<13){
				if($mes==1 OR $mes==3 OR $mes==5 OR $mes==7 OR $mes==8 OR $mes==10 OR $mes==12){
					// El dia estara entre 1 y 31
					if($dia>0 AND $dia<32){
						$bFecha = true;
					}else{
						$bFecha = false;
					}
				}else{
					if($mes==4 OR $mes==6 OR $mes==9 OR $mes==11){
						// El dia estara entre 1 y 30
						if($dia>0 AND $dia<31){
							$bFecha = true;
						}else{
							$bFecha = false;
						}
					}else{
						if($mes==2){
							// El dia estara entre 1 y 28
							if($dia>0 AND $dia<29){
								$bFecha = true;
							}else{
								// Si es bisiesto me vale el 29
								if($anio/4==intval($anio/4) AND $dia==29){
									$bFecha = true;
								}else{
									$bFecha = false;
								}
							}
						}else{
							$bFecha = false;
						}
					}
				}
			}else{
				$bFecha = false;
			}
		}
	}else{
		$bFecha = false;
	}
	if($bFecha){
		return $arrFecha;
	}else{
		return false;
	}
}
function pintarCamposHora($fecha_seleccionada=0,$idhora='hora',$idminutos='minutos'){
	$hora 	= 0;
	$minuto = 0;
	
	if(strtotime($fecha_seleccionada)!=-1){
		//Si es correcta la fechas
		$hora 	= date('G',$fecha_seleccionada);	//Hora de 0 a 23
		$minuto = date('i',$fecha_seleccionada);	//Minutos de 00 a 59
	}

	
	$strHtml = '';
	$strHtml .= '<select name="'.$idhora.'" id="'.$idhora.'">';
	$strHtml .= '	<optgroup label="hh"></optgroup>';
	for($j=0;$j<24;$j++){
		if(strlen($j)!=1){
			if($hora==$j){
				$strHtml .= '	<option value="'.$j.'" selected="selected">'.$j.'</option>';
			}else{
				$strHtml .= '	<option value="'.$j.'">'.$j.'</option>';
			}
		}else{
			if($hora==$j){
				$strHtml .= '	<option value="0'.$j.'" selected="selected">0'.$j.'</option>';
			}else{
				$strHtml .= '	<option value="0'.$j.'">0'.$j.'</option>';
			}
		}
	}
	$strHtml .= '</select> h. ';
	
	$strHtml .= '<select name="'.$idminutos.'" id="'.$idminutos.'">';
	$strHtml .= '	<optgroup label="mm"></optgroup>';
	for($j=0;$j<60;$j++){
		if(strlen($j)!=1){
			if($minuto==$j){
				$strHtml .= '	<option value="'.$j.'" selected="selected">'.$j.'</option>';
			}else{
				$strHtml .= '	<option value="'.$j.'">'.$j.'</option>';
			}
		}else{
			if($minuto=='0'.$j){
				$strHtml .= '	<option value="0'.$j.'" selected="selected">0'.$j.'</option>';
			}else{
				$strHtml .= '	<option value="0'.$j.'">0'.$j.'</option>';
			}
		}
	}
	$strHtml .= '</select> m.';
	
	return $strHtml;
}
function fechaUnix($strFecha){
	$i=0;
	$arrFecha = comprobarFecha($strFecha);
	if(is_array($arrFecha)){
		$intFecha = strtotime($arrFecha[1]."/".$arrFecha[0]."/".$arrFecha[2]);
		return $intFecha;
	}else{
		return -1;
	}
}
function fechaNormal($sFecha){
	$strFecha = explode(' ', $sFecha);
	$arrFecha = explode('-', $strFecha[0]);
	$arrHora = explode(':', $strFecha[1]);
	
	//$arrFecha = explode('-', $strFecha);
	$m_strFecha = $arrFecha[2].'/'.$arrFecha[1].'/'.$arrFecha[0];
	return $m_strFecha;
}
function fechaHoraNormal($sFecha){
	$strFecha = explode(' ', $sFecha);
	$arrFecha = explode('-', $strFecha[0]);
	$arrHora = explode(':', $strFecha[1]);
	$m_strFecha = $arrFecha[2].'/'.$arrFecha[1].'/'.$arrFecha[0].' '.$arrHora[0].':'.$arrHora[1];
	return $m_strFecha;
}
function fechaMySQL($strFecha){
	$arrFecha = explode('/', $strFecha);
	$m_strFecha = $arrFecha[2].'-'.$arrFecha[1].'-'.$arrFecha[0];
	return $m_strFecha;
}
function fechaHoraMySQL($sFecha){
	$strFecha = explode(' ', $sFecha);
	$arrFecha = explode('/', $strFecha[0]);
	$arrHora = explode(':', $strFecha[1]);
	$m_strFecha = $arrFecha[2].'-'.$arrFecha[1].'-'.$arrFecha[0].' '.$arrHora[0].':'.$arrHora[1];
	return $m_strFecha;
}
function fechaMaquetada($strFecha, $separador){
	$arrFecha = explode('-', $strFecha);
	$m_strFecha = $arrFecha[2].$separador.$arrFecha[1].$separador.$arrFecha[0];
	return $m_strFecha;
}
function fechaHoraCorta($sFecha){
	$strFecha = fechaHoraMySQL($sFecha);
	$intFecha = strtotime($strFecha);
	$m_strFecha = date("H:i",$intFecha);
	return $m_strFecha;
}
function horaEn24($sHora){
	$horaTrozo = explode(" ", $sHora);
	$hora = explode(":", $horaTrozo[0]);
	$hora24 = $hora[0];
	if($horaTrozo[1]=="PM")
		$hora24 = str_pad(intval($hora[0])+12, 2, "0").":".$hora[1];
	
	return $hora24;
}
function horaEn12($sHora){
	$hora = explode(":", $sHora);
	$hora12 = $hora[0].":".$hora[1]." AM";
	if(intval($hora[0]) > 12)
		$hora12 = str_pad(intval($hora[0])-12, 2, "0").":".$hora[1]." PM";

	return $hora12;
}
function fechaConTexto(){
	$intFecha = strtotime($time);
	return fechaNombreDelDia($intFecha).', '.date("j",$intFecha).' '.strtolower(fechaNombreDelMes(date("n",$intFecha))).', '.date("Y",$intFecha);
}
function fechaConTexto_sin_yyyy($intFecha){
	return fechaNombreDelDia($intFecha).' '.date("j",$intFecha).' '.TXT_de.' '.fechaNombreDelMes(date("n",$intFecha));
}
function FechaInicioDia(){
	// Devuelve la fecha del dia actual a primera hora de la mañana
	return (intval((time()/86400))*86400)-(86400);
}
function FechaFinDia(){
	return ((intval((time()/86400))*86400));
}
function FechaInicioMes($FechaActual){
	if(strlen($FechaActual)>0 && comprobarFecha($FechaActual)){
		list($day, $month, $year) = split('[/.-]', fechaNormal($FechaActual) );
	}else{
		list($day, $month, $year) = split('[/.-]', fechaNormal(time()) );
	}
	
	return fechaUnix("01/".intval($month)."/".intval($year));
}
function FechaFinMes($FechaActual){
	if(strlen($FechaActual)>0 && comprobarFecha($FechaActual)){
		list($day, $month, $year) = split('[/.-]', fechaNormal($FechaActual) );
	}else{
		list($day, $month, $year) = split('[/.-]', fechaNormal(time()) );
	}
	
	if(intval($month)==12){
		return fechaUnix("01/01/".(intval($year)+1));
	}else{
		return fechaUnix("01/".(intval($month)+1)."/".$year);
	}
}

function NombreMes($mes)
{
	setlocale(LC_TIME, 'spanish');
	$nombre=strftime("%B",mktime(0, 0, 0, $mes, 1, 2000));
	
	return ucfirst($nombre);
}

function NombreDiaSemana($mes)
{
	setlocale(LC_TIME, 'spanish');
	$nombre=strftime("%A",mktime(0, 0, 0, $mes, 1, 2000));

	return utf8_encode(ucfirst($nombre));
}

function calcularEdad($intTime){
	//fecha actual
	$dia=date("j");
	$mes=date("n");
	$ano=date("Y");
	 
	//fecha de nacimiento 
	$dianaz=date("j",$intTime);
	$mesnaz=date("n",$intTime);
	$anonaz=date("Y",$intTime);
	 
	//si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual 
	if (($mesnaz == $mes) && ($dianaz > $dia)) {
		$ano=($ano-1); 
	}
	 
	//si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual
	if ($mesnaz > $mes) {
		$ano=($ano-1);
	}
	 
	//ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad
	$edad=($ano-$anonaz);
	 
	return $edad;
}
function categorizaFecha($fechaDada){
	$strResultado="";
	
	if(intval($fechaDada)>0){
		$fechaAhora=time();
		
		$unDia 		= FechaInicioDia();
		$unaSemana	= 60*60*24*7;
		
		$fechaAyer			= $unDia;
		$fechaSemanaAnterior= $fechaAhora - $unaSemana;
		
		if($fechaDada>$fechaAyer){
			$strResultado = TXT_hoy_a_las." ".fechaHoraCorta($fechaDada);
		}elseif($fechaAyer>$fechaDada && $fechaDada>$fechaSemanaAnterior){
			$strResultado = fechaNombreDelDia($fechaDada)."  ".TXT_a_las." ".fechaHoraCorta($fechaDada);
		}else{
			$strResultado = fechaHoraNormal($fechaDada);
		}
	}
	
	return $strResultado;
}

function add_date($givendate, $day=0, $mth=0, $yr=0) {
	$cd = strtotime($givendate);
	$newdate = date('Y-m-d h:i:s', mktime(date('h',$cd),
			date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
			date('d',$cd)+$day, date('Y',$cd)+$yr));
	return $newdate;
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//UTILES: MAILING																								/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function mail_attach($to,$from,$subject,$body,$fname="",$data="",$priority=3,$type="Application/Octet-Stream"){
	$cabeceras  = "MIME-Version: 1.0\n";
	$cabeceras .= "Content-type: ".CONTENT_TYPE."\n";
	$cabeceras .= "From: ".$from."\n";
	return @mail($to,$subject,$body,$cabeceras);
}
function enviarPhpMailer($mailpara,$maildesde,$Asunto,$Cuerpo,$CuerpoAlt="",$vCC="",$vBBC="",$respuestaPara="",$nombreAdjunto="",$rutaAdjunto=""){
	//if(ENTORNO==0) return false;
	
	if(strlen($mailpara)>0 && strlen($maildesde)>0 ){
		$array_datos= explode("@",$mailpara);
		$size=sizeof($array_datos);
		if($size==2){
			if(strlen($Asunto)>0 && strlen($Cuerpo)>0){
				//Crear objeto
				require_once (RUTA_RAIZ."classes/phpmailer/class.phpmailer.php");
				$objMail = new phpmailer();
				
				//Configuracion
				//$objMail->PluginDir = PHPMAILER_INCLUDES;
				$objMail->Mailer 	= PHPMAILER_TIPOHOST;
				$objMail->Host 		= PHPMAILER_HOST;
				$objMail->Timeout	= 30;
				if(strpos(PHPMAILER_HOST, "gmail") > 0){
					$objMail->Port = PHPMAILER_PORT;
					$objMail->SMTPSecure = 'ssl';  //habilita la encriptacion SSL
				}
				
				//Autenticacion
				$objMail->SMTPAuth = true;
				$objMail->Username = PHPMAILER_USER; 
				$objMail->Password = PHPMAILER_PWD;
				
				//Parametrizacion From
				$objMail->From 			= $maildesde;
				$objMail->FromName 	= $maildesde;
				
				//ReplyTo,CC,BCC
				if(strlen($respuestaPara)>0){
				  $respuestaPara_subarray= explode("@",$respuestaPara);
				  if(sizeof($respuestaPara_subarray)==2){
				  	$objMail->AddReplyTo($respuestaPara);
				  }
				}
				if (is_array($vCC)) {
					foreach ($vCC as $valueCC) {  
						$vCC_subarray= explode("@",$valueCC);
						if(sizeof($vCC_subarray)==2){
						 $objMail->AddCC($valueCC);
						}
					}  
				} 	
				if (is_array($vBBC)) {
					foreach ($vBBC as $valueBBC) {  
						$vBBC_subarray= explode("@",$valueBBC);
						if(sizeof($vBBC_subarray)==2){
						 $objMail->AddBCC($valueBBC);
						}
					}  
				} 				
				//To, asunto y cuerpo
				  $objMail->AddAddress($mailpara);
				  $objMail->Subject = $Asunto;
				  $objMail->Body = $Cuerpo;//Formato Html
				  if(strlen($CuerpoAlt)>0){//Formato Texto
				  	$objMail->AltBody = $CuerpoAlt; 
				  }else{
				  	$objMail->AltBody = "Este mensaje se encuentra codificado en formato HTML, por favor, modifique la parametrización de su cliente de correo";
				  }
		
				//Adjuntos
				if(strlen($rutaAdjunto)>0 && strlen($nombreAdjunto)>0){
					$objMail->AddAttachment($rutaAdjunto, $nombreAdjunto); 
				}
			
				//Enviando....
				$exito = false;
				$exito = $objMail->Send();
				$intentos=1; 
				while ((!$exito) && ($intentos < 3)) {
					sleep(2);
					//echo $objMail->ErrorInfo."<br />";
					$exito = $objMail->Send();
					$intentos=$intentos+1;	
				}
				return $exito; 
			}else{
				//echo "Parametros incorrectos: cuerpo o asunto vacios"."<br />"; 
				return false;
			}
		}else{
			//echo "Parametros incorrectos: posible code injection"."<br />";
			return false;
		}
	}else{
		//echo "Parametros incorrectos: mailto"."<br />";
		return false;
	}
} 
/*
function enviarPhpMailerBoletin($arrayTo,$Asunto,$Cuerpo){
	//Crear objeto
	require_once (RUTA_RAIZ."classes/phpMailer/class.phpmailer.php");
	$objMail = new phpmailer();
	
	//Configuracion
	$objMail->Mailer = PHPMAILER_TIPOHOST;
	$objMail->Host 	 = PHPMAILER_HOST;
	$objMail->Timeout= 30;
	//Autenticacion
	$objMail->SMTPAuth = true;
	$objMail->Username = PHPMAILER_USER; 
	$objMail->Password = PHPMAILER_PWD;
	//Parametrizacion From
	$objMail->From 		= EMAIL_FROM_BOLETIN;
	$objMail->FromName 	= EMAIL_FROM_BOLETIN;
	
	//ReplyTo,CC,BCC
	$objMail->AddReplyTo(EMAIL_FROM_BOLETIN);
				
	if (is_array($arrayTo)) {
		foreach ($arrayTo as $userEmail) {  
			$objMail->AddAddress($userEmail);
	  		$objMail->Subject 	= $Asunto;
	  		$objMail->Body 		= $Cuerpo;
			$objMail->AltBody 	= "Este mensaje se encuentra codificado en formato HTML, por favor, modifique la parametrizacin de su cliente de correo";
			
		 	if( !$objMail->Send() ){
       		 	echo "There has been a mail error sending to ".$userEmail."<br>";
			 	echo "Mailer Error: ".($objMail->ErrorInfo)."<br>";
			}else{
				echo "Send Ok: ".$userEmail."<br>";
			}
			// Clear all addresses and attachments for next loop
			$objMail->ClearAddresses();
			$objMail->ClearAttachments();
		}  
	} 
	
	unset($objMail);				
}
*/
function enviarPhpMailerBoletin($arrayTo,$Asunto,$Cuerpo){
	require_once (RUTA_RAIZ."classes/phpMailer/class.phpmailer.php");
	
	$tamanio_pack 		= 25;
	$envio_time_sleep 	= 6;
	$envio_intentos 	= 3;
	
	//Crear objeto
	$objMail = new phpmailer();
	$objMail->Mailer = PHPMAILER_TIPOHOST;
	$objMail->Host 	 = PHPMAILER_HOST;
	$objMail->Timeout= 30;
	$objMail->SMTPAuth = true;
	$objMail->Username = PHPMAILER_USER; 
	$objMail->Password = PHPMAILER_PWD;
	$objMail->From 		= EMAIL_FROM_BOLETIN;
	$objMail->FromName 	= EMAIL_FROM_BOLETIN;
	$objMail->AddReplyTo(EMAIL_FROM_BOLETIN);
	$objMail->Subject 	= $Asunto;
	$objMail->Body 		= $Cuerpo;
	$objMail->AltBody 	= "Este mensaje se encuentra codificado en formato HTML, por favor, modifique la parametrizacin de su cliente de correo";
	
	if (is_array($arrayTo)) {
		$count 	= count($arrayTo);
		$cuenta = 0;

		while ( $cuenta < $count ) {
			$userEmail = $arrayTo[$cuenta];

			if(!isset($objMail)){
				$objMail = new phpmailer();
				$objMail->Mailer = PHPMAILER_TIPOHOST;
				$objMail->Host 	 = PHPMAILER_HOST;
				$objMail->Timeout= 30;
				$objMail->SMTPAuth = true;
				$objMail->Username = PHPMAILER_USER; 
				$objMail->Password = PHPMAILER_PWD;
				$objMail->From 		= EMAIL_FROM_BOLETIN;
				$objMail->FromName 	= EMAIL_FROM_BOLETIN;
				$objMail->AddReplyTo(EMAIL_FROM_BOLETIN);
				$objMail->Subject 	= $Asunto;
				$objMail->Body 		= $Cuerpo;
				$objMail->AltBody 	= "Este mensaje se encuentra codificado en formato HTML, por favor, modifique la parametrizacin de su cliente de correo";
			}
			$objMail->AddBCC($userEmail);
			echo $userEmail.",";
			
			if($cuenta>0 && $cuenta%$tamanio_pack==0){
				echo "<br /><strong>ENVIANDO PACK (cuenta:".$cuenta.",tamanio_pack:".$tamanio_pack.")...</strong><br />";
				
				$exito = false;
				if(ENTORNO!=0) $exito = $objMail->Send(); //ENVIO

				$intentos=1; 
				while ((!$exito) && ($intentos < $envio_intentos)) {
					echo "<font color=\"FF0000\">Mailer ERROR (pack ".$cuenta.", intento ".$intentos."): ".($objMail->ErrorInfo)."</font><br>";	
					if($intentos < $envio_intentos){
						echo "<font color=\"3300FF\">Volvemos a intentar el envio del pack ".$cuenta." pasados ".$envio_time_sleep." segundos...</font><br>";	
					}	
					sleep($envio_time_sleep);		
					flush();	
						
					if(ENTORNO!=0) $exito = $objMail->Send(); //ENVIO
					
					$intentos=$intentos+1;	
				}
				if($exito){
					echo "<font color=\"339900\">Send Ok: pack ".$cuenta.", intento ".$intentos."</font><br>";
				}
				
				$objMail->ClearAddresses();
				unset($objMail);
				echo "<br>";
				flush();
			}
			$cuenta++;
		}
		
		if(!isset($objMail)){
			$objMail = new phpmailer();
			$objMail->Mailer = PHPMAILER_TIPOHOST;
			$objMail->Host 	 = PHPMAILER_HOST;
			$objMail->Timeout= 30;
			$objMail->SMTPAuth = true;
			$objMail->Username = PHPMAILER_USER; 
			$objMail->Password = PHPMAILER_PWD;
			$objMail->From 		= EMAIL_FROM_BOLETIN;
			$objMail->FromName 	= EMAIL_FROM_BOLETIN;
			$objMail->AddReplyTo(EMAIL_FROM_BOLETIN);
			$objMail->Subject 	= $Asunto;
			$objMail->Body 		= $Cuerpo;
			$objMail->AltBody 	= "Este mensaje se encuentra codificado en formato HTML, por favor, modifique la parametrizacin de su cliente de correo";
		}
		
		//Ultimo de los envios...
		echo "<br /><strong>ENVIANDO ÚLTIMO PACK...</strong><br />";
		
		$objMail->AddBCC(EMAIL_FROM_BOLETIN);
		$exito = false;

		if(ENTORNO!=0) $exito = $objMail->Send();//ENVIO

		$intentos=1; 
		while ((!$exito) && ($intentos < $envio_intentos)) {
			echo "<font color=\"FF0000\">Mailer ERROR (pack último, intento ".$intentos."): ".($objMail->ErrorInfo)."</font><br>";	
			if($intentos < $envio_intentos){
				echo "<font color=\"3300FF\">Volvemos a intentarlo pasados ".$envio_time_sleep." segundos...</font><br>";	
			}	
			sleep($envio_time_sleep);		
			flush();	

			if(ENTORNO!=0) $exito = $objMail->Send();//ENVIO

			$intentos=$intentos+1;	
		}
		echo "<br>";
		if($exito){
			echo "<font color=\"339900\">Send Ok: pack último, intento ".$intentos."</font><br>";
		}
		echo "<br>";
		echo "<br>";
		
		flush();
	} 
	unset($objMail);				
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//MENSAJES Y MAQUETACION																					/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function maquetarError($strError="", $bCerrar=1){
	$resultado ="";
	if(strlen($strError)>0){
		$resultado .= "<div class=\"alert alert-block alert-error fade in\" style=\"text-align:center;\">"."\n";
		if($bCerrar==1){
				$resultado .= "		<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>"."\n";
		}
		$resultado .= "		<h4 class=\"alert-heading\">".TextoDeIdioma("ERROR")."! ".TextoDeIdioma("Lo_sentimos")."!</h4>"."\n";
		$resultado .= "		<p>".$strError."</p>"."\n";
		$resultado .= "</div>"."\n";
	}
	return $resultado;
}
function maquetarAviso($strAviso="", $bCerrar=1){
	$resultado ="";
	if(strlen($strAviso)>0){
		$resultado .= "<div class=\"alert alert-block alert-warning fade in\" style=\"text-align:center;\">"."\n";
		if($bCerrar==1){
				$resultado .= "		<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>"."\n";
		}
		$resultado .= "		<h4 class=\"alert-heading\">".TextoDeIdioma("ATENCION")."!</h4>"."\n";
		$resultado .= "		<p>".$strAviso."</p>"."\n";
		$resultado .= "</div>"."\n";
	}
	return $resultado;
}
function maquetarCorrecto($strInfo="", $bCerrar=1){
	$resultado = "";
	if(strlen($strInfo)>0){
		$resultado .= "<div class=\"alert alert-block alert-success fade in\" style=\"text-align:center;\">";
		if($bCerrar==1){
				$resultado .= "		<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>"."\n";
		}
		$resultado .= "		<h4 class=\"alert-heading\">".TextoDeIdioma("CORRECTO")."!</h4>"."\n";
		$resultado .= "		<p>".$strInfo."</p>"."\n";
		$resultado .= "</div>";
	}
	return $resultado;
}
function maquetarInfo($strInfo="", $bCerrar=1){
	$resultado = "";
	if(strlen($strInfo)>0){
		 $resultado .= "<div class=\"alert alert-block alert-info fade in\" style=\"text-align:center;\">";
		 if($bCerrar==1){
		 		$resultado .= "		<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>"."\n";
		 }
		 $resultado .= "		<h4 class=\"alert-heading\">".TextoDeIdioma("INFORMACION").":</h4>"."\n";
		 $resultado .= "		<p>".$strInfo."</p>"."\n";
		 $resultado .= "</div>";
	}
	return $resultado;
}

function TextoDeIdiomaConst($clave){
	$constanteTexto = 'TXT_'.$clave; 
	
	return constant($constanteTexto);
}

function TextoDeIdioma($tag, $param = "")
{
	//if($_SESSION["sPANEL_Idioma"] != ""){
		/*if(ENTORNO==0){// Desarrollo
			$ficheroXML = RAIZ_FILE_SYSTEM.'\funciones\lenguaje-'.$_SESSION["sPANEL_Idioma"].'.xml';
		}else{
			$ficheroXML = RAIZ_FILE_SYSTEM.'/funciones/lenguaje-'.$_SESSION["sPANEL_Idioma"].'.xml';
		}*/
	
		//SE PONEN FIJOS YA QUE NO DESEAN QUE LA INTERFAZ CAMBIE DE IDIOMA...
		//if(ENTORNO==0){// Desarrollo
			$ficheroXML = RAIZ_FILE_SYSTEM.RUTA_ADM.'lenguaje-es.xml';
		/*}else{
			$ficheroXML = RAIZ_FILE_SYSTEM.RUTA_ADM.'lenguaje-es.xml';
		}*/
		//echo $ficheroXML;
		/*$doc = simplexml_load_file($ficheroXML);
		$texto = $doc->Panel_de_Control[0];*/
		
		$doc = new DOMDocument;
		$doc->preserveWhiteSpace = false;
		$doc->load($ficheroXML);
		$xpath = new DOMXPath($doc);

		$xmlQuery = "//".$tag."";
    $result = $xpath->query($xmlQuery);
    $texto = $result->item(0)->nodeValue;
    $k=0;
		/* Si hay mas de 1 nodo...   
 		foreach ($result as $nodo){
    	//print_r($nodo);
    	if($k==0)
    		$texto .= $nodo->nodeValue;
    	$k++;
    }
    */
		$palabra = "";
		if($texto!=""){
			$palabra = $texto;
		}
		
		if($param!=""){
			$palabra = str_replace("%1", $param, $texto);
		}
		if($palabra==""){
			$palabra = $tag;
		}
		
		return $palabra;
	//}
}

function TextoDeIdiomaFront($tag, $param = "")
{
	if($_SESSION["sFRONT_Idioma"] != ""){
		
		$texto = ExecQueryValue("SELECT Texto FROM tbIdiomaTexto WHERE Tag = '".$tag."'");
		
		$palabra = "";
		if($texto!=""){
			$palabra = $texto;
		}

		if($param!=""){
			$palabra = str_replace("%1", $param, $texto);
		}
		return $palabra;
	}
}

function TextoDeAccionForm($accion){
	switch ($accion){
		case  ACCION_NUEVO:
			return TextoDeIdioma("Alta_de");
			break;
		case  ACCION_EDITAR:
			return TextoDeIdioma("Edicion_de");
			break;
		case  ACCION_BORRAR:
			return TextoDeIdioma("Borrado_de");
			break;
	}
	
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//PAGINACION
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function MostrarPaginacion($actual, $total, $por_pagina, $enlace) 
{
	$html = "";
	$numPagsVisibles = 9; // Siempre IMPAR
	$numPagsVisTrozo = ($numPagsVisibles-1) / 2;
	if($total > $por_pagina){
		$parametros = "&pag=";
		//$parametros = "";
		//$total_paginas = (($total / $por_pagina) + ceil($total % $por_pagina));
		$total_paginas = ceil($total / $por_pagina);
		//$html = "actual:".$actual ." - total:". $total_paginas;
	
		$p_inicio = 1;
		$p_final = $numPagsVisibles;
		$anterior = $actual - 1;
		$posterior = $actual + 1;
		if ($actual > $numPagsVisTrozo && $actual <= ($total_paginas - $numPagsVisTrozo))
		{
			$p_inicio = $actual - $numPagsVisTrozo;
			$p_final = $actual + $numPagsVisTrozo;
		}
		if ($actual > ($total_paginas - $numPagsVisTrozo))
		{
			$p_inicio = $total_paginas - ($numPagsVisTrozo*2);
			$p_final = $total_paginas;
		}
		if($p_inicio <= 0){
			$p_inicio = 1;
		}
	
		if ($p_final > $total_paginas){
			$p_final = $total_paginas;
		}
		
		//$html = "actual:".$actual ." - total:". $total_paginas." p_inicio: ".$p_inicio." p_final: ".$p_final;
		if ($total_paginas > 1)
		{
			//$html = "<div class=\"btn-toolbar\">";
			$html .= "<div class=\"btn-group\">";
			if ($actual > 1)
			{
				$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros."1"."'\" class=\"btn\">".TextoDeIdioma("Inicio")."</button>";
			}
			else
			{
				$html .= "<button disabled type=\"button\" class=\"btn\">".TextoDeIdioma("Inicio")."</button>";
			}
			if ($actual > ($numPagsVisibles))
			{
				$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.($actual - ($numPagsVisibles+1))."'\" class=\"btn\"><<</button>";
			}else{
				$html .= "<button disabled type=\"button\" class=\"btn\"><<</button>";
			}
			if ($actual > 1){
				$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.$anterior."'\" class=\"btn\"><</button>";
			}else{
				$html .= "<button disabled type=\"button\" class=\"btn\"><</button>";
			}
			for ($i = $p_inicio; $i <= $p_final; $i++)
			{
				if($i==$actual)
				{
					$html .= "<button type=\"button\" class=\"btn btn-inverse\">".$actual."</button>";
				}else{
					$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.$i."'\" class=\"btn\">".$i."</button>";
				}
			}
	    if ($actual < $total_paginas){
				$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.$posterior."'\" class=\"btn\">></button>";
			}else{
				$html .= "<button disabled type=\"button\" class=\"btn\">></button>";
			}
	    if ($actual < ($total_paginas - ($numPagsVisibles))){
				$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.($actual + ($numPagsVisibles+1))."'\" class=\"btn\">>></button>";
			}else{
				$html .= "<button disabled type=\"button\" class=\"btn\">>></button>";
			}
			if ($actual < $total_paginas)
	    {
	    	$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.$total_paginas."'\" class=\"btn\">".TextoDeIdioma("Final")."</button>";
			}else{
	    	$html .= "<button disabled type=\"button\" class=\"btn\">".TextoDeIdioma("Final")."</button>";
	    }
			$html .= "</div>";
			//$html .= "</div>";
		}
	}
	return $html;
}


function CampoCabeceraTablaListado($campo, $txtCabecera, $orden, $paginaHtml, $paramsFiltro, $htmlTipoOrdenASC, $htmlTipoOrdenDESC, $alineacion)
{
	$colSel = "";

	if ($orden == $campo)
	{
		$colSel = "style=\"background-color:lightgray;\"";
	}else{
		$colSel = "";
  }

  return "<th ".$colSel." style=\"text-align:".$alineacion."\" nowrap>".$txtCabecera."&nbsp;<a href=\"".$paginaHtml."?orden=".$campo."&tipoOrden=ASC&".$paramsFiltro."\">".$htmlTipoOrdenASC."</a>&nbsp;<a href=\"".$paginaHtml."?orden=".$campo."&tipoOrden=DESC&".$paramsFiltro."\">".$htmlTipoOrdenDESC."</a></th>";
}

function PaginacionTablaListado($colspan, $totalRegs, $p_inicio, $p_final, $pag, $regsPorPag, $urlPag)
{
	$strHtml = "";
	if (totalRegs > 0)
	{
		$strHtml .= "<tfoot>";
		$strHtml .= "	<tr>";
		$strHtml .= "		<td colspan=\"2\" style=\"text-align:left;\">".TextoDeIdioma("Total_Registros").": ".$totalRegs.", ".TextoDeIdioma("mostrando_del")." ".$p_inicio." ".TextoDeIdioma("al")." ".$p_final."</td>";
		$strHtml .= "		<td colspan=\"".$colspan."\" style=\"text-align:right;\">".MostrarPaginacion($pag, $totalRegs, $regsPorPag, $urlPag)."</td>";
		$strHtml .= "	</tr>";
		$strHtml .= "</tfoot>";
	}
	return $strHtml;
}

function PaginacionListado($colspan, $totalRegs, $p_inicio, $p_final, $pag, $regsPorPag, $urlPag)
{
	$strHtml = "";
	if ($totalRegs > 0)
	{
		//$strHtml .= "<div class=\"pagination pagination-custom pull-left\">";
		$strHtml .= "<div class=\"pull-left\">";
		//$strHtml .= "		<span class=\"text-align:left;\">".TextoDeIdioma("Total_Registros").": ".$totalRegs.", ".TextoDeIdioma("mostrando_del")." ".$p_inicio." ".TextoDeIdioma("al")." ".$p_final."</span>&nbsp;&nbsp;";
		$strHtml .= "		".MostrarPaginacion($pag, $totalRegs, $regsPorPag, $urlPag)."";
		$strHtml .= "</div>";
	}
	return $strHtml;
}

function urls_amigables($url) 
{
	// Tranformamos todo a minusculas
	$url = strtolower($url);
	//Rememplazamos caracteres especiales latinos
	$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
	$repl = array('a', 'e', 'i', 'o', 'u', 'n');
	$url = str_replace ($find, $repl, $url);
	// Añaadimos los guiones
	$find = array(' ', '&', '\r\n', '\n', '+');
	$url = str_replace ($find, '-', $url);
	// Eliminamos y Reemplazamos demás caracteres especiales
	$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
	$repl = array('', '-', '');
	$url = preg_replace ($find, $repl, $url);
	
	return $url;
}
?>