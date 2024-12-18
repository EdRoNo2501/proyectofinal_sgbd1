# Integrantes
- Rojas Nolasco Edgard Jesus (100%)
- Quiroz Acho Daniel Mauricio (100%)
- Mori Perez Luis Alberto (100%)
- Ramos Correa Marcelo (100%)
- Bautista Camila (100%)


# Tutorial 
1. Descargar el zip
2. Extraer el zip
3. cambiarle de nombre a la carpeta , ejemplo : tramite
4. mover a la carpeta disco localc -> xampp -> htdocs y pegarlo ahi
5. luego abrir el archivo db_tablasrelacionales y ejecutar las tablas y todo lo demas uno por uno
6. abrir en xampp admin
7. en la direccion de link poner localhost/nombredelacarpeta
8. comprobar si todo esta bien



# Conexión de bases de datos 
```
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
```

# Cuentas creadas 
- usu: jrojas@lamolina.edu.pe pass: usuario 1
- usu: rperez@lamolina.edu.pe pass: usuario 2
- usu: pquispe@lamolina.edu.pe pass: usuario 3


> Ejecutar la database en la carpeta docs tablas_database (esta en orden) uno por uno
