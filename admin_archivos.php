<?php
session_start();
include "config/database.php";

if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'Administrador') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_documento = $_POST['id_documento'];
    $archivo = $_FILES['archivo'];

    // Validar que no hubo errores al subir el archivo
    if ($archivo['error'] === 0) {
        $nombre_archivo = basename($archivo['name']);
        $ruta_archivo = "uploads/documentos/" . $nombre_archivo;

        // Mover el archivo a la carpeta "uploads/documentos/"
        if (move_uploaded_file($archivo['tmp_name'], $ruta_archivo)) {
            $stmt = $conn->prepare("INSERT INTO Archivo (nombre_archivo, tipo_archivo, tamanio, ruta, id_documento) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $nombre_archivo,
                $archivo['type'],
                $archivo['size'],
                $ruta_archivo,
                $id_documento
            ]);

            $mensaje = "Archivo subido correctamente.";
        } else {
            $error = "Error al mover el archivo al directorio.";
        }
    } else {
        $error = "Error al subir el archivo.";
    }
}

// Consulta los documentos para mostrarlos en el formulario
$documentos = $conn->query("SELECT id_documento, tipo_documento FROM Documento")->fetchAll(PDO::FETCH_ASSOC);
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
        <?php if (isset($mensaje)) echo "<p class='success'>$mensaje</p>"; ?>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" enctype="multipart/form-data">
            <label>Seleccionar Documento:</label>
            <select name="id_documento" required>
                <?php foreach ($documentos as $documento): ?>
                    <option value="<?= $documento['id_documento'] ?>">
                        <?= $documento['tipo_documento'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Archivo (PDF):</label>
            <input type="file" name="archivo" accept="application/pdf" required>
            <button type="submit" class="btn">Subir Archivo</button>
        </form>
        <a href="admin_dashboard.php">Volver</a>
    </div>
</body>
</html>
