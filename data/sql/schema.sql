/* esquema base del proyecto cappuchino */

DROP TABLE IF EXISTS `gestion`;
CREATE TABLE `gestion` (
    `ident` int unsigned NOT NULL auto_increment,
    `nombre` varchar(128) NOT NULL,
    PRIMARY KEY (`ident`)
) DEFAULT CHARACTER SET UTF8;

DROP TABLE IF EXISTS `carrera`;
CREATE TABLE `carrera` (
    `ident` int unsigned NOT NULL auto_increment,
    `gestion` int unsigned NOT NULL,
    `codigo` varchar(32) NOT NULL,
    `nombre` varchar(128) NOT NULL,
    PRIMARY KEY (`ident`),
    INDEX (`gestion`),
    FOREIGN KEY (`gestion`) REFERENCES `gestion`(`ident`) ON UPDATE CASCADE ON DELETE RESTRICT
) DEFAULT CHARACTER SET UTF8;

DROP TABLE IF EXISTS `materia`;
CREATE TABLE `materia` (
    `ident` int unsigned NOT NULL auto_increment,
    `carrera` int unsigned NOT NULL,
    `codigo` varchar(32) NOT NULL,
    `nombre` varchar(128) NOT NULL,
    `nivel` int unsigned NOT NULL,
    PRIMARY KEY (`ident`),
    INDEX (`carrera`),
    FOREIGN KEY (`carrera`) REFERENCES `carrera`(`ident`) ON UPDATE CASCADE ON DELETE RESTRICT
) DEFAULT CHARACTER SET UTF8;

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
    `ident` int unsigned NOT NULL auto_increment,
    `apellidos` varchar(128) NOT NULL,
    `nombres` varchar(128) NOT NULL,
    `email` varchar(128) NOT NULL,
    `password` varchar(128) NOT NULL DEFAULT '',
    PRIMARY KEY (`ident`)
) DEFAULT CHARACTER SET UTF8;

DROP TABLE IF EXISTS `usuario_materia`;
CREATE TABLE `usuario_materia` (
    `usuario` int unsigned NOT NULL,
    `materia` int unsigned NOT NULL,
    PRIMARY KEY (`usuario`, `materia`),
    INDEX (`usuario`),
    FOREIGN KEY (`usuario`) REFERENCES `usuario`(`ident`) ON UPDATE CASCADE ON DELETE CASCADE,
    INDEX (`materia`),
    FOREIGN KEY (`materia`) REFERENCES `materia`(`ident`) ON UPDATE CASCADE ON DELETE CASCADE
) DEFAULT CHARACTER SET UTF8;

DROP TABLE IF EXISTS `docente`;
CREATE TABLE `docente` (
    `ident` int unsigned NOT NULL auto_increment,
    `apellidos` varchar(128) NOT NULL,
    `nombres` varchar(128) NOT NULL,
    PRIMARY KEY (`ident`)
) DEFAULT CHARACTER SET UTF8;

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE `grupo` (
    `ident` int unsigned NOT NULL auto_increment,
    `carrera` int unsigned NOT NULL,
    `materia` int unsigned NOT NULL,
    `docente` int unsigned NOT NULL,
    `codigo` varchar(32) NOT NULL,
    `nombre` varchar(128) NOT NULL,
    `nivel` int unsigned NOT NULL,
    PRIMARY KEY (`ident`),
    INDEX (`carrera`, `materia`),
    FOREIGN KEY (`carrera`, `ident`) REFERENCES `materia`(`carrera`, `ident`) ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX (`docente`),
    FOREIGN KEY (`docente`) REFERENCES `docente`(`ident`) ON UPDATE CASCADE ON DELETE RESTRICT
) DEFAULT CHARACTER SET UTF8;
