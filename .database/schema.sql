-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `privilege`;
CREATE TABLE `privilege` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `privilege` (`id`, `name`) VALUES
(1,	'Adresář'),
(2,	'Vyhledávač');

DROP TABLE IF EXISTS `rules`;
CREATE TABLE `rules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('deny','allow') COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_admin_id` int(10) unsigned NOT NULL,
  `village_id` int(10) unsigned NOT NULL,
  `privilege_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_admin_id_village_id_privilege_id` (`user_admin_id`,`village_id`,`privilege_id`),
  KEY `user_admin_id` (`user_admin_id`),
  KEY `village_id` (`village_id`),
  KEY `privilege_id` (`privilege_id`),
  CONSTRAINT `rules_ibfk_4` FOREIGN KEY (`user_admin_id`) REFERENCES `user_admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rules_ibfk_5` FOREIGN KEY (`village_id`) REFERENCES `village` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `rules_ibfk_6` FOREIGN KEY (`privilege_id`) REFERENCES `privilege` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `user_admin`;
CREATE TABLE `user_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `village`;
CREATE TABLE `village` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `village` (`id`, `name`) VALUES
(1,	'Praha'),
(2,	'Brno');
