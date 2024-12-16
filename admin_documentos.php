<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_documento = $_POST['tipo_documento'];
    $fecha_recepcion = date('Y-m-d');
    $emisor = $_POST['emisor'];
    $receptor = $_POST['receptor'];
    $motivo = $_POST['motivo'];
    $estado = "Pendiente";
    $id_usuario = $_POST['id_usuario'];

    $stmt = $conn->prepare("INSERT INTO Documento (tipo_documento, fecha_recepcion, emisor, receptor, motivo, estado, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$tipo_documento, $fecha_recepcion, $emisor, $receptor, $motivo, $estado, $id_usuario]);

    $mensaje = "Documento asignado correctamente.";
}

// Consulta los usuarios para el formulario
$usuarios = $conn->query("SELECT id_usuario, nombres, apellidos FROM Usuario")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Documentos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Gestionar Documentos</h1>
        <?php if (isset($mensaje)) echo "<p class='success'>$mensaje</p>"; ?>
        <form method="POST">
            <label>Tipo de Documento:</label>
            <input type="text" name="tipo_documento" required>
            <label>Emisor:</label>
            <input type="text" name="emisor" required>
            <label>Receptor:</label>
            <input type="text" name="receptor" required>
            <label>Motivo:</label>
            <input type="text" name="motivo" required>
            <label>Asignar a Usuario:</label>
            <select name="id_usuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id_usuario'] ?>">
                        <?= $usuario['nombres'] . " " . $usuario['apellidos'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Asignar Documento</button>
        </form>
        <a href="admin_dashboard.php">Volver</a>
    </div>
</body>
</html>
