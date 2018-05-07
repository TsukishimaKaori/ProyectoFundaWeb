

<html>
    <head>      
        <meta charset="UTF-8">
        <title>Alemania </title>
        <link href="/recursos/css/posiciones.css" rel="stylesheet"/>
    </head>
    <body >
        <?php
        $Partido=1;
        if(isset($_GET['Partido'])){
            $Partido=$_GET['Partido'];
        }
        $partido= recuperarUnPartidos($Partido);
        echo 'Jornada '. $partido['Jornada'] .'Fecha '. $partido['Fecha'] .' '. $partido['Local'] .' '.  $partido['Visita'] .' '. $partido['GolesLocal'] . '-' . $partido['GolesVisita'] ;      
        ?>
        <div id="map" style="width:400px;height:400px;background:yellow"></div> 
        <span id="duracion"></span>
       
<?php
$equipo1= recuperarEquipos($partido['Local']);
$equipo2= recuperarEquipos($partido['Visita']);

echo'<script>
function initMap() {
        //get api uses
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        //waypoints to add
        var waypts = [{ location: { lat: '.$equipo1['Latitud'].', lng: '.$equipo1['Longitud'].'}, stopover: true }, { location: { lat: '.$equipo2['Latitud'].', lng: '.$equipo2['Longitud'].' }, stopover: true }];

        //api map
        var map = new google.maps.Map(document.getElementById(\'map\'), {
            zoom: 6,
            center: { lat: waypts[0].location.lat, lng: waypts[0].location.lng }
        });
        //add map
        directionsDisplay.setMap(map);

        // set the new
        //new Array(waypts[0].location.lat,waypts[0].location.lng)
        directionsService.route({
            origin: { lat: waypts[0].location.lat, lng: waypts[0].location.lng },//db waypoint start
            destination: { lat: waypts[0].location.lat, lng: waypts[0].location.lng },//db waypoint end
            waypoints: waypts,
            travelMode: google.maps.TravelMode.DRIVING
        }, function (response, status) {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(response);
                 var route = response.routes[0];
    var duration = 0;
     route.legs.forEach(function (leg) {
     
      duration += leg.duration.value;
    });
            document.getElementById("duracion").innerHTML ="duracion: " +((duration/60)/60) + \' horas\';
            } else {
                window.alert(\'Ha fallat la comunicació amb el mapa a causa de: \' + status);
            }
        });
      
    }
    
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqhBwQyP78-NXPHNPNw9LGmNEkPUYlDYM &callback=initMap"></script>';

function claveBase(){
            return   $con= mysqli_connect('localhost', 'root', 'root', 'futbol');
        }

function recuperarUnPartidos($id) {
            $con = claveBase();
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

            //if(isset($_GET['id'])){
            $strSQL = "SELECT * FROM `partidos` WHERE id='$id' ";

            //$strSQL = "SELECT * from juegos";
            // Execute the query.
            $equipos = array();
            $query = mysqli_query($con, $strSQL);
            if (!$query) {
                echo 'Ha ocurrido un error';
            } else {
                while ($result = mysqli_fetch_assoc($query)) {
                    $equipos = $result;
                }
            }

            // Close the connection
            mysqli_close($con);
            return $equipos;
}
function recuperarEquipos($nombre) {
            $con=claveBase();
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

            //if(isset($_GET['id'])){
            $strSQL = "SELECT `Id`, `Nombre`, `Latitud` , `Longitud`  FROM `equipos` where `Nombre`='$nombre' ";

            //$strSQL = "SELECT * from juegos";
            // Execute the query.
            $equipos = array();
            $query = mysqli_query($con, $strSQL);
            if (!$query) {
                echo 'Ha ocurrido un error';
            } else {
                while ($result = mysqli_fetch_assoc($query)) {
                    $equipos = $result;
                }
            }

            // Close the connection
            mysqli_close($con);
            return $equipos;
            //}
        }
         
?>
        
        
    </body>
</html>