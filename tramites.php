<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta de trámites del usuario autenticado
$stmt = $conn->prepare("
    SELECT 
        Tramite.id_tramite, Tramite.fecha_inicio, Tramite.fecha_fin, Tramite.estado_tramite,
        Documento.tipo_documento, Documento.fecha_recepcion, Archivo.nombre_archivo, Archivo.ruta
    FROM Tramite
    INNER JOIN Documento ON Tramite.id_documento = Documento.id_documento
    LEFT JOIN Archivo ON Documento.id_documento = Archivo.id_documento
    WHERE Documento.id_usuario = ?
");
$stmt->execute([$id_usuario]);
$tramites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Trámites</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Mis Trámites</h1>
        <?php if (count($tramites) > 0): ?>
        <table border="1">
            <tr>
                <th>ID Trámite</th>
                <th>Tipo de Documento</th>
                <th>Fecha Recepción</th>
                <th>Estado</th>
                <th>Archivo</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($tramites as $tramite): ?>
            <tr>
                <td><?= $tramite['id_tramite'] ?></td>
                <td><?= $tramite['tipo_documento'] ?></td>
                <td><?= $tramite['fecha_recepcion'] ?></td>
                <td><?= $tramite['estado_tramite'] ?></td>
                <td>
                    <?php if ($tramite['nombre_archivo']): ?>
                        <a href="<?= $tramite['ruta'] ?>" target="_blank"><?= $tramite['nombre_archivo'] ?></a>
                    <?php else: ?>
                        No disponible
                    <?php endif; ?>
                </td>
                <td>
                    <a href="detalle_tramite.php?id=<?= $tramite['id_tramite'] ?>">Ver Detalle</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p>No tienes trámites registrados.</p>
        <?php endif; ?>
        <a href="index.php">Volver al Inicio</a>
    </div>
</body>
</html>
