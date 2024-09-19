<?php
    session_start(); // Iniciar una sesión para poder almacenar datos de usuario
    require_once 'db_connection.php'; // Conexión a la base de datos
    include('modify.html'); // Incluir el formulario de modificación de datos

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        echo "ID: $id";
        
    }
    else{
        echo "No se ha recibido el ID del usuario.";
    }
?>
