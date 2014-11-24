#Como root
CREATE DATABASE webinar;
USE webinar;
CREATE TABLE `producto` 
(
	`id` int(11) NOT NULL auto_increment,
	`codigo` varchar(40) NOT NULL,
	`nombre` varchar(40) NOT NULL,
	`descripcion` varchar(200) default NULL,
	`estado` enum('A','I') default 'A',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `codigo` (`codigo`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE USER webinar identified by 'webinar';

GRANT ALL PRIVILEGES ON webinar.* TO 'webinar'@'localhost' identified by 'webinar';
