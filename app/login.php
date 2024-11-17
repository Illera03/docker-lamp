<?php
require_once 'db_connection.php'; // Conexión a la base de datos

session_start(); // Asegúrate de que la sesión esté iniciada

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Ruta del archivo de log
$log_file = "/var/log/app_logs/login_attempts.log";

// ----- BLOQUEO DE LOGS SEGUIDOS -----
// Función para verificar los intentos fallidos
function checkFailedAttempts($email, $log_file, $max_attempts = 5, $lock_time = 600) {
    // Leer el archivo de log
    $log_lines = file($log_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $failed_attempts = [];
    $current_time = time();

    foreach ($log_lines as $line) {
        if (strpos($line, $email) !== false && strpos($line, 'FAILED') !== false) {
            // Obtener la fecha y hora del intento fallido
            preg_match('/\[(.*?)\]/', $line, $matches); // Extrae la fecha y hora
            $timestamp = strtotime($matches[1]);
            
            // Agregar al array si está dentro del rango de bloqueo
            if ($current_time - $timestamp <= $lock_time) {
                $failed_attempts[] = $timestamp;
            }
        }
    }

    // Si superó el máximo de intentos fallidos, bloquear al usuario
    if (count($failed_attempts) >= $max_attempts) {
        return [
            'blocked' => true,
            'lock_time_left' => $lock_time - ($current_time - max($failed_attempts))
        ];
    }

    return ['blocked' => false];
}
// ------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificación CSRF
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $ip_address = $_SERVER['REMOTE_ADDR']; // Obtener la dirección IP del usuario
        $timestamp = date("Y-m-d H:i:s"); // Fecha y hora actual

        // ----- BLOQUEO DE LOGS SEGUIDOS -----
        // Verificar si el usuario está bloqueado
        $lock_status = checkFailedAttempts($email, $log_file);
        if ($lock_status['blocked']) {
            $minutes_left = round($lock_status['lock_time_left'] / 60);
            die("Tu cuenta está bloqueada. Inténtalo nuevamente en $minutes_left minutos.");
        }
        // ------------------

        // Consulta para buscar el usuario por email
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($query);
        
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) { // Si existe un usuario con ese email
            $user = $result->fetch_assoc();
            // Verificar la contraseña
            if (password_verify($password, $user['password'])) {
                // Registro de intento exitoso en el archivo de log
                $log_message = "[$timestamp] SUCCESS: Email: $email, IP: $ip_address\n";
                file_put_contents($log_file, $log_message, FILE_APPEND);

                // Contraseña correcta, iniciar sesión
                unset($_SESSION['csrf_token']); // Eliminar el token CSRF solo al iniciar sesión correctamente
                $id = $user['id'];
                header("Location: show_user.php?id=$id");            
                exit();
            } else {
                echo "<h3>Contraseña incorrecta.</h3>";

                // Registro de intento fallido por contraseña incorrecta
                $log_message = "[$timestamp] FAILED: Incorrect password for Email: $email, IP: $ip_address\n";
                file_put_contents($log_file, $log_message, FILE_APPEND); 
            }
        } else {
            echo "No existe ningún usuario con ese email.";

            // Registro de intento fallido por email inexistente
            $log_message = "[$timestamp] FAILED: Non-existent email: $email, IP: $ip_address\n";
            file_put_contents($log_file, $log_message, FILE_APPEND);
        }
        
        $stmt->close();
    } else {
        die("Error de validación CSRF");
    }
}

include('login.html'); // Incluir el formulario de login si no se ha enviado el formulario
?>
