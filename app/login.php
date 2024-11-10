<?php
require_once 'db_connection.php'; // Conexión a la base de datos

// ------------------ Inicio de la implementación CSRF ------------------
session_start(); // Asegúrate de que la sesión esté iniciada

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio y lo guarda en la sesión
}
// ------------------ Fin de la implementación CSRF ------------------

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ------------------ Verificación CSRF ------------------
    // Verificar que el token CSRF enviado coincide con el token almacenado en la sesión
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        unset($_SESSION['csrf_token']); // Eliminar el token CSRF después de procesarlo
        // ------------------ Fin de la verificación CSRF ------------------

        $email = $_POST['email'];
        $password= $_POST['password'];
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
            if (password_verify($password, $user['password']) ) {
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
        die("Error de validación CSRF");
    }
} 
include('login.html'); // Incluir el formulario de login si no se ha enviado el formulario
?>
