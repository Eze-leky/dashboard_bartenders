-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 25-04-2025 a las 15:00:20
-- Versión del servidor: 9.2.0
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `academia_virtual`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `accesos`
--

CREATE TABLE `accesos` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `accesos`
--

INSERT INTO `accesos` (`id`, `user_id`, `fecha`, `ip`) VALUES
(1, 2, '2025-04-24 16:40:54', '::1'),
(2, 3, '2025-04-24 16:56:40', '::1'),
(3, 3, '2025-04-24 17:14:58', '::1'),
(4, 2, '2025-04-24 17:16:25', '::1'),
(5, 3, '2025-04-24 17:31:45', '::1'),
(6, 2, '2025-04-24 17:32:20', '::1'),
(7, 3, '2025-04-24 17:33:10', '::1'),
(8, 2, '2025-04-24 17:36:04', '::1'),
(9, 3, '2025-04-24 17:37:07', '::1'),
(10, 2, '2025-04-24 17:37:19', '::1'),
(11, 3, '2025-04-24 17:46:06', '::1'),
(12, 2, '2025-04-24 17:53:15', '::1'),
(13, 3, '2025-04-24 17:57:09', '::1'),
(14, 3, '2025-04-24 17:59:48', '::1'),
(15, 3, '2025-04-24 18:05:34', '::1'),
(16, 2, '2025-04-24 18:17:44', '::1'),
(17, 3, '2025-04-24 18:31:27', '::1'),
(18, 4, '2025-04-24 18:36:37', '::1'),
(19, 2, '2025-04-24 18:37:30', '::1'),
(20, 2, '2025-04-24 20:46:59', '::1'),
(21, 4, '2025-04-24 20:49:34', '::1'),
(22, 3, '2025-04-24 20:51:35', '::1'),
(23, 2, '2025-04-24 21:02:01', '::1'),
(24, 2, '2025-04-24 21:07:42', '::1'),
(25, 2, '2025-04-24 21:11:51', '::1'),
(27, 2, '2025-04-24 21:36:09', '::1'),
(28, 2, '2025-04-24 21:38:55', '::1'),
(29, 2, '2025-04-24 21:41:21', '::1'),
(30, 2, '2025-04-24 22:15:42', '::1'),
(31, 3, '2025-04-24 22:16:12', '::1'),
(32, 2, '2025-04-24 22:22:33', '::1'),
(33, 2, '2025-04-24 22:23:59', '::1'),
(35, 2, '2025-04-24 22:26:48', '::1'),
(37, 2, '2025-04-24 22:33:56', '::1'),
(38, 2, '2025-04-24 22:41:18', '::1'),
(39, 2, '2025-04-24 22:48:15', '::1'),
(41, 2, '2025-04-24 23:30:56', '::1'),
(43, 2, '2025-04-24 23:42:30', '::1'),
(45, 2, '2025-04-24 23:46:29', '::1'),
(46, 17, '2025-04-25 00:02:54', '::1'),
(47, 2, '2025-04-25 00:02:58', '::1'),
(48, 2, '2025-04-25 08:41:00', '::1'),
(49, 2, '2025-04-25 08:41:50', '::1'),
(50, 2, '2025-04-25 08:50:08', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones`
--

CREATE TABLE `asignaciones` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `curso_id` int DEFAULT NULL,
  `dias` varchar(100) DEFAULT NULL,
  `mes` int DEFAULT NULL,
  `anio` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE `cursos` (
  `id` int NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `nombre_real` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `manual_path` varchar(255) DEFAULT NULL,
  `diploma_path` varchar(255) DEFAULT NULL,
  `role` enum('admin','alumno') DEFAULT 'alumno',
  `curso_id` int DEFAULT NULL,
  `dias_cursada` varchar(255) DEFAULT NULL,
  `mes` int DEFAULT NULL,
  `anio` int DEFAULT NULL,
  `grupo` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `nombre_real`, `email`, `password`, `manual_path`, `diploma_path`, `role`, `curso_id`, `dias_cursada`, `mes`, `anio`, `grupo`) VALUES
(2, 'eze', 'Ezequiel', 'ezequielmorales232@gmail.com', '$2y$10$YTV1XgiGQp5dTBviDadgQuPMXtw28Yy4S9D.lQEQJUNDR83yZ2vC2', 'assets/pdfs/manuales/Manual completo + recetario 2024 v2.pdf', 'assets/pdfs/diplomas/certificado.pdf', 'admin', NULL, NULL, NULL, NULL, 1),
(3, 'Ayelen.altamiranda', 'Ayelen Altamiranda', 'ayelen@gmail.com', '$2y$10$3u6FmRiHmsb.BsOjlpkGP.WiJcAw0Vwm2Oc0ldxizhS2I09.VSE06', 'assets/pdfs/manuales/Manual completo + recetario 2024 v2.pdf', 'assets/pdfs/diplomas/certificado.pdf', 'alumno', NULL, NULL, NULL, NULL, 3),
(4, 'ezequiel', 'Morales Barruti', 'eleze@eze.com', '$2y$10$fM2ycrgh5xZI6HmjzL9.HOE66K8Au10wdalX.EmlyJGeK0fGG.gia', '../assets/pdfs/manuales/Manual completo + recetario 2024 v2.pdf', '../assets/pdfs/diplomas/certificado.pdf', 'alumno', NULL, NULL, NULL, NULL, 2),
(17, 'Camila', 'camila', 'camila@camila.com', '$2y$10$P6WPwsK9yjE9HPIZJdudguic38LAcoUqcId5axOy7L7F.9R3/5zVK', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, 2),
(18, 'lucila', 'lucila', 'lu@lu', '$2y$10$VP6xKOQ94yWuC2NRME33auF7Mhj8VXJqTqFhqEr5N1f53HetJwjFC', 'assets/pdfs/manuales/Manual completo + recetario 2024 v2.pdf', 'assets/pdfs/diplomas/certificado.pdf', 'alumno', NULL, NULL, NULL, NULL, 1),
(19, 'hola', 'hola', 'hola@hola', '$2y$10$tZz50AEdYN7ZNa.cKN9PTOSwLVgZ1xse4hWFCWUlAz6BzKRr5ukIy', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, 1),
(20, 'Pedro', 'pedro', 'p@p', '$2y$10$D/RhaebW6dqOriCNi5M0zOkq6JozNu/B.Ol3pOHzlX9ywsvoLcO6G', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, 1),
(21, 'alumno1', NULL, 'alumno1@email.com', 'hashed_password1', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, 1),
(22, 'alumno2', NULL, 'alumno2@email.com', 'hashed_password2', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(23, 'alumno3', NULL, 'alumno3@email.com', 'hashed_password3', 'assets/pdfs/manuales/Manual completo + recetario 2024 v2.pdf', NULL, 'alumno', NULL, NULL, NULL, NULL, 2),
(24, 'alumno4', NULL, 'alumno4@email.com', 'hashed_password4', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, 2),
(25, 'alumno5', NULL, 'alumno5@email.com', 'hashed_password5', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(26, 'alumno6', NULL, 'alumno6@email.com', 'hashed_password6', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(27, 'alumno7', NULL, 'alumno7@email.com', 'hashed_password7', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(28, 'alumno8', NULL, 'alumno8@email.com', 'hashed_password8', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(29, 'alumno9', NULL, 'alumno9@email.com', 'hashed_password9', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(30, 'alumno10', NULL, 'alumno10@email.com', 'hashed_password10', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(31, 'alumno11', NULL, 'alumno11@email.com', 'hashed_password11', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(32, 'alumno12', NULL, 'alumno12@email.com', 'hashed_password12', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(33, 'alumno13', NULL, 'alumno13@email.com', 'hashed_password13', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(34, 'alumno14', NULL, 'alumno14@email.com', 'hashed_password14', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(35, 'alumno15', NULL, 'alumno15@email.com', 'hashed_password15', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(36, 'alumno16', NULL, 'alumno16@email.com', 'hashed_password16', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(37, 'alumno17', NULL, 'alumno17@email.com', 'hashed_password17', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(38, 'alumno18', NULL, 'alumno18@email.com', 'hashed_password18', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(39, 'alumno19', NULL, 'alumno19@email.com', 'hashed_password19', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL),
(40, 'alumno20', NULL, 'alumno20@email.com', 'hashed_password20', NULL, NULL, 'alumno', NULL, NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `curso_id` (`curso_id`);

--
-- Indices de la tabla `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `accesos`
--
ALTER TABLE `accesos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cursos`
--
ALTER TABLE `cursos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `accesos`
--
ALTER TABLE `accesos`
  ADD CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `asignaciones`
--
ALTER TABLE `asignaciones`
  ADD CONSTRAINT `asignaciones_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `asignaciones_ibfk_2` FOREIGN KEY (`curso_id`) REFERENCES `cursos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
