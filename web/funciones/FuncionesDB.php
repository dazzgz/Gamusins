<?
function Conecta(){
	if($conexion=mysql_connect(DB_HOST, DB_USER, DB_PASS)){
		mysql_query("SET character_set_results=utf8", $conexion);
		mb_language('uni');
		mb_internal_encoding('UTF-8');
		mysql_select_db(DB_HOST, $conexion);
		mysql_query("set names 'utf8'", $conexion);
		
		return $conexion;
	}else{
//		@mail(EMAIL_ERROR,"Error en la Web ".SERVER,"Error en la conexion con la base de datos.");
		/*echo "DB_HOST:".DB_HOST;
		echo "<br />DB_USER:".DB_USER;
		echo "<br />DB_PASS".DB_PASS;*/
		trigger_error("Error: no se puede conectar con la base de datos.",E_USER_ERROR);
		return $conexion;
	}
}

function ExecPA($consulta, $conexion=""){
	if($conexion=="" || $conexion==0){
		$conexion=Conecta();
	}
	if($consulta==""){
		trigger_error("Error: la cadena consulta no puede estar vacia.", E_USER_ERROR);
	}else{
		mysql_select_db(DB_NAME);
		$resultado = mysql_query($consulta, $conexion);
		if($resultado==0){
			// Error en SQL
			trigger_error("Error: el SQL no es correcto.<br />".$consulta."<br />".mysql_error(), E_USER_ERROR);
		}else{
			// Devuelvo el resultado
			return $resultado;
		}
	}
}

function ExecQuerySingle($consulta, $conexion=""){
	if($conexion=="" || $conexion==0){
		$conexion=Conecta();
	}
	if($consulta==""){
		trigger_error("Error: la cadena consulta no puede estar vacia.", E_USER_ERROR);
	}else{
		mysql_select_db(DB_NAME);
		$resultado = mysql_query($consulta, $conexion);
		if($resultado==0){
			// Error en SQL
			trigger_error("Error: el SQL no es correcto.<br />".$consulta."<br />".mysql_error(), E_USER_ERROR);
		}else{
			// Devuelvo el resultado
			return mysql_fetch_array($resultado);
			
			//A RECUPERAR CON: $resultado["NombreDeCampo"];
		}
	}
}

function ExecQueryValue($consulta, $conexion=""){
	if($conexion=="" || $conexion==0){
		$conexion=Conecta();
	}
	if($consulta==""){
		trigger_error("Error: la cadena consulta no puede estar vacia.", E_USER_ERROR);
	}else{
		mysql_select_db(DB_NAME);
		$resultado = mysql_query($consulta, $conexion);
		if($resultado==0){
			// Error en SQL
			trigger_error("Error: el SQL no es correcto.<br />".$consulta."<br />".mysql_error(), E_USER_ERROR);
		}else{
			// Devuelvo el resultado
			return ValorCelda($resultado, 0, 0);
		}
	}
}

function ValorCelda($rtdo, $fila, $nombreColumna){
	return mysql_result($rtdo, $fila, $nombreColumna);
}

function NumeroFilas($rtdo){
	return mysql_num_rows($rtdo);
}

function Num_Elementos(){
	return mysql_affected_rows();
}

function Desconecta(){
	mysql_close();
}
?>