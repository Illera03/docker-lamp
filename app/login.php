<?php
session_start(); // Iniciar una sesión para poder almacenar datos de usuario
require_once 'db_connection.php'; // Conexión a la base de datos

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consulta para buscar el usuario por email
    $query = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) { // si existe al menos un usuario con ese email
        // Obtener los datos del usuario
        $user = $result->fetch_assoc();
        
        // Verificar la contraseña
        if ($password === $user['password']) {
            // Contraseña correcta, iniciar sesión
            
            $id = $user['id'];
            $_SESSION['user_id'] = $user['id'];
            header("Location: modify.php?id=$id");            
            exit();
        } else {
            
            echo "<h3>Contraseña incorrecta.</h3>";
        }
    } else {
        echo "No existe ningún usuario con ese email.";
    }
} 
include('login.html'); // Incluir el formulario de login si no se ha enviado el formulario

?>
