-- Crear las tablas base primero ------------------------------------------------

-- Tabla Usuario
CREATE TABLE Usuario (
  id_usuario INT NOT NULL AUTO_INCREMENT,
  nombres VARCHAR(200) NOT NULL,
  apellidos VARCHAR(200) NOT NULL,
  cargo VARCHAR(200),
  correo VARCHAR(200) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY (id_usuario)
)


-- agrega columna contraseña para la tabla usuario
-- ALTER TABLE Usuario ADD COLUMN password VARCHAR(255) NOT NULL; 


-- Primero se crea al admin que gestionara los usuarios 
UPDATE Usuario 
SET password = '$2y$10$w2xpglesITs2/uFDuLfUV.4uuhdnDwdK7h8BCgpozdhE8.PIpPKOu' -- contra: admin'
WHERE correo = 'lagarcia@lamolina.edu.pe'; 

-- Los otros usuarios previamente creados 

INSERT INTO `usuario` VALUES (9, 'Jesus', 'Rojas', 'Estudiante ', 'jrojas@lamolina.edu.pe', '$2y$10$T6Nd2HGFVb9b2pVchawloefo23WijsesKS0nnXP7qpHZNlNSBAt56');
INSERT INTO `usuario` VALUES (10, 'Pablo', 'Quispe', 'Profesor', 'pquispe@lamolina.edu.pe', '$2y$10$e.WV3k4RT9DUyN/b2Ds3kuW9vGV0aOEVtUbVFMVZdiiq8TRa6JBIO');
INSERT INTO `usuario` VALUES (11, 'Roxana ', 'Perez', 'Estudiante', 'rperez@lamolina.edu.pe', '$2y$10$WWYJRBV9I5O1KjUHVctW1OJVo33BSD7AwnoZ8zLjArxc2qEikK.Fy');




-- Tabla Rol
CREATE TABLE Rol (
  id_rol INT NOT NULL AUTO_INCREMENT,
  permisos VARCHAR(250) NOT NULL,
  PRIMARY KEY (id_rol)
);

-- datos: 
INSERT INTO Rol (permisos) VALUES
('Gestión de documentos'),
('Seguimiento de trámites'),
('Administración de usuarios');



-- Crear las tablas relacionadas con claves foráneas ---------------------------

-- Tabla Documento
CREATE TABLE Documento (
  id_documento INT NOT NULL AUTO_INCREMENT,
  tipo_documento VARCHAR(50) NOT NULL,
  fecha_recepcion DATE NOT NULL,
  emisor VARCHAR(250) NOT NULL,
  receptor VARCHAR(250) NOT NULL,
  motivo VARCHAR(250),
  estado ENUM('Pendiente', 'En Proceso', 'Finalizado') NOT NULL,
  palabras_clave VARCHAR(200),
  id_usuario INT,
  PRIMARY KEY (id_documento),
  FOREIGN KEY (id_usuario) REFERENCES Usuario (id_usuario) ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- Datos para la tabla Documento
INSERT INTO `documento` VALUES (6, 'Oficio', '2024-12-18', 'Jesus Rojas', 'Luis Alberto', 'Actualización de datos', 'Pendiente', 'actualización, datos personales', 9);
INSERT INTO `documento` VALUES (7, 'Oficio', '2024-12-18', 'Pablo Quispe', 'Luis Alberto', 'cambio de horario', 'Pendiente', 'horario', 10);
INSERT INTO `documento` VALUES (8, 'Resolución ', '2024-12-18', 'Roxana Perez', 'Luis Alberto', 'Solicitud de certificado', 'Pendiente', 'certificado', 11);






-- Tabla Tramite
CREATE TABLE Tramite (
  id_tramite INT NOT NULL AUTO_INCREMENT,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE DEFAULT NULL,
  estado_tramite ENUM('Pendiente', 'En Proceso', 'Finalizado') NOT NULL,
  id_documento INT NULL,
  `id_area` int NULL DEFAULT NULL,
  PRIMARY KEY (id_tramite),
  FOREIGN KEY (id_documento) REFERENCES Documento (id_documento) ON DELETE RESTRICT ON UPDATE RESTRICT

);
ALTER TABLE Tramite
ADD COLUMN id_area INT DEFAULT NULL,
ADD FOREIGN KEY (id_area) REFERENCES Area (id_area) ON DELETE SET NULL ON UPDATE CASCADE;


-- Tabla Area
CREATE TABLE Area (
  id_area INT NOT NULL AUTO_INCREMENT,
  nombre_area VARCHAR(250) NOT NULL,
  descripcion TEXT,
  id_documento INT NOT NULL,
  PRIMARY KEY (id_area),
  FOREIGN KEY (id_documento) REFERENCES Documento (id_documento) ON DELETE RESTRICT ON UPDATE RESTRICT
);

-- Tabla Seguimiento
CREATE TABLE Seguimiento (
  id_seguimiento INT NOT NULL AUTO_INCREMENT,
  fecha_seguimiento DATE NOT NULL,
  observacion VARCHAR(250),
  estado_actual ENUM('Pendiente', 'En Proceso', 'Finalizado') NOT NULL,
  responsable_nombre VARCHAR(250) NOT NULL,
  id_tramite INT NOT NULL,
  id_rol INT,
  PRIMARY KEY (id_seguimiento),
  FOREIGN KEY (id_tramite) REFERENCES Tramite (id_tramite) ON DELETE RESTRICT ON UPDATE RESTRICT,
  FOREIGN KEY (id_rol) REFERENCES Rol (id_rol) ON DELETE RESTRICT ON UPDATE RESTRICT
);


-- Tabla Archivo
CREATE TABLE Archivo (
  id_archivo INT NOT NULL AUTO_INCREMENT,
  nombre_archivo VARCHAR(250) NOT NULL,
  tipo_archivo VARCHAR(50) NOT NULL,
  tamanio INT NOT NULL,
  ruta VARCHAR(500) NOT NULL,
  id_documento INT,
  PRIMARY KEY (id_archivo),
  FOREIGN KEY (id_documento) REFERENCES Documento (id_documento) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Insertar datos de ejemplo ---------------------------------------------------




-- Datos para la tabla Tramite
INSERT INTO `tramite` VALUES (16, '2024-12-18', NULL, 'Pendiente', 6, 1);
INSERT INTO `tramite` VALUES (17, '2024-12-18', NULL, 'Pendiente', 7, 2);
INSERT INTO `tramite` VALUES (18, '2024-12-18', NULL, 'Pendiente', 8, 1);

-- Datos para la tabla Area
 INSERT INTO Area (nombre_area, descripcion, id_documento) VALUES
('Oficina de Administración', 'Encargada de gestionar recursos y logística', 1),
('Dirección Académica', 'Responsable de la planificación académica', 2);

-- Datos para la tabla Seguimiento
--INSERT INTO Seguimiento (fecha_seguimiento, observacion, estado_actual, responsable_nombre, id_tramite, id_rol) VALUES


-- Datos para la tabla Archivo
INSERT INTO Archivo (nombre_archivo, tipo_archivo, tamanio, ruta, id_documento) VALUES
INSERT INTO `archivo` VALUES (11, 'oficio300.pdf', 'application/pdf', 0, 'uploads/documentos/oficio300.pdf', 6);
INSERT INTO `archivo` VALUES (12, 'oficio400.pdf', 'application/pdf', 0, 'uploads/documentos/oficio400.pdf', 7);
INSERT INTO `archivo` VALUES (13, 'resolucion224.pdf', 'application/pdf', 0, 'uploads/documentos/resolucion224.pdf', 8);
