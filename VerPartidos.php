<html>
    <head>      
        <meta charset="UTF-8">
        <title>Alemania</title>
        <link href="/recursos/css/posiciones.css" rel="stylesheet"/>
        <?php require ('Conexion.php'); ?>
    </head>
    <body style="background-color: #e6e6fa">
        <?php
        crearTabla();

        function crearTabla() {
            $equipo = 'Bayern München';
            if (isset($_GET['Equipo'])) {
                $equipo = $_GET['Equipo'];
            }
            echo '<div style ="text-align: center"><img src = "../ProyectoFundaWeb/imagenes/BundesLiga.png" style="margin:0 auto"/></div>';
            echo '<h3 style ="text-align: center">' . $equipo . '</h3>';
            echo '<table style="margin:0 auto" border = 2 id = "tabla-principal">
                <thead>
                <tr style = "background-color: #ffffcc">            
                    <th>Jor</th>
                    <th>Fecha</th>
                    <th>Local</th>
                    <th>Visita</th>
                    <th>Marcador</th>
                    <th>Ruta</th>
                    <th>Km</th>
                 </tr>
                </thead>
                <tbody style = "background-color: white">';
            $partidos = recuperarPartidos($equipo);
            foreach ($partidos as $part) {
                $distancia = CalculaDistancia($part['Local'], $part['Visita']);
                echo '<tr>
                 <td>' . $part['Jornada'] . '</td>  
                 <td>' . $part['Fecha'] . '</td>  
                 <td>' . $part['Local'] . '</td>  
                 <td>' . $part['Visita'] . '</td>  
                 <td>' . $part['GolesLocal'] . '-' . $part['GolesVisita'] . '</td>  
                  <td><a href="../ProyectoFundaWeb/VerMapa.php?Distancia='.$distancia.' & Partido=' . $part['Id'] . '""><img src = "imagenes/mapa.png"/></a></td>' .
                '<td>' . $distancia . '</td>';

//<td><a href="../ProyectoFundaWeb/VerMapa.php?Partido='. $part['id'] .'""><img src = "imagenes/mapa.png"/></a></td>';
                echo '</tr>';
            }
            echo ' </tbody>
            </table>';
        }

        function recuperarPartidos($equipo) {
            // $con = mysqli_connect('localhost', 'root', 'root', 'futbol');
            $con = establecerConexion();

            //if(isset($_GET['id'])){
            $strSQL = "SELECT * FROM `partidos` WHERE Local = '$equipo' or Visita = '$equipo'";

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

        function CalculaDistancia($EquipoLocal, $EquipoVisita) {
            $Con = establecerConexion();
            $Distancia = 0;
            $sql = "SELECT * FROM equipos WHERE nombre = '$EquipoLocal'";
            if ($oRs = mysqli_query($Con, $sql)) {
                $row = mysqli_fetch_array($oRs);
                $LatL = $row["Latitud"];
                $LonL = $row["Longitud"];
            } else {
                Echo "Error: No pudo ejecutar el $sql<br>";
                return -1;
            }

            $sql = "SELECT * FROM equipos WHERE nombre = '$EquipoVisita'";
            if ($oRs = mysqli_query($Con, $sql)) {
                $row = mysqli_fetch_array($oRs);
                $LatV = $row["Latitud"];
                $LonV = $row["Longitud"];
            } else {
                Echo "Error: No pudo ejecutar el $sql<br>";
                return -1;
            }
            $Distancia = ( 6371 * acos((cos(deg2rad($LatL)) ) * (cos(deg2rad($LatV))) * (cos(deg2rad($LonV) - deg2rad($LonL)) ) + ((sin(deg2rad($LatL))) * (sin(deg2rad($LatV))))) );
            $Distancia = number_format($Distancia, 0, '.', ',');
            return $Distancia;
        }
        ?>

        <h3 style = "text-align: center"><a href="../ProyectoFundaWeb/Posiciones.php">Posiciones</a></h3>        
        <h3 style = "text-align: center">QUETZAL</h3>
    </body>
</html>







