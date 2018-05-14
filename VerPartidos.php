<html>
    <head>      
        <meta charset="UTF-8">
        <title>Alemania </title>
        <link href="/recursos/css/posiciones.css" rel="stylesheet"/>
    </head>
    <body >

        <?php
        crearTabla();

        function crearTabla() {
             $equipo='Bayern München';
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
                    <th>ruta</th>
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
                  <td><a href="../ProyectoFundaWeb/VerMapa.php?Partido='. $part['Id'] .'""><img src = "imagenes/mapa.png"/></a></td>';
                  //<td><a href="../ProyectoFundaWeb/VerMapa.php?Partido='. $part['id'] .'""><img src = "imagenes/mapa.png"/></a></td>';
                echo '</tr>';
            }
            echo ' </tbody>
            </table>';
        }

        function recuperarPartidos($equipo) {
            //$con = mysqli_connect('localhost', 'root', 'root', 'futbol');
            $con = mysqli_connect('localhost', 'root', '', 'futbol');
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

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
        ?>



    </body>
</html>







