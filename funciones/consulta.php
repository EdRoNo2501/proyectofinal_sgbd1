<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];

if ($rol === 'Administrador') {
    // Consulta todos los trámites
    $stmt = $conn->query("SELECT Tramite.*, Documento.tipo_documento, Documento.fecha_recepcion FROM Tramite INNER JOIN Documento ON Tramite.id_documento = Documento.id_documento");
} else {
    // Consulta trámites del usuario autenticado
    $stmt = $conn->prepare("SELECT Tramite.*, Documento.tipo_documento, Documento.fecha_recepcion FROM Tramite INNER JOIN Documento ON Tramite.id_documento = Documento.id_documento WHERE Documento.id_usuario = ?");
    $stmt->execute([$id_usuario]);
}

$tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Consulta de Trámites</title>
</head>
<body>
    <h1>Consulta de Trámites</h1>
    <table border="1">
        <tr>
            <th>ID Trámite</th>
            <th>Documento</th>
            <th>Fecha Recepción</th>
            <th>Estado</th>
        </tr>
        <?php foreach ($tramites as $tramite): ?>
        <tr>
            <td><?= $tramite['id_tramite'] ?></td>
            <td><?= $tramite['tipo_documento'] ?></td>
            <td><?= $tramite['fecha_recepcion'] ?></td>
            <td><?= $tramite['estado_tramite'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <a href="index.php">Volver</a>
</body>
</html>
