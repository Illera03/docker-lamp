<?php
  echo '<h1>FORMULARIO DE REGISTRO</h1>';
  // phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }

//Formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $dni = mysqli_real_escape_string($conn, $_POST['dni']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña

  // Inserción de datos en la base de datos
  $sql = "INSERT INTO usuarios (nombre, dni, telefono, fecha_nacimiento, email, password) 
          VALUES ('$name', '$dni', '$phone', '$birthdate', '$email', '$password')";

  if (mysqli_query($conn, $sql)) {
      echo "Nuevo usuario registrado exitosamente.";
  } else {
      echo "Error: " . mysqli_error($conn);
  }
}

// Incluir la parte del frontend
include('register.html');

// Mostrar la tabla de usuarios
$query = mysqli_query($conn, "SELECT * FROM usuarios") or die (mysqli_error($conn));

echo '<h1>Lista de usuarios registrados:</h1>';
echo '<table>';
echo '<tr><th>ID</th><th>Nombre</th></tr>';

while ($row = mysqli_fetch_array($query)) {
  echo "<tr><td>{$row['id']}</td><td>{$row['nombre']}</td></tr>";
}

echo '</table>';

?>
