<?php

session_start(); // Iniciar la sesión
require_once "db_connection.php"; // Conexión a la base de datos
include("modify.html"); // Incluir el formulario

// Verificar si se recibió el ID del usuario
/*if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $_SESSION["id"] = $id;
}else {
    echo "No se ha recibido el ID del usuario.";
} */


// Verificar si el ID del usuario está en la sesión
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
} else {
    die("No se ha encontrado el ID del usuario en la sesión.");
}

    
    // Verificar si el formulario fue enviado
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener los datos enviados desde el formulario
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $dni = mysqli_real_escape_string($conn, $_POST['dni']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        // Validar que todos los campos están completos
        if (!empty($name) && !empty($dni) && !empty($phone) && !empty($birthdate) && !empty($email) && !empty($password)) {
            // Preparar la consulta para actualizar los datos del usuario

            $query = "UPDATE usuarios SET nombre = ?, dni = ?, telefono = ?, fecha_nacimiento = ?, email = ?, password = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssssi", $name, $dni, $phone, $birthdate, $email, $password, $id);
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
    
    mysqli_close($conn); // Cerrar la conexión a la base de datos
?>


