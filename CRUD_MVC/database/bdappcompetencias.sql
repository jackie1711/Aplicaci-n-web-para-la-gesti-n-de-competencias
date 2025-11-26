-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 26-11-2025 a las 22:52:55
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
-- Base de datos: `bdappcompetencias`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `idAdministrador` int(11) NOT NULL,
  `NumeroEmpleado` varchar(20) DEFAULT NULL,
  `Departamento` varchar(50) DEFAULT NULL,
  `Cargo` varchar(50) DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`idAdministrador`, `NumeroEmpleado`, `Departamento`, `Cargo`, `idUsuarios`) VALUES
(1, 'ADM-001', 'Sistemas', 'Jefa de Departamento', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaciones_evidencias`
--

CREATE TABLE `asignaciones_evidencias` (
  `idAsignacion` int(11) NOT NULL,
  `NombreEvidencia` varchar(255) NOT NULL,
  `Descripcion` text DEFAULT NULL,
  `FechaAsignacion` date NOT NULL DEFAULT curdate(),
  `FechaLimite` date NOT NULL,
  `Estado` enum('Pendiente','Entregada','Evaluada') NOT NULL DEFAULT 'Pendiente',
  `idEstudiantes` int(11) NOT NULL,
  `idDocentes` int(11) NOT NULL,
  `idCompetencias` int(11) NOT NULL,
  `idEvidencia` int(11) DEFAULT NULL,
  `NombreArchivo` varchar(255) DEFAULT NULL,
  `RutaArchivo` varchar(255) DEFAULT NULL,
  `FechaEntrega` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `competencias`
--

CREATE TABLE `competencias` (
  `idCompetencias` int(11) NOT NULL,
  `NombreCompetencia` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `idDocentes` int(11) NOT NULL,
  `Especialidad` varchar(45) DEFAULT NULL,
  `MateriaImpartida` varchar(45) DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `idEstudiantes` int(11) NOT NULL,
  `Matricula` varchar(45) DEFAULT NULL,
  `Grupo` varchar(45) DEFAULT NULL,
  `EstadoAcademico` enum('Activo','Inactivo') DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `idEvaluaciones` int(11) NOT NULL,
  `Fecha` date NOT NULL,
  `Observaciones` text DEFAULT NULL,
  `TipoEvaluacion` varchar(50) DEFAULT NULL,
  `Calificacion` varchar(50) DEFAULT NULL,
  `idEstudiantes` int(11) NOT NULL,
  `idDocentes` int(11) NOT NULL,
  `idCompetencias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evidencias`
--

CREATE TABLE `evidencias` (
  `idEvidencias` int(11) NOT NULL,
  `idEstudiantes` int(11) NOT NULL,
  `idCompetencias` int(11) NOT NULL,
  `NombreArchivo` varchar(255) NOT NULL,
  `RutaArchivo` varchar(500) NOT NULL,
  `TipoArchivo` varchar(50) NOT NULL,
  `TamanoArchivo` int(11) NOT NULL,
  `FechaSubida` datetime DEFAULT current_timestamp(),
  `Estado` enum('Pendiente','Revisada','Aprobada','Rechazada') DEFAULT 'Pendiente',
  `Observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuarios` int(11) NOT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Apellido` varchar(45) NOT NULL,
  `TipoUsuario` enum('Administrador','Docente','Estudiante') NOT NULL,
  `FechaNac` date DEFAULT NULL,
  `Sexo` enum('Masculino','Femenino') DEFAULT NULL,
  `Telefono` varchar(12) DEFAULT NULL,
  `Correo` varchar(100) DEFAULT NULL,
  `Contrasena` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuarios`, `Nombre`, `Apellido`, `TipoUsuario`, `FechaNac`, `Sexo`, `Telefono`, `Correo`, `Contrasena`) VALUES
(1, 'Irma Yazmín', 'Hernández Báez', 'Administrador', '2000-07-24', 'Femenino', '777-345-8765', 'ibaez@upemor.edu.mx', '$2y$10$.AcnfopQep3CeiU0eViz1eRcyS858z8PEy7NZIaWS.0XlTuopU2p2');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`idAdministrador`),
  ADD KEY `idUsuarios` (`idUsuarios`);

--
-- Indices de la tabla `asignaciones_evidencias`
--
ALTER TABLE `asignaciones_evidencias`
  ADD PRIMARY KEY (`idAsignacion`),
  ADD KEY `idEstudiantes` (`idEstudiantes`),
  ADD KEY `idDocentes` (`idDocentes`),
  ADD KEY `idCompetencias` (`idCompetencias`),
  ADD KEY `idEvidencia` (`idEvidencia`);

--
-- Indices de la tabla `competencias`
--
ALTER TABLE `competencias`
  ADD PRIMARY KEY (`idCompetencias`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`idDocentes`),
  ADD KEY `idUsuarios` (`idUsuarios`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`idEstudiantes`),
  ADD UNIQUE KEY `Matricula` (`Matricula`),
  ADD KEY `idUsuarios` (`idUsuarios`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`idEvaluaciones`),
  ADD KEY `idEstudiantes` (`idEstudiantes`),
  ADD KEY `idDocentes` (`idDocentes`),
  ADD KEY `idCompetencias` (`idCompetencias`);

--
-- Indices de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD PRIMARY KEY (`idEvidencias`),
  ADD KEY `idEstudiantes` (`idEstudiantes`),
  ADD KEY `idCompetencias` (`idCompetencias`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuarios`),
  ADD UNIQUE KEY `Correo` (`Correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `idAdministrador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asignaciones_evidencias`
--
ALTER TABLE `asignaciones_evidencias`
  MODIFY `idAsignacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `competencias`
--
ALTER TABLE `competencias`
  MODIFY `idCompetencias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `idDocentes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `idEstudiantes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `idEvaluaciones` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `evidencias`
--
ALTER TABLE `evidencias`
  MODIFY `idEvidencias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idUsuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asignaciones_evidencias`
--
ALTER TABLE `asignaciones_evidencias`
  ADD CONSTRAINT `asignaciones_evidencias_ibfk_1` FOREIGN KEY (`idEstudiantes`) REFERENCES `estudiantes` (`idEstudiantes`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignaciones_evidencias_ibfk_2` FOREIGN KEY (`idDocentes`) REFERENCES `docentes` (`idDocentes`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignaciones_evidencias_ibfk_3` FOREIGN KEY (`idCompetencias`) REFERENCES `competencias` (`idCompetencias`) ON DELETE CASCADE,
  ADD CONSTRAINT `asignaciones_evidencias_ibfk_4` FOREIGN KEY (`idEvidencia`) REFERENCES `evidencias` (`idEvidencias`) ON DELETE SET NULL;

--
-- Filtros para la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`idEstudiantes`) REFERENCES `estudiantes` (`idEstudiantes`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluaciones_ibfk_2` FOREIGN KEY (`idDocentes`) REFERENCES `docentes` (`idDocentes`) ON DELETE CASCADE,
  ADD CONSTRAINT `evaluaciones_ibfk_3` FOREIGN KEY (`idCompetencias`) REFERENCES `competencias` (`idCompetencias`) ON DELETE CASCADE;

--
-- Filtros para la tabla `evidencias`
--
ALTER TABLE `evidencias`
  ADD CONSTRAINT `evidencias_ibfk_1` FOREIGN KEY (`idEstudiantes`) REFERENCES `estudiantes` (`idEstudiantes`) ON DELETE CASCADE,
  ADD CONSTRAINT `evidencias_ibfk_2` FOREIGN KEY (`idCompetencias`) REFERENCES `competencias` (`idCompetencias`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
