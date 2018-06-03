<html>
    <head>      
        <meta charset="UTF-8">
        <title>Alemania </title>
        <?php require ('Conexion.php'); ?>
    </head>
    <a href="../ProyectoFundaWeb/VerMapaCompleto.php?Equipo='. $p['Equipo'] .'">
        <img src = "../ProyectoFundaWeb/imagenes/BundesLiga.png" style="margin:0 auto"/>
    </a>
    <h3>BundesLiga</h3>
    <h3>Tabla de distancia en Km</h3>
    <body style = "background-color: #e6e6fa; margin: 0 auto; text-align:center;">  

    </body>
    <?php
    $equipos = recuperarEquipos();
    crearTabla($equipos);
    echo '<h3> <a href="../ProyectoFundaWeb/Posiciones.php"> Posiciones</a> </h3><br><br>';

    function calculaDistancia($EquipoLocal, $EquipoVisita) {
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

    function recuperarEquipos() {
        $con = establecerConexion();
        $strSQL = "SELECT `Id`, `Nombre` FROM `equipos`";
        $equipos = array();
        $query = mysqli_query($con, $strSQL);
        if (!$query) {
            echo 'Ha ocurrido un error';
        } else {
            while ($result = mysqli_fetch_assoc($query)) {
                $equipos[] = $result;
            }
        }
        mysqli_close($con);
        return $equipos;
    }

    function crearTabla($equipos) {
        echo '<table style="margin:0 auto" border = 2>';
        echo '<thead><tr  style = "background-color:#d3d3d3;"><th>Equipos</th>';
        foreach ($equipos as $equipo) {
            echo '<th>' . $equipo['Id'] . '</th>';
        }

        echo '<th>Total</th></tr></thead>';
        echo '<tbody>';
        $i = 0;
        foreach ($equipos as $equipoLocal) {
            $suma = 0;

            if ($i % 2 == 0) {
                $color = '#b8d1f3';
            } else {
                $color = 'white';
            }
            $i = $i + 1;
            echo '<tr style = "background-color:' . $color . ';">'
            . '<td>' . $equipoLocal['Nombre'] . '</td>';
            foreach ($equipos as $equipoVisita) {
                if ($equipoLocal['Nombre'] === $equipoVisita['Nombre']) {
                    echo '<td>-</td>';
                } else {
                    $distancia = calculaDistancia($equipoLocal['Nombre'], $equipoVisita['Nombre']);
                    $suma += $distancia;
                    echo "<td>" . $distancia . "</td>";
                }
            }
            echo '<td>' . $suma . '</td></tr>';
        }
        echo '</tbody>'
        . '</table>';
    }
    ?>

</html>

