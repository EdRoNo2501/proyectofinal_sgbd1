<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $cargo = $_POST['cargo'];
    $correo = $_POST['correo'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO Usuario (nombres, apellidos, cargo, correo, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombres, $apellidos, $cargo, $correo, $password]);

    $mensaje = "Usuario creado correctamente.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Gestionar Usuarios</h1>
        <?php if (isset($mensaje)) echo "<p class='success'>$mensaje</p>"; ?>
        <form method="POST">
            <label>Nombres:</label>
            <input type="text" name="nombres" required>
            <label>Apellidos:</label>
            <input type="text" name="apellidos" required>
            <label>Cargo:</label>
            <input type="text" name="cargo" required>
            <label>Correo:</label>
            <input type="email" name="correo" required>
            <label>Contraseña:</label>
            <input type="text" name="password" placeholder="Generar automáticamente" value="<?= bin2hex(random_bytes(4)) ?>" required>
            <button type="submit" class="btn">Añadir Usuario</button>
        </form>
        <a href="admin_dashboard.php">Volver</a>
    </div>
</body>
</html>
