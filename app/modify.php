<?php
    session_start(); // Iniciar una sesión para poder almacenar datos de usuario
    require_once 'db_connection.php'; // Conexión a la base de datos
    include('modify.html'); // Incluir el formulario de modificación de datos
?>
