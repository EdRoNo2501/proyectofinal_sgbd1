<?php
session_start();
include "config/database.php";
include "funciones/archivos.php";

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo_documento = $_POST['tipo_documento'];
    $fecha_recepcion = date("Y-m-d");
    $emisor = $_POST['emisor'];
    $receptor = $_POST['receptor'];
    $motivo = $_POST['motivo'];
    $estado = "Pendiente";
    $palabras_clave = $_POST['palabras_clave'];
    $id_usuario = $_SESSION['id_usuario'];

    $stmt = $conn->prepare("INSERT INTO Documento (tipo_documento, fecha_recepcion, emisor, receptor, motivo, estado, palabras_clave, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$tipo_documento, $fecha_recepcion, $emisor, $receptor, $motivo, $estado, $palabras_clave, $id_usuario]);

    $id_documento = $conn->lastInsertId();

    if (!empty($_FILES['archivo']['name'])) {
        if (subirArchivo($_FILES['archivo'], $id_documento)) {
            echo "Documento registrado y archivo subido correctamente.";
        } else {
            echo "Error al subir el archivo.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro de Documentos</title>
</head>
<body>
    <h1>Registro de Documentos</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Tipo de Documento:</label>
        <input type="text" name="tipo_documento" required>
        <br>
        <label>Emisor:</label>
        <input type="text" name="emisor" required>
        <br>
        <label>Receptor:</label>
        <input type="text" name="receptor" required>
        <br>
        <label>Motivo:</label>
        <input type="text" name="motivo" required>
        <br>
        <label>Palabras Clave:</label>
        <input type="text" name="palabras_clave">
        <br>
        <label>Archivo (PDF):</label>
        <input type="file" name="archivo" accept="application/pdf">
        <br>
        <button type="submit">Registrar</button>
    </form>
    <a href="index.php">Volver</a>
</body>
</html>
