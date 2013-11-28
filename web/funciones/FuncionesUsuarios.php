<?
function mostrar_formulario_permisos_cliente($Elemento_ID, $Seccion_ID){
	return mostrar_formulario_permisos($Elemento_ID, $Seccion_ID, 0);
}

function mostrar_formulario_permisos_grupo($Elemento_ID, $Seccion_ID){
	return mostrar_formulario_permisos($Elemento_ID, $Seccion_ID, 1);
}

function mostrar_formulario_permisos($Elemento_ID, $Seccion_ID, $bGrupo){
	if ($bGrupo > 0)
	{
		$PA ="SELECT Grupo_ID AS valor, Nombre AS descripcion ";
		$PA.=" FROM tbGrupo ";
		$ComboName = "Grupo_ID[]";
	}
	else
	{
		$PA ="SELECT Cliente_ID AS valor, Nombre AS descripcion ";
		$PA.=" FROM tbCliente ";
		$ComboName = "Cliente_ID[]";
	}

	$PADatos ="SELECT Cliente_Grupo_ID AS valor";
	$PADatos.=" FROM tbPermiso ";
	$PADatos.=" WHERE bGrupo=".$bGrupo." ";
	$PADatos.=" AND Seccion_ID = ".$Seccion_ID." "; 
	$PADatos.=" AND Elemento_ID = ".$Elemento_ID;
	$Sin_Nada = "";
	$Multiple = 10;		


	return generaComboBoxMovimiento($PA,$PADatos,$ComboName,$Multiple);
}

function recojer_formulario_permisos_cliente($Elemento_ID, $Seccion_ID)
{
	return recojer_formulario_permisos($Elemento_ID, $Seccion_ID, 0);
}

function recojer_formulario_permisos_grupo($Elemento_ID, $Seccion_ID)
{
	return recojer_formulario_permisos($Elemento_ID, $Seccion_ID, 1);
}

function recojer_formulario_permisos($Elemento_ID, $Seccion_ID, $bGrupo)
{
	eliminar_permisos(intval($Elemento_ID), 0, $Seccion_ID, $bGrupo);
	
	if ($bGrupo > 0) 
	{
		$Campo_Nombre = "Grupo_ID";	
	}
	else
	{
		$Campo_Nombre = "Cliente_ID";	
	}
	
	// Grupos opcionales:
	foreach($_POST[$Campo_Nombre] as $Cliente_Grupo_ID)
	{
		$PA	= "INSERT INTO tbPermiso (Elemento_ID,Cliente_Grupo_ID, Seccion_ID, bGrupo) VALUES (";
		$PA.= "".$Elemento_ID.", ";
		$PA.= "".$Cliente_Grupo_ID.", ";
		$PA.= "".$Seccion_ID.", ";
		$PA.= "".$bGrupo." ";
		$PA.= ");";		
		ExecPA($PA);
	}
} 

function eliminar_permisos($Elemento_ID, $Cliente_Grupo_ID, $Seccion_ID, $bGrupo)
{
	
	$sql_where = "";
	if ($Elemento_ID > 0) 		$sql_where .= " AND Elemento_ID = ".$Elemento_ID;
	if ($Cliente_Grupo_ID > 0) 	$sql_where .= " AND Cliente_Grupo_ID = ".$Cliente_Grupo_ID;
	if ($Seccion_ID > 0) 		$sql_where .= " AND Seccion_ID = ".$Seccion_ID;
	$sql_where .= " AND bGrupo = ".$bGrupo;
	
	$PA = "DELETE FROM tbPermiso WHERE 0 = 0 ".$sql_where;
	ExecPA($PA);
}

function es_publico($Elemento_ID, $Seccion_ID)
{
	
	$sql_where = "";
	$sql_where .= " AND Elemento_ID = ".$Elemento_ID;
	$sql_where .= " AND Cliente_Grupo_ID = 1";
	$sql_where .= " AND Seccion_ID = ".$Seccion_ID;
	$sql_where .= " AND bGrupo = 1";
	
	$PA_publico = "SELECT COUNT(Elemento_ID) As Cuenta FROM tbPermiso WHERE 0 = 0 ".$sql_where;
	$rs_cuenta = ExecPA($PA_publico);
	$Cuenta		= intval(ValorCelda($rs_cuenta,0,"Cuenta"));
	
	return iif($Cuenta>0, 1, 0);
}

function cliente_tiene_permisos($Elemento_ID, $Cliente_ID, $Seccion_ID)
{
	// Función que responde a la pregunta de si un cliente dado tiene permisos sobre un elemento dado de una cierta sección
	// Ej: P: Tiene el usuario 89 permisos sobre la noticia 127? R: Sí
	$Elemento_ID = intval($Elemento_ID);
	$Cliente_ID = intval($Cliente_ID);
	$Seccion_ID = intval($Seccion_ID);
	if ($Elemento_ID && $Cliente_ID>0 && $Seccion_ID>0)
	{
		$PA_publico = "SELECT COUNT(Elemento_ID) As Cuenta FROM tbPermiso WHERE 0 = 0 ";
		$PA_publico .= " AND Elemento_ID = ".$Elemento_ID;
		$PA_publico .= " AND Cliente_Grupo_ID = ".$Cliente_ID;
		$PA_publico .= " AND Seccion_ID = ".$Seccion_ID;
		$PA_publico .= " AND bGrupo = 0";
		$rs_cuenta = ExecPA($PA_publico);
		$Cuenta		= intval(ValorCelda($rs_cuenta,0,"Cuenta"));
		if ($Cuenta>0) return true;
		
		
		$PA_publico = "SELECT COUNT(Elemento_ID) As Cuenta FROM tbPermiso ";
		$PA_publico .= " INNER JOIN tbCliente_Grupo ON tbCliente_Grupo.Grupo_ID = tbPermiso.Cliente_Grupo_ID ";
		$PA_publico .= " WHERE 0 = 0 ";
		$PA_publico .= " AND Elemento_ID = ".$Elemento_ID;
		$PA_publico .= " AND tbCliente_Grupo.Cliente_ID = ".$Cliente_ID;
		$PA_publico .= " AND Seccion_ID = ".$Seccion_ID;
		$PA_publico .= " AND bGrupo = 1";
		$rs_cuenta = ExecPA($PA_publico);
		$Cuenta		= intval(ValorCelda($rs_cuenta,0,"Cuenta"));
		if ($Cuenta>0) return true;
		
	}
	return false;
}

