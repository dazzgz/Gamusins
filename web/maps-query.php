<?php
// CLASE PARA OBTENER DIRECCIONES DE GMAPS /////////////////////

class MapsQuery 
{
    function __construct() {
        
    }

    public function get_direccion($lat, $lon) {
        // Set POST variables
        $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$lon.'&sensor=false';
        //http://maps.googleapis.com/maps/api/geocode/json?latlng=41.6789532,-0.8663873&sensor=false

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        //curl_setopt($ch, CURLOPT_POST, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        if ($result === false) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);
        
        return $result;
    }
    
    function get_geocode($city){
			$cityclean = str_replace (" ", "+", $city);
			$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$cityclean."&sensor=false";
			
			$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $details_url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	
    	// Execute post
      $result = curl_exec($ch);
      if ($result === false) {
      	die('Curl failed: ' . curl_error($ch));
      }

      // Close connection
      curl_close($ch);
       
      return $result;
    }
}
?>