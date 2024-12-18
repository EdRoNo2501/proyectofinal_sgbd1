# Tutorial 
1. Descargar el zip
2. Extraer el zip
3. cambiarle de nombre a la carpeta , ejemplo : tramite
4. mover a la carpeta disco localc -> xampp -> htdocs y pegarlo ahi
5. luego abrir el archivo db_tablasrelacionales y ejecutar las tablas y todo lo demas uno por uno
6. abrir en xampp admin
7. en la direccion de link poner localhost/nombredelacarpeta
8. comprobar si todo esta bien


# Conexcion de bases de datos 

<?php
$host = "localhost";
$dbname = "db_tramite_u3"; # nombre de la base de datos puesto en el navicat
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
    exit;
}
?>
