<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$id_tramite = $_GET['id'] ?? null;

if (!$id_tramite) {
    header("Location: tramites.php");
    exit;
}

// Consulta los detalles del trámite
$stmt = $conn->prepare("
    SELECT 
        Tramite.*, Documento.tipo_documento, Documento.motivo, Documento.palabras_clave,
        Documento.fecha_recepcion, Archivo.nombre_archivo, Archivo.ruta
    FROM Tramite
    INNER JOIN Documento ON Tramite.id_documento = Documento.id_documento
    LEFT JOIN Archivo ON Documento.id_documento = Archivo.id_documento
    WHERE Tramite.id_tramite = ? AND Documento.id_usuario = ?
");
$stmt->execute([$id_tramite, $id_usuario]);
$tramite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tramite) {
    header("Location: tramites.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Trámite</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Detalle del Trámite</h1>
        <p><strong>ID Trámite:</strong> <?= $tramite['id_tramite'] ?></p>
        <p><strong>Tipo de Documento:</strong> <?= $tramite['tipo_documento'] ?></p>
        <p><strong>Motivo:</strong> <?= $tramite['motivo'] ?></p>
        <p><strong>Palabras Clave:</strong> <?= $tramite['palabras_clave'] ?></p>
        <p><strong>Fecha Recepción:</strong> <?= $tramite['fecha_recepcion'] ?></p>
        <p><strong>Estado:</strong> <?= $tramite['estado_tramite'] ?></p>
        <?php if ($tramite['nombre_archivo']): ?>
            <p><strong>Archivo:</strong> <a href="<?= $tramite['ruta'] ?>" target="_blank"><?= $tramite['nombre_archivo'] ?></a></p>
        <?php endif; ?>
        <a href="tramites.php">Volver a Mis Trámites</a>
    </div>
</body>
</html>
