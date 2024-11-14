<?php
require_once 'db_connection.php'; // Conexión a la base de datos

session_start(); // Asegúrate de que la sesión esté iniciada

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        unset($_SESSION['csrf_token']); // Eliminar el token CSRF después de procesarlo

        $email = $_POST['email'];
        $password = $_POST['password']; 

        // Obtener la dirección IP del usuario
        $ip_address = $_SERVER['REMOTE_ADDR'];

        // Consulta para buscar el usuario por email
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Registro de intento exitoso
                $log_query = "INSERT INTO login_attempts (email, ip_address, success) VALUES (?, ?, 1)";
                $log_stmt = $conn->prepare($log_query);
                $log_stmt->bind_param("ss", $email, $ip_address);
                $log_stmt->execute();
                $log_stmt->close();

                // Iniciar sesión y redirigir al usuario
                $id = $user['id'];
                header("Location: show_user.php?id=$id");
                exit();
            } else {
                echo "<h3>Contraseña incorrecta.</h3>";

                // Registro de intento fallido
                $log_query = "INSERT INTO login_attempts (email, ip_address, success) VALUES (?, ?, 0)";
                $log_stmt = $conn->prepare($log_query);
                $log_stmt->bind_param("ss", $email, $ip_address);
                $log_stmt->execute();
                $log_stmt->close();
            }
        } else {
            echo "No existe ningún usuario con ese email.";

            // Registro de intento fallido con email inexistente
            $log_query = "INSERT INTO login_attempts (email, ip_address, success) VALUES (?, ?, 0)";
            $log_stmt = $conn->prepare($log_query);
            $log_stmt->bind_param("ss", $email, $ip_address);
            $log_stmt->execute();
            $log_stmt->close();
        }
        $stmt->close();
    } else {
        die("Error de validación CSRF");
    }
}

include('login.html');
?>
