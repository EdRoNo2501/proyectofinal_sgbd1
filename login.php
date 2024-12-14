<?php
session_start();
include "config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM Usuario WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() === 0) {
        die("El correo no está registrado en la base de datos.");
    }

    echo "Contraseña en la base de datos: " . $usuario['password'];
    echo "Contraseña ingresada: " . $password;

    if ($usuario && password_verify($password, $usuario['password'])) {
        echo "La contraseña es válida.";
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['rol'] = $usuario['cargo'];
        header("Location: index.php");
        exit;
    } else {
        echo "La contraseña no coincide.";
        $error = "Correo o contraseña incorrectos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="login-container">
        <h1>Inicio de Sesión</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" placeholder="Ingrese su correo" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Ingrese su contraseña" required>
            </div>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>

