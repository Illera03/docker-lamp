<?php
  // Mostrar el título del formulario de registro
  echo '<h1>PÁGINA WEB</h1>';
  // phpinfo();

  // Información de conexión a la base de datos
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  // Conexión a la base de datos
  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    // Mostrar mensaje de error en caso de que la conexión falle
    die("Database connection failed: " . $conn->connect_error);
  }

// Incluir la parte del frontend
include('register.html');


//Formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $dni = mysqli_real_escape_string($conn, $_POST['dni']);
  $phone = mysqli_real_escape_string($conn, $_POST['phone']);
  $birthdate = mysqli_real_escape_string($conn, $_POST['birthdate']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  //$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña (posible mejora de seguridad)

  // Inserción de datos en la base de datos
  $sql = "INSERT INTO usuarios (nombre, dni, telefono, fecha_nacimiento, email, password) 
          VALUES ('$name', '$dni', '$phone', '$birthdate', '$email', '$password')";

  // Verificar si la inserción de datos fue exitosa
  if (mysqli_query($conn, $sql)) {
      echo "Nuevo usuario registrado exitosamente.";
  } else {
      echo "Error: " . mysqli_error($conn); 
  }
}

// Mostrar la tabla de usuarios registrados (ID y Nombre)
$query = mysqli_query($conn, "SELECT * FROM usuarios") or die (mysqli_error($conn)); // Se guarda en query la consulta

echo '<h1>Lista de usuarios registrados:</h1>';
echo '<table>';
echo '<tr><th>ID</th><th>Nombre</th></tr>';

// Se recorre el resultado de la consulta y se va imprimiendo en la tabla
while ($row = mysqli_fetch_array($query)) {
  echo "<tr><td>{$row['id']}</td><td>{$row['nombre']}</td></tr>";
}

echo '</table>';

mysqli_close($conn); // Cerrar la conexión a la base de datos



//MEJORAS: 
// 1. COMPROBAR QUE EL USUARIO NO EXISTA ANTES DE REGISTRARLO (EMAIL ÚNICO)
?>

