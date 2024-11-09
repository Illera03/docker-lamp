<?php
session_start(); // Asegúrate de iniciar la sesión

require_once 'db_connection.php'; // Conexión a la base de datos

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio
}

// Verificar si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar el token CSRF
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error: token CSRF inválido, por favor vuelve a intentar.");
    }

    // Procesar los datos del formulario
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $dni = mysqli_real_escape_string($conn, $_POST['dni']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); //! Algoritmo hash para guardar la contraseña

    // Inserción de datos en la base de datos
    $sql = "INSERT INTO usuarios (nombre, dni, telefono, fecha_nacimiento, email, password) 
            VALUES ('$name', '$dni', '$phone', '$birthdate', '$email', '$password_hash')";

    // Verificar si la inserción de datos fue exitosa
    if (mysqli_query($conn, $sql)) {
        echo "Nuevo usuario registrado exitosamente.";  
    } else {
        echo "Error: " . mysqli_error($conn); 
    }
}

mysqli_close($conn); // Cerrar la conexión a la base de datos

include('register.html'); // Incluir el formulario de registro
?>



