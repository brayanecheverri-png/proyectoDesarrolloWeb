-- ============================================================
-- Base de datos: bolsa_empleo
-- Versión actualizada con tabla participante y relaciones completas
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS `bolsa_empleo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `bolsa_empleo`;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pais` (
  `cod_pais` varchar(2) NOT NULL,
  `nom_pais` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pais` VALUES
('CO','Colombia'),('US','Estados Unidos'),('MX','México'),
('AR','Argentina'),('VE','Venezuela'),('EC','Ecuador'),('PE','Perú'),('CL','Chile');

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ciudad` (
  `cod_ciudad` varchar(5) NOT NULL,
  `nom_ciudad` varchar(50) NOT NULL,
  `cod_estado` varchar(2) DEFAULT NULL,
  `cod_pais` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`cod_ciudad`),
  KEY `cod_pais` (`cod_pais`),
  CONSTRAINT `ciudad_ibfk_1` FOREIGN KEY (`cod_pais`) REFERENCES `pais` (`cod_pais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `ciudad` VALUES
('BOG','Bogotá','DC','CO'),('MED','Medellín','AN','CO'),
('CAL','Cali','VC','CO'),('PER','Pereira','RI','CO'),
('BAR','Barranquilla','AT','CO'),('BUC','Bucaramanga','SA','CO'),
('MAN','Manizales','CA','CO'),('CTG','Cartagena','BO','CO');

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `discapacidad` (
  `cod_discapacidad` varchar(2) NOT NULL,
  `nom_discapacidad` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_discapacidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `discapacidad` VALUES
('NA','Ninguna'),('AU','Auditiva'),('VI','Visual'),
('MO','Motora'),('CO','Cognitiva'),('PS','Psicosocial');

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `idioma` (
  `cod_idioma` int NOT NULL AUTO_INCREMENT,
  `nom_idioma` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_idioma`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `idioma` (`nom_idioma`) VALUES
('Español'),('Inglés'),('Francés'),('Portugués'),('Alemán'),('Italiano'),('Mandarín');

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `nivel_educativo` (
  `cod_nivel_educativo` varchar(2) NOT NULL,
  `nom_nivel_educativo` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_nivel_educativo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `nivel_educativo` VALUES
('BA','Bachillerato'),('TE','Técnico'),('TL','Tecnológico'),
('PR','Profesional'),('ES','Especialización'),('MA','Maestría'),('DO','Doctorado');

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `estado_profesional` (
  `cod_estado_prof` varchar(2) NOT NULL,
  `nom_estado_prof` varchar(50) NOT NULL,
  PRIMARY KEY (`cod_estado_prof`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `estado_profesional` VALUES
('EM','Empleado'),('DE','Desempleado'),('IN','Independiente'),('ES','Estudiando'),('PE','Pensionado');

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `empresa_of` (
  `cod_empresa` varchar(15) NOT NULL,
  `nom_empresa` varchar(50) NOT NULL,
  `num_ruc` varchar(15) DEFAULT NULL,
  `nom_representante` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cod_ciudad` varchar(5) DEFAULT NULL,
  `cod_estado` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`cod_empresa`),
  KEY `cod_ciudad` (`cod_ciudad`),
  CONSTRAINT `empresa_of_ibfk_1` FOREIGN KEY (`cod_ciudad`) REFERENCES `ciudad` (`cod_ciudad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `participante` (
  `num_ident` varchar(15) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `cod_ciudad` varchar(5) DEFAULT NULL,
  `cod_nivel_educativo` varchar(2) DEFAULT NULL,
  `cod_estado_prof` varchar(2) DEFAULT NULL,
  `cod_discapacidad` varchar(2) DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`num_ident`),
  KEY `cod_ciudad` (`cod_ciudad`),
  KEY `cod_nivel_educativo` (`cod_nivel_educativo`),
  KEY `cod_discapacidad` (`cod_discapacidad`),
  CONSTRAINT `participante_ibfk_1` FOREIGN KEY (`cod_ciudad`) REFERENCES `ciudad` (`cod_ciudad`),
  CONSTRAINT `participante_ibfk_2` FOREIGN KEY (`cod_nivel_educativo`) REFERENCES `nivel_educativo` (`cod_nivel_educativo`),
  CONSTRAINT `participante_ibfk_3` FOREIGN KEY (`cod_discapacidad`) REFERENCES `discapacidad` (`cod_discapacidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario` (
  `cod_usuario` varchar(15) NOT NULL,
  `Participante_num_ident` varchar(15) NOT NULL,
  `num_usuario` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `estado` bit(1) DEFAULT b'1',
  PRIMARY KEY (`cod_usuario`),
  UNIQUE KEY `num_usuario` (`num_usuario`),
  KEY `Participante_num_ident` (`Participante_num_ident`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`Participante_num_ident`) REFERENCES `participante` (`num_ident`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `oferta_trabajo_of` (
  `cod_oferta` int NOT NULL AUTO_INCREMENT,
  `estado` varchar(2) DEFAULT 'AC',
  `num_oferta` varchar(15) DEFAULT NULL,
  `nom_puesto_trabajo` varchar(100) DEFAULT NULL,
  `descripcion` text,
  `requisitos` text,
  `salario` varchar(20) DEFAULT NULL,
  `num_vacantes` int DEFAULT 1,
  `horario` varchar(50) DEFAULT NULL,
  `duracion` varchar(50) DEFAULT NULL,
  `experiencia` varchar(100) DEFAULT NULL,
  `cod_discapacidad` varchar(2) DEFAULT NULL,
  `cod_idioma` int DEFAULT NULL,
  `cod_nivel` varchar(2) DEFAULT NULL,
  `cod_ciudad` varchar(5) DEFAULT NULL,
  `cod_empresa` varchar(15) DEFAULT NULL,
  `fecha_publicacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cod_oferta`),
  KEY `cod_discapacidad` (`cod_discapacidad`),
  KEY `cod_idioma` (`cod_idioma`),
  KEY `cod_nivel` (`cod_nivel`),
  KEY `cod_ciudad` (`cod_ciudad`),
  KEY `cod_empresa` (`cod_empresa`),
  CONSTRAINT `oferta_ibfk_1` FOREIGN KEY (`cod_discapacidad`) REFERENCES `discapacidad` (`cod_discapacidad`),
  CONSTRAINT `oferta_ibfk_2` FOREIGN KEY (`cod_idioma`) REFERENCES `idioma` (`cod_idioma`),
  CONSTRAINT `oferta_ibfk_3` FOREIGN KEY (`cod_nivel`) REFERENCES `nivel_educativo` (`cod_nivel_educativo`),
  CONSTRAINT `oferta_ibfk_4` FOREIGN KEY (`cod_ciudad`) REFERENCES `ciudad` (`cod_ciudad`),
  CONSTRAINT `oferta_ibfk_5` FOREIGN KEY (`cod_empresa`) REFERENCES `empresa_of` (`cod_empresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `postulacion` (
  `cod_postulacion` int NOT NULL AUTO_INCREMENT,
  `cod_oferta` int NOT NULL,
  `num_ident_participante` varchar(15) NOT NULL,
  `fecha_postulacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `estado` varchar(2) DEFAULT 'PE',
  PRIMARY KEY (`cod_postulacion`),
  KEY `cod_oferta` (`cod_oferta`),
  KEY `num_ident_participante` (`num_ident_participante`),
  CONSTRAINT `postulacion_ibfk_1` FOREIGN KEY (`cod_oferta`) REFERENCES `oferta_trabajo_of` (`cod_oferta`),
  CONSTRAINT `postulacion_ibfk_2` FOREIGN KEY (`num_ident_participante`) REFERENCES `participante` (`num_ident`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

-- ============================================================
-- SOPORTE PARA USUARIOS DE TIPO EMPRESA
-- Los usuarios empresa tienen cod_usuario = 'EMP-{cod_empresa}'
-- y se asocian a un participante (el representante).
-- ============================================================

-- Ejemplo: crear un participante-representante y su usuario empresa
-- (Solo para referencia; adaptar según los datos reales)
--
-- INSERT INTO participante (num_ident, nombre, apellido, email, telefono)
-- VALUES ('900123456', 'Carlos', 'Representante', 'empresa@tech.co', '3001234567');
--
-- INSERT INTO usuario (cod_usuario, Participante_num_ident, num_usuario, password_hash, estado)
-- VALUES ('EMP-TECH001', '900123456', 'empresa_tech', password_hash_aqui, b'1');
-- (donde 'TECH001' es el cod_empresa en empresa_of)

