<?php

// Conexión a la base de datos
require_once 'db_connection.php'; 
include('add_item.html');

// Comprobamos si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $genre = mysqli_real_escape_string($conn, $_POST['genre']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);

    // Validar que todos los campos están completos
    if (!empty($name) && !empty($date) && !empty($genre) && !empty($rating) && !empty($price)) {
        // Consulta para insertar los datos en la tabla de juegos
        $query = "INSERT INTO juegos (nombre, fecha_lanzamiento, genero, nota, precio) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $conn->error);
        }

        // Vincular los parámetros y ejecutar la consulta
        $stmt->bind_param("sssdd", $name, $date, $genre, $rating, $price);

        if ($stmt->execute()) {
            echo "Juego añadido correctamente.";
        } else {
            echo "Error al añadir el juego: ";
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Por favor, completa todos los campos.";
    }
}


// Cerrar la conexión
mysqli_close($conn);

?>