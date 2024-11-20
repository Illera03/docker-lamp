-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 16-09-2020 a las 16:37:17
-- Versión del servidor: 10.5.5-MariaDB-1:10.5.5+maria~focal
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios y juegos`
--

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200),
    dni VARCHAR(255),
    telefono VARCHAR(255),
    fecha_nacimiento VARCHAR(255),
    email VARCHAR(200),
    password VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE juegos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    fecha_lanzamiento VARCHAR(255),
    genero VARCHAR(255),
    nota VARCHAR(255),
    precio VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


--
-- Volcado de datos para la tabla `usuarios`
--



--
-- Volcado de datos para la tabla `juego`
--


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
/*ALTER TABLE `usuarios`*/
/*  ADD PRIMARY KEY (`id`);*/
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
