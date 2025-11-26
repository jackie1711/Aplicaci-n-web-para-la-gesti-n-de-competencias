SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `administradores`;
CREATE TABLE `administradores` (
  `idAdministrador` int(11) NOT NULL AUTO_INCREMENT,
  `NumeroEmpleado` varchar(20) DEFAULT NULL,
  `Departamento` varchar(50) DEFAULT NULL,
  `Cargo` varchar(50) DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL,
  PRIMARY KEY (`idAdministrador`),
  KEY `idUsuarios` (`idUsuarios`),
  CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO administradores VALUES("1","ADM-001","Sistemas","Jefa de Departamento","12");
INSERT INTO administradores VALUES("2","ADM-002","Dirección Académica","Jefe de Direccion","1005");


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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO asignaciones_evidencias VALUES("3","asdfghjklkjhgfds<zx","sdftgyuhjikoloijdfvc m,","2025-11-21","2025-11-30","Evaluada","2","2","3","5","A17.EdnaFlores_EsmeraldaUrquiza_JuanVásquez_Factibilidad01.docx.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/69213bcc6e455.pdf","2025-11-21 22:27:56");
INSERT INTO asignaciones_evidencias VALUES("6","qwertyuioiuyf","qwertyuiofghjmnbcxzhg","2025-11-25","2025-12-05","Entregada","4","2","3","7","Documento A4 Portada Proyecto Empresarial Moderno Geométrico Rosa y Blanco.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/692658f332fde.pdf","2025-11-25 19:33:39");
INSERT INTO asignaciones_evidencias VALUES("7","Proyecto Final de Programacion","Portada, Indice, Objetivos, Introduccion, Desarrollo, Conclucion","2025-11-25","2025-12-05","Evaluada","4","2","1","9","A.18_FloresEdna_UrquizaEsmeralda_VasquezJuan.pdf","C:\\xampp\\htdocs\\ESTANCIAII\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/692684710dffb.pdf","2025-11-25 22:39:13");


DROP TABLE IF EXISTS `competencias`;
CREATE TABLE `competencias` (
  `idCompetencias` int(11) NOT NULL AUTO_INCREMENT,
  `NombreCompetencia` varchar(100) DEFAULT NULL,
  `Descripcion` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`idCompetencias`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO competencias VALUES("1","Logica de Programacion","saber programar");
INSERT INTO competencias VALUES("3","Pensamiento critico","criticar gente\n");
INSERT INTO competencias VALUES("4","Pensamiento Matematico","Manejar la lógica matemática un al menos un 70%");


DROP TABLE IF EXISTS `docentes`;
CREATE TABLE `docentes` (
  `idDocentes` int(11) NOT NULL AUTO_INCREMENT,
  `Especialidad` varchar(45) DEFAULT NULL,
  `MateriaImpartida` varchar(45) DEFAULT NULL,
  `idUsuarios` int(11) DEFAULT NULL,
  PRIMARY KEY (`idDocentes`),
  KEY `idUsuarios` (`idUsuarios`),
  CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`idUsuarios`) REFERENCES `usuarios` (`idUsuarios`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO docentes VALUES("2","Redes Estancia","Ruteo y Conmutacion","10");


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

INSERT INTO estudiantes VALUES("2","UHEO230139","C","Activo","8");
INSERT INTO estudiantes VALUES("3","VEJO230305","C","Activo","9");
INSERT INTO estudiantes VALUES("4","fzeo230300","B","Activo","11");


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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO evaluaciones VALUES("5","2025-11-21","asdfghjklñ{poiuytrew","Producto","Excelente","2","2","3");
INSERT INTO evaluaciones VALUES("7","2025-11-25","bien echo, trabajo competo","Producto","Bueno","4","2","1");


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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO evidencias VALUES("3","3","1","Mis Reportes - UPEMOR.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/691fc69c8fd53.pdf","application/pdf","362299","2025-11-20 19:55:40","Aprobada","buen trabajo");
INSERT INTO evidencias VALUES("4","2","3","EP4_FloresEdna_VásquezJuan_Documento.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/692132e020514.pdf","application/pdf","3461815","2025-11-21 21:49:52","Aprobada","ASDFGHJKLKJYTR");
INSERT INTO evidencias VALUES("5","2","3","A17.EdnaFlores_EsmeraldaUrquiza_JuanVásquez_Factibilidad01.docx.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/69213bcc6e455.pdf","application/pdf","660527","2025-11-21 22:27:56","Aprobada","asdfghjklñ{poiuytrew");
INSERT INTO evidencias VALUES("6","3","4","EP4_FloresEdna_VásquezJuan_Documento.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/6923e18f0a8b8.pdf","application/pdf","3461815","2025-11-23 22:39:43","Aprobada","sdfghjklñoiuytreswa<sdfghj");
INSERT INTO evidencias VALUES("7","4","3","Documento A4 Portada Proyecto Empresarial Moderno Geométrico Rosa y Blanco.pdf","C:\\xampp\\htdocs\\prueba\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/692658f332fde.pdf","application/pdf","999059","2025-11-25 19:33:39","Pendiente",NULL);
INSERT INTO evidencias VALUES("8","4","1","A.18_FloresEdna_UrquizaEsmeralda_VasquezJuan.pdf","C:\\xampp\\htdocs\\ESTANCIAII\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/6926846ede481.pdf","application/pdf","8722534","2025-11-25 22:39:10","Pendiente",NULL);
INSERT INTO evidencias VALUES("9","4","1","A.18_FloresEdna_UrquizaEsmeralda_VasquezJuan.pdf","C:\\xampp\\htdocs\\ESTANCIAII\\CRUD_MVC\\app\\controllers/../../uploads/evidencias/692684710dffb.pdf","application/pdf","8722534","2025-11-25 22:39:13","Aprobada","bien echo, trabajo competo");


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
) ENGINE=InnoDB AUTO_INCREMENT=1006 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usuarios VALUES("8","Meme","Urquiza Hernandez","Estudiante","2005-11-19","Femenino","777-125-4869","uheo230139@upemor.edu.mx","$2y$10$WixzoSsp0B59JMHlkSrPiOGmx6JvFb986a2vJdi6So/3aucYwfnE2");
INSERT INTO usuarios VALUES("9","Juan Alberto","Vasquez Estrada","Estudiante","2003-07-06","Masculino","777-104-0403","vejo230305@upemor.edu.mx","$2y$10$js4HESchQcetuly/paTCA.6s5VuFRghmeU/79jeek8V/2cFZF1GW.");
INSERT INTO usuarios VALUES("10","Jose Enrique","Zagal Solano","Docente","1999-10-09","Femenino","777-345-2039","jose.zagal@upemor.edu.mx","$2y$10$s3d64nokXpZJp/Oszt6kDu0EYVz6BMN.AMqh244Zm5Fp8PMUdm/j.");
INSERT INTO usuarios VALUES("11","edna jaqueline","Flores Zarco","Estudiante","2005-03-25","Femenino","777-287-8690","fzeo230300@upemor.edu.mx","$2y$10$Mpnn4LeY1vGOJIxdRoRKqeC/J30/fEW1bdEJkCaiDgGsR./7a0u3O");
INSERT INTO usuarios VALUES("12","Irma Yazmín","Hernández Báez","Administrador","2000-07-24","Femenino","777-345-8765","ibaez@upemor.edu.mx","$2y$10$.AcnfopQep3CeiU0eViz1eRcyS858z8PEy7NZIaWS.0XlTuopU2p2");
INSERT INTO usuarios VALUES("1005","Kamina","Aniki","Administrador","2000-03-11","Masculino","777-123-4567","Kamina@upemor.edu.mx","$2y$10$ySqUdKbG2x9.2LsiiXYxreVPWz0qucYdFVwTYlO0Oeu80J/OHWKhu");


