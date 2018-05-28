<?php

function establecerConexion() {


    $con = mysqli_connect('localhost', 'root', 'root', 'futbol');
       if (!$con->set_charset("utf8")) {
        printf("Error cargando el conjunto de caracteres utf8: %s\n", $c->error);
        exit();
    }  

    if (mysqli_connect_errno()) {
        echo "Falló la conexión: " . mysqli_connect_errno();
        exit();
        
    }
    return $con;
}
