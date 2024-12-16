<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Panel de Administración</h1>
        <p>Bienvenido, administrador. Seleccione una opción:</p>
        <a class="btn" href="admin_usuarios.php">Gestionar Usuarios</a>
        <a class="btn" href="admin_documentos.php">Gestionar Documentos</a>
        <a class="btn" href="admin_tramites.php">Gestionar Trámites</a>
        <a class="btn" href="admin_archivos.php">Subir Archivos PDF</a>
        <a class="btn" href="logout.php">Cerrar Sesión</a>
        
    </div>
</body> 
</html>






