-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-03-2025 a las 18:49:32
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `freefire`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `armas`
--

CREATE TABLE `armas` (
  `id_arma` int NOT NULL,
  `nombreArma` varchar(250) DEFAULT NULL,
  `balas` int DEFAULT NULL,
  `daño` int DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `id_categoria` int DEFAULT NULL,
  `nivelRequerido` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `armas`
--

INSERT INTO `armas` (`id_arma`, `nombreArma`, `balas`, `daño`, `foto`, `id_categoria`, `nivelRequerido`) VALUES
(1, 'Revolver', 100, 2, 'revolver.png', 1, 1),
(2, 'Pistola 9mm', 120, 2, 'pistola9mm.png', 1, 1),
(3, 'Desert Eagle', 50, 2, 'desert_eagle.png', 1, 1),
(4, 'Sarten', 0, 1, 'Sarten.png', 2, 1),
(5, 'Machete', 0, 1, 'machete.png', 2, 1),
(6, 'Katana', 0, 1, 'katana.png', 2, 1),
(7, 'MP40', 300, 10, 'MP40.png', 3, 2),
(8, 'AK-47', 150, 10, 'AK-47.png', 3, 2),
(9, 'M249', 200, 10, 'm249.png', 3, 2),
(10, 'SVD', 10, 20, 'svd.png', 4, 2),
(11, 'kar98k', 22, 20, 'kar98k.png', 4, 2),
(12, 'AWM', 15, 20, 'AWM.png', 4, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ataque`
--

CREATE TABLE `ataque` (
  `id_disparolog` int NOT NULL,
  `id_usuario` int NOT NULL,
  `codigoPartida` int DEFAULT NULL,
  `id_arma` int NOT NULL,
  `id_atacado` int NOT NULL,
  `id_zonaAtaque` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoriadearmas`
--

CREATE TABLE `categoriadearmas` (
  `id_categoria` int NOT NULL,
  `nombreCategoria` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `categoriadearmas`
--

INSERT INTO `categoriadearmas` (`id_categoria`, `nombreCategoria`) VALUES
(1, 'Pistolas'),
(2, 'Cuerpo a cuerpo'),
(3, 'Ametralladoras'),
(4, 'Francotiradores');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int NOT NULL,
  `estado` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `estado`) VALUES
(1, 'Activo'),
(2, 'Bloqueado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugadores_en_sala`
--

CREATE TABLE `jugadores_en_sala` (
  `id_registro` int NOT NULL,
  `id_sala` int NOT NULL,
  `id_usuario` int NOT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `listo` int DEFAULT NULL,
  `codigoPartida` int DEFAULT NULL,
  `hora_ingreso` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `jugadores_en_sala`
--

INSERT INTO `jugadores_en_sala` (`id_registro`, `id_sala`, `id_usuario`, `nombre_usuario`, `listo`, `codigoPartida`, `hora_ingreso`) VALUES
(475, 3, 42, 'pinwisaurio', NULL, 1302777, '2025-03-08 16:59:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mundos`
--

CREATE TABLE `mundos` (
  `id_mundo` int NOT NULL,
  `nombreMundo` varchar(500) DEFAULT NULL,
  `fotoMapa` varchar(250) DEFAULT NULL,
  `nivelRequerido` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `mundos`
--

INSERT INTO `mundos` (`id_mundo`, `nombreMundo`, `fotoMapa`, `nivelRequerido`) VALUES
(1, 'Bermudas', 'mapa1.jpeg', 1),
(2, 'Verdanzk', 'mapa2.png', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id_partida` int NOT NULL,
  `id_usuario` int DEFAULT NULL,
  `tiempo` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ganador` varchar(250) DEFAULT NULL,
  `codigoPartida` int DEFAULT NULL,
  `id_sala` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `partida`
--

INSERT INTO `partida` (`id_partida`, `id_usuario`, `tiempo`, `ganador`, `codigoPartida`, `id_sala`) VALUES
(159, 43, '2025-03-03 16:27:40', '42', 4352649, 1),
(160, 42, '2025-03-03 16:27:40', '42', 4352649, 1),
(161, 42, '2025-03-03 22:27:59', '42', 4529909, 1),
(162, 42, '2025-03-03 22:32:13', '42', 6125853, 1),
(163, 43, '2025-03-03 22:32:13', '42', 6125853, 1),
(164, NULL, '2025-03-04 00:27:36', NULL, NULL, 1),
(165, 43, '2025-03-04 00:30:23', '42', 7734717, 1),
(166, 42, '2025-03-04 00:30:23', '42', 7734717, 1),
(167, 42, '2025-03-04 00:35:39', '43', 2025418, 3),
(168, 43, '2025-03-04 00:35:39', '43', 2025418, 3),
(169, 43, '2025-03-04 18:03:27', '42', 6271962, 1),
(170, 42, '2025-03-04 18:03:27', '42', 6271962, 1),
(171, NULL, '2025-03-05 12:25:45', NULL, NULL, NULL),
(172, NULL, '2025-03-05 12:25:59', NULL, NULL, NULL),
(173, NULL, '2025-03-05 12:26:10', NULL, NULL, NULL),
(174, 43, '2025-03-05 12:34:02', '42', 8361572, 1),
(175, 42, '2025-03-05 12:34:02', '42', 8361572, 1),
(176, 42, '2025-03-05 12:47:20', '42', 7783302, 1),
(177, 43, '2025-03-05 12:47:20', '42', 7783302, 1),
(178, 43, '2025-03-05 12:50:53', '43', 2175328, 1),
(179, 42, '2025-03-05 12:50:53', '43', 2175328, 1),
(180, 43, '2025-03-05 13:28:28', '43', 9396922, 1),
(181, 43, '2025-03-05 21:17:30', '42', 2285666, 1),
(182, 42, '2025-03-05 21:17:30', '42', 2285666, 1),
(183, 42, '2025-03-06 11:39:26', '43', 9898279, 1),
(184, 43, '2025-03-06 11:39:26', '43', 9898279, 1),
(185, 42, '2025-03-06 12:37:46', '43', 6800189, 1),
(186, 43, '2025-03-06 12:37:46', '43', 6800189, 1),
(187, 42, '2025-03-06 13:05:15', '43', 535664, 1),
(188, 43, '2025-03-06 13:05:15', '43', 535664, 1),
(189, 43, '2025-03-06 22:29:08', '42', 3287891, 1),
(190, 42, '2025-03-06 22:29:08', '42', 3287891, 1),
(191, 43, '2025-03-07 12:09:31', '42', 2971906, 1),
(192, 42, '2025-03-07 12:09:31', '42', 2971906, 1),
(193, 48, '2025-03-07 12:11:25', NULL, 2937887, 1),
(194, 48, '2025-03-07 12:24:34', '43', 6470259, 2),
(195, 48, '2025-03-07 12:20:50', NULL, 5560664, 2),
(196, 43, '2025-03-07 12:24:34', '43', 6470259, 2),
(197, 48, '2025-03-07 12:27:07', '42', 6834873, 1),
(198, 42, '2025-03-07 12:27:07', '42', 6834873, 1),
(199, 43, '2025-03-07 12:49:23', '43', 427688, 1),
(200, 43, '2025-03-07 18:37:07', '43', 4915997, 1),
(201, 42, '2025-03-07 21:35:40', '42', 6042241, 2),
(202, 43, '2025-03-07 21:45:30', '42', 707797, 1),
(203, 42, '2025-03-07 21:45:30', '42', 707797, 1),
(204, 42, '2025-03-07 21:48:13', '46', 5537708, 1),
(205, 43, '2025-03-07 21:48:13', '46', 5537708, 1),
(206, 46, '2025-03-07 21:48:13', '46', 5537708, 1),
(207, 42, '2025-03-07 22:08:20', '42', 5570642, 2),
(208, 43, '2025-03-07 22:14:18', '43', 4905897, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personaje`
--

CREATE TABLE `personaje` (
  `id_personaje` int NOT NULL,
  `nombrePersonaje` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `personaje`
--

INSERT INTO `personaje` (`id_personaje`, `nombrePersonaje`, `imagen`) VALUES
(1, 'Miguel', './assets/personajes/Miguel.png'),
(2, 'Pedro', './assets/personajes/Pedro.png'),
(3, 'Caroline', './assets/personajes/Caroline.png'),
(4, 'Jinx', './assets/personajes/Jinx2.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int NOT NULL,
  `rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rol`) VALUES
(1, 'Administrado'),
(2, 'jugador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `salas`
--

CREATE TABLE `salas` (
  `id_sala` int NOT NULL,
  `jugadores` int DEFAULT NULL,
  `direccion` varchar(250) NOT NULL,
  `id_mundo` int DEFAULT NULL,
  `nivelRequerido` int DEFAULT NULL,
  `estado` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `salas`
--

INSERT INTO `salas` (`id_sala`, `jugadores`, `direccion`, `id_mundo`, `nivelRequerido`, `estado`) VALUES
(1, 0, './salaDeEspera/SalaDeEspera.php', 1, 1, 1),
(2, 0, './salaDeEspera/SalaDeEspera.php', 1, 1, 1),
(3, 5, './salaDeEspera/SalaDeEspera.php', 2, 2, 1),
(4, 5, './salaDeEspera/SalaDeEspera.php', 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `correo` varchar(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `contrasena` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `puntos` int DEFAULT NULL,
  `nivel` int DEFAULT NULL,
  `vida` int DEFAULT NULL,
  `ultimoIngreso` datetime DEFAULT NULL,
  `estado` int DEFAULT NULL,
  `personaje` int DEFAULT NULL,
  `id_rol` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `username`, `correo`, `contrasena`, `puntos`, `nivel`, `vida`, `ultimoIngreso`, `estado`, `personaje`, `id_rol`) VALUES
(42, 'pinwisaurio', 'brandonyulian10@gmail.com', '$2y$10$iTWeygOVSQdeSf32nDdrbOtLlgVIzY3VymVk3DEmEt6uvXC.ZMDPy', 600, 2, 100, NULL, 1, 3, 2),
(43, 'samuel1', 'herminda0620@hotmail.com', '$2y$10$MoVbQDl3H07kz2WAgkRN9.wGcnzpg.kQdUdcSPyWHh9nEWashmTsq', 121, 1, 100, NULL, 1, 2, 2),
(44, 'brandon', 'brandonvilla1211@gmail.com', '$2y$10$JXHgwKbEz939SSMYMOmPveGfuCZ/8aMTPmFnGulxtX6WDyooXSHEa', 100, 1, 100, NULL, 1, 2, 1),
(45, 'perez', '1106227450@gmail.com', '$2y$10$7qTe4lmUHV.SR4A7wWpNiux55BC.g90KVDUA9IEvxm4fQ5k1kc9sC', 100, 1, 100, NULL, 1, NULL, 2),
(46, 'perla', 'perlapinifo04@gmail.com', '$2y$10$Z/2xkoIfpkZk7oMgfeuviu9Z/svxsMRb7haLnwERibhtOOCxJEBHm', 417, 1, 100, NULL, 1, 1, 2),
(47, 'Alejopro', 'alejoreyvm@gmail.com', '$2y$10$OeZWrhcRs9qqgW06Y3JYB.G05mv7QuZztWXKXXbsdEl9sB13D2cXW', 100, 1, 100, NULL, 1, NULL, 2),
(48, 'Juego', 'alejoreydvm@gmail.com', '$2y$10$7Xm090vJPeBplIgS13MY6egy.xG9OHse9Slv5IIXRJZCVFS7FlUHa', 0, 1, 100, NULL, 1, 1, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonaataque`
--

CREATE TABLE `zonaataque` (
  `id_zonaAtaque` int NOT NULL,
  `nombreZona` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `zonaataque`
--

INSERT INTO `zonaataque` (`id_zonaAtaque`, `nombreZona`) VALUES
(1, 'Cabeza'),
(2, 'Pecho'),
(3, 'Brazos'),
(4, 'Piernas');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `armas`
--
ALTER TABLE `armas`
  ADD PRIMARY KEY (`id_arma`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `ataque`
--
ALTER TABLE `ataque`
  ADD PRIMARY KEY (`id_disparolog`),
  ADD KEY `id_arma` (`id_arma`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `idx_codigoPartida` (`codigoPartida`),
  ADD KEY `idx_id_zonaAtaque` (`id_zonaAtaque`);

--
-- Indices de la tabla `categoriadearmas`
--
ALTER TABLE `categoriadearmas`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `jugadores_en_sala`
--
ALTER TABLE `jugadores_en_sala`
  ADD PRIMARY KEY (`id_registro`),
  ADD KEY `id_sala` (`id_sala`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `mundos`
--
ALTER TABLE `mundos`
  ADD PRIMARY KEY (`id_mundo`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id_partida`),
  ADD KEY `id_sala` (`id_sala`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `personaje`
--
ALTER TABLE `personaje`
  ADD PRIMARY KEY (`id_personaje`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id_sala`),
  ADD KEY `id_mundo` (`id_mundo`),
  ADD KEY `estado` (`estado`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `usuario_ibfk_1` (`estado`),
  ADD KEY `usuario_ibfk_2` (`personaje`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `armas`
--
ALTER TABLE `armas`
  MODIFY `id_arma` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `ataque`
--
ALTER TABLE `ataque`
  MODIFY `id_disparolog` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoriadearmas`
--
ALTER TABLE `categoriadearmas`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `jugadores_en_sala`
--
ALTER TABLE `jugadores_en_sala`
  MODIFY `id_registro` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=476;

--
-- AUTO_INCREMENT de la tabla `mundos`
--
ALTER TABLE `mundos`
  MODIFY `id_mundo` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id_partida` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=209;

--
-- AUTO_INCREMENT de la tabla `personaje`
--
ALTER TABLE `personaje`
  MODIFY `id_personaje` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `salas`
--
ALTER TABLE `salas`
  MODIFY `id_sala` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `armas`
--
ALTER TABLE `armas`
  ADD CONSTRAINT `armas_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoriadearmas` (`id_categoria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ataque`
--
ALTER TABLE `ataque`
  ADD CONSTRAINT `ataque_ibfk_1` FOREIGN KEY (`id_arma`) REFERENCES `armas` (`id_arma`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `ataque_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `jugadores_en_sala`
--
ALTER TABLE `jugadores_en_sala`
  ADD CONSTRAINT `jugadores_en_sala_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `jugadores_en_sala_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`id_sala`) REFERENCES `salas` (`id_sala`) ON DELETE CASCADE,
  ADD CONSTRAINT `partida_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `salas`
--
ALTER TABLE `salas`
  ADD CONSTRAINT `salas_ibfk_1` FOREIGN KEY (`id_mundo`) REFERENCES `mundos` (`id_mundo`) ON DELETE CASCADE,
  ADD CONSTRAINT `salas_ibfk_2` FOREIGN KEY (`estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`estado`) REFERENCES `estado` (`id_estado`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`personaje`) REFERENCES `personaje` (`id_personaje`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `usuario_ibfk_3` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
