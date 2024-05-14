-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 05-05-2024 a las 18:40:33
-- Versión del servidor: 8.2.0
-- Versión de PHP: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `miracasa`
--

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `update_like`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_like` (IN `idRealEstate` INT, IN `idUser` INT, OUT `countLike` INT)   BEGIN
	DECLARE userLike INT;
    
	SELECT COUNT(l.id_realestate)
			    FROM `real_estate` r LEFT JOIN `like` l ON r.id_realestate = l.id_realestate
			    WHERE l.id_realestate = idRealEstate AND l.id_user = idUser INTO userLike;
                
    IF userLike = 1 THEN
        DELETE FROM `like` l
        	WHERE l.id_realestate = idRealEstate AND l.id_user = idUser;
        SELECT COUNT(l.id_realestate)
			    FROM `real_estate` r LEFT JOIN `like` l ON r.id_realestate = l.id_realestate
			    WHERE r.id_realestate = idRealEstate INTO countLike;
    ELSE IF userLike = 0 THEN
        INSERT INTO `like` VALUES(idRealEstate, idUser);
        SELECT COUNT(l.id_realestate)
			    FROM `real_estate` r LEFT JOIN `like` l ON r.id_realestate = l.id_realestate
			    WHERE r.id_realestate = idRealEstate INTO countLike;
    END IF;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `belong_to_cat`
--

DROP TABLE IF EXISTS `belong_to_cat`;
CREATE TABLE IF NOT EXISTS `belong_to_cat` (
  `id_realestate` int NOT NULL,
  `id_cat` int NOT NULL,
  PRIMARY KEY (`id_realestate`,`id_cat`),
  KEY `belong_to_cat_ibfk_2` (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `belong_to_cat`
--

INSERT INTO `belong_to_cat` (`id_realestate`, `id_cat`) VALUES
(2, 1),
(14, 1),
(1, 2),
(4, 3),
(12, 3),
(13, 3),
(14, 3),
(5, 4),
(12, 4),
(5, 5),
(10, 5),
(14, 5),
(4, 6),
(9, 6),
(10, 6),
(11, 6),
(12, 6),
(13, 6),
(14, 6),
(7, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `belong_to_type`
--

DROP TABLE IF EXISTS `belong_to_type`;
CREATE TABLE IF NOT EXISTS `belong_to_type` (
  `id_realestate` int NOT NULL,
  `id_type` int NOT NULL,
  PRIMARY KEY (`id_realestate`,`id_type`),
  KEY `belong_to_type_ibfk_2` (`id_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `belong_to_type`
--

