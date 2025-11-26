<?php

    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "bdappcompetencias";
    
    // Crear variable de conexión a la DB
    $connection = new mysqli($server, $user, $password, $db);

    // Evaluar la conexión de la base de datos
    if($connection->connect_errno){
        die("Conexión fallida: " . $connection->connect_errno);
    }
    
    // Configurar charset UTF-8 para evitar problemas con caracteres especiales
    $connection->set_charset("utf8mb4");
    
    // Solo mostrar mensaje de éxito en desarrollo (comentar en producción)
    // echo "Conexión exitosa";

?>