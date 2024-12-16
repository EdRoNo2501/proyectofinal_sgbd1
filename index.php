<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION['rol'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenido</h1>
        <?php if ($rol === 'Administrador'): ?>
            <p>Eres un administrador. Tienes acceso a todas las funciones.</p>
            <a class="btn" href="admin_dashboard.php">Ir al Panel de Administración</a>
        <?php else: ?>
            <p>Eres un usuario. Puedes consultar y gestionar tus trámites.</p>
            <a class="btn" href="tramites.php">Consultar Mis Trámites</a>
        <?php endif; ?>
        <a class="btn btn-secondary" href="logout.php">Cerrar Sesión</a>
    </div>
</body>
</html>

</html>
