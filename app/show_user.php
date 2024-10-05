<?php
    //session_start(); // Iniciar la sesión
    require_once "db_connection.php"; // Conexión a la base de datos
    include('show_user.html');
    // Verificar si el parámetro "id" está en la URL
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Convertir el id a un valor entero para mayor seguridad
        
        echo '<a href="modify_user.php?id=' . $id . '" id="mod_button" class="mod_button">Modificar perfil</a>';
        // Consultar los datos del usuario con el ID específico
        $query = mysqli_query($conn, "SELECT * FROM usuarios WHERE id = $id") or die (mysqli_error($conn));

        // Mostrar la tabla con los datos del usuario específico
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

        if ($row = mysqli_fetch_array($query)) {
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
    } else {
        echo 'No se proporcionó ningún ID.';
    }

mysqli_close($conn); // Cerrar la conexión a la base de datos
?>