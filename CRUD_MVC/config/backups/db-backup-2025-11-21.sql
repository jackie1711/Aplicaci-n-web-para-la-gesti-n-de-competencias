SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `asignaciones_evidencias`;
CREATE TABLE `asignaciones_evidencias` (
  `idAsignacion` int(11) NOT NULL AUTO_INCREMENT,
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
  `FechaEntrega` datetime DEFAULT NULL,
  PRIMARY KEY (`idAsignacion`),
  KEY `idEstudiantes` (`idEstudiantes`),
  KEY `idDocentes` (`idDocentes`),
  KEY `idCompetencias` (`idCompetencias`),
  KEY `idEvidencia` (`idEvidencia`),
  CONSTRAINT `asignaciones_evidencias_ibfk_1` FOREIGN KEY (`idEstudiantes`) REFERENCES `estudiantes` (`idEstudiantes`) ON DELETE CASCADE,
  CONSTRAINT `asignaciones_evidencias_ibfk_2` FOREIGN KEY (`idDocentes`) REFERENCES `docentes` (`idDocentes`) ON DELETE CASCADE,
  CONSTRAINT `asignaciones_evidencias_ibfk_3` FOREIGN KEY (`idCompetencias`) REFERENCES `competencias` (`idCompetencias`) ON DELETE CASCADE,
  CONSTRAINT `asignaciones_evidencias_ibfk_4` FOREIGN KEY (`idEvidencia`) REFERENCES `evidencias` (`idEvidencias`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO asignaciones_evidencias VALUES("1","Evidencia Integradora de Poo","vjkoiud","2025-11-20","2025-11-30","Evaluada","3","1","1","3","Mis Reportes - UPEMOR.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/691fc69c8fd53.pdf","2025-11-20 19:55:40");
INSERT INTO asignaciones_evidencias VALUES("2","holaaaaaaaegd","gjhkcdsxyghvew","2025-11-21","2025-11-30","Evaluada","4","1","3","4","Plantilla.docx","C:\\xampp\\htdocs\\prueba\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/6920adaf7a8f5.docx","2025-11-21 12:21:35");
INSERT INTO asignaciones_evidencias VALUES("3","ASDFGHJ","WERTYUIOP´ÑLKJHGFDSZX","2025-11-21","2025-11-30","Evaluada","5","1","2","5","_EP2_Vásquez_Juan.pdf","C:\\xampp\\htdocs\\prueba\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/6920c7e07f804.pdf","2025-11-21 14:13:20");


DROP TABLE IF EXISTS `competencias`;
CREATE TABLE `competencias` (
  `idCompetencias` int(11) NOT NULL AUTO_INCREMENT,
  `NombreCompetencia` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`idCompetencias`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO competencias VALUES("1","Logica de Programacion","saber programar");
INSERT INTO competencias VALUES("2","Pensamiento Matematico","saber mate");
INSERT INTO competencias VALUES("3","Pensamiento critico","criticar gente\n");


DROP TABLE IF EXISTS `docentes`;
CREATE TABLE `docentes` (
  `idDocentes` int(11) NOT NULL AUTO_INCREMENT,
  `Especialidad` varchar(45) DEFAULT NULL,
  `MateriaImpartida` varchar(45) DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDocentes`),
  KEY `idUsuarios` (`idUsuarios`),
  CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO docentes VALUES("1","Inteligencia arficial","Programación, Estancia","7");


DROP TABLE IF EXISTS `estudiantes`;
CREATE TABLE `estudiantes` (
  `idEstudiantes` int(11) NOT NULL AUTO_INCREMENT,
  `Matricula` varchar(45) DEFAULT NULL,
  `Grupo` varchar(45) DEFAULT NULL,
  `EstadoAcademico` enum('Activo','Inactivo') DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL,
  PRIMARY KEY (`idEstudiantes`),
  UNIQUE KEY `Matricula` (`Matricula`),
  KEY `idUsuarios` (`idUsuarios`),
  CONSTRAINT `estudiantes_ibfk_1` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO estudiantes VALUES("2","UHEO230139","B","Activo","8");
INSERT INTO estudiantes VALUES("3","VEJO230305","C","Activo","9");
INSERT INTO estudiantes VALUES("4","fzeo230300","C","Activo","10");
INSERT INTO estudiantes VALUES("5","AMVO230079","A","Activo","11");


DROP TABLE IF EXISTS `evaluaciones`;
CREATE TABLE `evaluaciones` (
  `idEvaluaciones` int(11) NOT NULL AUTO_INCREMENT,
  `Fecha` date NOT NULL,
  `Observaciones` text DEFAULT NULL,
  `TipoEvaluacion` varchar(50) DEFAULT NULL,
  `Calificacion` varchar(50) DEFAULT NULL,
  `idEstudiantes` int(11) NOT NULL,
  `idDocentes` int(11) NOT NULL,
  `idCompetencias` int(11) NOT NULL,
  PRIMARY KEY (`idEvaluaciones`),
  KEY `idEstudiantes` (`idEstudiantes`),
  KEY `idDocentes` (`idDocentes`),
  KEY `idCompetencias` (`idCompetencias`),
  CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`idEstudiantes`) REFERENCES `estudiantes` (`idEstudiantes`) ON DELETE CASCADE,
  CONSTRAINT `evaluaciones_ibfk_2` FOREIGN KEY (`idDocentes`) REFERENCES `docentes` (`idDocentes`) ON DELETE CASCADE,
  CONSTRAINT `evaluaciones_ibfk_3` FOREIGN KEY (`idCompetencias`) REFERENCES `competencias` (`idCompetencias`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO evaluaciones VALUES("2","2025-11-11","buuuuu","Producto","Deficiente","2","1","2");
INSERT INTO evaluaciones VALUES("3","2025-11-20","buen trabajo","Producto","Excelente","3","1","1");
INSERT INTO evaluaciones VALUES("4","2025-11-21","zxdcfvgbhnjlkjhgfdsazxcvbnm","Producto","Buena","4","1","3");
INSERT INTO evaluaciones VALUES("5","2025-11-21","asdfghjklñpoiuytrdfvbn","Producto","Deficiente","5","1","2");


DROP TABLE IF EXISTS `evidencias`;
CREATE TABLE `evidencias` (
  `idEvidencias` int(11) NOT NULL AUTO_INCREMENT,
  `idEstudiantes` int(11) NOT NULL,
  `idCompetencias` int(11) NOT NULL,
  `NombreArchivo` varchar(255) NOT NULL,
  `RutaArchivo` varchar(500) NOT NULL,
  `TipoArchivo` varchar(50) NOT NULL,
  `TamanoArchivo` int(11) NOT NULL,
  `FechaSubida` datetime DEFAULT current_timestamp(),
  `Estado` enum('Pendiente','Revisada','Aprobada','Rechazada') DEFAULT 'Pendiente',
  `Observaciones` text DEFAULT NULL,
  PRIMARY KEY (`idEvidencias`),
  KEY `idEstudiantes` (`idEstudiantes`),
  KEY `idCompetencias` (`idCompetencias`),
  CONSTRAINT `evidencias_ibfk_1` FOREIGN KEY (`idEstudiantes`) REFERENCES `estudiantes` (`idEstudiantes`) ON DELETE CASCADE,
  CONSTRAINT `evidencias_ibfk_2` FOREIGN KEY (`idCompetencias`) REFERENCES `competencias` (`idCompetencias`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO evidencias VALUES("2","2","2","EP04.5_C_JuanVasquez.jpg","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/6912b807d0e21_1762834439.jpg","image/jpeg","101114","2025-11-10 22:13:59","Pendiente",NULL);
INSERT INTO evidencias VALUES("3","3","1","Mis Reportes - UPEMOR.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/691fc69c8fd53.pdf","application/pdf","362299","2025-11-20 19:55:40","Aprobada","buen trabajo");
INSERT INTO evidencias VALUES("4","4","3","Plantilla.docx","C:\\xampp\\htdocs\\prueba\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/6920adaf7a8f5.docx","application/vnd.openxmlformats-officedocument.word","185205","2025-11-21 12:21:35","Aprobada","zxdcfvgbhnjlkjhgfdsazxcvbnm");
INSERT INTO evidencias VALUES("5","5","2","_EP2_Vásquez_Juan.pdf","C:\\xampp\\htdocs\\prueba\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/6920c7e07f804.pdf","application/pdf","10495871","2025-11-21 14:13:20","Rechazada","asdfghjklñpoiuytrdfvbn");


DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `idUsuarios` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(45) NOT NULL,
  `Apellido` varchar(45) NOT NULL,
  `TipoUsuario` enum('Administrador','Docente','Estudiante') NOT NULL,
  `FechaNac` date DEFAULT NULL,
  `Sexo` enum('Masculino','Femenino') DEFAULT NULL,
  `Telefono` varchar(12) DEFAULT NULL,
  `Correo` varchar(100) DEFAULT NULL,
  `Contrasena` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idUsuarios`),
  UNIQUE KEY `Correo` (`Correo`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usuarios VALUES("7","Juan Paulo","Sánchez Hernández","Docente","1888-11-10","Masculino","777-123-3231","juan.paulosh@upemor.edu.mx","$2y$10$bgX9TAIpv4NZO9EI/KwnfOplYMDYqXO.xfgeS61O2Cj8pUXtk17Rm");
INSERT INTO usuarios VALUES("8","Meme","Urquiza Hernandez","Estudiante","2005-11-19","Femenino","777-125-4869","uheo230139@upemor.edu.mx","$2y$10$WixzoSsp0B59JMHlkSrPiOGmx6JvFb986a2vJdi6So/3aucYwfnE2");
INSERT INTO usuarios VALUES("9","Juan Alberto","Vasquez Estrada","Estudiante","2003-07-06","Masculino","777-104-0403","vejo230305@upemor.edu.mx","$2y$10$js4HESchQcetuly/paTCA.6s5VuFRghmeU/79jeek8V/2cFZF1GW.");
INSERT INTO usuarios VALUES("10","Edna Jaqueline","Flores Zarco","Estudiante","2005-03-25","Femenino","777-125-4869","fzeo230300@upemor.edu.mx","$2y$10$sHjPfcpfggofAXO7BzWVOuFx8SepBFk9Gl3F8.WCppnc6/dkD1C2G");
INSERT INTO usuarios VALUES("11","valeria","Acosta Molina","Estudiante","2005-05-03","Femenino","777-456-7892","amvo230079@upemor.edu.mx","$2y$10$LkixOVoI/tD6NBMS8Nqz4.4cOF0WDlnA8C4.NHiDyi/6yY5i5tMDe");


