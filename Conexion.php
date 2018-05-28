<?php

function establecerConexion() {
    $con = mysqli_connect('localhost', 'root', '', 'futbol');
    if (mysqli_connect_errno()) {
        echo "Falló la conexión: " . mysqli_connect_errno();
        exit();
        
    }
    return $con;
}
