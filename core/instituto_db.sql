-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 24-11-2025 a las 19:27:44
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `instituto_db`
--

-- --------------------------------------------------------
DROP DATABASE IF EXISTS instituto_db;
CREATE DATABASE instituto_db;
USE instituto_db;

--
-- Estructura de tabla para la tabla `carrera`
--

DROP TABLE IF EXISTS `carrera`;
CREATE TABLE IF NOT EXISTS `carrera` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`id`, `descripcion`) VALUES
(1, 'Tecnicatura Superior en Administración con Orientación en Marketing'),
(2, 'Tecnicatura Superior en Administración Contable'),
(3, 'Tecnicatura Superior en Administración Pública'),
(4, 'Tecnicatura Superior en Análisis, Desarrollo y Programación de Aplicaciones'),
(5, 'Tecnicatura Superior en Enfermería'),
(6, 'Tecnicatura Superior en Guía de Turismo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidad`
--

DROP TABLE IF EXISTS `localidad`;
CREATE TABLE IF NOT EXISTS `localidad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `localidad`
--

INSERT INTO `localidad` (`id`, `descripcion`) VALUES
(1, 'San Vicente'),
(2, 'Alejandro Korn'),
(3, 'Guernica'),
(4, 'Glew'),
(5, 'Longchamps'),
(6, 'Domselaar');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `id` varchar(3) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `descripcion`) VALUES
('D', 'Director'),
('P', 'Profesor'),
('A', 'Alumno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `dni` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `telefono` varchar(19) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `usuario_name` varchar(20) NOT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `id_localidad` int DEFAULT NULL,
  `id_rol` varchar(3) NOT NULL,
  `fecha_inicio` DATE NULL,
  `fecha_finalizacion` DATE NULL,
  `activo` BOOLEAN DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_name` (`usuario_name`),
  UNIQUE KEY `email` (`email`),
  KEY `id_localidad` (`id_localidad`),
  KEY `id_rol` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `dni`, `nombre`, `apellido`, `telefono`, `email`, `password`, `usuario_name`, `calle`, `id_localidad`, `id_rol`,`fecha_inicio`) VALUES
(1, 43999888, 'Mariela', 'Aguirre', '1130989898', 'marcoronelgege@gmail.com', '$2y$10$/piXPMU4cqF8bK4y6r/CFOMM6UMXz7f8DFmNdhT1bBzf6kveXOLmy', 'alumno123', 'calle  001', 1, 'A', '2020-01-01'),
(2, 43999881, 'Marcos', 'Marquelo', '1130989891', 'alumno@correo.com', '$2y$10$MprR7r0bAQDslO.HmvvIUuQAV/GPq/5OrLGHiSNhjvxuAm7ycg9Pe', 'alumnoA123', 'calle 222', 2, 'A','2020-01-01'),
(3, 43999882, 'Roma', 'Romama', '1230989891', 'alumnoB@correo.com', '$2y$10$DQD.Kt8bQZ.gurgXtGHCD.RvY4shnjxNMSlKCu2VsDYpOt.nNFCdq', 'alumnoB123', 'calle 333', 4, 'A','2020-01-01'),
(4, 43999882, 'Alegra', 'Alegra', '1130989822', 'alumnoC@correo.com', '$2y$10$0Gk.1ygAT6PFENnvj4obhOLocYKSB7XIs9n2kqVPWpQpYu3rqWJVC', 'alumnoC123', 'calle 444', 3, 'A','2023-01-01'),
(5, 20999888, 'ramiro', 'ramiro', '1130989333', 'profesorA@correo.com', '$2y$10$Z.xDBhQ4eI2V4Wejw0YDQenR2U5Yg.fWJIDq20662cm9kcoSWhkKC', 'profeA123', 'calle 555', 5, 'P','2023-01-01'),
(6, 20999828, 'lorena', 'lorena', '1130989223', 'profesorB@correo.com', '$2y$10$8fG2LwT9ag9frUj1iT8TI./Jh2q.jzE0cf2JG9V4Ji3Mc.AgmV1Du', 'profeB123', 'calle 666', 3, 'P','2021-01-01'),
(7, 20999820, 'Sofia', 'soez', '1130989233', 'profesorC@correo.com', '$2y$10$KB23T1YP.WPRf0w.1viVcuO74CZgl7tcuL8ItAZeTfO1xQRB6qbba', 'profeC123', 'calle 777', 2, 'P','2020-01-01'),
(8, 20999855, 'lopez', 'lopez', '1130989255', 'profesorD@correo.com', '$2y$10$5UctRnK2G.LKp/lMp9.HT.VLRYCRzGE8xvt1h6JzygKBegrV6xAd.', 'profeD123', 'calle 888', 4, 'P','2022-01-01'),
(9, 20999222, 'paul', 'paula', '1130989222', 'profesorE@correo.com', '$2y$10$9Q11qfadoIVA7CkXgJer2.XbrjtPMs6PqutCsqLdN1pOcCpIUu0X2', 'profeE123', 'calle 999', 6, 'P','2021-01-01'),
(10, 20999555, 'federico', 'federico', '1130989101', 'profesorF@correo.com', '$2y$10$kaVQeqSn5SvQV.ULD4kMFeY4q2K9paPv/4IUGXfjB3eIkGjvDsT1q', 'profeF123', 'calle 101', 1, 'P','2018-01-01'),
(11, 25999555, 'Mara', 'Maez', '1130989112', 'director@correo.com', '$2y$10$t6NSCrTDFhyOIvT7L/iuj.cTKUDTRmoY03dCeOkW8rRIiNxW8.U4G', 'director123', 'calle 111', 1, 'D','2010-01-01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_carrera`
--

DROP TABLE IF EXISTS `usuario_carrera`;
CREATE TABLE IF NOT EXISTS `usuario_carrera` (
  `id_usuario` int NOT NULL,
  `id_carrera` int NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_carrera`),
  KEY `id_carrera` (`id_carrera`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario_carrera`
--

INSERT INTO `usuario_carrera` (`id_usuario`, `id_carrera`) VALUES
(1, 2),
(1, 4),
(1, 5),
(2, 3),
(2, 4),
(2, 6),
(3, 1),
(4, 1),
(4, 2),
(4, 4),
(4, 5),
(5, 1),
(6, 2),
(7, 3),
(8, 4),
(9, 5),
(10, 6);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
