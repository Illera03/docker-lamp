<?php
    $hostname = "db";
    $username = "admin__sgssi";
    $password = "U31sg09_09";
    $db = "database";

    // Conexión a la base de datos
    $conn = mysqli_connect($hostname, $username, $password, $db);
    if (!$conn) {
        die("Error al conectar a la base de datos: " . mysqli_connect_error());
    }
?>