function cliente_tiene_elementos_privados($Cliente_ID, $Seccion_ID)
{
	// Función que responde a la pregunta de si existen elementos privados asociados a un usuario para una sección.
	// Ejemplo: Tiene el usuario 89 alguna noticia que no sea pública? Sí, No.

	$Cliente_ID = intval($Cliente_ID);
	$Seccion_ID = intval($Seccion_ID);
	if ($Cliente_ID>0 && $Seccion_ID>0)
	{
		/*$PA_publico = "SELECT COUNT(per1.Elemento_ID) As Cuenta FROM tbPermiso AS per1 WHERE 0 = 0 ";
		$PA_publico .= " AND per1.Cliente_Grupo_ID = ".$Cliente_ID;
		$PA_publico .= " AND per1.Seccion_ID = ".$Seccion_ID;
		$PA_publico .= " AND per1.bGrupo = 0";
		$PA_publico .= " AND per1.Elemento_ID NOT IN (";
		
		$PA_publico .= " SELECT per2.Elemento_ID FROM tbPermiso As per2 WHERE 0 = 0 ";
		$PA_publico .= " AND per2.Elemento_ID = per1.Elemento_ID";
		$PA_publico .= " AND per2.Seccion_ID = per1.Seccion_ID";
		$PA_publico .= " AND per2.bGrupo = 1";
		$PA_publico .= " AND per2.Cliente_Grupo_ID IN (".GRUPO_TODOS.",".GRUPO_REGISTRADOS.")";
		
		$PA_publico .= " )";*/
		
		$PA_publico = "SELECT COUNT(per1.Elemento_ID) As Cuenta FROM tbPermiso AS per1 WHERE 0 = 0 ";
		$PA_publico .= " AND per1.Cliente_Grupo_ID = ".$Cliente_ID;
		$PA_publico .= " AND per1.Seccion_ID = ".$Seccion_ID;
		$PA_publico .= " AND per1.bGrupo = 0";
		$PA_publico .= " AND per1.Elemento_ID NOT IN (";
		
		$PA_publico .= subconsulta( 
					 " SELECT per2.Elemento_ID AS Indice  FROM tbPermiso As per2 WHERE 0 = 0 ".
			 		 " AND per2.bGrupo = 1".
					 " AND per2.Cliente_Grupo_ID IN (".GRUPO_TODOS.",".GRUPO_REGISTRADOS.")");
		$PA_publico .= " )";
		
		$rs_cuenta = ExecPA($PA_publico);
		$Cuenta		= intval(ValorCelda($rs_cuenta,0,"Cuenta"));
		
		if ($Cuenta>0) return true;
		
		
		$PA_publico = "SELECT COUNT(Elemento_ID) As Cuenta FROM tbPermiso ";
		$PA_publico .= " INNER JOIN tbCliente_Grupo ON tbCliente_Grupo.Grupo_ID = tbPermiso.Cliente_Grupo_ID ";
		$PA_publico .= " WHERE 0 = 0 ";
		$PA_publico .= " AND tbCliente_Grupo.Cliente_ID = ".$Cliente_ID;
		$PA_publico .= " AND Seccion_ID = ".$Seccion_ID;
		$PA_publico .= " AND bGrupo = 1";
		$PA_publico .= " AND tbPermiso.Cliente_Grupo_ID NOT IN (".GRUPO_TODOS.",".GRUPO_REGISTRADOS.")";
		
		$rs_cuenta = ExecPA($PA_publico);
		$Cuenta		= intval(ValorCelda($rs_cuenta,0,"Cuenta"));
		if ($Cuenta>0) return true;
		
		
	}
	return false;
}

function cliente_pertenece_a_grupo($Cliente_ID, $Grupo_ID)
{
	// Función que responde a la pregunta de si existen elementos privados asociados a un usuario para una sección.
	// Ejemplo: Tiene el usuario 89 alguna noticia que no sea pública? Sí, No.
	$Cliente_ID = intval($Cliente_ID);
	$Grupo_ID = intval($Grupo_ID);
	if ($Cliente_ID>0 && $Grupo_ID>0)
	{
		$PA_publico = "SELECT COUNT(Cliente_ID) As Cuenta FROM tbCliente_Grupo WHERE 0 = 0 ";
		$PA_publico .= " AND Cliente_ID = ".$Cliente_ID;
		$PA_publico .= " AND Grupo_ID = ".$Grupo_ID;
		$rs_cuenta = ExecPA($PA_publico);
		$Cuenta		= intval(ValorCelda($rs_cuenta,0,"Cuenta"));
		if ($Cuenta>0) return true;
		

	}
	return false;
}
?>