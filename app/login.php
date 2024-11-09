<?php
session_start(); // Iniciar sesión para almacenar el token CSRF
require_once 'db_connection.php'; // Conexión a la base de datos

// Generar un token CSRF y almacenarlo en la sesión
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Comprobar el token CSRF
    if (hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
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

        if ($result->num_rows > 0) { // Si existe al menos un usuario con ese email
            // Obtener los datos del usuario
            $user = $result->fetch_assoc();

            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Contraseña correcta, iniciar sesión
                $id = $user['id'];
                header("Location: show_user.php?id=$id");
                exit();
            } else {
                echo "<h3>Contraseña incorrecta.</h3>";
            }
        } else {
            echo "No existe ningún usuario con ese email.";
        }
    } else {
        echo "<h3>Solicitud inválida. Intenta de nuevo.</h3>";
    }
}

include('login.html'); // Incluir el formulario de login si no se ha enviado el formulario
?>
