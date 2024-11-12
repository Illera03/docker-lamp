<?php

// Conexión a la base de datos
require_once 'db_connection.php'; 
include('add_item.html');

// Comprobamos si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $encryptionKey = getenv('ENCRYPTION_KEY'); // clave de cifrado
    // Recibir los datos del formulario
    $name = encryptData(mysqli_real_escape_string($conn, $_POST['name']),$encryptionKey);
    $date = encryptData(mysqli_real_escape_string($conn, $_POST['date']),$encryptionKey);
    $genre = encryptData(mysqli_real_escape_string($conn, $_POST['genre']),$encryptionKey);
    $rating = encryptData(mysqli_real_escape_string($conn, $_POST['rating']),$encryptionKey);
    $price = encryptData(mysqli_real_escape_string($conn, $_POST['price']),$encryptionKey);

    // Validar que todos los campos están completos
    if (!empty($name) && !empty($date) && !empty($genre) && !empty($rating) && !empty($price)) {
        // Consulta para insertar los datos en la tabla de juegos
        $query = "INSERT INTO juegos (nombre, fecha_lanzamiento, genero, nota, precio) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        // Vincular los parámetros y ejecutar la consulta
        $stmt->bind_param("sssss", $name, $date, $genre, $rating, $price);

        if ($stmt->execute()) {
            echo "Juego añadido correctamente.";
        } else {
            echo "Error al añadir el juego: ";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos.";
    }
}


// Cerrar la conexión
mysqli_close($conn);

// Función para descifrar los datos
function decryptData($encryptedData, $key) {
    $cipherMethod = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipherMethod);

    // Decodificar desde base64
    $encryptedData = base64_decode($encryptedData);
    if ($encryptedData === false) {
        return false;
    }

    // Extraer el IV y los datos cifrados
    $iv = substr($encryptedData, 0, $ivLength);
    $cipherText = substr($encryptedData, $ivLength);

    // Descifrar el texto cifrado
    return openssl_decrypt($cipherText, $cipherMethod, $key, 0, $iv);
}

// Función para cifrar los datos
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