<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Marker Labels</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
    <?php 
    
        function claveBase(){
            //return   $con= mysqli_connect('localhost', 'root', 'root', 'futbol');
           return   $con= mysqli_connect('localhost', 'root', '', 'futbol');
        }
         function recuperarEquipos() {
            $con=claveBase();
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

            //if(isset($_GET['id'])){
            $strSQL = "SELECT `Id`, `Nombre`, `Latitud` , `Longitud`  FROM `equipos`";

            //$strSQL = "SELECT * from juegos";
            // Execute the query.
            $equipos = array();
            $query = mysqli_query($con, $strSQL);
            if (!$query) {
                echo 'Ha ocurrido un error';
            } else {
                while ($result = mysqli_fetch_assoc($query)) {
                    $equipos[] = $result;
                }
            }

            // Close the connection
            mysqli_close($con);
            return $equipos;
            //}
        }
    ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqhBwQyP78-NXPHNPNw9LGmNEkPUYlDYM"></script>
    <script>
      function initialize() {
        var alemania = { lat: 51.0000000 , lng:  9.0000000 };     //Latitud del centro del país    
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 6,
          center: alemania
        });        // This event listener calls addMarker() when the map is clicked.
        google.maps.event.addListener(map, 'click', function(event) {
          addMarker(event.latLng, map);
        });
      <?php
      $equipos = recuperarEquipos();
       foreach ($equipos as $e) {
           
          echo'  var image = {
    url: "imagenes/'. $e['Nombre'] . '.gif",
    // This marker is 20 pixels wide by 32 pixels high.
    size: new google.maps.Size(40, 40),
    // The origin for this image is (0, 0).
    origin: new google.maps.Point(0, 0),
    // The anchor for this image is the base of the flagpole at (0, 32).
    anchor: new google.maps.Point(0,2)
  };'
           . 'var ayr = { lat:'.$e["Latitud"].', lng: '.$e["Longitud"].' };        //Latitud de un lugar        
        addMarker(ayr, "'.$e['Nombre'].'", map,image);';
      }
       
        ?>
      }

      // Adds a marker to the map.
      function addMarker(location, label, map,imagen) {
        // Add the marker at the clicked location, and add the next-available label
        // from the array of alphabetical characters.
        var marker = new google.maps.Marker({
          position: location,
          label: label,
          icon:imagen,
          map: map
        });
      }


      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
  </head>
  <body>
     
    <div id="map"></div>
  </body>
</html>

