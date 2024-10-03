<?php

require_once "db_connection.php"; // Conexión a la base de datos
include("modify_item.html"); // Incluir el html

// Verificar si se ha proporcionado un ID en la URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Convertir el ID a un valor entero para mayor seguridad

    // Obtener los datos actuales del juego
    $query = "SELECT * FROM juegos WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $game = $result->fetch_assoc();

    // Guardar los valores actuales del juego
    $oldName = $game['nombre'];
    $oldReleaseDate = $game['fecha_lanzamiento'];
    $oldGenre = $game['genero'];
    $oldRating = $game['nota'];
    $oldPrice = $game['precio'];

    // Verificar si el formulario fue enviado
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Obtener los datos enviados desde el formulario
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $releaseDate = mysqli_real_escape_string($conn, $_POST['release_date']);
        $genre = mysqli_real_escape_string($conn, $_POST['genre']);
        $rating = mysqli_real_escape_string($conn, $_POST['rating']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);

        // Validar que todos los campos estén completos
        //if (!empty($name) && !empty($genre) && !empty($releaseDate) && !empty($rating) && !empty($price)) {
            // Preparar la consulta para actualizar los datos del juego
            $query = "UPDATE juegos SET nombre = ?, fecha_lanzamiento = ?, genero = ?, nota = ?, precio = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssddi", $name, $releaseDate, $genre, $rating, $price, $id);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo "Datos del juego actualizados correctamente.";
                } else {
                    echo "No se realizaron cambios en los datos del juego.";
                }
            } else {
                echo "Error al actualizar los datos del juego: " . $stmt->error;
            }
        //} else {
            //echo "Por favor, completa todos los campos.";
        //}
    }
} else {
    echo 'No se proporcionó ningún ID.';
}

mysqli_close($conn); // Cerrar la conexión a la base de datos
?>

<!-- Formulario para modificar los datos del juego -->
<form id="item_modify_form" action="modify_item.php?id=<?php echo $id; ?>" method="POST">

    <label for="name">Nombre del juego: </label>
    <input type="text" id="name" name="name" value="<?php echo $oldName; ?>"><br> 

    <label for="release_date">Fecha de lanzamiento:</label>
    <input type="date" id="release_date" name="release_date" value="<?php echo $oldReleaseDate; ?>"><br> 

    <label for="genre">Género:</label>
    <input type="text" id="genre" name="genre" value="<?php echo $oldGenre; ?>"><br> 

    <label for="rating">Nota:</label>
    <input type="number" step="0.01" id="rating" name="rating" value="<?php echo $oldRating; ?>"><br> 

    <label for="price">Precio:</label>
    <input type="number" step="0.01" id="price" name="price" value="<?php echo $oldPrice; ?>"><br> 

    <button class="modify_game_button" type="submit" id="modify_item_submit">Modificar juego</button>
</form>
