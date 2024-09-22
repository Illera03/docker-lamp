<?php
    include ('show_item.html');
    require_once "db_connection.php"; // Conexión a la base de datos
    
    // Verificar si el parámetro "id" está en la URL
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']); // Convertir el id a un valor entero para mayor seguridad

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
            
            // Mostrar los datos en una tabla
            echo '<table class="game_details_table">';
            echo '<tr><th>Nombre</th><td>' . htmlspecialchars($row['nombre']) . '</td></tr>';
            echo '<tr><th>Fecha de Lanzamiento</th><td>' . htmlspecialchars($row['fecha_lanzamiento']) . '</td></tr>';
            echo '<tr><th>Género</th><td>' . htmlspecialchars($row['genero']) . '</td></tr>';
            echo '<tr><th>Nota</th><td>' . htmlspecialchars($row['nota']) . '</td></tr>';
            echo '<tr><th>Precio</th><td>' . htmlspecialchars($row['precio']) . ' €</td></tr>';
            echo '</table>';

            // Mostrar botones para modificar y eliminar
            echo '<div class="action_buttons">';
            echo '<a href="modify_item.php?id=' . $id . '" class="button">Modificar</a> ';
            echo '<a href="delete_item.php?id=' . $id . '" class="button" onclick="return confirm(\'¿Estás seguro de que quieres eliminar este juego?\')">Eliminar</a>';
            echo '</div>';
        } else {
            echo 'No se encontró ningún juego con ese ID.';
        }

        $stmt->close(); // Cerrar la declaración preparada
    } else {
        echo 'No se proporcionó ningún ID de juego.';
    }

    $conn->close(); // Cerrar la conexión a la base de datos
?>
