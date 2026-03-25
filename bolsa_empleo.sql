-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-03-2026 a las 17:03:00
-- Versión del servidor: 8.4.3
-- Versión de PHP: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bolsa_empleo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ciudad`
--

CREATE TABLE `ciudad` (
  `cod_ciudad` varchar(5) NOT NULL,
  `nom_ciudad` varchar(50) NOT NULL,
  `cod_estado` varchar(2) DEFAULT NULL,
  `cod_pais` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `discapacidad`
--

CREATE TABLE `discapacidad` (
  `cod_discapacidad` varchar(2) NOT NULL,
  `nom_discapacidad` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa_of`
--

CREATE TABLE `empresa_of` (
  `cod_empresa` varchar(15) NOT NULL,
  `nom_empresa` varchar(50) NOT NULL,
  `num_ruc` varchar(15) DEFAULT NULL,
  `nom_representante` varchar(50) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `cod_ciudad` varchar(5) DEFAULT NULL,
  `cod_estado` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_profesional`
--

CREATE TABLE `estado_profesional` (
  `cod_estado_prof` varchar(2) NOT NULL,
  `nom_estado_prof` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `idioma`
--

CREATE TABLE `idioma` (
  `cod_idioma` int NOT NULL,
  `nom_idioma` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_educativo`
--

CREATE TABLE `nivel_educativo` (
  `cod_nivel_educativo` varchar(2) NOT NULL,
  `nom_nivel_educativo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oferta_trabajo_of`
--

CREATE TABLE `oferta_trabajo_of` (
  `cod_oferta` int NOT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `num_oferta` varchar(15) DEFAULT NULL,
  `nom_puesto_trabajo` varchar(50) DEFAULT NULL,
  `descripcion` text,
  `requisitos` varchar(50) DEFAULT NULL,
  `cod_discapacidad` varchar(2) DEFAULT NULL,
  `cod_idioma` int DEFAULT NULL,
  `cod_estado` varchar(2) DEFAULT NULL,
  `cod_nivel` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pais`
--

CREATE TABLE `pais` (
  `cod_pais` varchar(2) NOT NULL,
  `nom_pais` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cod_usuario` varchar(15) NOT NULL,
  `Participante_num_ident` varchar(15) NOT NULL,
  `num_usuario` varchar(15) DEFAULT NULL,
  `estado` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD PRIMARY KEY (`cod_ciudad`),
  ADD KEY `cod_pais` (`cod_pais`);

--
-- Indices de la tabla `discapacidad`
--
ALTER TABLE `discapacidad`
  ADD PRIMARY KEY (`cod_discapacidad`);

--
-- Indices de la tabla `empresa_of`
--
ALTER TABLE `empresa_of`
  ADD PRIMARY KEY (`cod_empresa`),
  ADD KEY `cod_ciudad` (`cod_ciudad`);

--
-- Indices de la tabla `estado_profesional`
--
ALTER TABLE `estado_profesional`
  ADD PRIMARY KEY (`cod_estado_prof`);

--
-- Indices de la tabla `idioma`
--
ALTER TABLE `idioma`
  ADD PRIMARY KEY (`cod_idioma`);

--
-- Indices de la tabla `nivel_educativo`
--
ALTER TABLE `nivel_educativo`
  ADD PRIMARY KEY (`cod_nivel_educativo`);

--
-- Indices de la tabla `oferta_trabajo_of`
--
ALTER TABLE `oferta_trabajo_of`
  ADD PRIMARY KEY (`cod_oferta`),
  ADD KEY `cod_discapacidad` (`cod_discapacidad`),
  ADD KEY `cod_idioma` (`cod_idioma`),
  ADD KEY `cod_nivel` (`cod_nivel`);

--
-- Indices de la tabla `pais`
--
ALTER TABLE `pais`
  ADD PRIMARY KEY (`cod_pais`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cod_usuario`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ciudad`
--
ALTER TABLE `ciudad`
  ADD CONSTRAINT `ciudad_ibfk_1` FOREIGN KEY (`cod_pais`) REFERENCES `pais` (`cod_pais`);

--
-- Filtros para la tabla `empresa_of`
--
ALTER TABLE `empresa_of`
  ADD CONSTRAINT `empresa_of_ibfk_1` FOREIGN KEY (`cod_ciudad`) REFERENCES `ciudad` (`cod_ciudad`);

--
-- Filtros para la tabla `oferta_trabajo_of`
--
ALTER TABLE `oferta_trabajo_of`
  ADD CONSTRAINT `oferta_trabajo_of_ibfk_1` FOREIGN KEY (`cod_discapacidad`) REFERENCES `discapacidad` (`cod_discapacidad`),
  ADD CONSTRAINT `oferta_trabajo_of_ibfk_2` FOREIGN KEY (`cod_idioma`) REFERENCES `idioma` (`cod_idioma`),
  ADD CONSTRAINT `oferta_trabajo_of_ibfk_3` FOREIGN KEY (`cod_nivel`) REFERENCES `nivel_educativo` (`cod_nivel_educativo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
