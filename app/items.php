<?php 
require_once "db_connection.php"; 
include('items.html');

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

// Obtener la clave de cifrado desde una variable de entorno (asegúrate de que esté configurada)
$encryptionKey = getenv('ENCRYPTION_KEY');

// Consulta para obtener los juegos, sus notas y el ID
$query = "SELECT id, nombre, nota FROM juegos"; 
$result = $conn->query($query);

// Verifica si hay resultados
if ($result->num_rows > 0) {
    echo '<table class="games_table">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Nombre del Juego</th>';
    echo '<th>Nota</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Iterar sobre los resultados y crear las filas de la tabla
    while ($row = $result->fetch_assoc()) {
        // Descifrar el nombre y la nota
        $nombreDescifrado = decryptData($row['nombre'], $encryptionKey);
        $notaDescifrada = decryptData($row['nota'], $encryptionKey);

        // Cifrar el ID antes de pasarlo a la URL
        $encryptedID = encryptData($row['id'], $encryptionKey);

        // Mostrar los datos en la tabla
        echo '<tr>';
        // Hacemos que el nombre del juego sea un enlace hacia show_item.php con el id cifrado
        echo '<td><a href="show_item.php?id=' . urlencode($encryptedID) . '">' . htmlspecialchars($nombreDescifrado) . '</a></td>';
        echo '<td>' . htmlspecialchars($notaDescifrada) . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo 'No hay juegos disponibles.';
}

$conn->close(); // Cerrar la conexión
?>


