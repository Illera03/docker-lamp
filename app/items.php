<?php 
  require_once "db_connection.php"; 
  include('items.html');
  
  // Consulta para obtener los juegos, sus notas y el ID
  $query = "SELECT id, nombre, nota FROM juegos"; 
  $result = $conn->query($query);
  
  // Verifica si hay resultados
  if ($result->num_rows > 0) {
      echo '<table class="games_table">';
      echo '<thead>';
      echo '<tr>';
      echo '<th>Nombre del Juego</th>';
      echo '<th>Nota</th>';
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';
  
      // Iterar sobre los resultados y crear las filas de la tabla
      while ($row = $result->fetch_assoc()) {
          echo '<tr>';
          // Hacemos que el nombre del juego sea un enlace hacia show_item.php con el id del juego
          echo '<td><a href="show_item.php?id=' . $row['id'] . '">' . htmlspecialchars($row['nombre']) . '</a></td>';
          echo '<td>' . htmlspecialchars($row['nota']) . '</td>';
          echo '</tr>';
      }
  
      echo '</tbody>';
      echo '</table>';
  } else {
      echo 'No hay juegos disponibles.';
  }
  
  $conn->close(); // Cerrar la conexión
?>
