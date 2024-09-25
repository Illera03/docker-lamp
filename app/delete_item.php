<?php
require_once "db_connection.php"; // Conexión a la base de datos

// Verificar si el parámetro "id" está en la URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir el id a un valor entero para mayor seguridad

    $query = "DELETE FROM juegos WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param('i', $id); // 'i' es para indicar que es un valor entero
        $stmt->execute();
        // Verificar si se eliminó algún registro
        if ($stmt->affected_rows > 0) {
            echo "Juego eliminado con éxito.";
        } else {
            echo "No se encontró un juego con el ID proporcionado.";
        }

        $stmt->close(); // Cerrar la declaración
    } else {
        echo "Error en la preparación de la consulta: " . $conn->error;
    }   

} else {
    echo 'No se proporcionó ningún ID de juego.';
}

?>