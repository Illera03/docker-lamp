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
    nombre VARCHAR(100),
    dni VARCHAR(10),
    telefono VARCHAR(9),
    fecha_nacimiento DATE,
    email VARCHAR(100),
    password VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE biblioteca_de_juegos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE juego (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    fecha_lanzamiento DATE,
    genero VARCHAR(100),
    nota FLOAT,
    precio FLOAT,
    bibliojuego_id INT,
    FOREIGN KEY (bibliojuego_id) REFERENCES biblioteca_de_juegos(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`) VALUES
(1, 'mikel'),
(2, 'aitor');

--
-- Volcado de datos para la tabla `bibliojuegos`
--

INSERT INTO `biblioteca_de_juegos` (`nombre`) VALUES
('The Legend of Zelda: Breath of the Wild'),
('Super Mario Odyssey'),
('Red Dead Redemption 2'),
('God of War'),
('The Witcher 3: Wild Hunt'),
('Minecraft'),
('Fortnite'),
('Overwatch'),
('Dark Souls III'),
('Animal Crossing: New Horizons');

--
-- Volcado de datos para la tabla `juego`
--
INSERT INTO `juego` (`nombre`, `fecha_lanzamiento`, `genero`, `nota`, `precio`, `bibliojuego_id`) VALUES
('The Legend of Zelda: Breath of the Wild', '2017-03-03', 'Aventura', 4.9, 59.99, 1),
('Super Mario Odyssey', '2017-10-27', 'Plataformas', 4.8, 59.99, 2),
('Red Dead Redemption 2', '2018-10-26', 'Acción/Aventura', 4.7, 69.99, 3),
('God of War', '2018-04-20', 'Acción/Aventura', 4.9, 59.99, 4),
('The Witcher 3: Wild Hunt', '2015-05-19', 'RPG', 4.9, 49.99, 5),
('Minecraft', '2011-11-18', 'Sandbox', 4.6, 26.95, 6),
('Fortnite', '2017-07-25', 'Battle Royale', 4.5, 0.00, 7),
('Overwatch', '2016-05-24', 'Shooter', 4.4, 39.99, 8),
('Dark Souls III', '2016-03-24', 'RPG', 4.7, 39.99, 9),
('Animal Crossing: New Horizons', '2020-03-20', 'Simulación', 4.8, 59.99, 10);

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
