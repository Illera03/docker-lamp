<?php
include ('show_item.html');
require_once "db_connection.php"; // Conexión a la base de datos

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

// Obtener la clave de cifrado desde una variable de entorno (asegúrate de que esté configurada)
$encryptionKey = getenv('ENCRYPTION_KEY');
if (!$encryptionKey) {
    die("Error: La clave de cifrado no está configurada.");
}

// Verificar si el parámetro "id" está en la URL
if (isset($_GET['id'])) {
    $encryptedID = $_GET['id']; // Recuperar el ID cifrado desde la URL
    $id = decryptData($encryptedID, $encryptionKey); // Descifrar el ID

    if ($id && is_numeric($id)) {
        $id = intval($id); // Convertir el ID descifrado a entero para mayor seguridad

        // Consulta para obtener los datos del juego con el ID proporcionado
        $query = "SELECT nombre, fecha_lanzamiento, genero, nota, precio FROM juegos WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id); // 'i' indica que es un valor entero
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica si se ha encontrado un juego con ese ID
        if ($result->num_rows > 0) {
            // Obtener los datos del juego
            $row = $result->fetch_assoc();

            // Descifrar los datos antes de mostrarlos
            $nombre = decryptData($row['nombre'], $encryptionKey);
            $fechaLanzamiento = decryptData($row['fecha_lanzamiento'], $encryptionKey);
            $genero = decryptData($row['genero'], $encryptionKey);
            $nota = decryptData($row['nota'], $encryptionKey);
            $precio = decryptData($row['precio'], $encryptionKey);

            // Mostrar los datos en una tabla
            echo '<table class="game_details_table">';
            echo '<tr><th>Nombre</th><td>' . htmlspecialchars($nombre) . '</td></tr>';
            echo '<tr><th>Fecha de Lanzamiento</th><td>' . htmlspecialchars($fechaLanzamiento) . '</td></tr>';
            echo '<tr><th>Género</th><td>' . htmlspecialchars($genero) . '</td></tr>';
            echo '<tr><th>Nota</th><td>' . htmlspecialchars($nota) . '</td></tr>';
            echo '<tr><th>Precio</th><td>' . htmlspecialchars($precio) . ' €</td></tr>';
            echo '</table>';

            // Mostrar botones para modificar y eliminar
            echo '<div class="action_buttons">';
            $encryptedID = urlencode($_GET['id']); // Mantener el ID cifrado en los enlaces
            echo '<a href="modify_item.php?id=' . $encryptedID . '" class="button" id="item_modify_submit">Modificar</a> ';
            echo '<a href="delete_item.php?id=' . $encryptedID . '" class="button" id="item_delete_submit" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este juego?\')">Eliminar</a>';
            echo '</div>';
        } else {
            echo 'No se encontró ningún juego con ese ID.';
        }

        $stmt->close(); // Cerrar la declaración preparada
    } else {
        echo 'ID no válido.';
    }
} else {
    echo 'No se proporcionó ningún ID de juego.';
}

$conn->close(); // Cerrar la conexión a la base de datos
?>