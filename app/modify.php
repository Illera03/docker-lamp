<?php

session_start(); // Iniciar la sesión
require_once "db_connection.php"; // Conexión a la base de datos
include("modify.html"); // Incluir el formulario

// Verificar si se recibió el ID del usuario
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $_SESSION["id"] = $id;
}else {
    echo "No se ha recibido el ID del usuario.";
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
            $query = "UPDATE usuarios SET nombre = ?, dni = ?, telefono = ?, fecha_nacimiento = ?, email = ?, contrasena = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $id = $_SESSION["id"];
            $stmt->bind_param("ssssssi", $name, $dni, $phone, $birthdate, $email, $password, $id);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                echo "Datos actualizados correctamente.";
                //header("Location: index.php");
            } else {
                echo "Error al actualizar los datos: " . $stmt->error;
            }
        } else {
            echo "Por favor, completa todos los campos.";
        }
    }

    // Consultar los datos del usuario con el ID específico
    $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE id = $id") or die (mysqli_error($conn));

    // Estilo de la tabla
    echo "<style>
        table {
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Arial;
            width: 80%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>";

    // Mostrar la tabla con los datos del usuario específico
    echo "<table>";
    echo "<tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>DNI</th>
            <th>Teléfono</th>
            <th>Fecha de Nacimiento</th>
            <th>Email</th>
            <th>Contraseña</th>
          </tr>";

    if ($row = mysqli_fetch_array($query)) {
        echo "<tr>
                <td>{$row["id"]}</td>
                <td>{$row["nombre"]}</td>
                <td>{$row["dni"]}</td>
                <td>{$row["telefono"]}</td>
                <td>{$row["fecha_nacimiento"]}</td>
                <td>{$row["email"]}</td>
                <td>{$row["contrasena"]}</td>
              </tr>";
    } else {
        echo "<tr><td colspan=7>No se encontraron datos para el usuario con ID: $id</td></tr>";
    }

    echo "</table>";


mysqli_close($conn); // Cerrar la conexión a la base de datos
?>


