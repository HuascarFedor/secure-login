<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "secure_login";

// Crear conexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexion
if ($conn->connect_error) {
  // Evitar en producción
  die("Conexion fallida " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $conn->real_escape_string($_POST['email']);
  $pass = $conn->real_escape_string($_POST['password']);

  // Verificar si el usuario existe
  $sql = "SELECT * FROM users WHERE email='$email'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verificar la contraseña
    if (password_verify($pass, $row['password'])) {
      echo "Inicio de sesión exitoso";
      // Creamos la sesión
      session_start();
      $_SESSION['id'] = $row['id'];
    } else {
      echo "El usuario no existe";
    }
  }
}
?>

<form method="POST" action="login.php">
  <input type="text" name="email" placeholder="E-Mail" required><br>
  <input type="password" name="password" placeholder="Contraseña" required><br>
  <input type="submit" value="Iniciar sesión"><br>
</form>