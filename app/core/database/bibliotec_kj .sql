-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-12-2025 a las 04:07:55
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bibliotec_kj`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor`
--

CREATE TABLE `autor` (
  `id_autor` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `autor`
--

INSERT INTO `autor` (`id_autor`, `nombre`) VALUES
(1, 'Antoine de Saint-Exupéry'),
(2, 'Alice Kellen'),
(3, 'Carlos Ruiz'),
(4, 'Junior'),
(5, 'Mark Manson'),
(6, 'Inma Rubiales'),
(7, 'César Pérez Gellida'),
(8, 'Alex Rovira'),
(9, 'Fernando Trias Bes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidad`
--

CREATE TABLE `disponibilidad` (
  `id_disponibilidad` int(11) NOT NULL,
  `id_libro` int(11) DEFAULT NULL,
  `cantidad_disponible` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `disponibilidad`
--

INSERT INTO `disponibilidad` (`id_disponibilidad`, `id_libro`, `cantidad_disponible`, `id_estado`) VALUES
(1, 1, 8, 1),
(2, 2, 10, 1),
(3, 4, 3, 1),
(4, 5, 3, 1),
(5, 6, 5, 1),
(9, 10, 6, 1),
(10, 11, 25, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `editorial`
--

CREATE TABLE `editorial` (
  `id_editorial` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `editorial`
--

INSERT INTO `editorial` (`id_editorial`, `nombre`) VALUES
(1, 'Reynal & Hitchcock'),
(2, 'Planeta'),
(3, 'Harper Collins'),
(4, 'Suma de letras'),
(5, 'Zenith');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

CREATE TABLE `estado` (
  `id_estado` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id_estado`, `nombre`) VALUES
(1, 'disponible'),
(2, 'prestado'),
(3, 'reservado'),
(4, 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favorito`
--

CREATE TABLE `favorito` (
  `id_favorito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_libro` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id_genero` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id_genero`, `nombre`) VALUES
(1, 'Novela corta'),
(2, 'Novela romántica contemporénea'),
(3, 'Misterio'),
(4, 'Intriga'),
(5, 'romance gotico'),
(6, 'Autoayuda contemporánea'),
(7, 'psicología práctica'),
(8, 'Novela juvenil romántica'),
(9, 'Novela negra/Thriller'),
(10, 'Autoayuda'),
(11, 'Fábula motivacional');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `id_libro` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `Estante` varchar(20) DEFAULT NULL,
  `año_publicacion` year(4) DEFAULT NULL,
  `id_editorial` int(11) DEFAULT NULL,
  `cantidad_total` int(11) NOT NULL,
  `Imagen` varchar(225) NOT NULL,
  `sipnosis` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`id_libro`, `titulo`, `Estante`, `año_publicacion`, `id_editorial`, `cantidad_total`, `Imagen`, `sipnosis`) VALUES
(1, 'El principito', 'A1', '1943', 1, 12, '1765055714_imagen_2025-12-06_161512812.png', ''),
(2, 'Nosotros en la Luna', 'A1', '2020', 2, 15, '1764906442_imagen_2025-12-04_224720701.png', ''),
(4, 'La sombra del viento', 'A2', '2001', 2, 8, '1764906366_imagen_2025-12-04_224604365.png', ''),
(5, 'El sutil arte de que te importe un carajo', 'A2', '2016', 3, 10, '1765153379_imagen_2025-12-07_192258549.png', ''),
(6, 'El arte de ser nosotros', 'A2', '2023', 2, 10, '1764906399_imagen_2025-12-04_224637584.png', ''),
(10, 'Memento Mori', 'A2', '2013', 4, 10, '6928977428d1b_imagen_2025-11-27_132437633.png', 'Memento Mori arranca con el hallazgo del cadáver de una joven en Valladolid, cuyos párpados han sido mutilados y en cuyo cuerpo aparecen escritos unos versos inquietantes. El inspector Ramiro Sancho se enfrenta a un asesino culto y meticuloso que utiliza la poesía y la música como parte de su macabro ritual. A medida que avanza la investigación, se despliega un juego psicológico entre cazador y presa, donde la inteligencia del criminal y la tensión narrativa convierten la historia en un thriller oscuro y absorbente que mezcla crimen, arte y obsesión.'),
(11, 'La buena suerte. Claves de la prosperidad', 'A3', '2004', 5, 25, '1764905733_imagen_2025-12-04_223319039.png', '');

--
-- Disparadores `libro`
--
DELIMITER $$
CREATE TRIGGER `crear_disponibilidad_despues_libro` AFTER INSERT ON `libro` FOR EACH ROW INSERT INTO disponibilidad (id_libro, cantidad_disponible, id_estado)
VALUES (NEW.id_libro, NEW.cantidad_total, 1)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_autor`
--

CREATE TABLE `libro_autor` (
  `id_libro` int(11) NOT NULL,
  `id_autor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro_autor`
--

INSERT INTO `libro_autor` (`id_libro`, `id_autor`) VALUES
(1, 1),
(2, 2),
(4, 3),
(4, 4),
(5, 5),
(6, 6),
(10, 7),
(11, 8),
(11, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_genero`
--

CREATE TABLE `libro_genero` (
  `id_libro` int(11) NOT NULL,
  `id_genero` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro_genero`
--

INSERT INTO `libro_genero` (`id_libro`, `id_genero`) VALUES
(1, 1),
(2, 2),
(4, 3),
(4, 4),
(4, 5),
(5, 6),
(5, 7),
(6, 8),
(10, 9),
(11, 10),
(11, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id_permiso` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `id_prestamo` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_libro` int(11) DEFAULT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion` date DEFAULT NULL,
  `estado` enum('activo','devuelto','retrasado') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`id_prestamo`, `id_usuario`, `id_libro`, `fecha_prestamo`, `fecha_devolucion`, `estado`) VALUES
(21, 14, 6, '2025-12-03', '2025-12-03', 'devuelto'),
(22, 14, 6, '2025-12-03', '2025-12-03', 'devuelto'),
(31, 13, 1, '2025-12-06', '2025-12-21', 'activo'),
(32, 14, 5, '2025-12-06', '2025-12-13', 'activo'),
(33, 14, 2, '2025-12-07', '2025-12-14', 'activo'),
(34, 14, 1, '2025-12-07', '2025-12-14', 'activo'),
(35, 14, 2, '2025-12-07', '2025-12-14', 'activo'),
(36, 18, 10, '2025-12-11', '2025-12-18', 'activo'),
(37, 18, 10, '2025-12-11', '2025-12-18', 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_libro` int(11) DEFAULT NULL,
  `fecha_reserva` date NOT NULL,
  `estado` enum('pendiente','prestado','cancelado','devuelto') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `id_usuario`, `id_libro`, `fecha_reserva`, `estado`) VALUES
(1, 14, 2, '2025-12-06', 'prestado'),
(2, 13, 1, '2025-12-06', 'prestado'),
(3, 14, 5, '2025-12-06', 'prestado'),
(4, 14, 1, '2025-12-07', 'prestado'),
(5, 14, 2, '2025-12-07', 'prestado'),
(6, 14, 6, '2025-12-07', 'pendiente'),
(7, 13, 2, '2025-12-07', 'pendiente'),
(8, 13, 1, '2025-12-10', 'pendiente'),
(9, 18, 10, '2025-12-10', 'prestado'),
(10, 18, 2, '2025-12-10', 'pendiente'),
(11, 18, 2, '2025-12-10', ''),
(12, 18, 10, '2025-12-11', 'prestado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `nombre`) VALUES
(1, 'Administrador '),
(2, 'Cliente ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_user`
--

CREATE TABLE `rol_user` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol_user`
--

INSERT INTO `rol_user` (`id_usuario`, `id_rol`) VALUES
(1, 1),
(5, 2),
(8, 2),
(11, 2),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(17, 2),
(18, 2),
(21, 2),
(22, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `tipo_documento` varchar(20) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `avatar_emoji` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `correo`, `contraseña`, `telefono`, `tipo_documento`, `numero_documento`, `fecha_registro`, `avatar_emoji`) VALUES
(1, 'Admin', 'admid@gmail.com', '$2y$10$UN2yuKpUbkpWHOM.tRHqGu21oSiXJpBlvtTvvOWsCJ56TbrSL1WWW', NULL, NULL, NULL, '2025-12-10 14:47:44', NULL),
(5, 'Marco Medina Molina1', 'marcos@gmail.com', '$2y$10$mJbttWTcdL0RvJuhooK43.cOtBz.UmypVK/q6IlK/U/TdqOX2Qafi', NULL, NULL, NULL, '2025-12-10 14:47:44', NULL),
(8, 'Valeria pulido', 'valeria@gmail.com', '$2y$10$YEsOi07x68k0MT3h.QQ9x.jTzUuuOTNMAroC/BS2rlU8/x6A8mSsS', NULL, NULL, NULL, '2025-12-10 14:47:44', NULL),
(11, 'Eulices Santamaria', 'eulises@gmail.com', '$2y$10$3vhHFdmOLLrKwrxQQCgYZe79DKn4zHCd8ANgfzQ.s56JlSe65hSw.', '3135224574', 'CC', '6708977', '2025-12-10 14:47:44', NULL),
(12, 'Daniel Suarez', 'daniel@gmail.com', '$2y$10$rw3k/leScaaVhwhZH0lnWucu3naPPF9OUWQ3F1UEqU12XTpEeNTL.', '3124225212', 'CC', '00000000', '2025-12-10 14:47:44', NULL),
(13, 'Dana Cifuentes', 'Danacici04@gmail.com', '$2y$10$.qFF/QJwrhs8I./1Pu52f.JP6zvW.wTVTCxxbxCw8g0J5hfUOSjZK', '3124750781', 'CC', '1056768630', '2025-12-10 14:47:44', NULL),
(14, 'junior', 'santamaria@gmail.com', '$2y$10$34mB90rdFzZujUrhdn73W..w9V2HR2JX9uSuMObaEMOaulIOu2UOq', '3152417443', 'CC', '1056769689', '2025-12-10 14:47:44', NULL),
(15, 'Daniela Caicedo', 'daniela@gmail.com', '$2y$10$zqElRzVdBsy10oWaMZKCa.htd4o3CBdcwierwl1hddTbytZS66Qxa', '1325255432', 'CC', '123124514154', '2025-12-10 14:47:44', NULL),
(17, 'Cataliana Gonzales', 'cata@gmail.com', '$2y$10$9xd1TGjTI9yNqK5.E57zHO7Iu8yzYS7cJvupgKxlXy8JGozqsJVSO', '12335469678', 'CC', '12435554862', '2025-12-10 14:47:44', NULL),
(18, 'Kasandra', '12345@gmail.com', '$2y$10$DRLi09k9JEJVhUL96UYT0Oo9aushFoqxsy7d5Z6KYqf1WU4P62Fju', '3124750781', 'CC', '123456789', '2025-12-10 14:47:44', '😁'),
(21, 'Kass', 'Kass@gmail.com', '$2y$10$O/2uITXYFSj6.yI3fDd21O7D6ncHFJzGD.RWXy5CVuIXbirwhdnly', '3124750781', 'CC', '1056768630', '2025-12-11 20:29:59', '👩‍🏫'),
(22, 'Kass', 'kasscifuentes@gmail.com', '$2y$10$5L.T.oBVttAaU28fdCNlNurVi/.GfPC41Y6RWlWmq4RkTDYmCtfZ2', '3124750123', 'CC', '1056768630', '2025-12-11 23:39:11', '🥳');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id_autor`);

--
-- Indices de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD PRIMARY KEY (`id_disponibilidad`),
  ADD KEY `id_libro` (`id_libro`),
  ADD KEY `fk_estado_disponibilidad` (`id_estado`);

--
-- Indices de la tabla `editorial`
--
ALTER TABLE `editorial`
  ADD PRIMARY KEY (`id_editorial`);

--
-- Indices de la tabla `estado`
--
ALTER TABLE `estado`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `favorito`
--
ALTER TABLE `favorito`
  ADD PRIMARY KEY (`id_favorito`),
  ADD UNIQUE KEY `ux_usuario_libro` (`id_usuario`,`id_libro`),
  ADD KEY `idx_favorito_usuario` (`id_usuario`),
  ADD KEY `idx_favorito_libro` (`id_libro`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id_genero`);

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id_libro`),
  ADD KEY `id_editorial` (`id_editorial`);

--
-- Indices de la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD PRIMARY KEY (`id_libro`,`id_autor`),
  ADD KEY `id_autor` (`id_autor`);

--
-- Indices de la tabla `libro_genero`
--
ALTER TABLE `libro_genero`
  ADD PRIMARY KEY (`id_libro`,`id_genero`),
  ADD KEY `id_genero` (`id_genero`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id_permiso`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_libro` (`id_libro`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `rol_user`
--
ALTER TABLE `rol_user`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autor`
--
ALTER TABLE `autor`
  MODIFY `id_autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  MODIFY `id_disponibilidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `editorial`
--
ALTER TABLE `editorial`
  MODIFY `id_editorial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `estado`
--
ALTER TABLE `estado`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `favorito`
--
ALTER TABLE `favorito`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id_genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id_permiso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `disponibilidad`
--
ALTER TABLE `disponibilidad`
  ADD CONSTRAINT `disponibilidad_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  ADD CONSTRAINT `fk_estado_disponibilidad` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`);

--
-- Filtros para la tabla `favorito`
--
ALTER TABLE `favorito`
  ADD CONSTRAINT `fk_favorito_libro` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_favorito_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`id_editorial`) REFERENCES `editorial` (`id_editorial`);

--
-- Filtros para la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD CONSTRAINT `libro_autor_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  ADD CONSTRAINT `libro_autor_ibfk_2` FOREIGN KEY (`id_autor`) REFERENCES `autor` (`id_autor`);

--
-- Filtros para la tabla `libro_genero`
--
ALTER TABLE `libro_genero`
  ADD CONSTRAINT `libro_genero_ibfk_1` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`),
  ADD CONSTRAINT `libro_genero_ibfk_2` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`);

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `prestamo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `prestamo_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`id_libro`) REFERENCES `libro` (`id_libro`);

--
-- Filtros para la tabla `rol_user`
--
ALTER TABLE `rol_user`
  ADD CONSTRAINT `rol_user_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `rol_user_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
