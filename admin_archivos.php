<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

$mensaje = $error = "";

// Manejo de la subida de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'];
    $archivo = $_FILES['archivo'];
    $tipo_documento = $_POST['tipo_documento'] ?? 'General'; // Tipo por defecto si no se especifica
    $motivo = $_POST['motivo'] ?? 'Sin motivo especificado'; // Motivo por defecto

    // Validar que no hubo errores al subir el archivo
    if ($archivo['error'] === 0) {
        $nombre_archivo = basename($archivo['name']);
        $ruta_archivo = "uploads/documentos/" . $nombre_archivo;

        // Mover el archivo a la carpeta "uploads/documentos/"
        if (move_uploaded_file($archivo['tmp_name'], $ruta_archivo)) {
            // Buscar o crear un documento asociado al usuario
            $stmt = $conn->prepare("SELECT id_documento FROM Documento WHERE id_usuario = ? ORDER BY fecha_recepcion DESC LIMIT 1");
            $stmt->execute([$id_usuario]);
            $documento = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$documento) {
                // Si no hay un documento previo, crear uno nuevo
                $stmt = $conn->prepare("INSERT INTO Documento (tipo_documento, fecha_recepcion, emisor, receptor, motivo, estado, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $tipo_documento,
                    date('Y-m-d'),
                    'Administrador',
                    'Usuario ID ' . $id_usuario,
                    $motivo,
                    'Pendiente',
                    $id_usuario
                ]);
                $id_documento = $conn->lastInsertId();
            } else {
                // Usar el documento existente
                $id_documento = $documento['id_documento'];
            }

            // Registrar el archivo en la tabla
            $stmt = $conn->prepare("INSERT INTO Archivo (nombre_archivo, tipo_archivo, tamanio, ruta, id_documento) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $nombre_archivo,
                $archivo['type'],
                $archivo['size'],
                $ruta_archivo,
                $id_documento
            ]);

            $mensaje = "Archivo subido correctamente y relacionado con el usuario.";
        } else {
            $error = "Error al mover el archivo al directorio.";
        }
    } else {
        $error = "Error al subir el archivo.";
    }
}

// Consulta los usuarios para el formulario
$usuarios = $conn->query("SELECT id_usuario, nombres, apellidos FROM Usuario")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivos PDF</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="container">
        <h1>Subir Archivos PDF</h1>
        <?php if ($mensaje) echo "<p class='success'>$mensaje</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Seleccionar Usuario:</label>
            <select name="id_usuario" required>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id_usuario'] ?>">
                        <?= $usuario['nombres'] . " " . $usuario['apellidos'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Tipo de Documento (Opcional):</label>
            <input type="text" name="tipo_documento" placeholder="Ejemplo: Contrato, Memorándum">
            <label>Motivo (Opcional):</label>
            <input type="text" name="motivo" placeholder="Motivo del documento">
            <label>Archivo (PDF):</label>
            <input type="file" name="archivo" accept="application/pdf" required>
            <button type="submit" class="btn">Subir Archivo</button>
        </form>
        <a href="admin_dashboard.php">Volver</a>
    </div>
</body>
</html>
