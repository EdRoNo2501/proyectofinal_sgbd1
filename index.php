<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Página Principal</title>
</head>
<body>
    <h1>Bienvenido</h1>
    <?php if ($rol === 'Administrador'): ?>
        <p>Eres un administrador. Tienes acceso a todas las funciones.</p>
    <?php else: ?>
        <p>Eres un usuario común. Puedes consultar y gestionar tus trámites.</p>
    <?php endif; ?>
    <a href="logout.php">Cerrar Sesión</a>
</body>
</html>
