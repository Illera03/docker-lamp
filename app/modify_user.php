<?php

require_once "db_connection.php"; // Conexión a la base de datos
include("modify_user.html"); // Incluir el html

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

// Verificar si se ha proporcionado un ID en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Consulta con declaración preparada para obtener datos del usuario
    $query = "SELECT * FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Obtener la clave de cifrado desde una variable de entorno o definirla (no debe estar en el código)
    $encryptionKey = getenv('ENCRYPTION_KEY'); // O usar una clave fija si es necesario

    // Guardar los valores actuales del usuario, descifrándolos
    $oldName = decryptData($user['nombre'], $encryptionKey);
    $oldDni = decryptData($user['dni'], $encryptionKey);
    $oldPhone = decryptData($user['telefono'], $encryptionKey);
    $oldBirthdate = decryptData($user['fecha_nacimiento'], $encryptionKey); // Fecha también cifrada
    $oldEmail = $user['email'];  // El email no se cifra, se usa tal como está
    $oldPassword = $user['password']; // La contraseña no se descifra, ya está en formato hash

    // Verificar si el formulario fue enviado
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener los datos enviados desde el formulario
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $dni = mysqli_real_escape_string($conn, $_POST['dni']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password_hash = password_hash(mysqli_real_escape_string($conn, $_POST['password']), PASSWORD_DEFAULT); // Encriptar la nueva contraseña

        // Validar que todos los campos están completos
        if (!empty($name) && !empty($dni) && !empty($phone) && !empty($birthdate) && !empty($email) && !empty($_POST['password'])) {
            // Cifrar los datos antes de actualizar (menos el email)
            $nameCifrado = encryptData($name, $encryptionKey);
            $dniCifrado = encryptData($dni, $encryptionKey);
            $phoneCifrado = encryptData($phone, $encryptionKey);
            $birthdateCifrado = encryptData($birthdate, $encryptionKey); // Fecha cifrada

            // Preparar la consulta para actualizar los datos del usuario
            $query = "UPDATE usuarios SET nombre = ?, dni = ?, telefono = ?, fecha_nacimiento = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($query);

            // Asegúrate de que estamos vinculando correctamente los parámetros
            $stmt->bind_param("ssssssi", $nameCifrado, $dniCifrado, $phoneCifrado, $birthdateCifrado, $email, $password_hash, $id);
            // Ejecutar la consulta
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Datos actualizados correctamente.";
                } else {
                    echo "No se realizaron cambios en los datos.";
                }
            } else {
                echo "Error al actualizar los datos: " . $stmt->error;
            }
        } else {
            echo "Por favor, completa todos los campos.";
        }
    }
} else {
    echo 'No se proporcionó ningún ID.';
}

mysqli_close($conn); // Cerrar la conexión a la base de datos
?>

<!-- Formulario para modificar los datos del usuario -->
<form id="user_modify_form" action="modify_user.php?id=<?php echo $id; ?>" method="POST">
    <label for="name">Nombre: </label>
    <input type="text" id="name" name="name" required value="<?php echo $oldName; ?>"><br> 

    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" required value="<?php echo $oldDni; ?>"><br> 

    <label for="phone">Teléfono:</label>
    <input type="text" id="phone" name="phone" required value="<?php echo $oldPhone; ?>"><br>

    <label for="birthdate">Fecha de nacimiento:</label>
    <input type="date" id="birthdate" name="birthdate" required value="<?php echo $oldBirthdate; ?>"><br> 

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required value="<?php echo $oldEmail; ?>"><br> 

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required value="<?php echo $oldPassword; ?>"><br> 

    <button class="user_modify_button" type="submit" id="user_modify_submit">Modificar datos</button>
</form>



