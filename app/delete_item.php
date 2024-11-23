<?php
require_once "db_connection.php"; // Conexión a la base de datos
// Función para descifrar datos
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
// Obtener la clave de cifrado
$encryptionKey = getenv('ENCRYPTION_KEY');
if (!$encryptionKey) {
    die("Error: La clave de cifrado no está configurada.");
}
// Verificar si el parámetro "id" está en la URL
if (isset($_GET['id'])) {
    $encryptedID = $_GET['id'];
    $id = decryptData($encryptedID, $encryptionKey); // Descifrar el ID

    $query = "DELETE FROM juegos WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $id); // 'i' es para indicar que es un valor entero
        $stmt->execute();
        // Verificar si se eliminó algún registro
        if ($stmt->affected_rows > 0) {
            echo "Juego eliminado con éxito.";
        } else {
            echo "No se encontró un juego con el ID proporcionado.";
        }

        $stmt->close(); // Cerrar la declaración
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }   

} else {
    echo 'No se proporcionó ningún ID de juego.';
}

?>