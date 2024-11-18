<?php
  
require_once 'db_connection.php'; // Conexión a la base de datos

// ------------------ Inicio de la implementación CSRF ------------------
session_start(); // Asegúrate de que la sesión esté iniciada

// Generar un token CSRF si no existe
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Genera un token aleatorio y lo guarda en la sesión
}
// ------------------ Fin de la implementación CSRF ------------------

include('register.html');

//Formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ------------------ Verificación CSRF ------------------
    // Verificar que el token CSRF enviado coincide con el token almacenado en la sesión
    if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
        unset($_SESSION['csrf_token']); // Eliminar el token CSRF después de procesarlo
        // ------------------ Fin de la verificación CSRF ------------------

        $nameNoCifrado = mysqli_real_escape_string($conn, $_POST['name']);
        $dniNoCifrado = mysqli_real_escape_string($conn, $_POST['dni']);
        $phoneNoCifrado = mysqli_real_escape_string($conn, $_POST['phone']);
        $birthdateNoCifrado = mysqli_real_escape_string($conn, $_POST['birthdate']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        //$password = mysqli_real_escape_string($conn, $_POST['password']);
        $password_hash = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_DEFAULT); //! Algoritmo hash para guardar la contraseña

        //obtener clave
        $encryptionKey = getenv('ENCRYPTION_KEY');
        //Cifrar datos

        $name = encryptData($nameNoCifrado, $encryptionKey);
        $dni = encryptData($dniNoCifrado, $encryptionKey);
        $phone = encryptData($phoneNoCifrado, $encryptionKey);
        $birthdate = encryptData($birthdateNoCifrado, $encryptionKey);
        
        // Inserción de datos en la base de datos
        $sql = "INSERT INTO usuarios (nombre, dni, telefono, fecha_nacimiento, email, password) 
        VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssssss", $name, $dni, $phone, $birthdate, $email, $password_hash);
        
        // Verificar si la inserción de datos fue exitosa
        if (mysqli_stmt_execute($stmt)) {
            echo "Nuevo usuario registrado exitosamente.";  
        } else {
            echo "Error: " . mysqli_error($conn); 
        }
    } else {
        die("Error de validación CSRF");
    }
}

mysqli_close($conn); // Cerrar la conexión a la base de datos
# Cifra datos
function encryptData($data, $key) {
    $cipherMethod = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipherMethod);
    $iv = openssl_random_pseudo_bytes($ivLength);

    $encryptedData = openssl_encrypt($data, $cipherMethod, $key, 0, $iv);
    if ($encryptedData === false) {
        return false;
    }

    // Devuelve los datos encriptados junto con el IV en base64
    return base64_encode($iv . $encryptedData);
}
?>
