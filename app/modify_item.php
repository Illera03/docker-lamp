<?php
require_once "db_connection.php"; // Conexión a la base de datos
include("modify_item.html"); // Incluir el HTML del formulario

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

// Función para cifrar datos
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

// Obtener la clave de cifrado
$encryptionKey = getenv('ENCRYPTION_KEY');
if (!$encryptionKey) {
    die("Error: La clave de cifrado no está configurada.");
}

// Variables para los datos del juego
$nombre = $fechaLanzamiento = $genero = $nota = $precio = "";

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recuperar el ID cifrado desde el formulario
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        die("ID no válido.");
    }

    $encryptedID = $_POST['id'];
    $id = decryptData($encryptedID, $encryptionKey); // Descifrar el ID

    if ($id && is_numeric($id)) {
        $id = intval($id); // Convertir el ID descifrado a entero

        // Recuperar los datos enviados desde el formulario
        $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
        $fechaLanzamiento = mysqli_real_escape_string($conn, $_POST['fecha_lanzamiento']);
        $genero = mysqli_real_escape_string($conn, $_POST['genero']);
        $nota = mysqli_real_escape_string($conn, $_POST['nota']);
        $precio = mysqli_real_escape_string($conn, $_POST['precio']);

        // Cifrar los nuevos datos antes de actualizarlos
        $nombreCifrado = encryptData($nombre, $encryptionKey);
        $fechaCifrada = encryptData($fechaLanzamiento, $encryptionKey);
        $generoCifrado = encryptData($genero, $encryptionKey);
        $notaCifrada = encryptData($nota, $encryptionKey);
        $precioCifrado = encryptData($precio, $encryptionKey);

        // Actualizar los datos del juego
        $query = "UPDATE juegos SET nombre = ?, fecha_lanzamiento = ?, genero = ?, nota = ?, precio = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssssi", $nombreCifrado, $fechaCifrada, $generoCifrado, $notaCifrada, $precioCifrado, $id);

        if ($stmt->execute()) {
            echo "Datos actualizados correctamente.";
        } else {
            echo "Error al actualizar los datos: " . $stmt->error;
        }
        $stmt->close();
    } else {
        die("ID no válido.");
    }
} else if (isset($_GET['id'])) {
    // Recuperar el ID cifrado desde la URL para cargar los datos actuales del juego
    $encryptedID = $_GET['id'];
    $id = decryptData($encryptedID, $encryptionKey); // Descifrar el ID

    if ($id && is_numeric($id)) {
        $id = intval($id); // Convertir el ID descifrado a entero

        // Consulta para obtener los datos actuales del juego
        $query = "SELECT nombre, fecha_lanzamiento, genero, nota, precio FROM juegos WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Descifrar los datos actuales del juego
            $nombre = decryptData($row['nombre'], $encryptionKey);
            $fechaLanzamiento = decryptData($row['fecha_lanzamiento'], $encryptionKey);
            $genero = decryptData($row['genero'], $encryptionKey);
            $nota = decryptData($row['nota'], $encryptionKey);
            $precio = decryptData($row['precio'], $encryptionKey);
        } else {
            die("Juego no encontrado.");
        }
        $stmt->close();
    } else {
        die("ID no válido.");
    }
} else {
    die("No se proporcionó ningún ID.");
}

mysqli_close($conn);
?>

<!-- Formulario para modificar los datos del juego -->
<form id="item_modify_form" method="post" action="modify_item.php">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($encryptedID); ?>">
    
    <label for="nombre">Nombre del juego:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required><br>

    <label for="fecha_lanzamiento">Fecha de lanzamiento:</label>
    <input type="date" id="fecha_lanzamiento" name="fecha_lanzamiento" value="<?php echo htmlspecialchars($fechaLanzamiento); ?>" required><br>

    <label for="genero">Género:</label>
    <input type="text" id="genero" name="genero" value="<?php echo htmlspecialchars($genero); ?>" required><br>

    <label for="nota">Nota:</label>
    <input type="number" id="nota" name="nota" step="0.1" min="0" max="10" value="<?php echo htmlspecialchars($nota); ?>" required><br>

    <label for="precio">Precio:</label>
    <input type="number" id="precio" name="precio" step="0.01" min="0" value="<?php echo htmlspecialchars($precio); ?>" required><br>

    <button class="modify_game_button" type="submit" id="modify_item_submit">Guardar Cambios</button>
</form>
