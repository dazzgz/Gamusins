<?php
define("RUTA_RAIZ","");
require_once("_SiteLayout.php");

$strHtml = "<!DOCTYPE html>"."\n";
//$strHtml .= "<html>"."\n";
$strHtml .= "<head>"."\n";
$strHtml .= "	<title>Panel de Control. ".PROJECT_NAME."</title>"."\n";
$strHtml .= "	<meta http-equiv=\"Content-Type\" content=\"".CONTENT_TYPE."\" />"."\n";
$strHtml .= "	<meta charset=\"".CHARSET."\">"."\n";	
//$strHtml .= "	<meta charset=\"UTF-8\">"."\n";

$strHtml .= "	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\" />"."\n";
$strHtml .= "	<script type=\"text/javascript\" src=\"http://maps.googleapis.com/maps/api/js?sensor=false\"></script>"."\n";
$strHtml .= "	<script type=\"text/javascript\" src=\"/js/jquery.cook.js\"></script>"."\n";

echo $strHtml;

$Empleado_ID = intval(parametro('Empleado_ID'));

//function cargaHTMLMapa($Empleado_ID){
//	$strHtmlMap = "
?>

		<script type='text/javascript'>
		//<![CDATA[
		<?php $strIcos = "var customIcons = {
				".ACCION_APP_LOGIN.": {
					icon: 'http://labs.google.com/ridefinder/images/mm_20_red.png'
				},
				".ACCION_JORNADA_INICIO.": {
					icon: '/adm/img/acciones/jornada_inicio.png'
				},
				".ACCION_NOTIFICACION.": {
					icon: '/adm/img/acciones/notificacion.png'
				},
				".ACCION_JORNADA_FINAL.": {
					icon: '/adm/img/acciones/jornada_final.png'
				},
				".ACCION_APP_LOGOUT.": {
					icon: 'http://labs.google.com/ridefinder/images/mm_20_blue.png'
				},
				".ACCION_GPS_POSICION.": {
					icon: '/adm/img/acciones/gps.png'
				},
				".ACCION_AVISO_RECOGER.": {
					icon: '/adm/img/acciones/recoger.png'
				},
				".ACCION_AVISO_DEVOLVER.": {
					icon: '/adm/img/acciones/devolver.png'
				},
				".ACCION_AVISO_CERRAR.": {
					icon: '/adm/img/acciones/cerrar.png'
				},
				".ACCION_AVISO_ACTUALIZAR.": {
					icon: '/adm/img/acciones/actualizar.png'
				},
				".ACCION_SINCRONIZAR.": {
					icon: '/adm/img/acciones/sincronizar.png'
				}
			};";
		//echo $strIcos;
?>
		var directionsDisplay;
		var directionsService = new google.maps.DirectionsService();
		var map;
		var markers;
		var rutaCoordinates = [];
		var rutaEmpleado;

		function initialize() {
		directionsDisplay = new google.maps.DirectionsRenderer();
			var maratron = new google.maps.LatLng(41.679108, -0.866335);
			var mapOptions = {
												zoom:13,
												mapTypeId: google.maps.MapTypeId.ROADMAP,
												center: maratron
											}
			map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
			directionsDisplay.setMap(map);
			var infoWindow = new google.maps.InfoWindow;
			downloadUrl('mapa-centros-ajax.php?Empleado_ID=<?php echo $Empleado_ID?>', function(data) {
				var xml = data.responseXML;
				markers = xml.documentElement.getElementsByTagName('marker');

				for (var i = 0; i < markers.length; i++) {
					var id = markers[i].getAttribute('id');
					var titulo = markers[i].getAttribute('titulo');
					var fecha = markers[i].getAttribute('fecha');
					var accion = markers[i].getAttribute('accion');
					var point = new google.maps.LatLng(
																							parseFloat(markers[i].getAttribute('lat')),
																							parseFloat(markers[i].getAttribute('lng')));
					var html = '<b>' + titulo + '</b> <br/>' + fecha;
					var icon = customIcons[accion] || {};
					var marker = new google.maps.Marker({
																							map: map,
																							position: point,
																							icon: icon.icon,
																							title: id
																						});
					var puntoRuta = new google.maps.LatLng(markers[i].getAttribute('lat'), markers[i].getAttribute('lng'));
					rutaCoordinates.push(puntoRuta);
          
          bindInfoWindow(marker, map, infoWindow, html);
        }

        rutaEmpleado = new google.maps.Polyline({
																									path: rutaCoordinates,
																									geodesic: true,
																									strokeColor: '#FF0000',
																									strokeOpacity: 1.0,
																									strokeWeight: 2
																								});
				rutaEmpleado.setMap(map);
			});
		}
    
		function bindInfoWindow(marker, map, infoWindow, html) {
			google.maps.event.addListener(marker, 'click', function(event) {
				infoWindow.setContent(html);
				infoWindow.open(map, marker);

				/*var finLat = event.latLng.lat();
				var finLon = event.latLng.lng();
				for (var i = 0; i < markers.length; i++) {
					var id = markers[i].getAttribute('id');
					var posLat = markers[i].getAttribute('lat');
					var posLon = markers[i].getAttribute('lng');
					if(id == marker.title){
						iniLat = markers[i-1].getAttribute('lat');
						iniLon = markers[i-1].getAttribute('lng');
					}
				}
				calcRoute(iniLat, iniLon, finLat, finLon);*/
			});
		}

		function downloadUrl(url, callback) {
			var request = window.ActiveXObject ?
					new ActiveXObject('Microsoft.XMLHTTP') :
					new XMLHttpRequest;

			request.onreadystatechange = function() {
				if (request.readyState == 4) {
					request.onreadystatechange = doNothing;
					callback(request, request.status);
				}
			};

			request.open('GET', url, true);
			request.send(null);
		}

		function doNothing() {}

		function calcRoute(latStart, lonStart, latEnd, lonEnd) {
			var start = latStart+', '+lonStart;
			var end = latEnd+', '+lonEnd;
			var request = {
										origin:start,
										destination:end,
										travelMode: google.maps.TravelMode.DRIVING
										};
			directionsService.route(request, function(response, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(response);
				}
			});
		}

		google.maps.event.addDomListener(window, 'load', initialize);
    //]]>
	</script>
<?php 
/*	";
	
	return $strHtmlMap;
}*/
?>
<body>
<div id="map-canvas" style="width: 800px; height: 600px"></div>
</body>
</html>