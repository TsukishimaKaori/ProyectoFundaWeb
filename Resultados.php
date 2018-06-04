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
    <h3>Tabla de resultados</h3>
    <body style = "background-color: #e6e6fa; margin: 0 auto; text-align:center;">  

    </body>
    <?php
    $equipos = recuperarEquipos();
    crearTabla($equipos);
    echo '<h3> <a href="../ProyectoFundaWeb/Posiciones.php"> Posiciones</a> </h3><br><br>';

    function recuperarPartidos($equipoLocal, $equipoVisita) {
        $con = establecerConexion();
        $strSQL = "SELECT * FROM `partidos` WHERE Local = '$equipoLocal' AND Visita = '$equipoVisita' ";
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

        echo '</tr></thead>';
        echo '<tbody>';
        $i = 0;
        foreach ($equipos as $equipoLocal) {
            $color = 'white';

            $i = $i + 1;
            echo '<tr style = "background-color:' . $color . ';">'
            . '<td  rowspan=2>' . $equipoLocal['Nombre'] . '</td>';
            foreach ($equipos as $equipoVisita) {
                if ($equipoLocal['Nombre'] === $equipoVisita['Nombre']) {
                    echo '<td style = "background-color:#d0d0d0;">-</td>';
                } else {
                    $partido = recuperarPartidos($equipoLocal['Nombre'], $equipoVisita['Nombre'])[0];
                    if ($partido['GolesLocal'] > $partido['GolesVisita']) {
                        $color = '#82e89f';
                    } else if ($partido['GolesLocal'] < $partido['GolesVisita']) {
                        $color = '#ffcccc';
                    } else {
                        $color = '#ffffcc';
                    }
                    $loc = $partido['GolesLocal'];
                    $vis = $partido['GolesVisita'];
                    echo '<td style = "background-color:' . $color . ';">' . $loc . '-' . $vis . '</td>';
                }
            }
            echo '</tr>';
            echo '<tr style = "background-color:' . $color . ';">';
            foreach ($equipos as $equipoVisita) {
                if ($equipoLocal['Nombre'] === $equipoVisita['Nombre']) {
                    echo '<td style = "background-color:#d0d0d0;">-</td>';
                } else {
                    $partido = recuperarPartidos($equipoVisita['Nombre'], $equipoLocal['Nombre'])[0];
                    if ($partido['GolesLocal'] > $partido['GolesVisita']) {
                        $color = '#82e89f';
                    } else if ($partido['GolesLocal'] < $partido['GolesVisita']) {
                        $color = '#ffcccc';
                    } else {
                        $color = '#ffffcc';
                    }

                    echo '<td style = "background-color:' . $color . ';">' . $partido['GolesLocal'] . '-' . $partido['GolesVisita'] . '</td>';
                }
            }

            echo '</tr>';
//            foreach ($equipos as $equipoVisita) {
//                if ($equipoLocal['Nombre'] === $equipoVisita['Nombre']) {
//                    echo '<td>-</td>';
//                } else {
//                    echo '<td>'.recuperarPartidos($equipoLocal['Nombre'], 0).'</td>';
//                }
//            }
        }
        echo '</tbody>'
        . '</table>';
    }
    ?>

</html>

