<?php 
  require_once "db_connection.php"; 
  include('items.html');
  
  // Consulta para obtener los juegos y sus notas
  $query = "SELECT nombre, nota FROM juego"; 
  $result = $conn->query($query);
  
  // Verifica si hay resultados
  if ($result->num_rows > 0) {
      echo '<table class="games_table">';
      echo '<thead>';
      echo '<tr>';
      echo '<th>Nombre del Juego</th>';
      echo '<th>Nota</th>';
      echo '<th>Acciones</th>'; // Para modificar y eliminar
      echo '</tr>';
      echo '</thead>';
      echo '<tbody>';
  
      // Iterar sobre los resultados y crear las filas de la tabla
      while ($row = $result->fetch_assoc()) {
          echo '<tr>';
          echo '<td>' . htmlspecialchars($row['nombre']) . '</td>'; // Nombre del juego
          echo '<td>' . htmlspecialchars($row['nota']) . '</td>';   // Nota
          echo '<td>';
          echo '<a href="modify_item?item=' . $row['id'] . '">Modificar</a> | ';
          echo '<a href="delete_item?item=' . $row['id'] . '">Eliminar</a>'; // Botones para modificar y eliminar
          echo '</td>';
          echo '</tr>';
      }
  
      echo '</tbody>';
      echo '</table>';
  } else {
      echo 'No hay juegos disponibles.';
  }
  
  $conn->close(); // Cerrar la conexión
  ?>
  

?>