INSERT INTO `belong_to_type` (`id_realestate`, `id_type`) VALUES
(4, 1),
(5, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(8, 2),
(2, 3),
(6, 4),
(3, 5),
(1, 6),
(7, 7),
(9, 9),
(10, 9),
(13, 9),
(4, 11),
(11, 13),
(13, 14),
(5, 16),
(12, 16),
(14, 16);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id_cat` int NOT NULL AUTO_INCREMENT,
  `name_cat` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `img_cat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `category`
--

INSERT INTO `category` (`id_cat`, `name_cat`, `img_cat`) VALUES
(1, 'Obra nueva', 'view/img/categories/obra_nueva.jpg'),
(2, 'A reformar', 'view/img/categories/a_reformar.jpg'),
(3, 'De diseño', 'view/img/categories/de_diseño.jpg'),
(4, 'De lujo', 'view/img/categories/de_lujo.jpg'),
(5, 'En zona residencial', 'view/img/categories/en_zona_residencial.jpg'),
(6, 'En la playa', 'view/img/categories/en_la_playa.jpg'),
(7, 'En el campo', 'view/img/categories/en_el_campo.jpg'),
(8, 'En la montaña', 'view/img/categories/en_la_montaña.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `city`
--

DROP TABLE IF EXISTS `city`;
CREATE TABLE IF NOT EXISTS `city` (
  `id_city` int NOT NULL AUTO_INCREMENT,
  `name_city` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `province` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `id_touristcat` int DEFAULT NULL,
  `img_city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_city`),
  KEY `city_ibfk_1` (`id_touristcat`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `city`
--

INSERT INTO `city` (`id_city`, `name_city`, `province`, `id_touristcat`, `img_city`) VALUES
(1, 'Albacete', 'Albacete', NULL, 'view/img/cities/albacete.jpg'),
(2, 'Alicante', 'Alicante', 2, 'view/img/cities/alicante.jpg'),
(3, 'Almería', 'Almería', 6, 'view/img/cities/almeria.jpg'),
(4, 'Ávila', 'Ávila', NULL, 'view/img/cities/avila.jpg'),
(5, 'Badajoz', 'Badajoz', NULL, 'view/img/cities/badajoz.jpg'),
(6, 'Barcelona', 'Barcelona', NULL, 'view/img/cities/barcelona.jpg'),
(7, 'Bilbao', 'Bizkaia', NULL, 'view/img/cities/bilbao.jpg'),
(8, 'Burgos', 'Burgos', NULL, 'view/img/cities/burgos.jpg'),
(9, 'Cáceres', 'Cáceres', NULL, 'view/img/cities/caceres.jpg'),
(10, 'Cádiz', 'Cádiz', 9, 'view/img/cities/cadiz.jpg'),
(11, 'Castellón de la Plana', 'Castellón', 8, 'view/img/cities/castellon.jpg'),
(12, 'Ciudad Real', 'Ciudad Real', NULL, 'view/img/cities/ciudad_real.jpg'),
(13, 'Córdoba', 'Córdoba', NULL, 'view/img/cities/cordoba.jpg'),
(14, 'A Coruña', 'A Coruña', 17, 'view/img/cities/a_coruña.jpg'),
(15, 'Cuenca', 'Cuenca', NULL, 'view/img/cities/cuenca.jpg'),
(16, 'Girona', 'Girona', 3, 'view/img/cities/girona.jpg'),
(17, 'Granada', 'Granada', NULL, 'view/img/cities/granada.jpg'),
(18, 'Guadalajara', 'Guadalajara', NULL, 'view/img/cities/guadalajara.jpg'),
(19, 'Huelva', 'Huelva', NULL, 'view/img/cities/huelva.jpg'),
(20, 'Huesca', 'Huesca', NULL, 'view/img/cities/huesca.jpg'),
(21, 'Jaén', 'Jaén', NULL, 'view/img/cities/jaen.jpg'),
(22, 'León', 'León', NULL, 'view/img/cities/leon.jpg'),
(23, 'Lleida', 'Lleida', NULL, 'view/img/cities/lleida.jpg'),
(24, 'Logroño', 'La Rioja', NULL, 'view/img/cities/logroño.jpg'),
(25, 'Lugo', 'Lugo', NULL, 'view/img/cities/lugo.jpg'),
(26, 'Madrid', 'Madrid', NULL, 'view/img/cities/madrid.jpg'),
(27, 'Málaga', 'Málaga', NULL, 'view/img/cities/malaga.jpg'),
(28, 'Murcia', 'Murcia', NULL, 'view/img/cities/murcia.jpg'),
(29, 'Ourense', 'Ourense', NULL, 'view/img/cities/ourense.jpg'),
(30, 'Oviedo', 'Asturias', NULL, 'view/img/cities/oviedo.jpg'),
(31, 'Palencia', 'Palencia', NULL, 'view/img/cities/palencia.jpg'),
(32, 'Palma de Mallorca', 'Illes Balears', NULL, 'view/img/cities/palma_de_mallorca.jpg'),
(33, 'Las Palmas de Gran Canaria', 'Las Palmas', NULL, 'view/img/cities/las_palmas_de_gran_canaria.jpg'),
(34, 'Pamplona', 'Navarra', NULL, 'view/img/cities/pamplona.jpg'),
(35, 'Pontevedra', 'Pontevedra', 18, 'view/img/cities/pontevedra.jpg'),
(36, 'Salamanca', 'Salamanca', NULL, 'view/img/cities/salamanca.jpg'),
(37, 'San Sebastián', 'Gipuzkoa', 14, 'view/img/cities/san_sebastian.jpg'),
(38, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife', NULL, 'view/img/cities/santa_cruz_de_tenerife.jpg'),
(39, 'Santander', 'Cantabria', 7, 'view/img/cities/santander.jpg'),
(40, 'Segovia', 'Segovia', NULL, 'view/img/cities/segovia.jpg'),
(41, 'Sevilla', 'Sevilla', NULL, 'view/img/cities/sevilla.jpg'),
(42, 'Soria', 'Soria', NULL, 'view/img/cities/soria.jpg'),
(43, 'Tarragona', 'Tarragona', 12, 'view/img/cities/tarragona.jpg'),
(44, 'Teruel', 'Teruel', NULL, 'view/img/cities/teruel.jpg'),
(45, 'Toledo', 'Toledo', NULL, 'view/img/cities/toledo.jpg'),
(46, 'Valencia', 'Valencia', 10, 'view/img/cities/valencia.jpg'),
(47, 'Valladolid', 'Valladolid', NULL, 'view/img/cities/valladolid.jpg'),
(48, 'Vitoria', 'Álava', NULL, 'view/img/cities/vitoria.jpg'),
(49, 'Zamora', 'Zamora', NULL, 'view/img/cities/zamora.jpg'),
(50, 'Zaragoza', 'Zaragoza', NULL, 'view/img/cities/zaragoza.jpg'),
(51, 'Ceuta', 'Ceuta', NULL, 'view/img/cities/ceuta.jpg'),
(52, 'Melilla', 'Melilla', NULL, 'view/img/cities/melilla.jpg'),
(53, 'Canet de Mar', 'Barcelona', 1, 'view/img/cities/canet_de_mar.jpg'),
(54, 'Castelldefels', 'Barcelona', 1, 'view/img/cities/castelldefels.jpg'),
(55, 'Sitges', 'Barcelona', 1, 'view/img/cities/sitges.jpg'),
(56, 'Altea', 'Alicante', 2, 'view/img/cities/altea.jpg'),
(57, 'Benidorm', 'Alicante', 2, 'view/img/cities/benidorm.jpg'),
(58, 'Torrevieja', 'Alicante', 2, 'view/img/cities/torrevieja.jpg'),
(59, 'Lloret de Mar', 'Girona', 3, 'view/img/cities/lloret_de_mar.jpg'),
(60, 'Águilas', 'Murcia', 4, 'view/img/cities/aguilas.jpg'),
(61, 'Cartagena', 'Murcia', 4, 'view/img/cities/cartagena.jpg'),
(62, 'Muros', 'A Coruña', 5, 'view/img/cities/muros.jpg'),
(63, 'Muxía', 'A Coruña', 5, 'view/img/cities/muxia.jpg'),
(64, 'Roquetas de Mar', 'Almería', 6, 'view/img/cities/roquetas_de_mar.jpg'),
(65, 'San Vicente de la Barquera', 'Cantabria', 7, 'view/img/cities/san_vicente_de_la_barquera.jpg'),
(66, 'Oropesa del Mar', 'Castellón', 8, 'view/img/cities/oropesa_del_mar.jpg'),
(67, 'Peñíscola', 'Castellón', 8, 'view/img/cities/penyiscola.jpg'),
(68, 'Vinarós', 'Castellón', 8, 'view/img/cities/vinaros.jpg'),
(69, 'El Puerto de Santa María', 'Cádiz', 9, 'view/img/cities/el_puerto_de_santa_maria.jpg'),
(70, 'Isla Cristina', 'Huelva', 9, 'view/img/cities/isla_cristina.jpg'),
(71, 'Gandía', 'Valencia', 10, 'view/img/cities/gandia.jpg'),
(72, 'Estepona', 'Málaga', 11, 'view/img/cities/estepona.jpg'),
(73, 'Marbella', 'Málaga', 11, 'view/img/cities/marbella.jpg'),
(74, 'Torremolinos', 'Málaga', 11, 'view/img/cities/torremolinos.jpg'),
(75, 'Cambrils', 'Tarragona', 12, 'view/img/cities/cambrils.jpg'),
(76, 'Salou', 'Tarragona', 12, 'view/img/cities/salou.jpg'),
(77, 'Almuñecar', 'Granada', 13, 'view/img/cities/almuñecar.jpg'),
(78, 'Motril', 'Granada', 13, 'view/img/cities/motril.jpg'),
(79, 'Hondarríbia', 'Gipuzkoa', 14, 'view/img/cities/hondarribia.jpg'),
(80, 'Gijón', 'Asturias', 15, 'view/img/cities/gijon.jpg'),
(81, 'Ribadesella', 'Asturias', 15, 'view/img/cities/ribadesella.jpg'),
(82, 'Ribadeo', 'Lugo', 16, 'view/img/cities/ribadeo.jpg'),
(83, 'Viveiro', 'Lugo', 16, 'view/img/cities/viveiro.jpg'),
(84, 'Ferrol', 'A Coruña', 17, 'view/img/cities/ferrol.jpg'),
(85, 'Sanxenxo', 'Pontevedra', 18, 'view/img/cities/sanxenxo.jpg'),
(86, 'Vigo', 'Pontevedra', 18, 'view/img/cities/vigo.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `exceptions`
--

DROP TABLE IF EXISTS `exceptions`;
CREATE TABLE IF NOT EXISTS `exceptions` (
  `id_excep` int NOT NULL,
  `spot` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `current_date_time` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `user_type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `exceptions`
--

INSERT INTO `exceptions` (`id_excep`, `spot`, `current_date_time`, `user_type`) VALUES
(503, 'Carrusel HOME', '2022-03-18 23:54:35', 'client'),
(503, 'Carrusel HOME', '2022-03-18 23:54:39', 'client'),
(503, 'Carrusel HOME', '2022-03-18 23:54:40', 'admin'),
(404, 'Carrusel HOME', '2022-03-18 23:54:41', 'client'),
(404, 'Carrusel HOME', '2022-03-18 23:55:30', 'admin'),
(404, 'Carrusel HOME', '2022-03-18 23:56:23', 'client'),
(503, 'Categories HOME', '2022-04-01 11:37:16', 'client'),
(503, 'Categories HOME', '2022-04-01 11:38:25', 'client'),
(503, 'Categories HOME', '2022-04-01 11:37:16', 'admin'),
(404, 'Categories HOME', '2022-04-01 11:38:40', 'admin'),
(404, 'Categories HOME', '2022-04-01 11:37:31', 'client'),
(404, 'Categories HOME', '2022-04-02 17:30:31', 'client');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `extras`
--

DROP TABLE IF EXISTS `extras`;
CREATE TABLE IF NOT EXISTS `extras` (
  `id_extras` int NOT NULL AUTO_INCREMENT,
  `name_extras` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `img_extras` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_extras`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `extras`
--

INSERT INTO `extras` (`id_extras`, `name_extras`, `img_extras`) VALUES
(1, 'Ascensor', 'view/img/extras/ascensor.png'),
(2, 'Parking', 'view/img/extras/parking.png'),
(3, 'Trastero', 'view/img/extras/trastero.png'),
(4, 'Balcón', 'view/img/extras/balcon.png'),
(5, 'Terraza', 'view/img/extras/terraza.png'),
(6, 'Piscina', 'view/img/extras/piscina.png'),
(7, 'Jardín', 'view/img/extras/jardin.png'),
(8, 'Amueblado', 'view/img/extras/amueblado.png'),
(9, 'Sin amueblar', 'view/img/extras/sin_amueblar.png'),
(10, 'Electrodomésticos', 'view/img/extras/electrodomesticos.png'),
(11, 'Aire acondicionado', 'view/img/extras/aire_acondicionado.png'),
(12, 'Calefacción', 'view/img/extras/calefaccion.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `has_extras`
--

DROP TABLE IF EXISTS `has_extras`;
CREATE TABLE IF NOT EXISTS `has_extras` (
  `id_realestate` int NOT NULL,
  `id_extras` int NOT NULL,
  PRIMARY KEY (`id_realestate`,`id_extras`),
  KEY `has_extras_ibfk_2` (`id_extras`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `has_extras`
--

INSERT INTO `has_extras` (`id_realestate`, `id_extras`) VALUES
(2, 1),
(4, 1),
(6, 1),
(8, 1),
(10, 1),
(4, 2),
(10, 2),
(12, 2),
(13, 2),
(4, 3),
(12, 3),
(13, 3),
(8, 4),
(13, 4),
(9, 5),
(11, 5),
(5, 6),
(12, 6),
(14, 6),
(5, 7),
(7, 7),
(12, 7),
(14, 7),
(3, 8),
(4, 8),
(5, 8),
(8, 8),
(9, 8),
(10, 8),
(11, 8),
(12, 8),
(13, 8),
(14, 8),
(4, 10),
(5, 10),
(8, 10),
(9, 10),
(10, 10),
(11, 10),
(12, 10),
(13, 10),
(14, 10),
(1, 11),
(3, 11),
(4, 11),
(5, 11),
(8, 11),
(9, 11),
(10, 11),
(11, 11),
(12, 11),
(13, 11),
(14, 11),
(1, 12),
(3, 12),
(4, 12),
(5, 12),
(9, 12),
(10, 12),
(11, 12),
(12, 12),
(13, 12),
(14, 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `img_realestate`
--

DROP TABLE IF EXISTS `img_realestate`;
CREATE TABLE IF NOT EXISTS `img_realestate` (
  `id_img_re` int NOT NULL AUTO_INCREMENT,
  `img_realestate` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `id_realestate` int NOT NULL,
  PRIMARY KEY (`id_img_re`),
  KEY `real_estate` (`id_realestate`)
) ENGINE=InnoDB AUTO_INCREMENT=79 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `img_realestate`
--

INSERT INTO `img_realestate` (`id_img_re`, `img_realestate`, `id_realestate`) VALUES
(1, 'view/img/real_estates/real_estate_1_1.jpg', 1),
(2, 'view/img/real_estates/real_estate_1_2.jpg', 1),
(3, 'view/img/real_estates/real_estate_1_3.jpg', 1),
(4, 'view/img/real_estates/real_estate_1_4.jpg', 1),
(5, 'view/img/real_estates/real_estate_2_1.jpg', 2),
(6, 'view/img/real_estates/real_estate_2_2.jpg', 2),
(7, 'view/img/real_estates/real_estate_2_3.jpg', 2),
(8, 'view/img/real_estates/real_estate_2_4.jpg', 2),
(9, 'view/img/real_estates/real_estate_3_1.jpg', 3),
(10, 'view/img/real_estates/real_estate_3_2.jpg', 3),
(11, 'view/img/real_estates/real_estate_3_3.jpg', 3),
(12, 'view/img/real_estates/real_estate_3_4.jpg', 3),
(13, 'view/img/real_estates/real_estate_4_1.jpg', 4),
(14, 'view/img/real_estates/real_estate_4_2.jpg', 4),
(15, 'view/img/real_estates/real_estate_4_3.jpg', 4),
(16, 'view/img/real_estates/real_estate_4_4.jpg', 4),
(17, 'view/img/real_estates/real_estate_5_1.jpg', 5),
(18, 'view/img/real_estates/real_estate_5_2.jpg', 5),
(19, 'view/img/real_estates/real_estate_5_3.jpg', 5),
(20, 'view/img/real_estates/real_estate_5_4.jpg', 5),
(21, 'view/img/real_estates/real_estate_6_1.jpg', 6),
(22, 'view/img/real_estates/real_estate_6_2.jpg', 6),
(23, 'view/img/real_estates/real_estate_6_3.jpg', 6),
(24, 'view/img/real_estates/real_estate_6_4.jpg', 6),
(25, 'view/img/real_estates/real_estate_7_1.jpg', 7),
(26, 'view/img/real_estates/real_estate_7_2.jpg', 7),
(27, 'view/img/real_estates/real_estate_7_3.jpg', 7),
(28, 'view/img/real_estates/real_estate_7_4.jpg', 7),
(29, 'view/img/real_estates/real_estate_8_1.jpg', 8),
(30, 'view/img/real_estates/real_estate_8_2.jpg', 8),
(31, 'view/img/real_estates/real_estate_8_3.jpg', 8),
(32, 'view/img/real_estates/real_estate_8_4.jpg', 8),
(33, 'view/img/real_estates/real_estate_9_1.jpg', 9),
(34, 'view/img/real_estates/real_estate_9_2.jpg', 9),
(35, 'view/img/real_estates/real_estate_9_3.jpg', 9),
(36, 'view/img/real_estates/real_estate_9_4.jpg', 9),
(37, 'view/img/real_estates/real_estate_10_1.jpg', 10),
(38, 'view/img/real_estates/real_estate_10_2.jpg', 10),
(39, 'view/img/real_estates/real_estate_10_3.jpg', 10),
(40, 'view/img/real_estates/real_estate_10_4.jpg', 10),
(41, 'view/img/real_estates/real_estate_10_5.jpg', 10),
(42, 'view/img/real_estates/real_estate_10_6.jpg', 10),
(43, 'view/img/real_estates/real_estate_10_7.jpg', 10),
(44, 'view/img/real_estates/real_estate_11_1.jpg', 11),
(45, 'view/img/real_estates/real_estate_11_2.jpg', 11),
(46, 'view/img/real_estates/real_estate_11_3.jpg', 11),
(47, 'view/img/real_estates/real_estate_11_4.jpg', 11),
(48, 'view/img/real_estates/real_estate_11_5.jpg', 11),
(49, 'view/img/real_estates/real_estate_11_6.jpg', 11),
(50, 'view/img/real_estates/real_estate_12_1.jpg', 12),
(51, 'view/img/real_estates/real_estate_12_2.jpg', 12),
(52, 'view/img/real_estates/real_estate_12_3.jpg', 12),
(53, 'view/img/real_estates/real_estate_12_4.jpg', 12),
(54, 'view/img/real_estates/real_estate_12_5.jpg', 12),
(55, 'view/img/real_estates/real_estate_12_6.jpg', 12),
(56, 'view/img/real_estates/real_estate_12_7.jpg', 12),
(57, 'view/img/real_estates/real_estate_12_8.jpg', 12),
(58, 'view/img/real_estates/real_estate_12_9.jpg', 12),
(59, 'view/img/real_estates/real_estate_12_10.jpg', 12),
(60, 'view/img/real_estates/real_estate_12_11.jpg', 12),
(61, 'view/img/real_estates/real_estate_13_1.jpg', 13),
(62, 'view/img/real_estates/real_estate_13_2.jpg', 13),
(63, 'view/img/real_estates/real_estate_13_3.jpg', 13),
(64, 'view/img/real_estates/real_estate_13_4.jpg', 13),
(65, 'view/img/real_estates/real_estate_13_5.jpg', 13),
(66, 'view/img/real_estates/real_estate_13_6.jpg', 13),
(67, 'view/img/real_estates/real_estate_13_7.jpg', 13),
(68, 'view/img/real_estates/real_estate_13_8.jpg', 13),
(69, 'view/img/real_estates/real_estate_13_9.jpg', 13),
(70, 'view/img/real_estates/real_estate_13_10.jpg', 13),
(71, 'view/img/real_estates/real_estate_14_1.jpg', 14),
(72, 'view/img/real_estates/real_estate_14_2.jpg', 14),
(73, 'view/img/real_estates/real_estate_14_3.jpg', 14),
(74, 'view/img/real_estates/real_estate_14_4.jpg', 14),
(75, 'view/img/real_estates/real_estate_14_5.jpg', 14),
(76, 'view/img/real_estates/real_estate_14_6.jpg', 14),
(77, 'view/img/real_estates/real_estate_14_7.jpg', 14),
(78, 'view/img/real_estates/real_estate_14_8.jpg', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `is_traded`
--

DROP TABLE IF EXISTS `is_traded`;
CREATE TABLE IF NOT EXISTS `is_traded` (
  `id_realestate` int NOT NULL,
  `id_op` int NOT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`id_realestate`,`id_op`),
  KEY `is_traded_ibfk_2` (`id_op`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `is_traded`
--

INSERT INTO `is_traded` (`id_realestate`, `id_op`, `price`) VALUES
(1, 1, 250000),
(2, 2, 80),
(3, 1, 200000),
(4, 1, 225000),
(4, 3, 550),
(5, 1, 1200000),
(6, 2, 50),
(7, 1, 42000),
(8, 4, 200),
(9, 2, 650),
(10, 1, 265000),
(11, 2, 599),
(12, 1, 375000),
(13, 1, 279000),
(13, 3, 550),
(14, 2, 680);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `like`
--

DROP TABLE IF EXISTS `like`;
CREATE TABLE IF NOT EXISTS `like` (
  `id_realestate` int NOT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id_realestate`,`id_user`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `like`
--

INSERT INTO `like` (`id_realestate`, `id_user`) VALUES
(3, 2),
(4, 2),
(5, 2),
(7, 2),
(9, 2),
(12, 2),
(14, 2),
(4, 3),
(9, 3),
(10, 3),
(14, 3),
(3, 4),
(9, 4),
(11, 4),
(14, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operation`
--

DROP TABLE IF EXISTS `operation`;
CREATE TABLE IF NOT EXISTS `operation` (
  `id_op` int NOT NULL AUTO_INCREMENT,
  `name_op` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `img_op` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_op`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `operation`
--

INSERT INTO `operation` (`id_op`, `name_op`, `img_op`) VALUES
(1, 'Compra', 'view/img/operations/compra.jpg'),
(2, 'Alquiler', 'view/img/operations/alquiler.jpg'),
(3, 'Alquiler con opción a compra', 'view/img/operations/alquiler_compra.jpg'),
(4, 'Compartir', 'view/img/operations/compartir.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `real_estate`
--

DROP TABLE IF EXISTS `real_estate`;
CREATE TABLE IF NOT EXISTS `real_estate` (
  `id_realestate` int NOT NULL AUTO_INCREMENT,
  `cadastre` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `id_city` int NOT NULL,
  `area` int NOT NULL,
  `rooms` int NOT NULL DEFAULT '0',
  `bathrooms` int NOT NULL DEFAULT '0',
  `floor` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL DEFAULT '0',
  `description` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `publication_date` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `visited` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_realestate`),
  UNIQUE KEY `cadastre` (`cadastre`),
  KEY `city` (`id_city`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `real_estate`
--

INSERT INTO `real_estate` (`id_realestate`, `cadastre`, `lat`, `lng`, `id_city`, `area`, `rooms`, `bathrooms`, `floor`, `description`, `publication_date`, `visited`) VALUES
(1, '7003051YJ4480S0045AA', 43.461647033691406, -3.819044351577759, 39, 150, 0, 1, 'Planta baja', 'Descubre el potencial de este local comercial a reformar en Santander. Con una ubicación estratégica que garantiza visibilidad y acceso, este espacio ofrece la oportunidad perfecta para dar vida a tu propio negocio. Transforma este local según tus necesidades.', '15-04-2017', 375),
(2, '8204201YJ5210S0087BB', 41.38701164491393, 2.1701472867137825, 6, 15, 0, 0, 'Subsótano', 'Se alquila plaza de garaje en el corazón de Barcelona con acceso cómodo y directo. Esta plaza ofrece conveniencia y seguridad para tu vehículo en una de las zonas más céntricas de la ciudad. Aprovecha esta oportunidad y asegura tu espacio de estacionamiento.', '10-11-2023', 227),
(3, '1234201YJ5210S0087CC', 37.38540018023119, -5.99179181508895, 41, 145, 0, 2, '2ª planta', 'Oportunidad única para establecer tu negocio en el corazón de Sevilla. Estas oficinas amuebladas ofrecen un entorno profesional y moderno con calefacción y aire acondicionado. Ubicación estratégica, mobiliario elegante y servicios esenciales hacen de este espacio la elección perfecta.', '22-07-2010', 266),
(4, '6845201YJ5210S0087DD', 38.3589899089205, -0.4674039924655232, 2, 140, 3, 2, '4ª planta', 'Elegante y moderno dúplex en Alicante. Con un diseño contemporáneo y equipamiento de alta gama, este hogar ofrece una experiencia de vida excepcional. Disfruta de vistas impresionantes al mar que complementan a la perfección la calidad interior del espacio.', '27-12-2023', 299),
(5, '2666201YJ5210S0087EE', 40.43789739994751, -3.632903906632869, 26, 500, 5, 4, '0', 'Esta casa excepcional, ubicada en una zona residencial de lujo en Madrid, redefine el concepto de elegancia y confort. Completamente equipada con detalle, esta propiedad ofrece un estilo de vida exclusivo. Disfruta de la privacidad y la sofisticación en cada rincón de esta residencia.', '02-12-2023', 498),
(6, '9736201YJ5210S0087FF', 39.02725747002513, -3.9403544312211514, 12, 15, 0, 0, '4ª planta', 'Pon orden en tu vida con nuestro trastero. Ubicado en una zona de fácil acceso, este espacio ofrece comodidad y seguridad para almacenar tus pertenencias. Ya sea para liberar espacio en casa u organizar herramientas y equipos, este trastero es la solución perfecta.', '02-12-2023', 45),
(7, '4852201YJ5210S0087GG', 42.36339348927319, -3.773002201902397, 8, 1000, 0, 0, '0', 'Descubre la serenidad de este amplio terreno, situado en una zona tranquila con vistas impresionantes. Disfruta de la amplitud, la privacidad y la belleza de un bonito jardín que complementa perfectamente el paisaje. Ideal para construir la casa de tus sueños.', '02-12-2023', 67),
(8, '5928522YJ5210S0496HH', 39.48681669185842, -0.36291043111424803, 46, 110, 4, 2, '3ª planta', 'Este acogedor piso en Valencia ofrece la solución perfecta para estudiantes que buscan comodidad y tranquilidad. Con 4 habitaciones bien equipadas y ubicado en una zona serena. Cada habitación está disponible individualmente a un precio asequible.', '12-01-2024', 58),
(9, '2470522YJ5210S0496XA', 37.418264266530805, -1.5616969248006018, 60, 75, 1, 1, '1ª planta', '¡Descubre el encanto del Mediterráneo en este apartamento frente a la playa en Águilas! Con vistas impresionantes y todas las comodidades necesarias, es el refugio perfecto para unas vacaciones inolvidables junto al mar.', '08-03-2020', 16),
(10, '3582522YJ5210S0496BR', 41.26676629490557, 1.9674137631022395, 54, 85, 2, 1, '2ª planta', 'Apartamento frente a la playa en Castelldefels. Vistas al mar desde todas las habitaciones, terraza privada y acceso directo a la playa. Moderno y luminoso, perfecto para disfrutar del estilo de vida mediterráneo.', '09-05-2021', 834),
(11, '7294522YJ5210S0496BZ', 37.19770063490664, -7.306096003374809, 70, 75, 1, 1, '1ª planta', 'Este encantador loft en Isla Cristina ofrece una experiencia costera incomparable, con vistas impresionantes del mar. Con un diseño moderno y acogedor, es perfecto para aquellos que buscan relajarse y disfrutar de las maravillas naturales de la costa.', '08-03-2018', 7),
(12, '5922522YJ5210S0496GH', 41.69850900898505, 2.846702596383006, 59, 200, 4, 3, '0', 'Esta impresionante casa, ubicada a pocos pasos de la playa, ofrece un estilo de vida costero incomparable. Con amplios espacios interiores y todas las comodidades modernas, esta propiedad es perfecta para quienes buscan confort y lujo en un entorno relajado junto al mar.', '22-07-2022', 14),
(13, '6578522YJ5210S0496UI', 43.326087675807315, -1.9719089752672998, 37, 145, 3, 2, 'Planta baja', 'Este amplio y luminoso apartamento te ofrece la oportunidad de disfrutar de la belleza del mar Cantábrico desde la comodidad de tu hogar. Situado en primera línea de playa, este apartamento es el refugio perfecto para relajarte y disfrutar de todo lo que San Sebastián tiene para ofrecer', '14-10-2021', 24),
(14, '7856522YJ5210S0496CF', 41.12373402265875, 1.2872493152878055, 43, 140, 2, 1, '0', 'Esta encantadora casa, ubicada a pocos pasos de la playa, ofrece la combinación perfecta de comodidad y tranquilidad. Con una piscina reluciente, una amplia terraza para disfrutar del sol y un exuberante jardín, esta propiedad es un paraíso para los amantes del aire libre.', '05-08-2019', 58);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tourist_cat`
--

DROP TABLE IF EXISTS `tourist_cat`;
CREATE TABLE IF NOT EXISTS `tourist_cat` (
  `id_touristcat` int NOT NULL AUTO_INCREMENT,
  `name_touristcat` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `img_touristcat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `map_touristcat` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_touristcat`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `tourist_cat`
--

INSERT INTO `tourist_cat` (`id_touristcat`, `name_touristcat`, `img_touristcat`, `map_touristcat`) VALUES
(1, 'Costa Barcelona', 'view/img/tourist_cat/costa_barcelona.jpg', 'view/img/tourist_cat/maps/mapa_costa_barcelona.png'),
(2, 'Costa Blanca', 'view/img/tourist_cat/costa_blanca.jpg', 'view/img/tourist_cat/maps/mapa_costa_blanca.png'),
(3, 'Costa Brava', 'view/img/tourist_cat/costa_brava.jpg', 'view/img/tourist_cat/maps/mapa_costa_brava.png'),
(4, 'Costa Cálida', 'view/img/tourist_cat/costa_calida.jpg', 'view/img/tourist_cat/maps/mapa_costa_calida.png'),
(5, 'Costa da Morte', 'view/img/tourist_cat/costa_da_morte.jpg', 'view/img/tourist_cat/maps/mapa_costa_da_morte.png'),
(6, 'Costa de Almería', 'view/img/tourist_cat/costa_almeria.jpg', 'view/img/tourist_cat/maps/mapa_costa_almeria.png'),
(7, 'Costa de Cantabria', 'view/img/tourist_cat/costa_cantabria.jpg', 'view/img/tourist_cat/maps/mapa_costa_cantabria.png'),
(8, 'Costa de Castellón', 'view/img/tourist_cat/costa_castellon.jpg', 'view/img/tourist_cat/maps/mapa_costa_castellon.png'),
(9, 'Costa de la Luz', 'view/img/tourist_cat/costa_de_la_luz.jpg', 'view/img/tourist_cat/maps/mapa_costa_de_la_luz.png'),
(10, 'Costa de Valencia', 'view/img/tourist_cat/costa_valencia.jpg', 'view/img/tourist_cat/maps/mapa_costa_valencia.png'),
(11, 'Costa del Sol', 'view/img/tourist_cat/costa_del_sol.jpg', 'view/img/tourist_cat/maps/mapa_costa_del_sol.png'),
(12, 'Costa Dorada', 'view/img/tourist_cat/costa_dorada.jpg', 'view/img/tourist_cat/maps/mapa_costa_dorada.png'),
(13, 'Costa Tropical', 'view/img/tourist_cat/costa_tropical.jpg', 'view/img/tourist_cat/maps/mapa_costa_tropical.png'),
(14, 'Costa Vasca', 'view/img/tourist_cat/costa_vasca.jpg', 'view/img/tourist_cat/maps/mapa_costa_vasca.png'),
(15, 'Costa Verde', 'view/img/tourist_cat/costa_verde.jpg', 'view/img/tourist_cat/maps/mapa_costa_verde.png'),
(16, 'Mariña Lucense', 'view/img/tourist_cat/marinya_lucense.jpg', 'view/img/tourist_cat/maps/mapa_marinya_lucense.png'),
(17, 'Rías Altas', 'view/img/tourist_cat/rias_altas.jpg', 'view/img/tourist_cat/maps/mapa_rias_altas.png'),
(18, 'Rías Baixas', 'view/img/tourist_cat/rias_baixas.jpg', 'view/img/tourist_cat/maps/mapa_rias_baixas.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `type`
--

DROP TABLE IF EXISTS `type`;
CREATE TABLE IF NOT EXISTS `type` (
  `id_type` int NOT NULL AUTO_INCREMENT,
  `name_type` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `subtype` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `img_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_type`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `type`
--

INSERT INTO `type` (`id_type`, `name_type`, `subtype`, `img_type`) VALUES
(1, 'Vivienda', 'Inmueble', 'view/img/types/vivienda.jpg'),
(2, 'Habitación', 'Inmueble', 'view/img/types/habitacion.jpg'),
(3, 'Garaje', 'Inmueble', 'view/img/types/garaje.jpg'),
(4, 'Trastero', 'Inmueble', 'view/img/types/trastero.jpg'),
(5, 'Oficina', 'Inmueble', 'view/img/types/oficina.jpg'),
(6, 'Local o nave', 'Inmueble', 'view/img/types/local_nave.jpg'),
(7, 'Terreno', 'Inmueble', 'view/img/types/terreno.jpg'),
(8, 'Edificio', 'Inmueble', 'view/img/types/edificio.jpg'),
(9, 'Apartamento', 'Piso', 'view/img/types/apartamento.jpg'),
(10, 'Ático', 'Piso', 'view/img/types/atico.jpg'),
(11, 'Dúplex', 'Piso', 'view/img/types/duplex.jpg'),
(12, 'Estudio', 'Piso', 'view/img/types/estudio.jpg'),
(13, 'Loft', 'Piso', 'view/img/types/loft.jpg'),
(14, 'Planta baja', 'Piso', 'view/img/types/planta_baja.jpg'),
(15, 'Planta intermedia', 'Piso', 'view/img/types/plantas_intermedias.jpg'),
(16, 'Casa', 'Casa', 'view/img/types/casa.jpg'),
(17, 'Casa adosada', 'Casa', 'view/img/types/casa_adosada.jpg'),
(18, 'Finca rústica', 'Casa', 'view/img/types/finca_rustica.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(25) COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `type_user` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `email`, `type_user`, `avatar`) VALUES
(1, 'montes', '$2y$12$2xBQyCFHA3JrxtRtasHk7.GyyXF7DYcdUkcCh77Rs6A4nbCazqPZK', 'framondo@gmail.com', 'admin', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=montes'),
(2, 'framondo', '$2y$12$2xBQyCFHA3JrxtRtasHk7.GyyXF7DYcdUkcCh77Rs6A4nbCazqPZK', 'framondo@hotmail.com', 'client', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=framondo'),
(3, 'miguel', '$2y$12$HjfLgcfAZLpTcwvC4K5MaOZdPXvq9bnlhaBLIqe1O9EW7wLrdCmna', 'miguel@hotmail.com', 'client', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=miguel'),
(4, 'laura', '$2y$12$aYcY6yKQiNOREGPkjeP8tOIXeBN0V7foeG/oo5mehXnAPLim/SKdC', 'laura@hotmail.com', 'client', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=laura'),
(5, 'guido', '$2y$12$2/mARIQLWghxwtwUfvJLxeEhRR15mrSI91lhLi9RrSueXSmrP8jWy', 'guido@hotmail.com', 'client', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=guido'),
(6, 'cristian', '$2y$12$hW0q5Us.77CyUc6.W18tY.BUlJ.lXku9b6YVnwYO6/8E3PWgOdQUq', 'cristian@hotmail.com', 'client', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=cristian'),
(7, 'llorenç', '$2y$12$KYrcIf0i.5N5TkCc7s5pNuzQ0.Bd4g/GWP9fdNyVgH5kUfx3ZqOPm', 'llorenc@hotmail.com', 'client', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=llorenç');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `belong_to_cat`
--
ALTER TABLE `belong_to_cat`
  ADD CONSTRAINT `belong_to_cat_ibfk_1` FOREIGN KEY (`id_realestate`) REFERENCES `real_estate` (`id_realestate`),
  ADD CONSTRAINT `belong_to_cat_ibfk_2` FOREIGN KEY (`id_cat`) REFERENCES `category` (`id_cat`);

--
-- Filtros para la tabla `belong_to_type`
--
ALTER TABLE `belong_to_type`
  ADD CONSTRAINT `belong_to_type_ibfk_1` FOREIGN KEY (`id_realestate`) REFERENCES `real_estate` (`id_realestate`),
  ADD CONSTRAINT `belong_to_type_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `type` (`id_type`);

--
-- Filtros para la tabla `city`
--
ALTER TABLE `city`
  ADD CONSTRAINT `city_ibfk_1` FOREIGN KEY (`id_touristcat`) REFERENCES `tourist_cat` (`id_touristcat`);

--
-- Filtros para la tabla `has_extras`
--
ALTER TABLE `has_extras`
  ADD CONSTRAINT `has_extras_ibfk_1` FOREIGN KEY (`id_realestate`) REFERENCES `real_estate` (`id_realestate`),
  ADD CONSTRAINT `has_extras_ibfk_2` FOREIGN KEY (`id_extras`) REFERENCES `extras` (`id_extras`);

--
-- Filtros para la tabla `img_realestate`
--
ALTER TABLE `img_realestate`
  ADD CONSTRAINT `img_realestate_ibfk_1` FOREIGN KEY (`id_realestate`) REFERENCES `real_estate` (`id_realestate`);

--
-- Filtros para la tabla `is_traded`
--
ALTER TABLE `is_traded`
  ADD CONSTRAINT `is_traded_ibfk_1` FOREIGN KEY (`id_realestate`) REFERENCES `real_estate` (`id_realestate`),
  ADD CONSTRAINT `is_traded_ibfk_2` FOREIGN KEY (`id_op`) REFERENCES `operation` (`id_op`);

--
-- Filtros para la tabla `like`
--
ALTER TABLE `like`
  ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`id_realestate`) REFERENCES `real_estate` (`id_realestate`),
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Filtros para la tabla `real_estate`
--
ALTER TABLE `real_estate`
  ADD CONSTRAINT `real_estate_ibfk_2` FOREIGN KEY (`id_city`) REFERENCES `city` (`id_city`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
