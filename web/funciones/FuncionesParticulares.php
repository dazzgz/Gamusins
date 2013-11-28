<?
/*
 * <div class="pagination-centered">
        <ul class="pagination">
          <li class="arrow unavailable"><a href="">&laquo;</a></li>
          <li class="current"><a href="">1</a></li>
          <li><a href="">2</a></li>
          <li><a href="">3</a></li>
          <li><a href="">4</a></li>
          <li class="unavailable"><a href="">&hellip;</a></li>
          <li><a href="">12</a></li>
          <li><a href="">13</a></li>
          <li class="arrow"><a href="">&raquo;</a></li>
        </ul>
      </div>
 * */
function MostrarPaginacionFront($actual, $total, $por_pagina, $enlace)
{
	$html = "";
	if($total > $por_pagina){
		$parametros = "&pag=";
		//$total_paginas = (($total / $por_pagina) + ceil($total % $por_pagina));
		$total_paginas = ceil($total / $por_pagina);
		//$html = "actual:".$actual ." - total:". $total_paginas;
		$p_inicio = 1;
		$p_final = 9;
		$anterior = $actual - 1;
		$posterior = $actual + 1;
		if ($actual > 4 && $actual <= ($total_paginas - 4))
		{
			$p_inicio = $actual - 4;
			$p_final = $actual + 4;
		}
		if ($actual > ($total_paginas - 4))
		{
			$p_inicio = $total_paginas - 8;
			$p_final = $total_paginas;
		}
		if($p_inicio <= 0){
			$p_inicio = 1;
		}

		if ($total_paginas > 1)
		{
			$html .= "<div class=\"pagination-centered\">";
			$html .= "<ul class=\"pagination\">";
			/*if ($actual > 1)
			{
				$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros."1"."'\" class=\"btn\">".TextoDeIdioma("Inicio")."</button>";
			}
			else
			{
				$html .= "<button disabled type=\"button\" class=\"btn\">".TextoDeIdioma("Inicio")."</button>";
			}*/
			if ($actual > 9){
				//$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.($actual - 10)."'\" class=\"btn\"><<</button>";
				$html .= "<li><a href=\"".$enlace.$parametros.($actual - 10)."\">&hellip;</a></li>";
			}else{
				//$html .= "<button disabled type=\"button\" class=\"btn\"><<</button>";
				$html .= "<li class=\"unavailable\">&hellip;</li>";
			}
			if ($actual > 1){
				//$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.$anterior."'\" class=\"btn\"><</button>";
				$html .= "<li class=\"arrow\"><a href=\"".$enlace.$parametros.$anterior."\">&laquo;</a></li>";
			}else{
				//$html .= "<button disabled type=\"button\" class=\"btn\"><</button>";
				$html .= "<li class=\"arrow unavailable\">&laquo;</li>";
			}
			for ($i = $p_inicio; $i <= $p_final; $i++)
			{
				if($i==$actual){
					//$html .= "<button type=\"button\" class=\"btn btn-inverse\">".$actual."</button>";
					$html .= "<li class=\"current\"><a href=\"".$enlace.$parametros.$i."\">".$actual."</a></li>";
				}else{
					//$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.$i."'\" class=\"btn\">".$i."</button>";
					$html .= "<li><a href=\"".$enlace.$parametros.$i."\">".$i."</a></li>";
				}
			}
	    if ($actual < $total_paginas){
				//$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.$posterior."'\" class=\"btn\">></button>";
	    	$html .= "<li class=\"arrow\"><a href=\"".$enlace.$parametros.$posterior."\">&raquo;</a></li>";
			}else{
				//$html .= "<button disabled type=\"button\" class=\"btn\">></button>";
				$html .= "<li class=\"arrow unavailable\">&raquo;</li>";
			}
	    if ($actual < ($total_paginas - 9)){
				//$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.($actual + 10)."'\" class=\"btn\">>></button>";
	    	$html .= "<li><a href=\"".$enlace.$parametros.($actual + 10)."\">&hellip;</a></li>";
			}else{
				//$html .= "<button disabled type=\"button\" class=\"btn\">>></button>";
				$html .= "<li class=\"unavailable\">&hellip;</li>";
			}
			/*if ($actual < $total_paginas){
	    	$html .= "<button type=\"button\" onclick=\"location.href='".$enlace.$parametros.$total_paginas."'\" class=\"btn\">".TextoDeIdioma("Final")."</button>";
			}else{
	    	$html .= "<button disabled type=\"button\" class=\"btn\">".TextoDeIdioma("Final")."</button>";
	    }*/
			$html .= "</ul>";
			$html .= "</div>";
		}
	}
	return $html;
}

