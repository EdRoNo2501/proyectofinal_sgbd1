<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

$mensaje = $error = "";

// Crear un nuevo trámite
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_tramite'])) {
    $id_usuario = $_POST['id_usuario'];
    $id_area = $_POST['id_area'];
    $fecha_inicio = date('Y-m-d');
    $estado_tramite = 'Pendiente';

    // Obtener un documento del usuario seleccionado
    $stmt = $conn->prepare("SELECT id_documento FROM Documento WHERE id_usuario = ? LIMIT 1");
    $stmt->execute([$id_usuario]);
    $documento = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($documento) {
        // Crear el trámite
        $stmt = $conn->prepare("INSERT INTO Tramite (fecha_inicio, estado_tramite, id_documento, id_area) VALUES (?, ?, ?, ?)");
        $stmt->execute([$fecha_inicio, $estado_tramite, $documento['id_documento'], $id_area]);
        $mensaje = "Trámite creado correctamente.";
    } else {
        $error = "El usuario seleccionado no tiene documentos registrados.";
    }
}

// Actualizar un trámite existente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actualizar_tramite'])) {
    $id_tramite = $_POST['id_tramite'];
    $nuevo_estado = $_POST['estado_tramite'];
    $id_area = $_POST['id_area'] ?? null;
    $responsable = $_SESSION['id_usuario'];

    // Actualizar estado del trámite
    $stmt = $conn->prepare("UPDATE Tramite SET estado_tramite = ?, id_area = ? WHERE id_tramite = ?");
    $stmt->execute([$nuevo_estado, $id_area, $id_tramite]);

    // Registrar el seguimiento
    $stmt = $conn->prepare("INSERT INTO Seguimiento (fecha_seguimiento, observacion, estado_actual, responsable_nombre, id_tramite) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        date('Y-m-d'),
        "Estado actualizado a $nuevo_estado",
        $nuevo_estado,
        "Administrador (ID: $responsable)",
        $id_tramite
    ]);

    $mensaje = "Trámite actualizado correctamente.";
}

// Consulta los trámites existentes
$tramites = $conn->query("
    SELECT 
        Tramite.id_tramite, Tramite.fecha_inicio, Tramite.fecha_fin, Tramite.estado_tramite, Tramite.id_area,
        Documento.tipo_documento, Documento.motivo,
        Usuario.nombres AS usuario_nombres, Usuario.apellidos AS usuario_apellidos,
        Area.nombre_area
    FROM Tramite
    LEFT JOIN Documento ON Tramite.id_documento = Documento.id_documento
    LEFT JOIN Usuario ON Documento.id_usuario = Usuario.id_usuario
    LEFT JOIN Area ON Tramite.id_area = Area.id_area
")->fetchAll(PDO::FETCH_ASSOC);

// Consulta las áreas disponibles
$areas = $conn->query("SELECT id_area, nombre_area FROM Area")->fetchAll(PDO::FETCH_ASSOC);

// Consulta los usuarios
$usuarios = $conn->query("SELECT id_usuario, nombres, apellidos FROM Usuario")->fetchAll(PDO::FETCH_ASSOC);
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
        <?php if ($mensaje) echo "<p class='success'>$mensaje</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>

        <h2>Crear Nuevo Trámite</h2>
        <form method="POST">
            <label>Seleccionar Usuario:</label>
            <select name="id_usuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id_usuario'] ?>">
                        <?= $usuario['nombres'] . " " . $usuario['apellidos'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Seleccionar Área:</label>
            <select name="id_area" required>
                <?php foreach ($areas as $area): ?>
                    <option value="<?= $area['id_area'] ?>"><?= $area['nombre_area'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="crear_tramite" class="btn">Crear Trámite</button>
        </form>

        <h2>Lista de Trámites</h2>
        <table border="1">
            <tr>
                <th>ID Trámite</th>
                <th>Usuario</th>
                <th>Tipo de Documento</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Área</th>
                <th>Fecha Inicio</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($tramites as $tramite): ?>
            <tr>
                <td><?= $tramite['id_tramite'] ?></td>
                <td><?= $tramite['usuario_nombres'] . " " . $tramite['usuario_apellidos'] ?></td>
                <td><?= $tramite['tipo_documento'] ?></td>
                <td><?= $tramite['motivo'] ?></td>
                <td><?= $tramite['estado_tramite'] ?></td>
                <td><?= $tramite['nombre_area'] ?? 'No asignada' ?></td>
                <td><?= $tramite['fecha_inicio'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id_tramite" value="<?= $tramite['id_tramite'] ?>">
                        <select name="estado_tramite" required>
                            <option value="Pendiente" <?= $tramite['estado_tramite'] === 'Pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="En Proceso" <?= $tramite['estado_tramite'] === 'En Proceso' ? 'selected' : '' ?>>En Proceso</option>
                            <option value="Finalizado" <?= $tramite['estado_tramite'] === 'Finalizado' ? 'selected' : '' ?>>Finalizado</option>
                        </select>
                        <select name="id_area">
                            <option value="">Sin área</option>
                            <?php foreach ($areas as $area): ?>
                                <option value="<?= $area['id_area'] ?>" <?= $tramite['id_area'] == $area['id_area'] ? 'selected' : '' ?>>
                                    <?= $area['nombre_area'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="actualizar_tramite" class="btn">Actualizar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <a href="admin_dashboard.php">Volver</a>
    </div>
</body>
</html>
