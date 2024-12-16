<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

$mensaje = $error = "";

// Manejo de la creación de documentos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_documento'])) {
    $tipo_documento = $_POST['tipo_documento'];
    $fecha_recepcion = date('Y-m-d');
    $emisor = $_POST['emisor'];
    $receptor = $_POST['receptor'];
    $motivo = $_POST['motivo'];
    $estado = "Pendiente";
    $id_usuario = $_POST['id_usuario'];
    $palabras_clave = $_POST['palabras_clave'] ?? null;

    $stmt = $conn->prepare("INSERT INTO Documento (tipo_documento, fecha_recepcion, emisor, receptor, motivo, estado, palabras_clave, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$tipo_documento, $fecha_recepcion, $emisor, $receptor, $motivo, $estado, $palabras_clave, $id_usuario]);

    $id_documento = $conn->lastInsertId();
    $mensaje = "Documento creado correctamente.";
}

// Consulta los usuarios para el formulario
$usuarios = $conn->query("SELECT id_usuario, nombres, apellidos FROM Usuario")->fetchAll(PDO::FETCH_ASSOC);

// Consulta los documentos para mostrar en la tabla
$documentos = $conn->query("
    SELECT Documento.id_documento, Documento.tipo_documento, Documento.motivo, Documento.fecha_recepcion, Documento.estado, Usuario.nombres, Usuario.apellidos
    FROM Documento
    INNER JOIN Usuario ON Documento.id_usuario = Usuario.id_usuario
")->fetchAll(PDO::FETCH_ASSOC);
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
        <?php if ($mensaje) echo "<p class='success'>$mensaje</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>

        <h2>Crear Documento</h2>
        <form method="POST">
            <input type="hidden" name="crear_documento" value="1">
            <label>Tipo de Documento:</label>
            <input type="text" name="tipo_documento" required>
            <label>Emisor:</label>
            <input type="text" name="emisor" required>
            <label>Receptor:</label>
            <input type="text" name="receptor" required>
            <label>Motivo:</label>
            <input type="text" name="motivo" required>
            <label>Palabras Clave:</label>
            <input type="text" name="palabras_clave" placeholder="Ejemplo: presupuesto, horarios">
            <label>Asignar a Usuario:</label>
            <select name="id_usuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id_usuario'] ?>">
                        <?= $usuario['nombres'] . " " . $usuario['apellidos'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn">Crear Documento</button>
        </form>

        <h2>Lista de Documentos</h2>
        <table border="1">
            <tr>
                <th>ID Documento</th>
                <th>Tipo</th>
                <th>Motivo</th>
                <th>Fecha Recepción</th>
                <th>Estado</th>
                <th>Usuario</th>
            </tr>
            <?php foreach ($documentos as $documento): ?>
            <tr>
                <td><?= $documento['id_documento'] ?></td>
                <td><?= $documento['tipo_documento'] ?></td>
                <td><?= $documento['motivo'] ?></td>
                <td><?= $documento['fecha_recepcion'] ?></td>
                <td><?= $documento['estado'] ?></td>
                <td><?= $documento['nombres'] . " " . $documento['apellidos'] ?></td>
            </tr>
            <?php endforeach; ?>


        </table>

        <a href="admin_dashboard.php">Volver</a>
    </div>
</body>
</html>
