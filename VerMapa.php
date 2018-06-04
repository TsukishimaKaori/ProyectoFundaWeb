<html>
    <head>      
        <meta charset="UTF-8">
        <title>Alemania </title>
        <link href="/recursos/css/posiciones.css" rel="stylesheet"/>
        <?php   require ('Conexion.php'); ?>
    </head>
    <body style = "background-color: #e6e6fa; margin: 0 auto; text-align:center;">
        <?php
        $Partido = 1;
        if (isset($_GET['Partido'])) {
            $Partido = $_GET['Partido'];
        }
        $partido = recuperarUnPartidos($Partido);
        echo '<div><h3>Jornada ' . $partido['Jornada'] . ' Fecha ' . $partido['Fecha'] . '</h3></div>'
            . '<div><label>' . $partido['Local'] . ' </label> <img src = "imagenes/' . $partido['Local'] . '.gif" style=""/><b>   ' .
        $partido['GolesLocal'] . '-' . $partido['GolesVisita'] . '   </b><img src = "imagenes/' . $partido['Visita'] . '.gif" style="margin:0 auto"/><label>' . $partido['Visita'] . ' </label><div>';
        echo '<h3>Distancia:'.$_GET['Distancia'].'</h3>';
        ?>
        <span id="duracion"></span>
        <div id="map" style="margin: 0 auto;width:700px;height:450px;background:yellow"></div> 
        
         
        <?php
        $equipo1 = recuperarEquipos($partido['Local']);
        $equipo2 = recuperarEquipos($partido['Visita']);
        ?>
        <script>
            function initMap() {
                //get api uses
                var directionsService = new google.maps.DirectionsService;
                var directionsDisplay = new google.maps.DirectionsRenderer;
                directionsDisplay.setOptions({suppressMarkers: true});
                //waypoints to add
                var waypts = [{location: {lat:<?php echo $equipo1['Latitud'] ?>, lng: <?php echo $equipo1['Longitud'] ?>, }, stopover: true},
                    {location: {lat: <?php echo $equipo2['Latitud'] ?>, lng: <?php echo $equipo2['Longitud'] ?>, }, stopover: true}];
                //api map
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 6,
                    center: {lat: waypts[0].location.lat, lng: waypts[0].location.lng}
                    
                });
                //add map
                directionsDisplay.setMap(map);
                // set the new
                //new Array(waypts[0].location.lat,waypts[0].location.lng)
                directionsService.route({
                    origin: {lat: waypts[0].location.lat, lng: waypts[0].location.lng}, //db waypoint start
                    destination: {lat: waypts[1].location.lat, lng: waypts[1].location.lng}, //db waypoint end

                    travelMode: google.maps.TravelMode.DRIVING
                }, function (response, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        
                        directionsDisplay.setDirections(response);
                        var route = response.routes[0];
                        var duration = 0;
                        var distance = 0;
                        route.legs.forEach(function (leg) {
                            distance += leg.distance.text;
                            duration += leg.duration.value;
                        });
                        document.getElementById("duracion").innerHTML = "<h3>Duración: " + calcularHoras(duration) + "<br></h3>";
                    } else {
                        window.alert('Ha fallat la comunicació amb el mapa a causa de: ' + status);
                    }
                });

                var image = {
                    url: "imagenes/<?php echo $equipo1['Nombre'] ?>.gif",

                    size: new google.maps.Size(40, 40),

                    origin: new google.maps.Point(0, 0),

                    anchor: new google.maps.Point(0, 2)
                };
               var image2 = { 
                    url: "imagenes/<?php echo $equipo2['Nombre'] ?>.gif",
                    
                    size: new google.maps.Size(40, 40),
                  
                    origin: new google.maps.Point(0, 0),
                   
                    anchor: new google.maps.Point(0, 2)
                };
               var image2 = { 
                    url: "imagenes/<?php echo $equipo2['Nombre'] ?>.gif",
                    
                    size: new google.maps.Size(40, 40),
                  
                    origin: new google.maps.Point(0, 0),
                   
                    anchor: new google.maps.Point(0, 2)
                };

              


              


                var ayr = {lat: <?php echo $equipo1["Latitud"] ?> , lng: <?php echo $equipo1["Longitud"] ?>};        //Latitud de un lugar        
                addMarker(ayr, '<?php echo $equipo1["Nombre"] ?>' , map, image);
                var ayr = {lat: <?php echo $equipo2["Latitud"] ?> , lng: <?php echo $equipo2["Longitud"] ?>};        //Latitud de un lugar        
                addMarker(ayr, '<?php echo $equipo2["Nombre"] ?>' , map, image2);

            }

            function addMarker(location, label, map, imagen) {
                // Add the marker at the clicked location, and add the next-available label
                // from the array of alphabetical characters.
                var marker = new google.maps.Marker({
                    position: location,
                    label: label,
                    icon: imagen,
                    map: map
                });
            }

            function calcularHoras(duracion) {
                var numero = ((duracion / 60) / 60);
                var horas = Math.trunc(numero);
                numero = (numero - horas) * 60;
                var minutos = Math.trunc(numero);
                numero = (numero - minutos) * 60;
                var segundos = Math.trunc(numero);
                return horas + ":" + minutos + ":" + segundos;
            }
        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAqhBwQyP78-NXPHNPNw9LGmNEkPUYlDYM&callback=initMap"></script>
        <?php    

        function recuperarUnPartidos($id) {
            $con = establecerConexion();
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
            $con = establecerConexion();
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
