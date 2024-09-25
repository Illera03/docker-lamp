<?php

session_start(); // Iniciar la sesión
require_once "db_connection.php"; // Conexión a la base de datos
include("modify.html"); // Incluir el formulario


if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir el id a un valor entero para mayor seguridad

    
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
} else {
    echo 'No se proporcionó ningún ID.';
}
    mysqli_close($conn); // Cerrar la conexión a la base de datos
?>
<!-- Se incluye aquí el formulario para poder mandar los datos a la url con el id de la url actual -->
<form id="modify_form" action="modify.php?id=<?php echo $id; ?>" method="POST">

    <label for="name">Nombre: </label>
    <input type="text" id="name" name="name" required placeholder="Juan Ruiz"><br> 

    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" required placeholder="12345678-A"><br> 

    <label for="phone">Teléfono:</label>
    <input type="text" id="phone" name="phone" required placeholder="123456789"><br>

    <label for="birthdate">Fecha de nacimiento:</label>
    <input type="date" id="birthdate" name="birthdate" required><br> 

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required placeholder="ejemplo@dominio.com"><br> 

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password" required placeholder="******"><br> <!-- Sin restricciones -->

    <button class="user_modify_button" type="button" id="user_modify_submit" onclick="comprobarDatos()">Modificar datos</button>
</form>


