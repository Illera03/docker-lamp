<?php
  
require_once 'db_connection.php'; // Conexión a la base de datos

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
      echo "Nuevo usuario registrado exitosamente.";  //TODO cambiar esto, para que aparezca o mas arriba o con una alerta
  } else {
      echo "Error: " . mysqli_error($conn); 
  }
}

mysqli_close($conn); // Cerrar la conexión a la base de datos

//MEJORAS: 
// 1. COMPROBAR QUE EL USUARIO NO EXISTA ANTES DE REGISTRARLO (EMAIL ÚNICO)
?>