// IMPORTACION DE XMLs
/////////////////////////////////////////////////////////////////////////////////
function ImportarXML($archivo, $IdImportacion) 
{

	$rutaFicheroXML = $archivo;
//echo $rutaFicheroXML;
	$doc = simplexml_load_file($rutaFicheroXML);
	//$doc = new SimpleXMLElement($xmlstr);
	
	$AgenteSolicitante = $_SESSION["sPANEL_Usuario_ID"];

	/*$texto = "";
	$texto .= "<br />";
	$texto .= $rutaFicheroXML;
	$texto .= "<br />";*/
	//print_r($doc);
	
	/*<CodigoREEEmpresaEmisora>0021</CodigoREEEmpresaEmisora>
	<CodigoREEEmpresaDestino>0737</CodigoREEEmpresaDestino>
	<CodigoDelProceso>P0</CodigoDelProceso>
	<CodigoDePaso>03</CodigoDePaso>
	<CodigoDeSolicitud>0000016312E5</CodigoDeSolicitud>
	<SecuencialDeSolicitud>01</SecuencialDeSolicitud>
	<Codigo>ES0021000000986687YF </Codigo>
	<FechaSolicitud>2012-06-04T12:03:00</FechaSolicitud>
	<Version>02</Version>*/
	$CodigoREEEmpresaEmisora = $doc->Cabecera[0]->CodigoREEEmpresaEmisora[0];
	$CodigoREEEmpresaDestino = $doc->Cabecera[0]->CodigoREEEmpresaDestino[0];
	$CodigoDelProceso = $doc->Cabecera[0]->CodigoDelProceso[0];
	$CodigoDePaso = $doc->Cabecera[0]->CodigoDePaso[0];
	$CodigoDeSolicitud = $doc->Cabecera[0]->CodigoDeSolicitud[0];
	$SecuencialDeSolicitud = $doc->Cabecera[0]->SecuencialDeSolicitud[0];
	$Codigo = $doc->Cabecera[0]->Codigo[0];
	//Trunco a 20 caracteres
	$Codigo = substr($Codigo, 0 , 20);
	$FechaSolicitud = $doc->Cabecera[0]->FechaSolicitud[0];
	$Version = $doc->Cabecera[0]->Version[0];
	
	//VALIDO QUE SE TRATA DE UN FICHERO P3, SINO FUERA...
	if($CodigoDelProceso != 'P0' || $CodigoDePaso != '03'){
		return -1; 
	}
	
	//Obtengo el IdImportacion anterior (si la hubiera) para el CUPS que se va a importar
	$row = ExecQuerySingle("SELECT IdP3, IdImportacion FROM tbP3 WHERE Codigo='".$Codigo."'");
	$IdImportacionOLD = intval($row["IdImportacion"]);
	$IdP3 = intval($row["IdP3"]);

//	echo $IdImportacionOLD.' - '.$IdP3;
	if($IdP3 > 0){
		//Elimino de la tabla tbP3 y tbP3ConsumoPeriodo el/los registro/s del CUPS que se va a importar (si existe)
		ExecPA("DELETE FROM tbP3 WHERE IdP3 = ".$IdP3);
		ExecPA("DELETE FROM tbP3ConsumoPeriodo WHERE IdP3 = ".$IdP3);
	}
	
	if($IdImportacionOLD > 0){
		//Establezco esa importación como NO INCORPORADA al sistema, ya que hemos eliminado el P3 correspondiente... 
		$PA = "UPDATE tbImportacion SET bIncorporado = 0";
		$PA .= " WHERE IdImportacion = ".$IdImportacionOLD;
		ExecPA($PA);
	}
	
	if($IdImportacion > 0){
		$PA = "UPDATE tbImportacion SET CUPS = '".$Codigo."'";
		$PA .= " WHERE IdImportacion = ".$IdImportacion;
		ExecPA($PA);
	}
	
	$texto .= $doc->Cabecera[0]->CodigoREEEmpresaEmisora[0]."<br />";
	$texto .= $doc->Cabecera[0]->CodigoREEEmpresaDestino[0]."<br />";
	$texto .= $doc->Cabecera[0]->CodigoDelProceso[0]."<br />";
	$texto .= $doc->Cabecera[0]->CodigoDePaso[0]."<br />";
	$texto .= $doc->Cabecera[0]->CodigoDeSolicitud[0]."<br />";
	$texto .= $doc->Cabecera[0]->SecuencialDeSolicitud[0]."<br />";
	$texto .= $doc->Cabecera[0]->Codigo[0]."<br />";
	$texto .= $doc->Cabecera[0]->FechaSolicitud[0]."<br />";
	$texto .= $doc->Cabecera[0]->Version[0]."<br />";

	/*<EnvioInformacionAlRegistroDePuntosDeSuministro>
			<Contrato>
				<CondicionesContractuales>
					<TarifaATR>011</TarifaATR>
					<TipoContrato>01</TipoContrato>
					<PotenciasContratadas>
						<Potencia Periodo="1">310000</Potencia>
						<Potencia Periodo="2">310000</Potencia>
						<Potencia Periodo="3">310000</Potencia>
						<Potencia Periodo="4">0</Potencia>
						<Potencia Periodo="5">0</Potencia>
						<Potencia Periodo="6">0</Potencia>
						<Potencia Periodo="7">0</Potencia>
						<Potencia Periodo="8">0</Potencia>
						<Potencia Periodo="9">0</Potencia>
						<Potencia Periodo="10">0</Potencia>
					</PotenciasContratadas>
				</CondicionesContractuales>
				<ImporteImpagos>0.00</ImporteImpagos>
				<DepositoGarantia>N</DepositoGarantia>
				<ImporteDeposito>0.00</ImporteDeposito>
			</Contrato>*/

	$TarifaATR = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->CondicionesContractuales[0]->TarifaATR[0];
	$TipoContrato = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->CondicionesContractuales[0]->TipoContrato[0];
	
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->CondicionesContractuales[0]->TarifaATR[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->CondicionesContractuales[0]->TipoContrato[0]."<br />";
	//$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->CondicionesContractuales[0]->PotenciasContratadas[0]."<br />";
	foreach ($doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->CondicionesContractuales[0]->PotenciasContratadas[0] as $potencia) {
		switch((string) $potencia['Periodo']) { // Toma los atributos como índices del elemento
			case '1':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '2':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '3':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '4':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '5':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '6':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '7':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '8':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '9':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
			case '10':
				$texto .= "Periodo ".$potencia['Periodo'].": " . $potencia."<br />";
				break;
		}
		//Meto las potencias en 1 array
		$arrPotenciaPeriodo[$potencia['Periodo']-1] = $potencia;
	}

	$ImporteImpagos = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->ImporteImpagos[0];
	$DepositoGarantia = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->DepositoGarantia[0];
	$ImporteDeposito = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->ImporteDeposito[0];
	
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->ImporteImpagos[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->DepositoGarantia[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Contrato[0]->ImporteDeposito[0]."<br />";

	/*<DatosTitular>
			<TipoTitular>PJ</TipoTitular>
			<Titular>EXTRACCION DE ARIDOS SIERRA NEGRA S.L.</Titular>
			<Calle>C/ MANUEL MACIA JUAN, 4</Calle>
			<Poblacion>ELCHE/ELX</Poblacion>
			<Provincia>ALICANTE</Provincia>
	</DatosTitular>*/
	$TipoTitular = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->TipoTitular[0];
	$Titular = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->Titular[0];
	$Calle = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->Calle[0];
	$Poblacion = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->Poblacion[0];
	$Provincia = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->Provincia[0];
	
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->TipoTitular[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->Titular[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->Calle[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->Poblacion[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DatosTitular[0]->Provincia[0]."<br />";

	/*<LocalizacionPS>
			<Pais>ESPAÑA</Pais>
			<Provincia>ALICANTE</Provincia>
			<Municipio>650000</Municipio>
			<Poblacion>CARRUS</Poblacion>
			<Calle>Ptda CARRUS CANTERA, 2</Calle>
			<CodPostal>3205</CodPostal>
	</LocalizacionPS>*/
	$Pais = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Pais[0];
	$Provincia = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Provincia[0];
	$Municipio = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Municipio[0];
	$Poblacion = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Poblacion[0];
	$Calle = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Calle[0];
	$CodPostal = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->CodPostal[0];
	
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Pais[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Provincia[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Municipio[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Poblacion[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->Calle[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->LocalizacionPS[0]->CodPostal[0]."<br />";

	/*<DerechosReconocidos>
			<DerechosAcceso>310000</DerechosAcceso>
			<FechaLimiteDerechosAcceso>9999-01-01</FechaLimiteDerechosAcceso>
			<DerechosExtension>310000</DerechosExtension>
			<FechaLimiteDerechos>9999-01-01</FechaLimiteDerechos>
	</DerechosReconocidos>*/
	$DerechosAcceso = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DerechosReconocidos[0]->DerechosAcceso[0];
	$FechaLimiteDerechosAcceso = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DerechosReconocidos[0]->FechaLimiteDerechosAcceso[0];
	$DerechosExtension = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DerechosReconocidos[0]->DerechosExtension[0];
	$FechaLimiteDerechos = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DerechosReconocidos[0]->FechaLimiteDerechos[0];
	
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DerechosReconocidos[0]->DerechosAcceso[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DerechosReconocidos[0]->FechaLimiteDerechosAcceso[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DerechosReconocidos[0]->DerechosExtension[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->DerechosReconocidos[0]->FechaLimiteDerechos[0]."<br />";

	/*<Caracteristicas>
			<PotenciaMaximaAutorizadaPorBIE>0</PotenciaMaximaAutorizadaPorBIE>
			<PotenciaMaximaAutorizadaPorActa>322000</PotenciaMaximaAutorizadaPorActa>
			<PotenciaDisponibleCaja>310000</PotenciaDisponibleCaja>
			<InstalacionICP>3</InstalacionICP>
			<SuministroLiberalizado>S</SuministroLiberalizado>
			<TipoPuntoMedida>03</TipoPuntoMedida>
			<Tension>20000</Tension>
			<ViviendaHabitual>N</ViviendaHabitual>
	</Caracteristicas>*/
	$PotenciaMaximaAutorizadaPorBIE = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->PotenciaMaximaAutorizadaPorBIE[0];
	$PotenciaMaximaAutorizadaPorActa = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->PotenciaMaximaAutorizadaPorActa[0];
	$PotenciaDisponibleCaja = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->PotenciaDisponibleCaja[0];
	$InstalacionICP = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->InstalacionICP[0];
	$SuministroLiberalizado = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->SuministroLiberalizado[0];
	$TipoPuntoMedida = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->TipoPuntoMedida[0];
	$Tension = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->Tension[0];
	$ViviendaHabitual = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->ViviendaHabitual[0];
	
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->PotenciaMaximaAutorizadaPorBIE[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->PotenciaMaximaAutorizadaPorActa[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->PotenciaDisponibleCaja[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->InstalacionICP[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->SuministroLiberalizado[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->TipoPuntoMedida[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->Tension[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Caracteristicas[0]->ViviendaHabitual[0]."<br />";

	/*<Historia>
		<FechaUltimoMovimientoContratacion>2011-06-30</FechaUltimoMovimientoContratacion>
		<FechaCambioComercializador>2011-07-31</FechaCambioComercializador>
		<FechaAlta>1985-12-09</FechaAlta>
		<FechaUltimaLectura>2012-04-30</FechaUltimaLectura>
	</Historia>*/
	$FechaUltimoMovimientoContratacion = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Historia[0]->FechaUltimoMovimientoContratacion[0];
	$FechaCambioComercializador = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Historia[0]->FechaCambioComercializador[0];
	$FechaAlta = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Historia[0]->FechaAlta[0];
	$FechaUltimaLectura = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Historia[0]->FechaUltimaLectura[0];
	
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Historia[0]->FechaUltimoMovimientoContratacion[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Historia[0]->FechaCambioComercializador[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Historia[0]->FechaAlta[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Historia[0]->FechaUltimaLectura[0]."<br />";

	/*<Consumo>
		<TipoPerfilDeConsumo>c </TipoPerfilDeConsumo>
		<Periodos>
			<Periodo>
			<AnioFacturacion>2012</AnioFacturacion>
			<TipoFacturacion>0</TipoFacturacion>
			<FechaLecturaInicial>2012-03-31</FechaLecturaInicial>
			<FechaLecturaFinal>2012-04-30</FechaLecturaFinal>
			<CodigoTarifa>011</CodigoTarifa>
			<EnergiasPH>
				<EnergiaPH periodo="1">413</EnergiaPH>
				<EnergiaPH periodo="2">935</EnergiaPH>
				<EnergiaPH periodo="3">1233</EnergiaPH>
				<EnergiaPH periodo="4">0</EnergiaPH>
				<EnergiaPH periodo="5">0</EnergiaPH>
				<EnergiaPH periodo="6">0</EnergiaPH>
				<EnergiaPH periodo="7">0</EnergiaPH>
			</EnergiasPH>
			<ReactivasPH>
				<ReactivaPH periodo="1">0</ReactivaPH>
				<ReactivaPH periodo="2">1</ReactivaPH>
				<ReactivaPH periodo="3">0</ReactivaPH>
				<ReactivaPH periodo="4">0</ReactivaPH>
				<ReactivaPH periodo="5">0</ReactivaPH>
				<ReactivaPH periodo="6">0</ReactivaPH>
				<ReactivaPH periodo="7">0</ReactivaPH>
			</ReactivasPH>
			<PotenciasPH>
				<PotenciaPH periodo="1">6</PotenciaPH>
				<PotenciaPH periodo="2">7</PotenciaPH>
				<PotenciaPH periodo="3">4</PotenciaPH>
				<PotenciaPH periodo="4">0</PotenciaPH>
				<PotenciaPH periodo="5">0</PotenciaPH>
				<PotenciaPH periodo="6">0</PotenciaPH>
				<PotenciaPH periodo="7">0</PotenciaPH>
			</PotenciasPH>
		</Periodo>*/
	
	$TipoPerfilDeConsumo = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Consumo[0]->TipoPerfilDeConsumo[0];
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Consumo[0]->TipoPerfilDeConsumo[0]."<br />";
	
	/*<Equipo>
	 <TipoAparato>CG</TipoAparato>
	<TipoEquipo>L03</TipoEquipo>
	<TipoPropiedad>1</TipoPropiedad>
	</Equipo>
	</EnvioInformacionAlRegistroDePuntosDeSuministro>*/
	
	$TipoAparato = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoAparato[0];
	$TipoEquipo = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoEquipo[0];
	$TipoPropiedad = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoPropiedad[0];
	//	$texto = "";
	
	
	$strSQL = "INSERT INTO tbP3 (";
	$strSQL .= " AgenteSolicitante";
	$strSQL .= ", IdImportacion";
	$strSQL .= ", CodigoREEEmpresaEmisora";
	$strSQL .= ", CodigoREEEmpresaDestino";
	$strSQL .= ", CodigoDelProceso";
	$strSQL .= ", CodigoDePaso";
	$strSQL .= ", CodigoDeSolicitud";
	$strSQL .= ", SecuencialDeSolicitud";
	$strSQL .= ", Codigo";
	$strSQL .= ", FechaSolicitud";
	$strSQL .= ", Version";
	$strSQL .= ", TarifaATR";
	$strSQL .= ", TipoContrato";
	$strSQL .= ", PotenciaPeriodo1";
	$strSQL .= ", PotenciaPeriodo2";
	$strSQL .= ", PotenciaPeriodo3";
	$strSQL .= ", PotenciaPeriodo4";
	$strSQL .= ", PotenciaPeriodo5";
	$strSQL .= ", PotenciaPeriodo6";
	$strSQL .= ", PotenciaPeriodo7";
	$strSQL .= ", PotenciaPeriodo8";
	$strSQL .= ", PotenciaPeriodo9";
	$strSQL .= ", PotenciaPeriodo10";
	$strSQL .= ", ImporteImpagos";
	$strSQL .= ", DepositoGarantia";
	$strSQL .= ", ImporteDeposito";
	$strSQL .= ", TipoTitular";
	$strSQL .= ", Titular";
	$strSQL .= ", Calle";
	$strSQL .= ", Poblacion";
	$strSQL .= ", Provincia";
	$strSQL .= ", Pais";
	$strSQL .= ", Provincia2";
	$strSQL .= ", Municipio";
	$strSQL .= ", Poblacion3";
	$strSQL .= ", Calle4";
	$strSQL .= ", CodPostal";
	$strSQL .= ", DerechosAcceso";
	$strSQL .= ", FechaLimiteDerechosAcceso";
	$strSQL .= ", DerechosExtension";
	$strSQL .= ", FechaLimiteDerechos";
	$strSQL .= ", PotenciaMaximaAutorizadaPorBIE";
	$strSQL .= ", PotenciaMaximaAutorizadaPorActa";
	$strSQL .= ", PotenciaDisponibleCaja";
	$strSQL .= ", InstalacionICP";
	$strSQL .= ", SuministroLiberalizado";
	$strSQL .= ", TipoPuntoMedida";
	$strSQL .= ", Tension";
	$strSQL .= ", ViviendaHabitual";
	$strSQL .= ", FechaUltimoMovimientoContratacion";
	$strSQL .= ", FechaCambioComercializador";
	$strSQL .= ", FechaAlta";
	$strSQL .= ", FechaUltimaLectura";
	$strSQL .= ", TipoPerfilDeConsumo";
	/*$strSQL .= ", AnioFacturacion";
	 $strSQL .= ", TipoFacturacion";
	$strSQL .= ", FechaLecturaInicial";
	$strSQL .= ", FechaLecturaFinal";
	$strSQL .= ", CodigoTarifa";
	$strSQL .= ", EnergiaPH";
	$strSQL .= ", Periodo5";
	$strSQL .= ", ReactivaPH";
	$strSQL .= ", Periodo6";
	$strSQL .= ", PotenciaPH";
	$strSQL .= ", Periodo7";*/
	$strSQL .= ", TipoAparato";
	$strSQL .= ", TipoEquipo";
	$strSQL .= ", TipoPropiedad";
	$strSQL .= ") VALUES (";
	$strSQL .= " '".$AgenteSolicitante."'";
	$strSQL .= ", ".$IdImportacion."";
	$strSQL .= ", '".$CodigoREEEmpresaEmisora."'";
	$strSQL .= ", '".$CodigoREEEmpresaDestino."'";
	$strSQL .= ", '".$CodigoDelProceso."'";
	$strSQL .= ", '".$CodigoDePaso."'";
	$strSQL .= ", '".$CodigoDeSolicitud."'";
	$strSQL .= ", '".$SecuencialDeSolicitud."'";
	$strSQL .= ", '".$Codigo."'";
	$strSQL .= ", '".$FechaSolicitud."'";
	$strSQL .= ", '".$Version."'";
	$strSQL .= ", '".$TarifaATR."'";
	$strSQL .= ", '".$TipoContrato."'";
	for($i=0; $i<count($arrPotenciaPeriodo);$i++){
		$strSQL .= ", '".$arrPotenciaPeriodo[$i]."'";
	}
	$strSQL .= ", '".$ImporteImpagos."'";
	$strSQL .= ", '".$DepositoGarantia."'";
	$strSQL .= ", '".$ImporteDeposito."'";
	$strSQL .= ", '".$TipoTitular."'";
	$strSQL .= ", '".$Titular."'";
	$strSQL .= ", '".$Calle."'";
	$strSQL .= ", '".$Poblacion."'";
	$strSQL .= ", '".$Provincia."'";
	$strSQL .= ", '".$Pais."'";
	$strSQL .= ", '".$Provincia2."'";
	$strSQL .= ", '".$Municipio."'";
	$strSQL .= ", '".$Poblacion3."'";
	$strSQL .= ", '".$Calle4."'";
	$strSQL .= ", '".$CodPostal."'";
	$strSQL .= ", '".$DerechosAcceso."'";
	$strSQL .= ", '".$FechaLimiteDerechosAcceso."'";
	$strSQL .= ", '".$DerechosExtension."'";
	$strSQL .= ", '".$FechaLimiteDerechos."'";
	$strSQL .= ", '".$PotenciaMaximaAutorizadaPorBIE."'";
	$strSQL .= ", '".$PotenciaMaximaAutorizadaPorActa."'";
	$strSQL .= ", '".$PotenciaDisponibleCaja."'";
	$strSQL .= ", '".$InstalacionICP."'";
	$strSQL .= ", '".$SuministroLiberalizado."'";
	$strSQL .= ", '".$TipoPuntoMedida."'";
	$strSQL .= ", '".$Tension."'";
	$strSQL .= ", '".$ViviendaHabitual."'";
	$strSQL .= ", '".$FechaUltimoMovimientoContratacion."'";
	$strSQL .= ", '".$FechaCambioComercializador."'";
	$strSQL .= ", '".$FechaAlta."'";
	$strSQL .= ", '".$FechaUltimaLectura."'";
	$strSQL .= ", '".$TipoPerfilDeConsumo."'";
	
	$strSQL .= ", '".$TipoAparato."'";
	$strSQL .= ", '".$TipoEquipo."'";
	$strSQL .= ", '".$TipoPropiedad."'";
	$strSQL .= ")";
	
	$res = 0;
	if(ExecPA($strSQL))
	{
		$IdP3 = mysql_insert_id();
	}
	
	//$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Consumo[0]->Periodos[0]->Periodo[0]."<br />";
	foreach ($doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Consumo[0]->Periodos[0]->Periodo as $periodo) {
		$AnioFacturacion = $periodo->AnioFacturacion[0];
		$TipoFacturacion = $periodo->TipoFacturacion[0];
		$FechaLecturaInicial = $periodo->FechaLecturaInicial[0];
		$FechaLecturaFinal = $periodo->FechaLecturaFinal[0];
		$CodigoTarifa = $periodo->CodigoTarifa[0];
		
		$texto .= $periodo->AnioFacturacion[0]."<br />";
		$texto .= $periodo->TipoFacturacion[0]."<br />";
		$texto .= $periodo->FechaLecturaInicial[0]."<br />";
		$texto .= $periodo->FechaLecturaFinal[0]."<br />";
		$texto .= $periodo->CodigoTarifa[0]."<br />";

		$texto .= "<br />";

		foreach ($periodo->EnergiasPH[0] as $energiaPH) {
			switch((string) $energiaPH['periodo']) { // Toma los atributos como índices del elemento
				case '1':
					$texto .= "EnergiaPH periodo ".$energiaPH['periodo'].": " . $energiaPH."<br />";
					break;
				case '2':
					$texto .= "EnergiaPH periodo ".$energiaPH['periodo'].": " . $energiaPH."<br />";
					break;
				case '3':
					$texto .= "EnergiaPH periodo ".$energiaPH['periodo'].": " . $energiaPH."<br />";
					break;
				case '4':
					$texto .= "EnergiaPH periodo ".$energiaPH['periodo'].": " . $energiaPH."<br />";
					break;
				case '5':
					$texto .= "EnergiaPH periodo ".$energiaPH['periodo'].": " . $energiaPH."<br />";
					break;
				case '6':
					$texto .= "EnergiaPH periodo ".$energiaPH['periodo'].": " . $energiaPH."<br />";
					break;
				case '7':
					$texto .= "EnergiaPH periodo ".$energiaPH['periodo'].": " . $energiaPH."<br />";
					break;
			}
			//Meto las potencias en 1 array
			$arrEnergiaPH[$energiaPH['periodo']-1] = $energiaPH;
		}

		$texto .= "<br />";

		foreach ($periodo->ReactivasPH[0] as $reactivaPH) {
			switch((string) $reactivaPH['periodo']) { // Toma los atributos como índices del elemento
				case '1':
					$texto .= "ReactivaPH periodo ".$reactivaPH['periodo'].": " . $reactivaPH."<br />";
					break;
				case '2':
					$texto .= "ReactivaPH periodo ".$reactivaPH['periodo'].": " . $reactivaPH."<br />";
					break;
				case '3':
					$texto .= "ReactivaPH periodo ".$reactivaPH['periodo'].": " . $reactivaPH."<br />";
					break;
				case '4':
					$texto .= "ReactivaPH periodo ".$reactivaPH['periodo'].": " . $reactivaPH."<br />";
					break;
				case '5':
					$texto .= "ReactivaPH periodo ".$reactivaPH['periodo'].": " . $reactivaPH."<br />";
					break;
				case '6':
					$texto .= "ReactivaPH periodo ".$reactivaPH['periodo'].": " . $reactivaPH."<br />";
					break;
				case '7':
					$texto .= "ReactivaPH periodo ".$reactivaPH['periodo'].": " . $reactivaPH."<br />";
					break;
			}
			//Meto las potencias en 1 array
			$arrReactivaPH[$reactivaPH['periodo']-1] = $reactivaPH;
		}

		$texto .= "<br />";

		foreach ($periodo->PotenciasPH[0] as $potenciaPH) {
			switch((string) $potenciaPH['periodo']) { // Toma los atributos como índices del elemento
				case '1':
					$texto .= "ReactivaPH periodo ".$potenciaPH['periodo'].": " . $potenciaPH."<br />";
					break;
				case '2':
					$texto .= "ReactivaPH periodo ".$potenciaPH['periodo'].": " . $potenciaPH."<br />";
					break;
				case '3':
					$texto .= "ReactivaPH periodo ".$potenciaPH['periodo'].": " . $potenciaPH."<br />";
					break;
				case '4':
					$texto .= "ReactivaPH periodo ".$potenciaPH['periodo'].": " . $potenciaPH."<br />";
					break;
				case '5':
					$texto .= "ReactivaPH periodo ".$potenciaPH['periodo'].": " . $potenciaPH."<br />";
					break;
				case '6':
					$texto .= "ReactivaPH periodo ".$potenciaPH['periodo'].": " . $potenciaPH."<br />";
					break;
				case '7':
					$texto .= "ReactivaPH periodo ".$potenciaPH['periodo'].": " . $potenciaPH."<br />";
					break;
			}
			//Meto las potencias en 1 array
			$arrPotenciaPH[$potenciaPH['periodo']-1] = $potenciaPH;
		}
		
		//INSERTO UN REGISTRO EN tbP3ConsumoPeriodo POR CADA PERIODO QUE ENCUENTRE
		$strSQL = "INSERT INTO tbP3ConsumoPeriodo (";
		$strSQL .= " IdP3";
		$strSQL .= ", AnioFacturacion";
		$strSQL .= ", TipoFacturacion";
		$strSQL .= ", FechaLecturaInicial";
		$strSQL .= ", FechaLecturaFinal";
		$strSQL .= ", CodigoTarifa";
		$strSQL .= ", EnergiaPH1";
		$strSQL .= ", EnergiaPH2";
		$strSQL .= ", EnergiaPH3";
		$strSQL .= ", EnergiaPH4";
		$strSQL .= ", EnergiaPH5";
		$strSQL .= ", EnergiaPH6";
		$strSQL .= ", EnergiaPH7";
		$strSQL .= ", ReactivaPH1";
		$strSQL .= ", ReactivaPH2";
		$strSQL .= ", ReactivaPH3";
		$strSQL .= ", ReactivaPH4";
		$strSQL .= ", ReactivaPH5";
		$strSQL .= ", ReactivaPH6";
		$strSQL .= ", ReactivaPH7";
		$strSQL .= ", PotenciaPH1";
		$strSQL .= ", PotenciaPH2";
		$strSQL .= ", PotenciaPH3";
		$strSQL .= ", PotenciaPH4";
		$strSQL .= ", PotenciaPH5";
		$strSQL .= ", PotenciaPH6";
		$strSQL .= ", PotenciaPH7";
		$strSQL .= ") VALUES (";
		$strSQL .= " ".$IdP3."";
		$strSQL .= ", ".$AnioFacturacion."";
		$strSQL .= ", ".$TipoFacturacion."";
		if($FechaLecturaInicial == "")
			$strSQL .= ", null";
		else
			$strSQL .= ", '".$FechaLecturaInicial."'";
		
		$strSQL .= ", '".$FechaLecturaFinal."'";
		$strSQL .= ", '".$CodigoTarifa."'";
		
		for($i=0; $i<count($arrEnergiaPH);$i++){
			$strSQL .= ", ".$arrEnergiaPH[$i]."";
		}
		
		for($i=0; $i<count($arrReactivaPH);$i++){
			$strSQL .= ", ".$arrReactivaPH[$i]."";
		}
		for($i=0; $i<count($arrPotenciaPH);$i++){
			$strSQL .= ", ".$arrPotenciaPH[$i]."";
		}
		$strSQL .= ")";
	//echo $strSQL;		
		$res = ExecPA($strSQL);
	}

	/*<Equipo>
			<TipoAparato>CG</TipoAparato>
			<TipoEquipo>L03</TipoEquipo>
			<TipoPropiedad>1</TipoPropiedad>
		</Equipo>
	</EnvioInformacionAlRegistroDePuntosDeSuministro>*/

	/*$TipoAparato = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoAparato[0];
	$TipoEquipo = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoEquipo[0];
	$TipoPropiedad = $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoPropiedad[0];
	
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoAparato[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoEquipo[0]."<br />";
	$texto .= $doc->EnvioInformacionAlRegistroDePuntosDeSuministro[0]->Equipo[0]->TipoPropiedad[0]."<br />";
	
	$strSQL = "INSERT INTO tbP3 (";
	$strSQL .= " AgenteSolicitante";
	$strSQL .= ", IdImportacion";
	$strSQL .= ", CodigoREEEmpresaEmisora";
	$strSQL .= ", CodigoREEEmpresaDestino";
	$strSQL .= ", CodigoDelProceso";
	$strSQL .= ", CodigoDePaso";
	$strSQL .= ", CodigoDeSolicitud";
	$strSQL .= ", SecuencialDeSolicitud";
	$strSQL .= ", Codigo";
	$strSQL .= ", FechaSolicitud";
	$strSQL .= ", Version";
	$strSQL .= ", TarifaATR";
	$strSQL .= ", TipoContrato";
	$strSQL .= ", PotenciaPeriodo1";
	$strSQL .= ", PotenciaPeriodo2";
	$strSQL .= ", PotenciaPeriodo3";
	$strSQL .= ", PotenciaPeriodo4";
	$strSQL .= ", PotenciaPeriodo5";
	$strSQL .= ", PotenciaPeriodo6";
	$strSQL .= ", PotenciaPeriodo7";
	$strSQL .= ", PotenciaPeriodo8";
	$strSQL .= ", PotenciaPeriodo9";
	$strSQL .= ", PotenciaPeriodo10";
	$strSQL .= ", ImporteImpagos";
	$strSQL .= ", DepositoGarantia";
	$strSQL .= ", ImporteDeposito";
	$strSQL .= ", TipoTitular";
	$strSQL .= ", Titular";
	$strSQL .= ", Calle";
	$strSQL .= ", Poblacion";
	$strSQL .= ", Provincia";
	$strSQL .= ", Pais";
	$strSQL .= ", Provincia2";
	$strSQL .= ", Municipio";
	$strSQL .= ", Poblacion3";
	$strSQL .= ", Calle4";
	$strSQL .= ", CodPostal";
	$strSQL .= ", DerechosAcceso";
	$strSQL .= ", FechaLimiteDerechosAcceso";
	$strSQL .= ", DerechosExtension";
	$strSQL .= ", FechaLimiteDerechos";
	$strSQL .= ", PotenciaMaximaAutorizadaPorBIE";
	$strSQL .= ", PotenciaMaximaAutorizadaPorActa";
	$strSQL .= ", PotenciaDisponibleCaja";
	$strSQL .= ", InstalacionICP";
	$strSQL .= ", SuministroLiberalizado";
	$strSQL .= ", TipoPuntoMedida";
	$strSQL .= ", Tension";
	$strSQL .= ", ViviendaHabitual";
	$strSQL .= ", FechaUltimoMovimientoContratacion";
	$strSQL .= ", FechaCambioComercializador";
	$strSQL .= ", FechaAlta";
	$strSQL .= ", FechaUltimaLectura";
	$strSQL .= ", TipoPerfilDeConsumo";
	//$strSQL .= ", AnioFacturacion";
	//$strSQL .= ", TipoFacturacion";
	//$strSQL .= ", FechaLecturaInicial";
	//$strSQL .= ", FechaLecturaFinal";
	//$strSQL .= ", CodigoTarifa";
	//$strSQL .= ", EnergiaPH";
	//$strSQL .= ", Periodo5";
	//$strSQL .= ", ReactivaPH";
	//$strSQL .= ", Periodo6";
	//$strSQL .= ", PotenciaPH";
	//$strSQL .= ", Periodo7";
	$strSQL .= ", TipoAparato";
	$strSQL .= ", TipoEquipo";
	$strSQL .= ", TipoPropiedad";
	$strSQL .= ") VALUES (";
	$strSQL .= " '".$AgenteSolicitante."'";
	$strSQL .= ", ".$IdImportacion."";
	$strSQL .= ", '".$CodigoREEEmpresaEmisora."'";
	$strSQL .= ", '".$CodigoREEEmpresaDestino."'";
	$strSQL .= ", '".$CodigoDelProceso."'";
	$strSQL .= ", '".$CodigoDePaso."'";
	$strSQL .= ", '".$CodigoDeSolicitud."'";
	$strSQL .= ", '".$SecuencialDeSolicitud."'";
	$strSQL .= ", '".$Codigo."'";
	$strSQL .= ", '".$FechaSolicitud."'";
	$strSQL .= ", '".$Version."'";
	$strSQL .= ", '".$TarifaATR."'";
	$strSQL .= ", '".$TipoContrato."'";
	for($i=0; $i<count($arrPotenciaPeriodo);$i++){
		$strSQL .= ", '".$arrPotenciaPeriodo[$i]."'";
	}
	$strSQL .= ", '".$ImporteImpagos."'";
	$strSQL .= ", '".$DepositoGarantia."'";
	$strSQL .= ", '".$ImporteDeposito."'";
	$strSQL .= ", '".$TipoTitular."'";
	$strSQL .= ", '".$Titular."'";
	$strSQL .= ", '".$Calle."'";
	$strSQL .= ", '".$Poblacion."'";
	$strSQL .= ", '".$Provincia."'";
	$strSQL .= ", '".$Pais."'";
	$strSQL .= ", '".$Provincia2."'";
	$strSQL .= ", '".$Municipio."'";
	$strSQL .= ", '".$Poblacion3."'";
	$strSQL .= ", '".$Calle4."'";
	$strSQL .= ", '".$CodPostal."'";
	$strSQL .= ", '".$DerechosAcceso."'";
	$strSQL .= ", '".$FechaLimiteDerechosAcceso."'";
	$strSQL .= ", '".$DerechosExtension."'";
	$strSQL .= ", '".$FechaLimiteDerechos."'";
	$strSQL .= ", '".$PotenciaMaximaAutorizadaPorBIE."'";
	$strSQL .= ", '".$PotenciaMaximaAutorizadaPorActa."'";
	$strSQL .= ", '".$PotenciaDisponibleCaja."'";
	$strSQL .= ", '".$InstalacionICP."'";
	$strSQL .= ", '".$SuministroLiberalizado."'";
	$strSQL .= ", '".$TipoPuntoMedida."'";
	$strSQL .= ", '".$Tension."'";
	$strSQL .= ", '".$ViviendaHabitual."'";
	$strSQL .= ", '".$FechaUltimoMovimientoContratacion."'";
	$strSQL .= ", '".$FechaCambioComercializador."'";
	$strSQL .= ", '".$FechaAlta."'";
	$strSQL .= ", '".$FechaUltimaLectura."'";
	$strSQL .= ", '".$TipoPerfilDeConsumo."'";
	
	$strSQL .= ", '".$TipoAparato."'";
	$strSQL .= ", '".$TipoEquipo."'";
	$strSQL .= ", '".$TipoPropiedad."'";
	$strSQL .= ")";
	 
	$res = 0;
	if(ExecPA($strSQL))
	{
		$IdP3 = mysql_insert_id();
		
		$strSQL = "INSERT INTO tbP3ConsumoPeriodo (";
		$strSQL .= " IdP3";
		$strSQL .= ", AnioFacturacion";
		$strSQL .= ", TipoFacturacion";
		$strSQL .= ", FechaLecturaInicial";
		$strSQL .= ", FechaLecturaFinal";
		$strSQL .= ", CodigoTarifa";
		$strSQL .= ", EnergiaPH1";
		$strSQL .= ", EnergiaPH2";
		$strSQL .= ", EnergiaPH3";
		$strSQL .= ", EnergiaPH4";
		$strSQL .= ", EnergiaPH5";
		$strSQL .= ", EnergiaPH6";
		$strSQL .= ", EnergiaPH7";
		$strSQL .= ", ReactivaPH1";
		$strSQL .= ", ReactivaPH2";
		$strSQL .= ", ReactivaPH3";
		$strSQL .= ", ReactivaPH4";
		$strSQL .= ", ReactivaPH5";
		$strSQL .= ", ReactivaPH6";
		$strSQL .= ", ReactivaPH7";
		$strSQL .= ", PotenciaPH1";
		$strSQL .= ", PotenciaPH2";
		$strSQL .= ", PotenciaPH3";
		$strSQL .= ", PotenciaPH4";
		$strSQL .= ", PotenciaPH5";
		$strSQL .= ", PotenciaPH6";
		$strSQL .= ", PotenciaPH7";
		$strSQL .= ") VALUES (";
		$strSQL .= " ".$IdP3."";
		$strSQL .= ", ".$AnioFacturacion."";
		$strSQL .= ", ".$TipoFacturacion."";
		$strSQL .= ", '".$FechaLecturaInicial."'";
		$strSQL .= ", '".$FechaLecturaFinal."'";
		$strSQL .= ", '".$CodigoTarifa."'";
		
		for($i=0; $i<count($arrEnergiaPH);$i++){
			$strSQL .= ", ".$arrEnergiaPH[$i]."";
		}
		
		for($i=0; $i<count($arrReactivaPH);$i++){
			$strSQL .= ", ".$arrReactivaPH[$i]."";
		}
		for($i=0; $i<count($arrPotenciaPH);$i++){
			$strSQL .= ", ".$arrPotenciaPH[$i]."";
		}
		$strSQL .= ")";
		
		$res = ExecPA($strSQL);
	}
	*/
	
	return $res;
}

function valorXML($att, $sxe) {
	$item =  $sxe->xpath('//item[@name="' . $att . '"]');
	return $item[0];
}

function atributoXML($att, $sxe) {
	$item =  $sxe->xpath('//item[@attributes="' . $att . '"]');
	return $item[0];
}


function ExportarXML(&$strError, $archivo, $directorio, $arrExtensiones=0, $strXml)
{
	$Fichero = $archivo;
	$AgenteSolicitante = $_SESSION["sPANEL_Usuario_ID"];
	$arrNombreFichero = explode('.', $Fichero);
	$rutaFicheroXML = "";
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
		if(file_exists(RAIZ_FILE_SYSTEM."/".DIR_UPLOAD.$directorio.$Fichero)){
		$dado = rand(1, 100);
				$ficheroNombre .= "-".str_pad($dado, 2, "0", STR_PAD_LEFT);
		}
		//$ficheroNombre = elimina_especialchars($ficheroNombre);
		//echo $ficheroNombre;
		//echo stripAccents($ficheroNombre);
		$Fichero = $ficheroNombre.".".$ficheroExtension;
		//Inicializo la variable de retorno con el nombre del fichero para tenerlo en la pagina llamadora
		$strError = $Fichero.'|'.$logError;
		
		$rutaFicheroXML = RAIZ_FILE_SYSTEM.'/'.DIR_UPLOAD.$directorio.$Fichero;
		//echo $rutaFicheroXML;
	}else{
		$strError = $Fichero.'|';
		$strError .= "Extensión no admitida. ".$ficheroExtension."<br />";
	}
	
	/*
	<?xml version="1.0" encoding="ISO-8859-1"?>
	<MensajeSolicitudInformacionAlRegistrodePS xmlns="http://localhost/elegibilidad" AgenteSolicitante="0737">
		<Cabecera>
			<CodigoREEEmpresaEmisora>0737</CodigoREEEmpresaEmisora>
			<CodigoREEEmpresaDestino>0021</CodigoREEEmpresaDestino>
			<CodigoDelProceso>P0</CodigoDelProceso>
			<CodigoDePaso>01</CodigoDePaso>
			<CodigoDeSolicitud>0000016312E5</CodigoDeSolicitud>
			<SecuencialDeSolicitud>01</SecuencialDeSolicitud>
			<Codigo>ES0021000000986687YF  </Codigo>
			<FechaSolicitud>2012-06-04T12:03:00</FechaSolicitud>
			<Version>02</Version>
		</Cabecera>
	</MensajeSolicitudInformacionAlRegistrodePS>
	*/
	if($rutaFicheroXML != ""){ 	
		file_put_contents($rutaFicheroXML, $strXml);
		
		return 1;
	}else{
		return 0;
	}
		
}

function UltimoPeriodoFacturadoCUPS($CUPS)
{
	
	$IdP3 = intval(ExecQueryValue("SELECT IdP3 FROM tbP3 WHERE Codigo = '".$CUPS."'"));
	
	if($IdP3 > 0)
	{
		$strSQL = "SELECT ";
		$strSQL .= " IdP3";
		$strSQL .= ", AnioFacturacion";
		$strSQL .= ", TipoFacturacion";
		$strSQL .= ", FechaLecturaInicial";
		$strSQL .= ", FechaLecturaFinal";
		$strSQL .= ", CodigoTarifa";
		$strSQL .= " FROM tbP3ConsumoPeriodo";
		$strSQL .= " WHERE IdP3 = ".$IdP3;
		$strSQL .= " ORDER BY FechaLecturaFinal DESC";
		$strSQL .= " LIMIT 1";
		
		$row = ExecQuerySingle($strSQL);
	}
	
	if($row != '')
		return $row;
	else
		return false;
}

function FechaUltimaFacturaCUPS($CUPS)
{

	$IdP3 = intval(ExecQueryValue("SELECT IdP3 FROM tbP3 WHERE Codigo = '".$CUPS."'"));

	if($IdP3 > 0)
	{
		$strSQL = "SELECT ";
		$strSQL .= " IdP3";
		$strSQL .= ", AnioFacturacion";
		$strSQL .= ", TipoFacturacion";
		$strSQL .= ", FechaLecturaInicial";
		$strSQL .= ", FechaLecturaFinal";
		$strSQL .= ", CodigoTarifa";
		$strSQL .= " FROM tbP3ConsumoPeriodo";
		$strSQL .= " WHERE IdP3 = ".$IdP3;
		$strSQL .= " ORDER BY FechaLecturaFinal DESC";
		$strSQL .= " LIMIT 1";

		$row = ExecQuerySingle($strSQL);
	}

	if($row["FechaLecturaFinal"] != '')
		return $row["FechaLecturaFinal"];
	else
		return false;
}

function CalculaFechaProximaFacturaCUPS($CUPS)
{

	$IdP3 = intval(ExecQueryValue("SELECT IdP3 FROM tbP3 WHERE Codigo = '".$CUPS."'"));

	if($IdP3 > 0)
	{
		$strSQL = "SELECT ";
		$strSQL .= " IdP3";
		$strSQL .= ", AnioFacturacion";
		$strSQL .= ", TipoFacturacion";
		$strSQL .= ", FechaLecturaInicial";
		$strSQL .= ", FechaLecturaFinal";
		$strSQL .= ", CodigoTarifa";
		$strSQL .= " FROM tbP3ConsumoPeriodo";
		$strSQL .= " WHERE IdP3 = ".$IdP3;
		$strSQL .= " ORDER BY FechaLecturaFinal DESC";
		$strSQL .= " LIMIT 1";

		$row = ExecQuerySingle($strSQL);
	}

	$fechaProximaFactura = '';
	if($row["FechaLecturaInicial"] != '' && $row["FechaLecturaFinal"] != ''){
		$dias = DiasEntreFechas($row["FechaLecturaInicial"], $row["FechaLecturaFinal"]);

		$fechaProximaFactura = add_date($row["FechaLecturaFinal"], $dias);
	}
	
	return $fechaProximaFactura;
}

// Calcula el numero de dias entre dos fechas.
// Da igual el formato de las fechas (dd-mm-aaaa o aaaa-mm-dd),
// pero el caracter separador debe ser un guión.
function DiasEntreFechas($fechaIni, $fechaFin){
	return ((strtotime($fechaFin)-strtotime($fechaIni))/86400);
}

function PeriodoFacturadoCUPSAFecha($CUPS, $FechaLecturaFinal)
{

	$IdP3 = intval(ExecQueryValue("SELECT IdP3 FROM tbP3 WHERE Codigo = '".$CUPS."'"));

	if($IdP3 > 0)
	{
		$strSQL = "SELECT ";
		$strSQL .= " IdP3";
		$strSQL .= ", AnioFacturacion";
		$strSQL .= ", TipoFacturacion";
		$strSQL .= ", FechaLecturaInicial";
		$strSQL .= ", FechaLecturaFinal";
		$strSQL .= ", CodigoTarifa";
		$strSQL .= ", EnergiaPH1, EnergiaPH2, EnergiaPH3, EnergiaPH4, EnergiaPH5, EnergiaPH6, EnergiaPH7";
		$strSQL .= ", ReactivaPH1, ReactivaPH2, ReactivaPH3, ReactivaPH4, ReactivaPH5, ReactivaPH6, ReactivaPH7";
		$strSQL .= ", PotenciaPH1, PotenciaPH2, PotenciaPH3, PotenciaPH4, PotenciaPH5, PotenciaPH6, PotenciaPH7";
		$strSQL .= " FROM tbP3ConsumoPeriodo";
		$strSQL .= " WHERE IdP3 = ".$IdP3;
		$strSQL .= " AND FechaLecturaFinal = '".$FechaLecturaFinal."'";
		$strSQL .= " LIMIT 1";

		$row = ExecQuerySingle($strSQL);
	}

	if($row != '')
		return $row;
	else
		return false;
}

function GenerarCodigoSolicitud()
{
	//Formato
	//$CodigoSolicitud = '0000016312E5';
	//$UltimoCodigoSolicitud = ExecQueryValue("SELECT CodigoSolicitud FROM tbExportacion WHERE CodigoREEEmpresaDestino = '".$CodigoREEEmpresaDestino."' ORDER BY IdExportacion DESC LIMIT 1");
	$UltimoCodigoSolicitud = ExecQueryValue("SELECT CodigoSolicitud FROM tbExportacion ORDER BY IdExportacion DESC LIMIT 1");
	$NumUltimoCodigoSolicitud = floatvalue(substr($UltimoCodigoSolicitud,0,10));
	$NumUltimoCodigoSolicitud++;
	$CodigoSolicitud = str_pad($NumUltimoCodigoSolicitud, 10, "0", STR_PAD_LEFT).'E5';
	
	return $CodigoSolicitud;
}

function NumeroPeriodosFacturadosCUPS($CUPS)
{
	$PA="SELECT PotenciaPeriodo1, PotenciaPeriodo2, PotenciaPeriodo3, PotenciaPeriodo4, PotenciaPeriodo5, PotenciaPeriodo6, PotenciaPeriodo7, PotenciaPeriodo8, PotenciaPeriodo9, PotenciaPeriodo10 FROM tbP3 WHERE Codigo = '".$CUPS."'";
	$resultado = ExecPA($PA);
	$NumElementos = mysql_affected_rows();
	
	if($NumElementos > 0){
		for($i=1; $i<11;$i++){
			if(floatvalue(ValorCelda($resultado,0,"PotenciaPeriodo".$i)) > 0){
				$arrPotenciaPeriodo[$i-1] = floatvalue(ValorCelda($resultado,0,"PotenciaPeriodo".$i));
			}
		}
		return intval(count($arrPotenciaPeriodo));
	}else{
		return 0;
	}
	
}

function PeriodosContratadosCUPS($CUPS)
{
	$PA="SELECT PotenciaPeriodo1, PotenciaPeriodo2, PotenciaPeriodo3, PotenciaPeriodo4, PotenciaPeriodo5, PotenciaPeriodo6, PotenciaPeriodo7, PotenciaPeriodo8, PotenciaPeriodo9, PotenciaPeriodo10 FROM tbP3 WHERE Codigo = '".$CUPS."'";
	$resultado = ExecPA($PA);
	$NumElementos = mysql_affected_rows();

	if($NumElementos > 0){
		for($i=1; $i<11;$i++){
			if(floatvalue(ValorCelda($resultado,0,"PotenciaPeriodo".$i)) > 0){
				$arrPotenciaPeriodo[$i-1] = floatvalue(ValorCelda($resultado,0,"PotenciaPeriodo".$i));
			}
		}
		return $arrPotenciaPeriodo;
	}else{
		return 0;
	}
}

function GenerarFacturasDeCUPS($Cliente_ID, $Contrato_ID, $CUPS_ID)
{
	$PA = "SELECT Factura_ID, IdP3, IdP3ConsumoPeriodo FROM tbFactura WHERE CUPS_ID = ".$CUPS_ID."";
	//echo $PA;
	$resultado = ExecPA($PA);
	$NumElementos = mysql_affected_rows();
	//Recorro todos los periodos a facturar del CUPS para generar las facturas...
	for($i=0;$i<$NumElementos;$i++)
	{
		$Factura_ID = intval(ValorCelda($resultado,$i,"Factura_ID"));
		
		$PA = "DELETE FROM tbFacturaDetalle WHERE Factura_ID = ".$Factura_ID;
		ExecPA($PA);
		$PA = "DELETE FROM tbFactura WHERE Factura_ID = ".$Factura_ID;
		ExecPA($PA);
	}
	//$Factura_ID = intval(ExecQueryValue("SELECT Factura_ID FROM tbFactura WHERE CUPS_ID = ".$CUPS_ID));
	//$PA = "DELETE FROM tbFactura WHERE CUPS_ID = ".$CUPS_ID;
	//ExecPA($PA);

	$CUPS = stripslashes(ExecQueryValue("SELECT Codigo FROM tbCUPS WHERE CUPS_ID = ".$CUPS_ID.""));
	$CUPS = substr($CUPS, 0, 20);
	
	$mensaje .= "<strong>".TextoDeIdioma("CUPS").": ".$CUPS." - ".TextoDeIdioma("Facturas_borradas_con_exito")."!.</strong><br />";
	
	$IdP3 = intval(ExecQueryValue("SELECT IdP3 FROM tbP3 WHERE Codigo = '".$CUPS."'"));
	
	if($IdP3 > 0)
	{
		$PA = "SELECT IdP3, IdP3ConsumoPeriodo FROM tbP3ConsumoPeriodo WHERE IdP3 = ".$IdP3."";
		//$PA .= " ORDER BY FechaLecturaFinal DESC LIMIT 1";
		//echo $PA;
		$resultado = ExecPA($PA);
		$NumElementos = mysql_affected_rows();
		//Recorro todos los periodos a facturar del CUPS para generar las facturas...
		for($i=0;$i<$NumElementos;$i++)
		{
			$IdP3								 	 		= intval(ValorCelda($resultado,$i,"IdP3"));
			$IdP3ConsumoPeriodo 	 		= intval(ValorCelda($resultado,$i,"IdP3ConsumoPeriodo"));

			GenerarFacturaDePeriodo($Cliente_ID, $Contrato_ID, $CUPS_ID, $IdP3, $IdP3ConsumoPeriodo);
		}
	}
	
	$mensaje .= "<strong>".TextoDeIdioma("CUPS").": ".$CUPS." - ".TextoDeIdioma("Facturas_generadas_con_exito").": ".$i.".</strong><br />";
	
	return $mensaje;
}

function GenerarFacturaDePeriodo($Cliente_ID, $Contrato_ID, $CUPS_ID, $IdP3, $IdP3ConsumoPeriodo)
{
	$strError = "";
	//Generacion de facturas de un periodo concreto
	$PA = "SELECT IdP3, IdP3ConsumoPeriodo, FechaLecturaInicial, FechaLecturaFinal, CodigoTarifa FROM tbP3ConsumoPeriodo WHERE IdP3 = ".$IdP3." AND IdP3ConsumoPeriodo = ".$IdP3ConsumoPeriodo;
	//echo $PA;
	$resultado = ExecPA($PA);
	$NumElementos = mysql_affected_rows();
	//Recorro todos los periodos a facturar del CUPS para generar las facturas...
	if($NumElementos > 0){
		$IdP3								 	 		= intval(ValorCelda($resultado,0,"IdP3"));
		$IdP3ConsumoPeriodo 	 		= intval(ValorCelda($resultado,0,"IdP3ConsumoPeriodo"));
		$TarifaATR		 			 			= (ValorCelda($resultado,0,"CodigoTarifa"));
		$TarifaATR_ID 						= ExecQueryValue("SELECT IdTarifaATR FROM tbTarifaATRIntegral WHERE Codigo = '".$TarifaATR."'");
		$FechaIniConsumoPeriodo		= (ValorCelda($resultado,0,"FechaLecturaInicial"));
		$FechaFinConsumoPeriodo		= (ValorCelda($resultado,0,"FechaLecturaFinal"));
	}
	$FechaFactura = date('Y-m-d H:i:s');
	$DescFactura = "Factura generada el: ".fechaHoraNormal($FechaFactura);
	$BaseImponible = 0;
	$IIEE = 0;
	$BaseImponibleIIEE = 0;
	$OtrosConceptos = 0;
	$AlquilerEquipos = 0;
	$ImporteBase = 0;
	$ImporteTotal = 0;

	$PA = "INSERT INTO tbFactura (";
	$PA .= " Cliente_ID";
	$PA .= ", CUPS_ID";
	$PA .= ", Contrato_ID";
	$PA .= ", IdP3";
	$PA .= ", IdP3ConsumoPeriodo";
	$PA .= ", FechaIniConsumoPeriodo";
	$PA .= ", FechaFinConsumoPeriodo";
	$PA .= ", TarifaATR_ID";
	$PA .= ", Descripcion";
	$PA .= ", FechaFactura";
	$PA .= ", BaseImponible";
	$PA .= ", IIEE";
	$PA .= ", BaseImponibleIIEE";
	$PA .= ", OtrosConceptos";
	$PA .= ", AlquilerEquipos";
	$PA .= ", ImporteBase";
	$PA .= ", ImporteTotal";
	$PA .= ") VALUES (";
	$PA .= " ".$Cliente_ID."";
	$PA .= ", ".$CUPS_ID."";
	$PA .= ", ".$Contrato_ID."";
	$PA .= ", ".$IdP3."";
	$PA .= ", ".$IdP3ConsumoPeriodo."";
	$PA .= ", '".$FechaIniConsumoPeriodo."'";
	$PA .= ", '".$FechaFinConsumoPeriodo."'";
	$PA .= ", ".$TarifaATR_ID."";
	$PA .= ", '".$DescFactura."'";
	$PA .= ", '".$FechaFactura."'";
	$PA .= ", ".$BaseImponible."";
	$PA .= ", ".$IIEE."";
	$PA .= ", ".$BaseImponibleIIEE."";
	$PA .= ", ".$OtrosConceptos."";
	$PA .= ", ".$AlquilerEquipos."";
	$PA .= ", ".$ImporteBase."";
	$PA .= ", ".$ImporteTotal."";
	$PA .= ")";
	//echo $PA;
	ExecPA($PA);
	$Factura_ID = mysql_insert_id();
	//$mensaje .= "<strong>".TextoDeIdioma("Registro_creado_con_exito")." - ".TextoDeIdioma("Factura").": ".$Factura_ID.".</strong><br />";

	//Calculo valores de la factura...
	$CUPS = stripslashes(ExecQueryValue("SELECT Codigo FROM tbCUPS WHERE CUPS_ID = ".$CUPS_ID.""));
	$CUPS = substr($CUPS, 0, 20);
	$FechaLectura = $FechaFinConsumoPeriodo;

	$arrPotenciaPeriodo = PeriodosContratadosCUPS($CUPS);
	$PCPeriodo1 = floatval($arrPotenciaPeriodo[0]);
	$PCPeriodo2 = floatval($arrPotenciaPeriodo[1]);
	$PCPeriodo3 = floatval($arrPotenciaPeriodo[2]);
	$PCPeriodo4 = floatval($arrPotenciaPeriodo[3]);
	$PCPeriodo5 = floatval($arrPotenciaPeriodo[4]);
	$PCPeriodo6 = floatval($arrPotenciaPeriodo[5]);
	$PCPeriodo7 = floatval($arrPotenciaPeriodo[6]);

	//DEBERIAN SER 10 ?????????????????
	$rowConsumoPeriodo = PeriodoFacturadoCUPSAFecha($CUPS, $FechaLectura);
	//print_r($rowConsumoPeriodo);
	$VCPotenciaP1 = floatval($rowConsumoPeriodo["PotenciaPH1"]);
	$VCPotenciaP2 = floatval($rowConsumoPeriodo["PotenciaPH2"]);
	$VCPotenciaP3 = floatval($rowConsumoPeriodo["PotenciaPH3"]);
	$VCPotenciaP4 = floatval($rowConsumoPeriodo["PotenciaPH4"]);
	$VCPotenciaP5 = floatval($rowConsumoPeriodo["PotenciaPH5"]);
	$VCPotenciaP6 = floatval($rowConsumoPeriodo["PotenciaPH6"]);
	$VCPotenciaP7 = floatval($rowConsumoPeriodo["PotenciaPH7"]);

	$VCEnergiaP1 = floatval($rowConsumoPeriodo["EnergiaPH1"]);
	$VCEnergiaP2 = floatval($rowConsumoPeriodo["EnergiaPH2"]);
	$VCEnergiaP3 = floatval($rowConsumoPeriodo["EnergiaPH3"]);
	$VCEnergiaP4 = floatval($rowConsumoPeriodo["EnergiaPH4"]);
	$VCEnergiaP5 = floatval($rowConsumoPeriodo["EnergiaPH5"]);
	$VCEnergiaP6 = floatval($rowConsumoPeriodo["EnergiaPH6"]);
	$VCEnergiaP7 = floatval($rowConsumoPeriodo["EnergiaPH7"]);
	
	$VCReactivaP1 = floatval($rowConsumoPeriodo["ReactivaPH1"]);
	$VCReactivaP2 = floatval($rowConsumoPeriodo["ReactivaPH2"]);
	$VCReactivaP3 = floatval($rowConsumoPeriodo["ReactivaPH3"]);
	$VCReactivaP4 = floatval($rowConsumoPeriodo["ReactivaPH4"]);
	$VCReactivaP5 = floatval($rowConsumoPeriodo["ReactivaPH5"]);
	$VCReactivaP6 = floatval($rowConsumoPeriodo["ReactivaPH6"]);
	$VCReactivaP7 = floatval($rowConsumoPeriodo["ReactivaPH7"]);
	
	$FechaHoy = date ('Y-m-d');
	//Obtengo Factores de tablas de tarifas
	$rowEstado = ExecQuerySingle("SELECT ImporteP1, ImporteP2, ImporteP3, ImporteP4, ImporteP5, ImporteP6, ImporteP7 FROM tbTarifaEstado WHERE TarifaATR_ID = ".$TarifaATR_ID." AND FechaVigor < '".$FechaHoy."'");
	$FactorEstadoP1 = floatval($rowEstado["ImporteP1"]);
	$FactorEstadoP2 = floatval($rowEstado["ImporteP2"]);
	$FactorEstadoP3 = floatval($rowEstado["ImporteP3"]);
	$FactorEstadoP4 = floatval($rowEstado["ImporteP4"]);
	$FactorEstadoP5 = floatval($rowEstado["ImporteP5"]);
	$FactorEstadoP6 = floatval($rowEstado["ImporteP6"]);
	$FactorEstadoP7 = floatval($rowEstado["ImporteP7"]);

	$rowComer = ExecQuerySingle("SELECT ImporteP1, ImporteP2, ImporteP3, ImporteP4, ImporteP5, ImporteP6, ImporteP7 FROM tbTarifaComer WHERE TarifaATR_ID = ".$TarifaATR_ID." AND FechaVigor < '".$FechaHoy."'");
	$FactorComerP1 = floatval($rowComer["ImporteP1"]);
	$FactorComerP2 = floatval($rowComer["ImporteP2"]);
	$FactorComerP3 = floatval($rowComer["ImporteP3"]);
	$FactorComerP4 = floatval($rowComer["ImporteP4"]);
	$FactorComerP5 = floatval($rowComer["ImporteP5"]);
	$FactorComerP6 = floatval($rowComer["ImporteP6"]);
	$FactorComerP7 = floatval($rowComer["ImporteP7"]);

	$rowPeaje = ExecQuerySingle("SELECT ImporteP1, ImporteP2, ImporteP3, ImporteP4, ImporteP5, ImporteP6, ImporteP7 FROM tbTarifaPeaje WHERE TarifaATR_ID = ".$TarifaATR_ID." AND FechaVigor < '".$FechaHoy."'");
	$FactorPeajeP1 = floatval($rowPeaje["ImporteP1"]);
	$FactorPeajeP2 = floatval($rowPeaje["ImporteP2"]);
	$FactorPeajeP3 = floatval($rowPeaje["ImporteP3"]);
	$FactorPeajeP4 = floatval($rowPeaje["ImporteP4"]);
	$FactorPeajeP5 = floatval($rowPeaje["ImporteP5"]);
	$FactorPeajeP6 = floatval($rowPeaje["ImporteP6"]);
	$FactorPeajeP7 = floatval($rowPeaje["ImporteP7"]);
	if ($FactorEstadoP1=="" || $FactorEstadoP2=="" || $FactorEstadoP3=="" || $FactorEstadoP4=="" || $FactorEstadoP5=="" || $FactorEstadoP6=="" || $FactorEstadoP7==""){
		$strError .= "Importes no encontrados en tabla auxiliar de ESTADO para la tarifa: ".$TarifaATR_ID." en vigor con fecha anterior a: ".$FechaHoy;
	}
	if ($FactorComerP1=="" || $FactorComerP2=="" || $FactorComerP3=="" || $FactorComerP4=="" || $FactorComerP5=="" || $FactorComerP6=="" || $FactorComerP7==""){
		$strError .= "Importes no encontrados en tabla auxiliar de COMERCIALIZADORA para la tarifa: ".$TarifaATR_ID." en vigor con fecha anterior a: ".$FechaHoy;
	}
	if ($FactorPeajeP1=="" || $FactorPeajeP2=="" || $FactorPeajeP3=="" || $FactorPeajeP4=="" || $FactorPeajeP5=="" || $FactorPeajeP6=="" || $FactorPeajeP7==""){
		$strError .= "Importes no encontrados en tabla auxiliar de COMERCIALIZADORA para la tarifa: ".$TarifaATR_ID." en vigor con fecha anterior a: ".$FechaHoy;
	}

	//CALCULO POTENCIA //////////////////////////////////////////////
	if($VCPotenciaP1 > 0)
	{
		$FactorPotenciaP1 = $VCPotenciaP1 / $PCPeriodo1;
		if($FactorPotenciaP1 <= 0.85){
			$PotenciaP1Facturar = floatval(0.85 * $PCPeriodo1);
			$ImportePotenciaP1 = floatval($PotenciaP1Facturar * $FactorEstadoP1);
		}else{
			if($FactorPotenciaP1 > 0.85 && $FactorPotenciaP1 < 1.05){
				$PotenciaP1Facturar = floatval($VCPotenciaP1);
				$ImportePotenciaP1 = floatval($PotenciaP1Facturar * $FactorEstadoP1);
			}else{
				if($FactorPotenciaP1 >= 1.05){
					$PotenciaP1Facturar = floatval($VCPotenciaP1 + (2 * ($VCPotenciaP1 - (1.05 * $PCPeriodo1))));
					$ImportePotenciaP1 = floatval($PotenciaP1Facturar * $FactorEstadoP1);
				}
			}
		}
		//echo "<br/>FactorPotenciaP1: ".$FactorPotenciaP1." <br/>PCPeriodo1: ".$PCPeriodo1." <br/>FactorEstadoP1: ".$FactorEstadoP1;
	}else{
		$ImportePotenciaP1 = 0;
	}
	//echo "ImportePotenciaP1: ".$ImportePotenciaP1."<br />";
	
	$ImportePotenciaP1 = 0;
	$ImportePotenciaP2 = 0;
	$ImportePotenciaP3 = 0;
	$ImportePotenciaP4 = 0;
	$ImportePotenciaP5 = 0;
	$ImportePotenciaP6 = 0;
	$ImportePotenciaP7 = 0;
	
	//CALCULO ENERGIA //////////////////////////////////////////////
	if($VCEnergiaP1 > 0)
	{
		$ImporteEnergiaP1 = floatval($VCEnergiaP1 * ($FactorPeajeP1 + $FactorComerP1));
	}else{
		$ImporteEnergiaP1 = 0;
	}
	//echo "ImporteEnergiaP1: ".$ImporteEnergiaP1."<br />";
	if($VCEnergiaP2 > 0)
	{
		$ImporteEnergiaP2 = floatval($VCEnergiaP2 * ($FactorPeajeP2 + $FactorComerP2));
	}else{
		$ImporteEnergiaP2 = 0;
	}
	if($VCEnergiaP3 > 0)
	{
		$ImporteEnergiaP3 = floatval($VCEnergiaP3 * ($FactorPeajeP3 + $FactorComerP3));
	}else{
		$ImporteEnergiaP3 = 0;
	}
	if($VCEnergiaP4 > 0)
	{
		$ImporteEnergiaP4 = floatval($VCEnergiaP4 * ($FactorPeajeP4 + $FactorComerP4));
	}else{
		$ImporteEnergiaP4 = 0;
	}
	if($VCEnergiaP5 > 0)
	{
		$ImporteEnergiaP5 = floatval($VCEnergiaP5 * ($FactorPeajeP5 + $FactorComerP5));
	}else{
		$ImporteEnergiaP5 = 0;
	}
	if($VCEnergiaP6 > 0)
	{
		$ImporteEnergiaP6 = floatval($VCEnergiaP6 * ($FactorPeajeP6 + $FactorComerP6));
	}else{
		$ImporteEnergiaP6 = 0;
	}
	if($VCEnergiaP7 > 0)
	{
		$ImporteEnergiaP7 = floatval($VCEnergiaP7 * ($FactorPeajeP7 + $FactorComerP7));
	}else{
		$ImporteEnergiaP7 = 0;
	}
	
	//CALCULO REACTIVA //////////////////////////////////////////////
	//echo "SELECT Importe FROM tbTarifaReactiva WHERE Porcentaje = 33 AND TarifaATR_ID = ".$TarifaATR_ID." AND FechaVigor < '".$FechaHoy."'"."<br />";
	$FactorReactiva_33 = ExecQueryValue("SELECT Importe FROM tbTarifaReactiva WHERE Porcentaje = 33 AND TarifaATR_ID = ".$TarifaATR_ID." AND FechaVigor < '".$FechaHoy."'");
	$FactorReactiva_75 = ExecQueryValue("SELECT Importe FROM tbTarifaReactiva WHERE Porcentaje = 75 AND TarifaATR_ID = ".$TarifaATR_ID." AND FechaVigor < '".$FechaHoy."'");
	
	if ($FactorReactiva_33=="" || $FactorReactiva_75==""){
		$strError .= "Importes no encontrados en tabla auxiliar de REACTIVA para la tarifa: ".$TarifaATR_ID." en vigor con fecha anterior a: ".$FechaHoy;
	}
	
	if($VCReactivaP1 > 0)
	{
		$FactorReactivaP1 = $VCReactivaP1 / $VCEnergiaP1;
		if($FactorReactivaP1 >= 0.75){
			$ImporteExcedenteReactivaP1 = floatval($VCReactivaP1 - (0.75 * $VCEnergiaP1));
			$ImporteReactivaP1 = floatval($ImporteExcedenteReactivaP1 * $FactorReactiva_75);
		}else{
			if($FactorReactivaP1 >= 0.33){
				$ImporteExcedenteReactivaP1 = floatval($VCReactivaP1 - (0.33 * $VCEnergiaP1));
				$ImporteReactivaP1 = floatval($ImporteExcedenteReactivaP1 * $FactorReactiva_33);
			}else{
				$ImporteReactivaP1 = 0;
			}
		}
		//echo "<br/>VCEnergiaP1: ".$VCEnergiaP1." <br/>VCReactivaP1: ".$VCReactivaP1." <br/>FactorReactivaP1: ".$FactorReactivaP1." <br/>ImporteExcedenteReactivaP1: ".$ImporteExcedenteReactivaP1." <br/>ImporteReactivaP1: ".$ImporteReactivaP1." <br />FactorReactiva_75: ".$FactorReactiva_75." <br />FactorReactiva_33: ".$FactorReactiva_33;
	}else{
		$ImporteReactivaP1 = 0;
	}
	//echo "ImporteReactivaP1: ".$ImporteReactivaP1."<br />";
	
	if($VCReactivaP2 > 0)
	{
		$FactorReactivaP2 = $VCReactivaP2 / $VCEnergiaP2;
		if($FactorReactivaP2 >= 0.75){
			$ImporteExcedenteReactivaP2 = floatval($VCReactivaP2 - (0.75 * $VCEnergiaP2));
			$ImporteReactivaP2 = floatval($ImporteExcedenteReactivaP2 * $FactorReactiva_75);
		}else{
			if($FactorReactivaP2 >= 0.33){
				$ImporteExcedenteReactivaP2 = floatval($VCReactivaP2 - (0.33 * $VCEnergiaP2));
				$ImporteReactivaP2 = floatval($ImporteExcedenteReactivaP2 * $FactorReactiva_33);
			}else{
				$ImporteReactivaP2 = 0;
			}
		}
		//echo "<br/>VCEnergiaP2: ".$VCEnergiaP2." <br/>VCReactivaP2: ".$VCReactivaP2." <br/>FactorReactivaP2: ".$FactorReactivaP2." <br/>ImporteExcedenteReactivaP2: ".$ImporteExcedenteReactivaP2." <br/>ImporteReactivaP2: ".$ImporteReactivaP2." <br />FactorReactiva_75: ".$FactorReactiva_75." <br />FactorReactiva_33: ".$FactorReactiva_33;
	}else{
		$ImporteReactivaP2 = 0;
	}
	//echo "ImporteReactivaP2: ".$ImporteReactivaP2."<br />";

	$ImporteReactivaP3 = 0;
	$ImporteReactivaP4 = 0;
	$ImporteReactivaP5 = 0;
	$ImporteReactivaP6 = 0;
	$ImporteReactivaP7 = 0;

	$PA = "INSERT INTO tbFacturaDetalle (";
	$PA .= " Factura_ID";
	$PA .= ", PCPeriodo1";
	$PA .= ", PCPeriodo2";
	$PA .= ", PCPeriodo3";
	$PA .= ", PCPeriodo4";
	$PA .= ", PCPeriodo5";
	$PA .= ", PCPeriodo6";
	$PA .= ", PCPeriodo7";
	$PA .= ", VCPotenciaP1";
	$PA .= ", VCPotenciaP2";
	$PA .= ", VCPotenciaP3";
	$PA .= ", VCPotenciaP4";
	$PA .= ", VCPotenciaP5";
	$PA .= ", VCPotenciaP6";
	$PA .= ", VCPotenciaP7";
	$PA .= ", VCEnergiaP1";
	$PA .= ", VCEnergiaP2";
	$PA .= ", VCEnergiaP3";
	$PA .= ", VCEnergiaP4";
	$PA .= ", VCEnergiaP5";
	$PA .= ", VCEnergiaP6";
	$PA .= ", VCEnergiaP7";
	$PA .= ", VCReactivaP1";
	$PA .= ", VCReactivaP2";
	$PA .= ", VCReactivaP3";
	$PA .= ", VCReactivaP4";
	$PA .= ", VCReactivaP5";
	$PA .= ", VCReactivaP6";
	$PA .= ", VCReactivaP7";
	$PA .= ", ImporteEnergiaP1";
	$PA .= ", ImporteEnergiaP2";
	$PA .= ", ImporteEnergiaP3";
	$PA .= ", ImporteEnergiaP4";
	$PA .= ", ImporteEnergiaP5";
	$PA .= ", ImporteEnergiaP6";
	$PA .= ", ImporteEnergiaP7";
	$PA .= ", ImporteReactivaP1";
	$PA .= ", ImporteReactivaP2";
	$PA .= ", ImporteReactivaP3";
	$PA .= ", ImporteReactivaP4";
	$PA .= ", ImporteReactivaP5";
	$PA .= ", ImporteReactivaP6";
	$PA .= ", ImporteReactivaP7";
	$PA .= ", ImportePotenciaP1";
	$PA .= ", ImportePotenciaP2";
	$PA .= ", ImportePotenciaP3";
	$PA .= ", ImportePotenciaP4";
	$PA .= ", ImportePotenciaP5";
	$PA .= ", ImportePotenciaP6";
	$PA .= ", ImportePotenciaP7";
	$PA .= ") VALUES (";
	$PA .= " ".$Factura_ID."";
	$PA .= ", ".$PCPeriodo1."";
	$PA .= ", ".$PCPeriodo2."";
	$PA .= ", ".$PCPeriodo3."";
	$PA .= ", ".$PCPeriodo4."";
	$PA .= ", ".$PCPeriodo5."";
	$PA .= ", ".$PCPeriodo6."";
	$PA .= ", ".$PCPeriodo7."";
	$PA .= ", ".$VCPotenciaP1."";
	$PA .= ", ".$VCPotenciaP2."";
	$PA .= ", ".$VCPotenciaP3."";
	$PA .= ", ".$VCPotenciaP4."";
	$PA .= ", ".$VCPotenciaP5."";
	$PA .= ", ".$VCPotenciaP6."";
	$PA .= ", ".$VCPotenciaP7."";
	$PA .= ", ".$VCEnergiaP1."";
	$PA .= ", ".$VCEnergiaP2."";
	$PA .= ", ".$VCEnergiaP3."";
	$PA .= ", ".$VCEnergiaP4."";
	$PA .= ", ".$VCEnergiaP5."";
	$PA .= ", ".$VCEnergiaP6."";
	$PA .= ", ".$VCEnergiaP7."";
	$PA .= ", ".$VCReactivaP1."";
	$PA .= ", ".$VCReactivaP2."";
	$PA .= ", ".$VCReactivaP3."";
	$PA .= ", ".$VCReactivaP4."";
	$PA .= ", ".$VCReactivaP5."";
	$PA .= ", ".$VCReactivaP6."";
	$PA .= ", ".$VCReactivaP7."";
	$PA .= ", ".$ImporteEnergiaP1."";
	$PA .= ", ".$ImporteEnergiaP2."";
	$PA .= ", ".$ImporteEnergiaP3."";
	$PA .= ", ".$ImporteEnergiaP4."";
	$PA .= ", ".$ImporteEnergiaP5."";
	$PA .= ", ".$ImporteEnergiaP6."";
	$PA .= ", ".$ImporteEnergiaP7."";
	$PA .= ", ".$ImporteReactivaP1."";
	$PA .= ", ".$ImporteReactivaP2."";
	$PA .= ", ".$ImporteReactivaP3."";
	$PA .= ", ".$ImporteReactivaP4."";
	$PA .= ", ".$ImporteReactivaP5."";
	$PA .= ", ".$ImporteReactivaP6."";
	$PA .= ", ".$ImporteReactivaP7."";
	$PA .= ", ".$ImportePotenciaP1."";
	$PA .= ", ".$ImportePotenciaP2."";
	$PA .= ", ".$ImportePotenciaP3."";
	$PA .= ", ".$ImportePotenciaP4."";
	$PA .= ", ".$ImportePotenciaP5."";
	$PA .= ", ".$ImportePotenciaP6."";
	$PA .= ", ".$ImportePotenciaP7."";
	$PA .= ")";
//echo $PA."<br />";
	ExecPA($PA);
	$FacturaDetalle_ID = mysql_insert_id();
	
	$SumaImporteBase = 0;
	//echo "SumaImportePotencia:".$SumaImportePotencia." SumaImporteEnergia:".$SumaImporteEnergia." SumaImporteReactiva:".$SumaImporteReactiva."<br />";
	
	//echo "SumaImporteBase:".$SumaImporteBase."<br />";
	
	
	$SumaImportePotencia = $ImportePotenciaP1 + $ImportePotenciaP2 + $ImportePotenciaP3 + $ImportePotenciaP4 + $ImportePotenciaP5 + $ImportePotenciaP6 + $ImportePotenciaP7;
	$SumaImporteEnergia = $ImporteEnergiaP1 + $ImporteEnergiaP2 + $ImporteEnergiaP3 + $ImporteEnergiaP4 + $ImporteEnergiaP5 + $ImporteEnergiaP6 + $ImporteEnergiaP7;
	$SumaImporteReactiva = $ImporteReactivaP1 + $ImporteReactivaP2 + $ImporteReactivaP3 + $ImporteReactivaP4 + $ImporteReactivaP5 + $ImporteReactivaP6 + $ImporteReactivaP7;
		
	$SumaImporteLinea = ($SumaImportePotencia + $SumaImporteEnergia + $SumaImporteReactiva);
	$SumaImporteBase += $SumaImporteLinea;
	
	$SumaImporteTotal = 0;
	$SumaImporteIVA += $SumaImporteBase * (0.21);
	$SumaImporteTotal += $SumaImporteBase + $SumaImporteIVA;
	
	$PA = "UPDATE tbFactura SET";
	$PA .= " SumaImportePotencia = ".floatval($SumaImportePotencia);
	$PA .= ", SumaImporteReactiva = ".floatval($SumaImporteReactiva);
	$PA .= ", SumaImporteEnergia = ".floatval($SumaImporteEnergia);
	$PA .= ", ImporteBase = ".floatval($SumaImporteBase);
	$PA .= ", ImporteTotal = ".floatval($SumaImporteTotal);
	$PA .= " WHERE Factura_ID = ".$Factura_ID;
//echo $PA."<br />";
	ExecPA($PA);
}
?>