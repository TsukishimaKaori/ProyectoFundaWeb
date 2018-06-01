<html>
    <head>      
        <meta charset="UTF-8">
        <title>Alemania</title>
        <link href="/recursos/css/posiciones.css" rel="stylesheet"/>
        <?php require ('Conexion.php'); ?>
    </head>
    <body>
        <?php
        crearTabla();

        function crearTabla() {
            $equipo = 'Bayern München';
            if (isset($_GET['Equipo'])) {
                $equipo = $_GET['Equipo'];
            }
            echo '<table style="margin:0 auto" border = 2 id = "tabla-principal">
                <thead>
                <tr>            
                    <th>Jor</th>
                    <th>Fecha</th>
                    <th>Local</th>
                    <th>Visita</th>
                    <th>Marcador</th>
                    <th>Ruta</th>
                    <th>Km</th>
                 </tr>
                </thead>
                <tbody>';
            $partidos = recuperarPartidos($equipo);
            foreach ($partidos as $part) {
                echo '<tr>
                 <td>' . $part['Jornada'] . '</td>  
                 <td>' . $part['Fecha'] . '</td>  
                 <td>' . $part['Local'] . '</td>  
                 <td>' . $part['Visita'] . '</td>  
                 <td>' . $part['GolesLocal'] . '-' . $part['GolesVisita'] . '</td>  
                  <td><a href="../ProyectoFundaWeb/VerMapa.php?Partido=' . $part['Id'] . '""><img src = "imagenes/mapa.png"/></a></td>'.
                        '<td>'. CalculaDistancia($part['Local'] , $part['Visita']).'</td>';
                  
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
            return $Distancia;
        }
        ?>

        <h3 style = "text-align: center"><a href="../ProyectoFundaWeb/Posiciones.php">Posiciones</a></h3>        
        <h3 style = "text-align: center">QUETZAL</h3>
    </body>
</html>







