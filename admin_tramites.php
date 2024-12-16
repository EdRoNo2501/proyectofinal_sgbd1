<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_inicio = date('Y-m-d');
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $estado_tramite = $_POST['estado_tramite'];
    $id_documento = $_POST['id_documento'];

    $stmt = $conn->prepare("INSERT INTO Tramite (fecha_inicio, fecha_fin, estado_tramite, id_documento) VALUES (?, ?, ?, ?)");
    $stmt->execute([$fecha_inicio, $fecha_fin, $estado_tramite, $id_documento]);

    $mensaje = "Trámite creado correctamente.";
}

// Consulta los documentos para el formulario
$documentos = $conn->query("SELECT id_documento, tipo_documento, motivo FROM Documento")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Trámites</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Gestionar Trámites</h1>
        <?php if (isset($mensaje)) echo "<p class='success'>$mensaje</p>"; ?>
        <form method="POST">
            <label>Seleccionar Documento:</label>
            <select name="id_documento" required>
                <?php foreach ($documentos as $documento): ?>
                    <option value="<?= $documento['id_documento'] ?>">
                        <?= "Doc: " . $documento['tipo_documento'] . " - Motivo: " . $documento['motivo'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Estado del Trámite:</label>
            <select name="estado_tramite" required>
                <option value="Pendiente">Pendiente</option>
                <option value="En Proceso">En Proceso</option>
                <option value="Finalizado">Finalizado</option>
            </select>
            <label>Fecha de Finalización (Opcional):</label>
            <input type="date" name="fecha_fin">
            <button type="submit" class="btn">Crear Trámite</button>
        </form>
        <a href="admin_dashboard.php">Volver</a>
    </div>
</body>
</html>
