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

    // Guardar los valores actuales del juego y escapar para evitar XSS
    $oldName = htmlspecialchars($game['nombre'], ENT_QUOTES, 'UTF-8');
    $oldReleaseDate = htmlspecialchars($game['fecha_lanzamiento'], ENT_QUOTES, 'UTF-8');
    $oldGenre = htmlspecialchars($game['genero'], ENT_QUOTES, 'UTF-8');
    $oldRating = htmlspecialchars($game['nota'], ENT_QUOTES, 'UTF-8');
    $oldPrice = htmlspecialchars($game['precio'], ENT_QUOTES, 'UTF-8');

    // Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Obtener y validar datos enviados desde el formulario
    $name = htmlspecialchars(trim($_POST['name']), ENT_QUOTES, 'UTF-8');
    $releaseDate = trim($_POST['release_date']);
    $genre = htmlspecialchars(trim($_POST['genre']), ENT_QUOTES, 'UTF-8');
    $rating = floatval($_POST['rating']);
    $price = floatval($_POST['price']);

    // Validar formato de fecha
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $releaseDate)) {
        echo "Error: Formato de fecha no válido.";
    } else {
        // Verificar que los campos no estén vacíos
        if (empty($name) || empty($genre) || empty($releaseDate)) {
            echo "Por favor, completa todos los campos obligatorios.";
        } 
        // Validar que rating y price sean mayores que 0 si no son vacíos
        elseif ($rating < 0 || $rating > 5) {
            echo "La nota debe ser positiva y menor o igual a 5.";
        } elseif ($price < 0) {
            echo "El precio debe ser positivo.";
        } else {
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
        }
    }
}
} else {
    echo 'No se proporcionó ningún ID.';
}

mysqli_close($conn); // Cerrar la conexión a la base de datos
?>

<!-- Formulario para modificar los datos del juego -->
<!-- Se incluyen los datos actuales del juego en los campos del formulario -->
<form id="item_modify_form" action="modify_item.php?id=<?php echo $id; ?>" method="POST">
    <label for="name">Nombre del juego: </label>
    <input type="text" id="name" name="name" required value="<?php echo $oldName; ?>"><br> 

    <label for="release_date">Fecha de lanzamiento:</label>
    <input type="date" id="release_date" name="release_date" required value="<?php echo $oldReleaseDate; ?>"><br> 

    <label for="genre">Género:</label>
    <input type="text" id="genre" name="genre" required value="<?php echo $oldGenre; ?>"><br> 

    <label for="rating">Nota:</label>
    <input type="number" step="0.01" id="rating" name="rating" required value="<?php echo $oldRating; ?>"><br> 

    <label for="price">Precio:</label>
    <input type="number" step="0.01" id="price" name="price" required value="<?php echo $oldPrice; ?>"><br> 

    <button class="modify_game_button" type="submit" id="modify_item_submit">Modificar juego</button>
</form>