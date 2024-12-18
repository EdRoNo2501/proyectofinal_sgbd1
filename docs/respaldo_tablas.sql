/*
 Navicat Premium Dump SQL

 Source Server         : 1_clase
 Source Server Type    : MySQL
 Source Server Version : 100432 (10.4.32-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : db_tramite_u3

 Target Server Type    : MySQL
 Target Server Version : 100432 (10.4.32-MariaDB)
 File Encoding         : 65001

 Date: 18/12/2024 00:39:36
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for archivo
-- ----------------------------
DROP TABLE IF EXISTS `archivo`;
CREATE TABLE `archivo`  (
  `id_archivo` int NOT NULL AUTO_INCREMENT,
  `nombre_archivo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_archivo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tamanio` int NOT NULL,
  `ruta` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_documento` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_archivo`) USING BTREE,
  INDEX `id_documento`(`id_documento` ASC) USING BTREE,
  CONSTRAINT `archivo_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of archivo
-- ----------------------------
INSERT INTO `archivo` VALUES (11, 'oficio300.pdf', 'application/pdf', 0, 'uploads/documentos/oficio300.pdf', 6);
INSERT INTO `archivo` VALUES (12, 'oficio400.pdf', 'application/pdf', 0, 'uploads/documentos/oficio400.pdf', 7);
INSERT INTO `archivo` VALUES (13, 'resolucion224.pdf', 'application/pdf', 0, 'uploads/documentos/resolucion224.pdf', 8);

-- ----------------------------
-- Table structure for area
-- ----------------------------
DROP TABLE IF EXISTS `area`;
CREATE TABLE `area`  (
  `id_area` int NOT NULL,
  `nombre_area` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `id_documento` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_area`) USING BTREE,
  INDEX `id_documento`(`id_documento` ASC) USING BTREE,
  CONSTRAINT `area_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of area
-- ----------------------------
INSERT INTO `area` VALUES (1, 'Oficina de Administración', 'Encargada de gestionar recursos y logística', NULL);
INSERT INTO `area` VALUES (2, 'Dirección Académica', 'Responsable de la planificación académica', NULL);

-- ----------------------------
-- Table structure for documento
-- ----------------------------
DROP TABLE IF EXISTS `documento`;
CREATE TABLE `documento`  (
  `id_documento` int NOT NULL AUTO_INCREMENT,
  `tipo_documento` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_recepcion` date NOT NULL,
  `emisor` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `receptor` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `motivo` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `estado` enum('Pendiente','En Proceso','Finalizado') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `palabras_clave` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_usuario` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_documento`) USING BTREE,
  INDEX `id_usuario`(`id_usuario` ASC) USING BTREE,
  CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of documento
-- ----------------------------
INSERT INTO `documento` VALUES (6, 'Oficio', '2024-12-18', 'Jesus Rojas', 'Luis Alberto', 'Actualización de datos', 'Pendiente', 'actualización, datos personales', 9);
INSERT INTO `documento` VALUES (7, 'Oficio', '2024-12-18', 'Pablo Quispe', 'Luis Alberto', 'cambio de horario', 'Pendiente', 'horario', 10);
INSERT INTO `documento` VALUES (8, 'Resolución ', '2024-12-18', 'Roxana Perez', 'Luis Alberto', 'Solicitud de certificado', 'Pendiente', 'certificado', 11);

-- ----------------------------
-- Table structure for rol
-- ----------------------------
DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol`  (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `permisos` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_rol`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of rol
-- ----------------------------
INSERT INTO `rol` VALUES (1, 'Gestión de documentos');
INSERT INTO `rol` VALUES (2, 'Seguimiento de trámites');
INSERT INTO `rol` VALUES (3, 'Administración de usuarios');

-- ----------------------------
-- Table structure for seguimiento
-- ----------------------------
DROP TABLE IF EXISTS `seguimiento`;
CREATE TABLE `seguimiento`  (
  `id_seguimiento` int NOT NULL AUTO_INCREMENT,
  `fecha_seguimiento` date NOT NULL,
  `observacion` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `estado_actual` enum('Pendiente','En Proceso','Finalizado') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `responsable_nombre` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_tramite` int NOT NULL,
  `id_rol` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_seguimiento`) USING BTREE,
  INDEX `id_tramite`(`id_tramite` ASC) USING BTREE,
  INDEX `id_rol`(`id_rol` ASC) USING BTREE,
  CONSTRAINT `seguimiento_ibfk_1` FOREIGN KEY (`id_tramite`) REFERENCES `tramite` (`id_tramite`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `seguimiento_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of seguimiento
-- ----------------------------

-- ----------------------------
-- Table structure for tramite
-- ----------------------------
DROP TABLE IF EXISTS `tramite`;
CREATE TABLE `tramite`  (
  `id_tramite` int NOT NULL AUTO_INCREMENT,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NULL DEFAULT NULL,
  `estado_tramite` enum('Pendiente','En Proceso','Finalizado') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_documento` int NOT NULL,
  `id_area` int NULL DEFAULT NULL,
  PRIMARY KEY (`id_tramite`) USING BTREE,
  INDEX `id_documento`(`id_documento` ASC) USING BTREE,
  INDEX `id_area`(`id_area` ASC) USING BTREE,
  CONSTRAINT `tramite_ibfk_1` FOREIGN KEY (`id_documento`) REFERENCES `documento` (`id_documento`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tramite_ibfk_2` FOREIGN KEY (`id_area`) REFERENCES `area` (`id_area`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tramite
-- ----------------------------
INSERT INTO `tramite` VALUES (16, '2024-12-18', NULL, 'Pendiente', 6, 1);
INSERT INTO `tramite` VALUES (17, '2024-12-18', NULL, 'Pendiente', 7, 2);
INSERT INTO `tramite` VALUES (18, '2024-12-18', NULL, 'Pendiente', 8, 1);

-- ----------------------------
-- Table structure for usuario
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario`  (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombres` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `apellidos` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cargo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `correo` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_usuario`) USING BTREE,
  UNIQUE INDEX `correo`(`correo` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of usuario
-- ----------------------------
INSERT INTO `usuario` VALUES (1, 'Luis Alberto', 'García Mendoza', 'Administrador', 'lagarcia@lamolina.edu.pe', '$2y$10$w2xpglesITs2/uFDuLfUV.4uuhdnDwdK7h8BCgpozdhE8.PIpPKOu');
INSERT INTO `usuario` VALUES (9, 'Jesus', 'Rojas', 'Estudiante ', 'jrojas@lamolina.edu.pe', '$2y$10$T6Nd2HGFVb9b2pVchawloefo23WijsesKS0nnXP7qpHZNlNSBAt56');
INSERT INTO `usuario` VALUES (10, 'Pablo', 'Quispe', 'Profesor', 'pquispe@lamolina.edu.pe', '$2y$10$e.WV3k4RT9DUyN/b2Ds3kuW9vGV0aOEVtUbVFMVZdiiq8TRa6JBIO');
INSERT INTO `usuario` VALUES (11, 'Roxana ', 'Perez', 'Estudiante', 'rperez@lamolina.edu.pe', '$2y$10$WWYJRBV9I5O1KjUHVctW1OJVo33BSD7AwnoZ8zLjArxc2qEikK.Fy');

SET FOREIGN_KEY_CHECKS = 1;
