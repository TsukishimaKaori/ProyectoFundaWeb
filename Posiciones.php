<html>
    <head>        
        <title>Alemania </title>
         <link href="../recursos/css/posiciones.css" rel="stylesheet"/>
    </head>
    <body>
        
    <?php
    crearTabla();
    function crearTabla(){
        echo '<table border = 2 id = "tabla-principal">
                <thead>
                <tr>
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
                <tbody>
                
                 <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                 </tr>
                 
                </tbody>
            </table>';
    }
    
    
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
    
      ?>  
        
    </body>
</html>
        
        

