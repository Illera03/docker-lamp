<?php
require_once "db_connection.php"; // Conexión a la base de datos
include('show_user.html'); // Interfaz HTML asociada

// Función para cifrar datos
function encryptData($data, $key) {
    $cipherMethod = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipherMethod);
    $iv = openssl_random_pseudo_bytes($ivLength);

    $encryptedData = openssl_encrypt($data, $cipherMethod, $key, 0, $iv);
    return base64_encode($iv . $encryptedData); // Retorna el IV y los datos cifrados
}

// Función para descifrar datos
function decryptData($encryptedData, $key) {
    $cipherMethod = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipherMethod);

    // Decodificar desde base64
    $encryptedData = base64_decode($encryptedData);
    if ($encryptedData === false) {
        return false;
    }

    // Extraer IV y datos cifrados
    $iv = substr($encryptedData, 0, $ivLength);
    $cipherText = substr($encryptedData, $ivLength);

    // Descifrar los datos
    return openssl_decrypt($cipherText, $cipherMethod, $key, 0, $iv);
}

// Obtener la clave de cifrado
$encryptionKey = getenv('ENCRYPTION_KEY');
if (!$encryptionKey) {
    die("Error: La clave de cifrado no está configurada.");
}

if (isset($_GET['id'])) {
    $encryptedID = $_GET['id']; // Recuperar el ID cifrado desde la URL
    $id = decryptData($encryptedID, $encryptionKey); // Descifrar el ID

    if ($id && intval($id) > 0) {
        $id = intval($id); // Convertir el ID a entero para mayor seguridad

        // Consulta para obtener los datos del usuario
        $query = "SELECT id, nombre, dni, telefono, fecha_nacimiento, email FROM usuarios WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $id); // Vincular el ID descifrado como parámetro
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Descifrar los datos antes de mostrarlos en la tabla
            $nombreDescifrado = decryptData($row['nombre'], $encryptionKey);
            $dniDescifrado = decryptData($row['dni'], $encryptionKey);
            $telefonoDescifrado = decryptData($row['telefono'], $encryptionKey);
            $fechaNacimientoDescifrada = decryptData($row['fecha_nacimiento'], $encryptionKey);

            // Mostrar los datos en una tabla
            echo '<table class="user_table">';
            echo "<tr><th>ID</th><td>" . htmlspecialchars($row['id']) . "</td></tr>";
            echo "<tr><th>Nombre</th><td>" . htmlspecialchars($nombreDescifrado) . "</td></tr>";
            echo "<tr><th>DNI</th><td>" . htmlspecialchars($dniDescifrado) . "</td></tr>";
            echo "<tr><th>Teléfono</th><td>" . htmlspecialchars($telefonoDescifrado) . "</td></tr>";
            echo "<tr><th>Fecha de Nacimiento</th><td>" . htmlspecialchars($fechaNacimientoDescifrada) . "</td></tr>";
            echo "<tr><th>Email</th><td>" . htmlspecialchars($row['email']) . "</td></tr>";
            echo '</table>';

            // Generar enlace para modificar el usuario
            $encryptedID = encryptData($id, $encryptionKey); // Cifrar el ID nuevamente para enlaces
            echo '<a href="modify_user.php?id=' . urlencode($encryptedID) . '" class="mod_button">Modificar perfil</a>';
        } else {
            echo "No se encontró ningún usuario con el ID especificado.";
        }
    } else {
        echo 'ID no válido.';
    }
} else {
    echo 'No se proporcionó ningún ID cifrado.';
}

mysqli_close($conn); // Cerrar la conexión a la base de datos
?>
