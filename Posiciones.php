<html>
    <head>      
        <meta charset="UTF-8">
        <title>Alemania </title>
        <link href="/recursos/css/posiciones.css" rel="stylesheet"/>
    </head>
    <body >
        <div style="text-align: center">
      
            <a href="../ProyectoFundaWeb/VerMapaCompleto.php?Equipo='. $p['Equipo'] .'"><img src = "../ProyectoFundaWeb/imagenes/BundesLiga.png" style="margin:0 auto"/></a>
        <h3>BundesLiga</h3>
        </div>
        <?php      
        crearTabla();

        function crearTabla() {
            $numeroFilas = 4;
            if (isset($_GET['numeroFilas'])) {
                $numeroFilas = $_GET['numeroFilas'];
                if ($numeroFilas < 0) {
                    $numeroFilas = 0;
                }
            }
            calcularPosicion();
            $posiciones = recuperarPosiciones();
           
            echo '<table style="margin:0 auto" border = 2 id = "tabla-principal">
                <thead>
                <tr>
                    <th>Imagen</th>
                   
                    <th>Equipo</th>
                    <th>PJ</th>
                    <th>PG</th>
                    <th>PE</th>
                    <th>PP</th>
                    <th>GF</th>
                    <th>GC</th>
                    <th>Dif</th>
                    <th>Puntos</th>                 
                    <th colspan="' . $numeroFilas . '"><a href="../ProyectoFundaWeb/Posiciones.php?numeroFilas=' . ($numeroFilas - 1) . '"><img src = "imagenes/izquierda.png"/></a>
                        Ujuegos ' . $numeroFilas . '<a href="../ProyectoFundaWeb/Posiciones.php?numeroFilas=' . ($numeroFilas + 1) . '"><img src = "imagenes/derecha.png"/></a></th>
                 </tr>
                </thead>
                <tbody>';
            foreach ($posiciones as $p) {
                $ruta = "imagenes/" . $p['Equipo'] . ".gif";
                echo '<tr>
                    <td><img src = "' . $ruta . '"/></td>
                   
                    <td><a href="../ProyectoFundaWeb/VerPartidos.php?Equipo='. $p['Equipo'] .'">' . $p['Equipo'] . '</a></td>                 
                    <td>' . $p['PJ'] . '</td>
                    <td>' . $p['PG'] . '</td>
                    <td>' . $p['PE'] . '</td>
                    <td>' . $p['PP'] . '</td>
                    <td>' . $p['GF'] . '</td>
                    <td>' . $p['GC'] . '</td>
                    <td>' . $p['Dif'] . '</td>
                    <td>' . $p['Puntos'] . '</td>';
                $juegos = explode(",", $p['Ujuegos']);

                for ($cont = 0, $numeroJuegos = count($juegos) - 1; $cont < $numeroFilas && $numeroJuegos > 0; $cont++, $numeroJuegos--) {
                    $Partido = recuperarUnPartidos($p['Equipo'], ($numeroJuegos));
                    $dato = $juegos[($numeroJuegos - 1)];
                    foreach ($Partido as $part) {
                        if ($dato == 'G') {
                            echo'<td style="background-color: green"><span title="' . $part['Jornada'] . ' Fecha:' . $part['Fecha'] . ' ' . $part['Local'] . ' ' . $part['GolesLocal'] . '-' .
                            $part['GolesVisita'] . ' ' . $part['Visita'] . '"</span>' . $dato . '</td>';
                        } else if ($dato == 'P') {
                            echo'<td style="background-color: red"><span title="' . $part['Jornada'] . ' Fecha:' . $part['Fecha'] . ' ' . $part['Local'] . ' ' . $part['GolesLocal'] . '-' .
                            $part['GolesVisita'] . ' ' . $part['Visita'] . '"</span>' . $dato . '</td>';
                        } else {
                            echo'<td style="background-color: yellow"><span title="' . $part['Jornada'] . ' Fecha:' . $part['Fecha'] . ' ' . $part['Local'] . ' ' . $part['GolesLocal'] . '-' .
                            $part['GolesVisita'] . ' ' . $part['Visita'] . '"</span>' . $dato . '</td>';
                        }
                    }
                }

                echo'</tr>';
            }
            echo ' </tbody>
            </table>';
        }

        function claveBase(){
            return   $con= mysqli_connect('localhost', 'root', 'root', 'futbol');
        }
        function recuperarEquipos() {
            $con=claveBase();
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

            //if(isset($_GET['id'])){
            $strSQL = "SELECT `Id`, `Nombre` FROM `equipos`";

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

        function recuperarPosiciones() {
              $con=claveBase();
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

            //if(isset($_GET['id'])){
            $strSQL = "SELECT `Equipo`, `PJ`, `PG`, `PE`, `PP`, `GF`, `GC`, `Dif`, `Puntos`, `Ujuegos` "
                    . "FROM `posiciones` order by Puntos DESC";

            //$strSQL = "SELECT * from juegos";
            // Execute the query.
            $posiciones = array();
            $query = mysqli_query($con, $strSQL);
            if (!$query) {
                echo 'Ha ocurrido un error';
            } else {
                while ($result = mysqli_fetch_assoc($query)) {
                    $posiciones[] = $result;
                }
            }

            // Close the connection
            mysqli_close($con);
            return $posiciones;
            //}
        }

        function recuperarPartidos($equipo) {
           $con=claveBase();
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

        function recuperarUnPartidos($equipo, $jornada) {
            $con=claveBase();
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

            //if(isset($_GET['id'])){
            $strSQL = "SELECT * FROM `partidos` WHERE Jornada='$jornada' and (Local = '$equipo' or Visita = '$equipo')";

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

        function borrarPosiciones() {
            $con=claveBase();
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

            $strSQL = "DELETE FROM `posiciones`";
            $query = mysqli_query($con, $strSQL);
            if (!$query) {
                echo $con->error;
                echo 'Ha ocurrido un error';
            }
        }

        function insertarPosiciones($posiciones) {
             $con=claveBase();
            if (mysqli_connect_errno()) {
                echo "Falló la conexión: " . mysqli_connect_errno();
                exit();
            }

            $strSQL = "INSERT INTO `posiciones`(`Equipo`, `PJ`, `PG`, `PE`, `PP`, `GF`, `GC`, `Dif`, `Puntos`, `Ujuegos`) "
                    . "VALUES ('$posiciones[0]',$posiciones[1],$posiciones[2],$posiciones[3],$posiciones[4],$posiciones[5],"
                    . "$posiciones[6],$posiciones[7],$posiciones[8], '$posiciones[9]')";
            //INSERT INTO `posiciones`(`Equipo`, `PJ`, `PG`, `PE`, `PP`, `GF`, `GC`, `Dif`, `Puntos`) VALUES ('Equipo',1,2,3,4,5,6,7,8);
            // Execute the query.
            $query2 = mysqli_query($con, $strSQL);
            if (!$query2) {
                echo $con->error;
                echo 'Ha ocurrido un error';
            }

            // Close the connection
            mysqli_close($con);
        }

        function calcularPosicion() {
            $equipos = recuperarEquipos();
            borrarPosiciones();
            foreach ($equipos as $e) {
                $partidos = recuperarPartidos($e['Nombre']);
                $posiciones = array();
                $posiciones[0] = $e['Nombre'];
                $posiciones[1] = count($partidos);
                $posiciones[2] = contarPartidosGanados($e['Nombre'], $partidos);
                $posiciones[3] = contarPartidosEmpates($partidos);
                $posiciones[4] = contarPartidosPerdidos($e['Nombre'], $partidos);
                $posiciones[5] = contarGolesAFavor($e['Nombre'], $partidos);
                $posiciones[6] = contarGolesEnContra($e['Nombre'], $partidos);
                $posiciones[7] = contarGolesAFavor($e['Nombre'], $partidos) - contarGolesEnContra($e['Nombre'], $partidos);
                $posiciones[8] = contarPartidosGanados($e['Nombre'], $partidos) * 3 + contarPartidosEmpates($partidos);
                $posiciones[9] = Infopartidos($e['Nombre'], $partidos);
                insertarPosiciones($posiciones);
            }
        }

        function Infopartidos($nombre, $partidos) {
            $detalle = "";
            foreach ($partidos as $p) {
                if ($p['Local'] === $nombre) {
                    if ($p['GolesLocal'] > $p['GolesVisita']) {
                        $detalle = $detalle . "G,";
                    } else if ($p['GolesLocal'] < $p['GolesVisita']) {
                        $detalle = $detalle . "P,";
                    } else {
                        $detalle = $detalle . "E,";
                    }
                } else if ($p['Visita'] === $nombre) {
                    if ($p['GolesLocal'] < $p['GolesVisita']) {
                        $detalle = $detalle . "G,";
                    } else if ($p['GolesLocal'] > $p['GolesVisita']) {
                        $detalle = $detalle . "P,";
                    } else {
                        $detalle = $detalle . "E,";
                    }
                }
            }
            return $detalle;
        }

        function contarPartidosGanados($nombre, $partidos) {
            $cont = 0;
            foreach ($partidos as $p) {
                if ($p['Local'] === $nombre && $p['GolesLocal'] > $p['GolesVisita']) {
                    $cont++;
                } else if ($p['Visita'] === $nombre && $p['GolesLocal'] < $p['GolesVisita']) {
                    $cont++;
                }
            }
            return $cont;
        }

        function contarPartidosPerdidos($nombre, $partidos) {
            $cont = 0;
            foreach ($partidos as $p) {
                if ($p['Local'] === $nombre && $p['GolesLocal'] < $p['GolesVisita']) {
                    $cont++;
                } else if ($p['Visita'] === $nombre && $p['GolesLocal'] > $p['GolesVisita']) {
                    $cont++;
                }
            }
            return $cont;
        }

        function contarPartidosEmpates($partidos) {
            $cont = 0;
            foreach ($partidos as $p) {
                if ($p['GolesLocal'] == $p['GolesVisita']) {
                    $cont++;
                }
            }
            return $cont;
        }

        function contarGolesAFavor($nombre, $partidos) {
            $cont = 0;
            foreach ($partidos as $p) {
                if ($p['Local'] === $nombre) {
                    $cont += $p['GolesLocal'];
                } else if ($p['Visita'] === $nombre) {
                    $cont += $p['GolesVisita'];
                }
            }
            return $cont;
        }

        function contarGolesEnContra($nombre, $partidos) {
            $cont = 0;
            foreach ($partidos as $p) {
                if ($p['Local'] === $nombre) {
                    $cont += $p['GolesVisita'];
                } else if ($p['Visita'] === $nombre) {
                    $cont += $p['GolesLocal'];
                }
            }
            return $cont;
        }

//        $partidos = recuperarPartidos("1. FC Köln");
//        foreach ($partidos as $p){
//            echo $p['Local'] . '<BR>';
//            echo $p['Visita'] . '<BR>';
//            echo $p['GolesLocal'] . '<BR>';
//            echo $p['GolesVisita'] . '<BR>';
//        }
        ?>  

    </body>
</html>



