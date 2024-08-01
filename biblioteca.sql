-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 17-07-2024 a las 02:52:48
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

DROP TABLE IF EXISTS `libros`;
CREATE TABLE IF NOT EXISTS `libros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(100) NOT NULL,
  `autor` varchar(100) NOT NULL,
  `editorial` varchar(100) DEFAULT NULL,
  `aniopubli` varchar(4) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `titulo`, `autor`, `editorial`, `aniopubli`, `categoria`, `cantidad`, `imagen`) VALUES
(25, 'otrooooo', 'otrooo', 'otroooo', '21', 'otroo', 10, 'imagenes/1623075087-sender-superviviente-1623075025.jpg'),
(26, 's', 's', 's', '20', 'ss', 200, 'imagenes/1623075087-sender-superviviente-1623075025.jpg'),
(23, 'Los hombres del norte', 'n/n', 'n/n', '2020', 'nuevo', 20, 'imagenes/libro-imprenta-lima.jpg'),
(22, 'historia', 'nose', 'nuevo', '1999', 'suspensso', 15, 'imagenes/descarga.png'),
(21, 'harry potter', 'nuevo', 'santillana', '2020', 'historia', 20, 'imagenes/f.elconfidencial.com_original_ce0_8c6_ca1_ce08c6ca14ec097b36ad332c00f08f1e.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contrasena` varchar(100) NOT NULL,
  `celular` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `correo`, `contrasena`, `celular`) VALUES
(1, 'Alexis', 'Chillagana', 'alex@gmail.com', '999', '09980'),
(5, 'PAo', 'soSA', 'pao@gmail.com', '', '0000000001'),
(6, 'Oso', 'Pedro', 'oso@gmail.com', '123', '089'),
(7, 'Pablo', 'Lloa', 'pablo@gmail.com', '123', '085'),
(8, 'P', 'Pedro', 'pepe@g.com', '123', '085'),
(9, 'K', 'K', 'k@gmail.com', '123', '58962'),
(10, 'Juan', 'Qui', 'juan@gmail.com', '123', '0998042108'),
(11, 'Nuevo', 'Nuevo', 'nuevo@gmai.com', '123', '0998042'),
(12, 'Pepedsd', 'Pepesfd', 'pesdasdpe@g.com', '123', '08'),
(13, 'Mm', 'Mm', 'mm@gmail.com', '123', '12'),
(14, 'Asas', 'Dwd', 'adriancoddfdnrado@gmail.com', '123', '0895'),
(15, 'Askajdj', 'Dehkbf', 'kjfew@gmail.com', '123', '05998'),
(16, 'Sad', 'Ddd', 'a@a.com', '123', '095'),
(17, 'Pablo', 'Molina', 'pablom@gmail.com', 'PabloM34', '0987676756');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
