<html>
    <head>      
         <meta charset="UTF-8">
        <title>Alemania </title>
        <link href="/recursos/css/posiciones.css" rel="stylesheet"/>
    </head>
    <body>

        <?php
        crearTabla();

        function crearTabla() {

            $equipos = recuperarEquipos();
            calcularPosicion();

            echo '<table border = 2 id = "tabla-principal">
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
                    <th>Ujuegos</th>
                 </tr>
                </thead>
                <tbody>';
            foreach ($equipos as $e) {
                $ruta =  "imagenes/" . $e['Id'] . ".gif";
                echo '<tr>
                    <td><img src = "'.$ruta.'"/></td>
                    <td>' . $e['Nombre'] . '</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>';
            }
            echo ' </tbody>
            </table>';
        }

        function recuperarEquipos() {
            $con = mysqli_connect('localhost', 'root', '', 'futbol');
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
                   $equipos[]=$result;
                
                }
            }

            // Close the connection
            mysqli_close($con);
            return $equipos;
            //}
        }
        
        function recuperarPosiciones() {
            $con = mysqli_connect('localhost', 'root', '', 'futbol');
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
                   $posiciones[]=$result;
                
                }
            }

            // Close the connection
            mysqli_close($con);
            return $posiciones;
            //}
        }
        
        function recuperarPartidos($equipo) {
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
                   $equipos[]=$result;
                
                }
            }

            // Close the connection
            mysqli_close($con);
            return $equipos;
            //}
        }
        
        function borrarPosiciones(){
            $con = mysqli_connect('localhost', 'root', '', 'futbol');
            if(mysqli_connect_errno()){
                echo "Falló la conexión: ". mysqli_connect_errno();
                exit();
            }
        
            $strSQL = "DELETE FROM `posiciones`";
            $query = mysqli_query($con, $strSQL);
                if(!$query){
                    echo $con->error;
                    echo 'Ha ocurrido un error';
            }
        }
        
        function insertarPosiciones($posiciones){
        $con = mysqli_connect('localhost', 'root', '', 'futbol');
        if(mysqli_connect_errno()){
            echo "Falló la conexión: ". mysqli_connect_errno();
            exit();
        }
        
        $strSQL = "INSERT INTO `posiciones`(`Equipo`, `PJ`, `PG`, `PE`, `PP`, `GF`, `GC`, `Dif`, `Puntos`, `Ujuegos`) "
                . "VALUES ('$posiciones[0]',$posiciones[1],$posiciones[2],$posiciones[3],$posiciones[4],$posiciones[5],"
                . "$posiciones[6],$posiciones[7],$posiciones[8], 'No hay nada')";
        //INSERT INTO `posiciones`(`Equipo`, `PJ`, `PG`, `PE`, `PP`, `GF`, `GC`, `Dif`, `Puntos`) VALUES ('Equipo',1,2,3,4,5,6,7,8);
            // Execute the query.
        $query2 = mysqli_query($con, $strSQL);
        if(!$query2){
            echo $con->error;
            echo 'Ha ocurrido un error';
        }
 
            // Close the connection
            mysqli_close($con);

    }
        
    function calcularPosicion(){
        $equipos = recuperarEquipos();
        borrarPosiciones();
        foreach ($equipos as $e){
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
            insertarPosiciones($posiciones);
            
        }
    }
    
    function contarPartidosGanados($nombre, $partidos){
        $cont = 0;
        foreach ($partidos as $p){
            if($p['Local'] === $nombre && $p['GolesLocal'] > $p['GolesVisita']){
                $cont++;
            }else if($p['Visita'] === $nombre && $p['GolesLocal'] < $p['GolesVisita']){
                $cont++;
            }
        }
        return $cont;
    }
    
    function contarPartidosPerdidos($nombre, $partidos){
        $cont = 0;
        foreach ($partidos as $p){
            if($p['Local'] === $nombre && $p['GolesLocal'] < $p['GolesVisita']){
                $cont++;
            }else if($p['Visita'] === $nombre && $p['GolesLocal'] > $p['GolesVisita']){
                $cont++;
            }
        }
        return $cont;
    }
    
    function contarPartidosEmpates($partidos){
        $cont = 0;
        foreach ($partidos as $p){
            if($p['GolesLocal'] == $p['GolesVisita']){
                $cont++;
            }
        }
        return $cont;
    }
    
    function contarGolesAFavor($nombre, $partidos){
        $cont = 0;
        foreach ($partidos as $p){
            if($p['Local'] === $nombre){
                $cont += $p['GolesLocal'];
            }else if($p['Visita'] === $nombre){
                $cont += $p['GolesVisita'];
            }
        }
        return $cont;
    }
    
    function contarGolesEnContra($nombre, $partidos){
        $cont = 0;
        foreach ($partidos as $p){
            if($p['Local'] === $nombre){
                $cont += $p['GolesVisita'];
            }else if($p['Visita'] === $nombre){
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



