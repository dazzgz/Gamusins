<?php
define("RUTA_RAIZ","");
require_once("_SiteLayout.php");

$bMapa = true;
$strHtmlFrame = Cabecera(SEC_PEDIDO, $bMapa, $parametros_query);
echo $strHtmlFrame;
?>
<script type="text/javascript">
$(document).ready(function()
{
	function handle_geolocation_query(position) {
		var s = document.querySelector('#status');

		if (s.className == 'success') {
			// not sure why we're hitting this twice in FF, I think it's to do with a cached result coming back
			return;
		}

		s.innerHTML = "Te encontré!";
		s.className = 'success';

		var mapcanvas = document.createElement('div');
		mapcanvas.id = 'mapcanvas';
		mapcanvas.style.height = '800';
		mapcanvas.style.width = '600';

		document.querySelector('article').appendChild(mapcanvas);

		var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

		 
		var myOptions = {
			zoom: 15,
			center: latlng,
			mapTypeControl: false,
			navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);

		var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			title:"Estás aquí!"
		});
		$.cookie("MyLat", position.coords.latitude);
		$.cookie("MyLon", position.coords.longitude);
	}

	function handle_errors(error)
  {
	    var msg = "";
        switch(error.code)
        {
            case error.PERMISSION_DENIED: msg = "El usuario no ha querido compartir su información de geoposicionamento";
            break;
            case error.POSITION_UNAVAILABLE: msg = "No ha sido posible detectar tu posición actual";
            break;
            case error.TIMEOUT: msg = "Tiempo de espera superado, al intentar obtener tu posición";
            break;
            default: alert("Error desconocido");
            break;
        }

        var s = document.querySelector('#status');
		s.className = 'fail';
		s.innerHTML = typeof error == 'string' ? error + " " + msg : "failed " + msg;
  }
/*
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(handle_geolocation_query, handle_errors);
	} else {
		error('geolocalizacion no soportada por su dispositivo');
	}*/

	$("#check").click(function()
	{
	 var lat = $.cookie("MyLat");
	 var lon = $.cookie("MyLon");
	 //alert("Lat:"+lat+" Lon:"+lon);
	 codeLatLng(lat, lon)
	});

});

function codeLatLng(lat, lng) {
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
      console.log(results)
        if (results[1]) {
         //formatted address
         alert(results[0].formatted_address)
        //find country name
             for (var i=0; i<results[0].address_components.length; i++) {
            for (var b=0;b<results[0].address_components[i].types.length;b++) {

            //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                if (results[0].address_components[i].types[b] == "administrative_area_level_1") {
                    //this is the object you are looking for
                    city= results[0].address_components[i];
                    break;
                }
            }
        }
        //city data
        //alert(city.short_name + " " + city.long_name)


        } else {
          alert("No se han encontrado resultados");
        }
      } else {
        alert("Geocoder ha fallado mientras: " + status);
      }
    });
  }
</script>
<div class="row-fluid">
	<h2 id="scroll_up">Centros</h2>
	<div style='width:100%;height:auto'>
	
	<article>
	<p style="display:none">Location: <span id="status">checking...</span></p>
	<input type="button" id="check" value='Check-out' style="background:#00880E;padding:8px; font-weight:bold;width:100%; font-size:14px;color:#fff; margin-bottom:5px"/>
	</article>
</div>
<?php echo Pie();?>