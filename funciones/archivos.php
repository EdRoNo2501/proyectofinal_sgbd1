function subirArchivo($archivo, $idDocumento) {
    $directorio = "uploads/documentos/";
    $rutaArchivo = $directorio . basename($archivo["name"]);
    $tipoArchivo = mime_content_type($archivo["tmp_name"]);
    $tamanioArchivo = $archivo["size"];

    if (move_uploaded_file($archivo["tmp_name"], $rutaArchivo)) {
        global $conn;
        $stmt = $conn->prepare("INSERT INTO Archivo (nombre_archivo, tipo_archivo, tamanio, ruta, id_documento) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$archivo["name"], $tipoArchivo, $tamanioArchivo, $rutaArchivo, $idDocumento]);
        return true;
    }
    return false;
}
