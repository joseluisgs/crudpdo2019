-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 05-12-2019 a las 10:55:57
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `CRUD`
--
CREATE DATABASE IF NOT EXISTS `CRUD` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `CRUD`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'pepe@pepe.com', '926e27eecdbc7a18858b3798ba99bddd');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnado`
--

DROP TABLE IF EXISTS `alumnado`;
CREATE TABLE `alumnado` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alumnado`
--

INSERT INTO `alumnado` (`id`, `nombre`, `apellidos`, `email`) VALUES
(1, 'Alumno a', 'Odio PHP Mucho', 'ana@anayaw.com'),
(2, 'Jose', 'Jamon Pedroches', 'jose@pedroches.es');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnadocrud`
--

DROP TABLE IF EXISTS `alumnadocrud`;
CREATE TABLE `alumnadocrud` (
  `id` int(11) NOT NULL,
  `dni` text COLLATE utf8_spanish_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `idioma` text COLLATE utf8_spanish_ci NOT NULL,
  `matricula` text COLLATE utf8_spanish_ci NOT NULL,
  `lenguaje` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `alumnadocrud`
--

INSERT INTO `alumnadocrud` (`id`, `dni`, `nombre`, `email`, `password`, `idioma`, `matricula`, `lenguaje`, `fecha`, `imagen`) VALUES
(1, '12345678A', 'Pedro Pedrinez', 'pedro@pedrinez.com', 'fcea920f7412b5da7be0cf42b8c93759', 'castellano, ingles', 'completa', 'JAVA', '13/11/2019', '4ed5ba7721a45eb76be40a38458201f4.jpeg'),
(2, '12345678V', 'Eva Perez', 'prueba@prueba.es', '8fc001a0c0207c15ddf61e8214fa1364', 'castellano, ingles, chino', 'modular', 'PYTHON', '20/11/2019', '593c6ac12db159f092a38f823ee0df70.jpeg'),
(3, '12345677A', 'Angela No me fastides con mas cosas', 'angela@seriemucho.com', '9514b76a780a7812eda8150a6d43f260', 'chino', 'completa', 'PHP', '15/11/2019', '4bee37ee50145021ff3912a95f710590.jpeg'),
(4, '12345678D', 'Luz Fuego y DestrucciÃ³n', 'arde@infierno.com', 'f5f091a697cd91c4170cda38e81f4b1a', 'castellano, ingles', 'completa', 'C#', '15/11/2019', '88547de87ee61eae9b5b698f94d9305f.jpeg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `alumnado`
--
ALTER TABLE `alumnado`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `alumnadocrud`
--
ALTER TABLE `alumnadocrud`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dni_unique` (`dni`) USING HASH;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `alumnado`
--
ALTER TABLE `alumnado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `alumnadocrud`
--
ALTER TABLE `alumnadocrud`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
