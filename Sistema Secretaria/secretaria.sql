-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-06-2025 a las 03:26:26
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
-- Base de datos: `secretaria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ads`
--

CREATE TABLE `ads` (
  `id` int(255) NOT NULL,
  `cedula` varchar(200) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `carrera` varchar(200) NOT NULL,
  `fecha_nac` varchar(200) NOT NULL,
  `genero` varchar(200) NOT NULL,
  `militar` varchar(255) NOT NULL,
  `rango` varchar(255) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `fecha_ing` varchar(200) NOT NULL,
  `fecha_egr` varchar(200) NOT NULL,
  `estado` varchar(20) DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ads`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desastres`
--

CREATE TABLE `desastres` (
  `id` int(255) NOT NULL,
  `cedula` varchar(200) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `carrera` varchar(200) NOT NULL,
  `fecha_nac` varchar(200) NOT NULL,
  `genero` varchar(200) NOT NULL,
  `militar` varchar(255) NOT NULL,
  `rango` varchar(200) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `fecha_ing` varchar(200) NOT NULL,
  `fecha_egr` varchar(200) NOT NULL,
  `estado` varchar(20) DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `economia`
--

CREATE TABLE `economia` (
  `id` int(255) NOT NULL,
  `cedula` varchar(200) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `carrera` varchar(200) NOT NULL,
  `fecha_nac` varchar(200) NOT NULL,
  `genero` varchar(200) NOT NULL,
  `militar` varchar(255) NOT NULL,
  `rango` varchar(255) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `fecha_ing` varchar(200) NOT NULL,
  `fecha_egr` varchar(200) NOT NULL,
  `estado` varchar(20) DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `economia`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enfermeria`
--

CREATE TABLE `enfermeria` (
  `id` int(255) NOT NULL,
  `cedula` varchar(200) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `carrera` varchar(200) NOT NULL,
  `fecha_nac` varchar(200) NOT NULL,
  `genero` varchar(200) NOT NULL,
  `militar` varchar(255) NOT NULL,
  `rango` varchar(200) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `fecha_ing` varchar(200) NOT NULL,
  `fecha_egr` varchar(200) NOT NULL,
  `estado` varchar(20) DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `enfermeria`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_pasantias`
--

CREATE TABLE `estudiantes_pasantias` (
  `id_estudiante` int(11) NOT NULL,
  `id_pasantia` int(11) DEFAULT NULL,
  `carrera` varchar(50) DEFAULT NULL,
  `cedula` varchar(20) DEFAULT NULL,
  `nombres` varchar(100) DEFAULT NULL,
  `apellidos` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes_pasantias`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes_proyectos`
--

CREATE TABLE `estudiantes_proyectos` (
  `id_estudiante` int(11) NOT NULL,
  `id_proyecto` int(11) NOT NULL,
  `carrera` varchar(50) NOT NULL,
  `cedula` varchar(20) NOT NULL,
  `nombres` varchar(255) NOT NULL,
  `apellidos` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes_proyectos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingenieria_civil`
--

CREATE TABLE `ingenieria_civil` (
  `id` int(255) NOT NULL,
  `cedula` varchar(200) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `carrera` varchar(200) NOT NULL,
  `fecha_nac` varchar(200) NOT NULL,
  `genero` varchar(200) NOT NULL,
  `militar` varchar(255) NOT NULL,
  `rango` varchar(200) NOT NULL,
  `telefono` varchar(200) NOT NULL,
  `correo` varchar(200) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `fecha_ing` varchar(200) NOT NULL,
  `fecha_egr` varchar(200) NOT NULL,
  `estado` varchar(20) DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingenieria_civil`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `periodo` varchar(7) DEFAULT NULL,
  `nombre_materia` varchar(100) DEFAULT NULL,
  `codigo` varchar(20) DEFAULT NULL,
  `cedula` varchar(15) DEFAULT NULL,
  `nombres` varchar(50) DEFAULT NULL,
  `apellidos` varchar(50) DEFAULT NULL,
  `carrera` varchar(20) DEFAULT NULL,
  `definitiva` decimal(4,2) DEFAULT NULL,
  `tipo_materia` varchar(100) DEFAULT NULL COMMENT 'Clasifica si la materia es regular, trabajo de grado o práctica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pasantias`
--

CREATE TABLE `pasantias` (
  `id_pasantia` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_culminacion` date DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `institucion` varchar(255) DEFAULT NULL,
  `tutor_academico` varchar(100) DEFAULT NULL,
  `tutor_institucional` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pasantias`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pensum_ads`
--

CREATE TABLE `pensum_ads` (
  `id` int(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `materia` varchar(200) NOT NULL,
  `tipo_materia` enum('regular','trabajo_grado','practica') DEFAULT 'regular' COMMENT 'Clasifica si la materia es regular, trabajo de grado o práctica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pensum_ads`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pensum_civil`
--

CREATE TABLE `pensum_civil` (
  `id` int(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `materia` varchar(200) NOT NULL,
  `tipo_materia` enum('regular','trabajo_grado','practica') DEFAULT 'regular' COMMENT 'Clasifica si la materia es regular, trabajo de grado o práctica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pensum_civil`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pensum_desastres`
--

CREATE TABLE `pensum_desastres` (
  `id` int(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `materia` varchar(200) NOT NULL,
  `tipo_materia` enum('regular','trabajo_grado','practica') DEFAULT 'regular' COMMENT 'Clasifica si la materia es regular, trabajo de grado o práctica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pensum_desastres`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pensum_economia`
--

CREATE TABLE `pensum_economia` (
  `id` int(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `materia` varchar(200) NOT NULL,
  `tipo_materia` enum('regular','trabajo_grado','practica') DEFAULT 'regular' COMMENT 'Clasifica si la materia es regular, trabajo de grado o práctica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pensum_economia`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pensum_enfermeria`
--

CREATE TABLE `pensum_enfermeria` (
  `id` int(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `materia` varchar(200) NOT NULL,
  `tipo_materia` enum('regular','trabajo_grado','practica') DEFAULT 'regular' COMMENT 'Clasifica si la materia es regular, trabajo de grado o práctica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pensum_enfermeria`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_seguridad`
--

CREATE TABLE `preguntas_seguridad` (
  `id` int(255) NOT NULL,
  `pregunta1` varchar(200) NOT NULL,
  `respuesta1` varchar(200) NOT NULL,
  `pregunta2` varchar(200) NOT NULL,
  `respuesta2` varchar(200) NOT NULL,
  `pregunta3` varchar(200) NOT NULL,
  `respuesta3` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `preguntas_seguridad`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proyectos`
--

CREATE TABLE `proyectos` (
  `id_proyecto` int(11) NOT NULL,
  `periodo` varchar(10) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `ubicacion` varchar(255) NOT NULL,
  `tutor` varchar(255) NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proyectos`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(255) NOT NULL,
  `Nombre_Completo` varchar(200) NOT NULL,
  `Nombre_Usuario` varchar(200) NOT NULL,
  `Cedula` varchar(200) NOT NULL,
  `Correo_Electronico` varchar(200) NOT NULL,
  `Contraseña` varchar(200) NOT NULL,
  `rol` varchar(50) NOT NULL DEFAULT 'viewer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--


--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `desastres`
--
ALTER TABLE `desastres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `economia`
--
ALTER TABLE `economia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `enfermeria`
--
ALTER TABLE `enfermeria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estudiantes_pasantias`
--
ALTER TABLE `estudiantes_pasantias`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `id_pasantia` (`id_pasantia`);

--
-- Indices de la tabla `estudiantes_proyectos`
--
ALTER TABLE `estudiantes_proyectos`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD KEY `id_proyecto` (`id_proyecto`);

--
-- Indices de la tabla `ingenieria_civil`
--
ALTER TABLE `ingenieria_civil`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pasantias`
--
ALTER TABLE `pasantias`
  ADD PRIMARY KEY (`id_pasantia`);

--
-- Indices de la tabla `pensum_ads`
--
ALTER TABLE `pensum_ads`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pensum_civil`
--
ALTER TABLE `pensum_civil`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pensum_desastres`
--
ALTER TABLE `pensum_desastres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pensum_economia`
--
ALTER TABLE `pensum_economia`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pensum_enfermeria`
--
ALTER TABLE `pensum_enfermeria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  ADD PRIMARY KEY (`id_proyecto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `desastres`
--
ALTER TABLE `desastres`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `economia`
--
ALTER TABLE `economia`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `enfermeria`
--
ALTER TABLE `enfermeria`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT de la tabla `estudiantes_pasantias`
--
ALTER TABLE `estudiantes_pasantias`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estudiantes_proyectos`
--
ALTER TABLE `estudiantes_proyectos`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ingenieria_civil`
--
ALTER TABLE `ingenieria_civil`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT de la tabla `pasantias`
--
ALTER TABLE `pasantias`
  MODIFY `id_pasantia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pensum_ads`
--
ALTER TABLE `pensum_ads`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `pensum_civil`
--
ALTER TABLE `pensum_civil`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT de la tabla `pensum_desastres`
--
ALTER TABLE `pensum_desastres`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `pensum_economia`
--
ALTER TABLE `pensum_economia`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de la tabla `pensum_enfermeria`
--
ALTER TABLE `pensum_enfermeria`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `preguntas_seguridad`
--
ALTER TABLE `preguntas_seguridad`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `proyectos`
--
ALTER TABLE `proyectos`
  MODIFY `id_proyecto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `estudiantes_pasantias`
--
ALTER TABLE `estudiantes_pasantias`
  ADD CONSTRAINT `estudiantes_pasantias_ibfk_1` FOREIGN KEY (`id_pasantia`) REFERENCES `pasantias` (`id_pasantia`);

--
-- Filtros para la tabla `estudiantes_proyectos`
--
ALTER TABLE `estudiantes_proyectos`
  ADD CONSTRAINT `estudiantes_proyectos_ibfk_1` FOREIGN KEY (`id_proyecto`) REFERENCES `proyectos` (`id_proyecto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
