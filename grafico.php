
<html>
    <head>
        <meta charset="utf-8">
        <title>Alemania </title>
        <?php require ('Conexion.php'); ?>
        <?php
//include 'Conexion.php';
        $equipos = recuperarEquipos();
        $CantEquipos = count($equipos); //Se obtiene en la BD
        $CantJornadas = recuperarNumeroJornadas(); //Se obtiene en la BD
        $c = 30; //Tamaño del cuadrito
        $jornadas = OrdenarPosicion($equipos, $CantJornadas);
        if(isset($_GET["Equipo"])){
            $equipo=$_GET["Equipo"];
        }else{
        $equipo = "Bayer Leverkusen";        
        }
        $posiciones = OptenerPosiciones($equipo, $jornadas);
        ?>
        <script type="application/javascript">
            function draw() {
            var Equipos  = <?php echo $CantEquipos; ?>;
            var Jornadas = <?php echo $CantJornadas; ?>;
            var c = <?php echo $c; ?>;
            

            var canvas = document.getElementById("canvas");
            var ctx = canvas.getContext("2d");

            // ctx.fillStyle = "#f1f2f4"; //Background gris muy claro
            //ctx.fillRect(0, 0, canvas.width, canvas.height);

            //Dibuja la cuadrícula	  
            ctx.beginPath();

            ctx.strokeStyle = 'MidNightBlue';
            ctx.font = '20px serif';


            //Dibuja las líneas verticales
            var tamanio = 0;
            for (var x=0; x<=Jornadas*c; x=x+c){          
            ctx.moveTo(x, 0);
            ctx.lineTo(x, Equipos*c+c);    
            ctx.fillText(tamanio, x,Equipos*c+c); 
            tamanio = tamanio +1;
            }
            //Dibuja las líneas horizontales 
            tamanio = 0;
            for (var x=0; x<=Equipos*c; x=x+c){
            ctx.moveTo(0, x);
            ctx.lineTo(Jornadas*c+c,x);
            ctx.fillText(tamanio,0,x); 
            tamanio = tamanio +1;
            }



            //            context.fillStyle = "red";
            //context.strokeStyle="black";
            //context.arc(50, 50, 5, 0, 2 * Math.PI, false);
            //context.fill();
            //context.stroke();

            ctx.closePath();
            ctx.stroke();
            
                 <?php
                    $i = 0;
                    foreach ($posiciones as $p) {
                    echo 'dibujarCirculo(ctx,' . $i . ',' . $p . ',10,);';
                    $i++;
                    }
?>
           

            }

            function dibujarCirculo(ctx,x,y,radio){
            ctx.beginPath();
            ctx.arc((x+1)*30+15,(y-1)*30+15,radio,0,2 * Math.PI, false);
            if(y<5){
                ctx.fillStyle="#36ff33";
            }else if(y>14){
                ctx.fillStyle="#ff5833";
            }else{
                ctx.fillStyle="#ecff33";
            }
            
            ctx.fill();
            ctx.closePath();
            }


        </script>
    </head>

    <body style = "background-color: #e6e6fa; " onload="draw();">
        <style>
            h3 {
                color: black;
                text-shadow: 2px 2px 4px #101010;
                text-align: center;
            }

            #canvas {
                display: block;
                margin: 0 auto;	  
                border: 3px solid #000000;
                box-shadow: 5px 10px 18px #888888;
            }  
        </style>
      
        <?php echo '<h3>'.$equipo.' <br><img src = "../ProyectoFundaWeb/imagenes/'.$equipo.'.gif" style="margin:0 auto; widh: 40px; height:40px;"/></h3>'; 
        
        ?>    

        <div><canvas id="canvas" width="<?php echo $CantJornadas * $c + $c; ?>" height="<?php echo $CantEquipos * $c + $c; ?>"</canvas></div>
        
        <?php echo'<h3><a href="../ProyectoFundaWeb/Posiciones.php">
                            Posiciones
                       </a></h3>';
        
        echo '<h3>QUETZAL</h3>';
            

            function recuperarEquipos() {
                $con = establecerConexion();
                //if(isset($_GET['id'])){
                $strSQL = "SELECT `Nombre` FROM `equipos`";

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

            function recuperarNumeroJornadas() {
                $con = establecerConexion();
                if (mysqli_connect_errno()) {
                    echo "Falló la conexión: " . mysqli_connect_errno();
                    exit();
                }


                $strSQL = "SELECT MAX(Jornada) as numero FROM partidos";
                $Numero;
                $query = mysqli_query($con, $strSQL);
                if (!$query) {
                    echo 'Ha ocurrido un error';
                } else {
                    while ($result = mysqli_fetch_assoc($query)) {
                        $Numero = $result['numero'];
                    }
                }

                // Close the connection
                mysqli_close($con);
                return $Numero;
                //}
            }

            function recuperarPartidos($equipo) {
                $con = establecerConexion();

                //if(isset($_GET['id'])){
                $strSQL = "SELECT * FROM `partidos` WHERE Local = '$equipo' or Visita = '$equipo' ORDER by Jornada";

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

            function OptenerPosiciones($equipo, $jornadas) {
                $i = 1;
                $posJornada = array();
                foreach ($jornadas as $jornada) {
                    $count = 1;
                    foreach ($jornada as $jorn) {
                        if ($jorn['nombre'] == $equipo) {
                            $posJornada[$i++] = $count;
                        }
                        $count++;
                    }
                }
                return $posJornada;
            }

            function OrdenarPosicion($equipos, $CantJornadas) {
                $jornadas = array();

                $puntosequipos = puntosPorJornada($equipos);
                for ($i = 1; $i <= $CantJornadas; $i++) {
                    $jornadas[$i] = array();
                    $jornada = 1;
                    foreach ($puntosequipos as $e) {
                        $jornadas[$i][$jornada++] = $e[$i];
                    }
                    $jornadas[$i] = OrdenadorJornadas($jornadas[$i]);
                }
                return $jornadas;
            }

            function OrdenadorJornadas($jornada) {
                $numero = count($jornada);
                $bandera = true;
                $i = 1;
                while ($bandera) {
                    $bandera = false;
                    for ($j = 1; $j < $numero; $j++) {
                        if ($jornada[$j]['puntos'] < $jornada[$j + 1]['puntos']) {
                            $tmp = $jornada[$j + 1];
                            $jornada[$j + 1] = $jornada[$j];
                            $jornada[$j] = $tmp;
                            $bandera = true;
                        } else
                        if ($jornada[$j]['puntos'] == $jornada[$j + 1]['puntos'] && $jornada[$j]['goles'] < $jornada[$j + 1]['goles']) {
                            $tmp = $jornada[$j + 1];
                            $jornada[$j + 1] = $jornada[$j];
                            $jornada[$j] = $tmp;
                            $bandera = true;
                        }
                    }
                }
                return $jornada;
            }

            function puntosPorJornada($equipos) {
                $puntosequipos = array();
                foreach ($equipos as $e) {
                    $puntosPorequipo = array();
                    $partidos = recuperarPartidos($e['Nombre']);
                    $nombre = $e['Nombre'];
                    $jornada = 1;
                    $partidosGanados = 0;
                    $partidosEmpatados = 0;
                    $golesaFavor = 0;
                    $puntos = 0;
                    foreach ($partidos as $p) {
                        $puntosJornada = array();
                        if ($p['GolesLocal'] == $p['GolesVisita']) {
                            $partidosEmpatados++;
                        } else
                        if ($p['Local'] === $nombre) {
                            if ($p['GolesLocal'] > $p['GolesVisita']) {
                                $partidosGanados++;
                            }
                            $golesaFavor += ($p['GolesLocal'] - $p['GolesVisita']);
                        } else
                        if ($p['Visita'] === $nombre) {
                            if ($p['GolesLocal'] < $p['GolesVisita']) {
                                $partidosGanados++;
                            }
                            $golesaFavor += ($p['GolesVisita'] - $p['GolesLocal']);
                        }

                        $puntosJornada['puntos'] = $partidosGanados * 3 + $partidosEmpatados;
                        $puntosJornada['goles'] = $golesaFavor;
                        $puntosJornada['nombre'] = $nombre;
                        $puntosPorequipo[$jornada++] = $puntosJornada;
                    }
                    $puntosequipos[$nombre] = $puntosPorequipo;
                }
                return $puntosequipos;
            }
            ?>
    </body>
</html>
