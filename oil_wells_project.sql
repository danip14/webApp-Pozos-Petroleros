-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-07-2023 a las 08:51:35
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `oil_wells_project`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oil_wells`
--

CREATE TABLE `oil_wells` (
  `id` int(7) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `oil_wells`
--

INSERT INTO `oil_wells` (`id`, `name`) VALUES
(1, 'Maracaibo'),
(2, 'Carabobo'),
(3, 'Furrial'),
(4, 'Oriente'),
(5, 'Morichal'),
(6, 'Lagunillas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `psi_data`
--

CREATE TABLE `psi_data` (
  `id` int(11) NOT NULL,
  `oil_wells_id` int(11) NOT NULL,
  `psi` float NOT NULL,
  `dt` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `psi_data`
--

INSERT INTO `psi_data` (`id`, `oil_wells_id`, `psi`, `dt`) VALUES
(1, 1, 300.45, '2023-04-20 02:40:48'),
(2, 2, 310.45, '2023-07-01 02:43:43'),
(3, 1, 3123.45, '2023-07-06 00:31:04'),
(4, 6, 500.45, '2023-07-02 18:42:16'),
(5, 6, 321, '2023-07-06 02:14:51'),
(34, 5, 1, '2010-06-11 22:02:00'),
(35, 4, 12, '2023-07-20 11:08:00'),
(36, 2, 1, '2023-07-13 10:14:00'),
(43, 1, 345, '2022-09-12 16:47:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `oil_wells`
--
ALTER TABLE `oil_wells`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `psi_data`
--
ALTER TABLE `psi_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_oil_wells_id` (`oil_wells_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `oil_wells`
--
ALTER TABLE `oil_wells`
  MODIFY `id` int(7) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `psi_data`
--
ALTER TABLE `psi_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `psi_data`
--
ALTER TABLE `psi_data`
  ADD CONSTRAINT `fk_oil_wells_id` FOREIGN KEY (`oil_wells_id`) REFERENCES `oil_wells` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
