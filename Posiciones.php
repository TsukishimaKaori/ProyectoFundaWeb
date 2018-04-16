<?php

function recuperarEquipos(){
        $con = mysqli_connect('localhost', 'root', '', 'futbol');
        if(mysqli_connect_errno()){
            echo "Falló la conexión: ". mysqli_connect_errno();
            exit();
        }
        
        //if(isset($_GET['id'])){
            $strSQL = "SELECT `Id`, `Nombre` FROM `equipos`";
 
            //$strSQL = "SELECT * from juegos";
            // Execute the query.
 
            $equipos = array();
            $query = mysqli_query($con, $strSQL);
            if(!$query){
                echo 'Ha ocurrido un error';
            }else{
                while($result = mysqli_fetch_assoc($query))
                {
                    $id = $result['Id'];
                    $nombre = $result['Nombre'];
                    $equipos[] = $id;
                    $equipos[] = $nombre;
                }
            }
 
            // Close the connection
            mysqli_close($con);
            return $equipos;
        //}
    }
    
    $equipos = recuperarEquipos();
    
    foreach($equipos as $e){
        echo $e . '<BR>';
    }

