<?php
    require_once "db_connection.php"; // Conexión a la base de datos
    include('show_user.html');
    // Verificar si el parámetro "id" está en la URL
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Convertir el id a un valor entero para mayor seguridad
        if ($id > 0) {
            // Preparar la consulta para obtener los datos del usuario
            $query = "SELECT * FROM usuarios WHERE id = ?";
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
                    <th>Contraseña</th>
                </tr>";
        
            if ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row["id"]}</td>
                        <td>{$row["nombre"]}</td>
                        <td>{$row["dni"]}</td>
                        <td>{$row["telefono"]}</td>
                        <td>{$row["fecha_nacimiento"]}</td>
                        <td>{$row["email"]}</td>
                        <td>{$row["password"]}</td>
                    </tr>";
            } else {
                echo "<tr><td colspan=7>No se encontraron datos para el usuario con ID: $id</td></tr>";
            }
        
            echo "</table>";
        }else {
            echo 'ID no válido.';
        }
    } else {
        echo 'No se proporcionó ningún ID.';
    }

mysqli_close($conn); // Cerrar la conexión a la base de datos
?>