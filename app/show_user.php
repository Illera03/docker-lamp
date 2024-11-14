<?php
require_once "db_connection.php"; // Conexión a la base de datos
include('show_user.html');

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

// Verificar si el parámetro "id" está en la URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir el id a un valor entero para mayor seguridad
    if ($id > 0) {
        echo '<a href="modify_user.php?id=' . $id . '" id="mod_button" class="mod_button">Modificar perfil</a>';
        // Preparar la consulta para obtener los datos del usuario
        $query = "SELECT id, nombre, dni, telefono, fecha_nacimiento, email, password FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id); // El "i" indica que $id es un entero
        $stmt->execute();
        $result = $stmt->get_result(); // Obtener el resultado de la consulta
    
        // Mostrar la tabla con los datos del usuario
        echo '<table class="user_table">';
        echo "<tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Fecha de Nacimiento</th>
                <th>Email</th>
            </tr>";

        if ($row = $result->fetch_assoc()) {
            // Obtener la clave de cifrado
            $encryptionKey = getenv('ENCRYPTION_KEY'); // O usar una clave fija si es necesario
            
            // Descifrar los campos cifrados (excepto email y password)
            $nombre = decryptData($row['nombre'], $encryptionKey);
            $dni = decryptData($row['dni'], $encryptionKey);
            $telefono = decryptData($row['telefono'], $encryptionKey);
            $fecha_nacimiento = decryptData($row['fecha_nacimiento'], $encryptionKey);
            
            // Mostrar los datos en la tabla (no descifras email y password)
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$nombre}</td>
                    <td>{$dni}</td>
                    <td>{$telefono}</td>
                    <td>{$fecha_nacimiento}</td>
                    <td>{$row['email']}</td>
                </tr>";
        } else {
            echo "<tr><td colspan=7>No se encontraron datos para el usuario con ID: $id</td></tr>";
        }
    
        echo "</table>";
    } else {
        echo 'ID no válido.';
    }
} else {
    echo 'No se proporcionó ningún ID.';
}

mysqli_close($conn); // Cerrar la conexión a la base de datos
?>
