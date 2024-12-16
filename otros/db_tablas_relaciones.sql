-- Crear las tablas base primero ------------------------------------------------

-- Tabla Usuario
CREATE TABLE Usuario (
  id_usuario INT NOT NULL AUTO_INCREMENT,
  nombres VARCHAR(200) NOT NULL,
  apellidos VARCHAR(200) NOT NULL,
  cargo VARCHAR(200),
  correo VARCHAR(200) NOT NULL UNIQUE,
  PRIMARY KEY (id_usuario)
)


-- agrega columna contraseña para la tabla usuario
ALTER TABLE Usuario ADD COLUMN password VARCHAR(255) NOT NULL; 


UPDATE Usuario 
SET password = '$2y$10$w2xpglesITs2/uFDuLfUV.4uuhdnDwdK7h8BCgpozdhE8.PIpPKOu' -- contra: admin'
WHERE correo = 'lagarcia@lamolina.edu.pe'; 


UPDATE Usuario 
SET password = '$2y$10$QiFpG7P.Y2lSc.aAkLDtcuAp5rE22/rtTDFT5rUuKZTPi/FEQfRz6' -- Contraseña: estudiante
WHERE correo = 'amlopez@lamolina.edu.pe';


-- Tabla Rol
CREATE TABLE Rol (
  id_rol INT NOT NULL AUTO_INCREMENT,
  permisos VARCHAR(250) NOT NULL,
  PRIMARY KEY (id_rol)
);

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

-- Tabla Tramite
CREATE TABLE Tramite (
  id_tramite INT NOT NULL AUTO_INCREMENT,
  fecha_inicio DATE NOT NULL,
  fecha_fin DATE DEFAULT NULL,
  estado_tramite ENUM('Pendiente', 'En Proceso', 'Finalizado') NOT NULL,
  id_documento INT NOT NULL,
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

-- Datos para la tabla Usuario
INSERT INTO Usuario (nombres, apellidos, cargo, correo) VALUES
('Juan Carlos', 'Pérez Huamán', 'Profesor', 'jcperez@lamolina.edu.pe'),
('Ana María', 'Lopez Torres', 'Estudiante', 'amlopez@lamolina.edu.pe'),
('Luis Alberto', 'García Mendoza', 'Administrador', 'lagarcia@lamolina.edu.pe');

-- Datos para la tabla Rol
INSERT INTO Rol (permisos) VALUES
('Gestión de documentos'),
('Seguimiento de trámites'),
('Administración de usuarios');

-- Datos para la tabla Documento
INSERT INTO Documento (tipo_documento, fecha_recepcion, emisor, receptor, motivo, estado, palabras_clave, id_usuario) VALUES
('Memorándum', '2024-12-01', 'Oficina de Administración', 'Decanato', 'Solicitud de presupuesto', 'Pendiente', 'presupuesto, administración', 3),
('Oficio', '2024-12-02', 'Dirección Académica', 'Profesor Juan Carlos Pérez', 'Entrega de horarios', 'En Proceso', 'horarios, academia', 1);

-- Datos para la tabla Tramite
INSERT INTO Tramite (fecha_inicio, fecha_fin, estado_tramite, id_documento) VALUES
('2024-12-01', NULL, 'Pendiente', 1),
('2024-12-02', '2024-12-05', 'Finalizado', 2);

-- Datos para la tabla Area
INSERT INTO Area (nombre_area, descripcion, id_documento) VALUES
('Oficina de Administración', 'Encargada de gestionar recursos y logística', 1),
('Dirección Académica', 'Responsable de la planificación académica', 2);

-- Datos para la tabla Seguimiento
INSERT INTO Seguimiento (fecha_seguimiento, observacion, estado_actual, responsable_nombre, id_tramite, id_rol) VALUES
('2024-12-03', 'Documento revisado por el decano', 'En Proceso', 'Juan Carlos Pérez', 1, 2),
('2024-12-06', 'Trámite finalizado y archivado', 'Finalizado', 'Ana María Lopez', 2, 1);

-- Datos para la tabla Archivo
INSERT INTO Archivo (nombre_archivo, tipo_archivo, tamanio, ruta, id_documento) VALUES
('Solicitud_Presupuesto.pdf', 'application/pdf', 2048, '/uploads/documentos/Solicitud_Presupuesto.pdf', 1),
('Horario_Entrega.pdf', 'application/pdf', 1024, '/uploads/documentos/Horario_Entrega.pdf', 2);