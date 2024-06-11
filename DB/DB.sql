-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 11-06-2024 a las 17:13:07
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
DROP PROCEDURE IF EXISTS `delete_cart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_cart` (IN `uid` VARCHAR(100))   BEGIN
	DELETE
    	FROM `cart` c
        WHERE c.uid = uid;
END$$

DROP PROCEDURE IF EXISTS `delete_lineCart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_lineCart` (IN `id_realestate` INT, IN `uid` VARCHAR(100))   BEGIN
	DELETE
		FROM `cart` c
		WHERE c.id_realestate = id_realestate AND c.uid = uid;
END$$

DROP PROCEDURE IF EXISTS `insert_bill`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_bill` (IN `uid` VARCHAR(100), OUT `id_bill` INT)   BEGIN
    	INSERT INTO `bill` (`uid`, `date`)
        VALUES (uid, CURRENT_DATE());
        
        SELECT LAST_INSERT_ID() INTO id_bill;
END$$

DROP PROCEDURE IF EXISTS `insert_bill_detail`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_bill_detail` (IN `uid` VARCHAR(100), IN `id_bill` INT)   BEGIN
	INSERT INTO `bill_detail` (`id_bill`, `id_realestate`, `quantity`, `price`)
    SELECT id_bill, ca.id_realestate, ca.quantity, s.price
		FROM `real_estate` r
		INNER JOIN `cart` ca ON r.id_realestate = ca.id_realestate
		INNER JOIN `is_traded` s ON r.id_realestate = s.id_realestate 
		INNER JOIN `operation` o ON o.id_op = s.id_op
		WHERE ca.uid = uid
		GROUP BY ca.id_realestate;
END$$

DROP PROCEDURE IF EXISTS `insert_cart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_cart` (IN `id_realestate` INT, IN `uid` VARCHAR(100))   BEGIN
	INSERT INTO `cart` (`date_time`, `id_realestate`, `uid`, `quantity`)
    VALUES (CURRENT_TIMESTAMP, id_realestate, uid, 1);
END$$

DROP PROCEDURE IF EXISTS `insert_purchase_log`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_purchase_log` (IN `uid` VARCHAR(100))   BEGIN
	INSERT INTO `purchase_log` (`date_time`, `id_realestate`, `uid`, `quantity`)
    SELECT CURRENT_TIMESTAMP, c.id_realestate, c.uid, c.quantity
    	FROM `cart` c
        WHERE c.uid = uid; 
END$$

DROP PROCEDURE IF EXISTS `update_cart`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_cart` (IN `id_realestate` INT, IN `uid` VARCHAR(100), IN `operation` VARCHAR(25))   BEGIN
	UPDATE `cart` c
		SET c.quantity = c.quantity + operation
		WHERE c.id_realestate = id_realestate AND c.uid = uid;
END$$

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

DROP PROCEDURE IF EXISTS `update_stock`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_stock` (IN `uid` VARCHAR(100))   BEGIN
    	UPDATE `real_estate` r
	        SET r.stock = r.stock - (SELECT c.quantity FROM `cart` c WHERE c.uid = uid AND c.id_realestate = r.id_realestate)
            WHERE r.id_realestate = (SELECT c.id_realestate FROM `cart` c WHERE c.uid = uid AND c.id_realestate = r.id_realestate);
END$$

DROP PROCEDURE IF EXISTS `update_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user` (IN `uid` VARCHAR(100), IN `username` VARCHAR(25), IN `email` VARCHAR(50), IN `phone` VARCHAR(12), IN `avatar` VARCHAR(200))   BEGIN
	IF username IS NULL OR username = "" THEN
    	UPDATE `user_local` u
			SET u.email = email, u.phone = phone, u.avatar = avatar
			WHERE u.uid = uid;
    ELSE
    	UPDATE `user_local` u
			SET u.username = username, u.email = email, u.phone = phone, u.avatar = avatar
			WHERE u.uid = uid;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `update_user2`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_user2` (IN `uid` VARCHAR(100), IN `new_username` VARCHAR(25), IN `new_email` VARCHAR(50), IN `new_phone` VARCHAR(12), IN `new_avatar` VARCHAR(200))   BEGIN
    UPDATE `user_local` u
    SET
        u.username = COALESCE(new_username, u.username),
        u.email = COALESCE(new_email, u.email),
        u.phone = COALESCE(new_phone, u.phone),
        u.avatar = COALESCE(new_avatar, u.avatar)
    WHERE
        u.uid = uid;
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
-- Estructura de tabla para la tabla `bill`
--

DROP TABLE IF EXISTS `bill`;
CREATE TABLE IF NOT EXISTS `bill` (
  `id_bill` int NOT NULL AUTO_INCREMENT,
  `uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_bill`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=10007 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `bill`
--

INSERT INTO `bill` (`id_bill`, `uid`, `date`) VALUES
(10000, '2', '2024-06-01'),
(10001, '3', '2024-06-02'),
(10002, '2', '2024-06-03'),
(10003, '2', '2024-06-04'),
(10004, '3', '2024-06-04'),
(10005, '8a2447f38f24', '2024-06-10'),
(10006, '2', '2024-06-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bill_detail`
--

DROP TABLE IF EXISTS `bill_detail`;
CREATE TABLE IF NOT EXISTS `bill_detail` (
  `id_line` int NOT NULL AUTO_INCREMENT,
  `id_bill` int NOT NULL,
  `id_realestate` int NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  PRIMARY KEY (`id_line`,`id_bill`),
  KEY `id_realestate` (`id_realestate`),
  KEY `bill_detail_ibfk_1` (`id_bill`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `bill_detail`
--

INSERT INTO `bill_detail` (`id_line`, `id_bill`, `id_realestate`, `quantity`, `price`) VALUES
(1, 10000, 3, 1, 200000),
(2, 10000, 7, 2, 42000),
(3, 10000, 12, 1, 375000),
(4, 10001, 2, 1, 80),
(5, 10001, 9, 1, 650),
(7, 10002, 3, 2, 200000),
(8, 10002, 4, 1, 225000),
(10, 10003, 3, 1, 200000),
(11, 10003, 5, 1, 1200000),
(12, 10003, 14, 2, 680),
(13, 10004, 1, 1, 250000),
(14, 10004, 3, 1, 200000),
(15, 10004, 7, 1, 42000),
(16, 10005, 6, 2, 50),
(17, 10005, 7, 2, 42000),
(18, 10005, 12, 1, 375000),
(19, 10006, 2, 4, 80),
(20, 10006, 4, 4, 225000),
(21, 10006, 10, 1, 265000),
(22, 10006, 11, 2, 599);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `date_time` datetime NOT NULL,
  `id_realestate` int NOT NULL,
  `uid` varchar(100) COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id_realestate`,`uid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `cart`
--

INSERT INTO `cart` (`date_time`, `id_realestate`, `uid`, `quantity`) VALUES
('2024-06-10 11:56:09', 3, '8a2447f38f24', 1),
('2024-06-11 16:33:15', 5, '2', 1),
('2024-06-11 16:33:25', 7, '2', 1),
('2024-06-04 11:03:04', 12, '3', 1),
('2024-06-04 11:02:49', 14, '3', 1);

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
(1, 'Obra nueva', 'obra_nueva.jpg'),
(2, 'A reformar', 'a_reformar.jpg'),
(3, 'De diseño', 'de_diseño.jpg'),
(4, 'De lujo', 'de_lujo.jpg'),
(5, 'En zona residencial', 'en_zona_residencial.jpg'),
(6, 'En la playa', 'en_la_playa.jpg'),
(7, 'En el campo', 'en_el_campo.jpg'),
(8, 'En la montaña', 'en_la_montaña.jpg');

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
(1, 'Albacete', 'Albacete', NULL, 'albacete.jpg'),
(2, 'Alicante', 'Alicante', 2, 'alicante.jpg'),
(3, 'Almería', 'Almería', 6, 'almeria.jpg'),
(4, 'Ávila', 'Ávila', NULL, 'avila.jpg'),
(5, 'Badajoz', 'Badajoz', NULL, 'badajoz.jpg'),
(6, 'Barcelona', 'Barcelona', NULL, 'barcelona.jpg'),
(7, 'Bilbao', 'Bizkaia', NULL, 'bilbao.jpg'),
(8, 'Burgos', 'Burgos', NULL, 'burgos.jpg'),
(9, 'Cáceres', 'Cáceres', NULL, 'caceres.jpg'),
(10, 'Cádiz', 'Cádiz', 9, 'cadiz.jpg'),
(11, 'Castellón de la Plana', 'Castellón', 8, 'castellon.jpg'),
(12, 'Ciudad Real', 'Ciudad Real', NULL, 'ciudad_real.jpg'),
(13, 'Córdoba', 'Córdoba', NULL, 'cordoba.jpg'),
(14, 'A Coruña', 'A Coruña', 17, 'a_coruña.jpg'),
(15, 'Cuenca', 'Cuenca', NULL, 'cuenca.jpg'),
(16, 'Girona', 'Girona', 3, 'girona.jpg'),
(17, 'Granada', 'Granada', NULL, 'granada.jpg'),
(18, 'Guadalajara', 'Guadalajara', NULL, 'guadalajara.jpg'),
(19, 'Huelva', 'Huelva', NULL, 'huelva.jpg'),
(20, 'Huesca', 'Huesca', NULL, 'huesca.jpg'),
(21, 'Jaén', 'Jaén', NULL, 'jaen.jpg'),
(22, 'León', 'León', NULL, 'leon.jpg'),
(23, 'Lleida', 'Lleida', NULL, 'lleida.jpg'),
(24, 'Logroño', 'La Rioja', NULL, 'logroño.jpg'),
(25, 'Lugo', 'Lugo', NULL, 'lugo.jpg'),
(26, 'Madrid', 'Madrid', NULL, 'madrid.jpg'),
(27, 'Málaga', 'Málaga', NULL, 'malaga.jpg'),
(28, 'Murcia', 'Murcia', NULL, 'murcia.jpg'),
(29, 'Ourense', 'Ourense', NULL, 'ourense.jpg'),
(30, 'Oviedo', 'Asturias', NULL, 'oviedo.jpg'),
(31, 'Palencia', 'Palencia', NULL, 'palencia.jpg'),
(32, 'Palma de Mallorca', 'Illes Balears', NULL, 'palma_de_mallorca.jpg'),
(33, 'Las Palmas de Gran Canaria', 'Las Palmas', NULL, 'las_palmas_de_gran_canaria.jpg'),
(34, 'Pamplona', 'Navarra', NULL, 'pamplona.jpg'),
(35, 'Pontevedra', 'Pontevedra', 18, 'pontevedra.jpg'),
(36, 'Salamanca', 'Salamanca', NULL, 'salamanca.jpg'),
(37, 'San Sebastián', 'Gipuzkoa', 14, 'san_sebastian.jpg'),
(38, 'Santa Cruz de Tenerife', 'Santa Cruz de Tenerife', NULL, 'santa_cruz_de_tenerife.jpg'),
(39, 'Santander', 'Cantabria', 7, 'santander.jpg'),
(40, 'Segovia', 'Segovia', NULL, 'segovia.jpg'),
(41, 'Sevilla', 'Sevilla', NULL, 'sevilla.jpg'),
(42, 'Soria', 'Soria', NULL, 'soria.jpg'),
(43, 'Tarragona', 'Tarragona', 12, 'tarragona.jpg'),
(44, 'Teruel', 'Teruel', NULL, 'teruel.jpg'),
(45, 'Toledo', 'Toledo', NULL, 'toledo.jpg'),
(46, 'Valencia', 'Valencia', 10, 'valencia.jpg'),
(47, 'Valladolid', 'Valladolid', NULL, 'valladolid.jpg'),
(48, 'Vitoria', 'Álava', NULL, 'vitoria.jpg'),
(49, 'Zamora', 'Zamora', NULL, 'zamora.jpg'),
(50, 'Zaragoza', 'Zaragoza', NULL, 'zaragoza.jpg'),
(51, 'Ceuta', 'Ceuta', NULL, 'ceuta.jpg'),
(52, 'Melilla', 'Melilla', NULL, 'melilla.jpg'),
(53, 'Canet de Mar', 'Barcelona', 1, 'canet_de_mar.jpg'),
(54, 'Castelldefels', 'Barcelona', 1, 'castelldefels.jpg'),
(55, 'Sitges', 'Barcelona', 1, 'sitges.jpg'),
(56, 'Altea', 'Alicante', 2, 'altea.jpg'),
(57, 'Benidorm', 'Alicante', 2, 'benidorm.jpg'),
(58, 'Torrevieja', 'Alicante', 2, 'torrevieja.jpg'),
(59, 'Lloret de Mar', 'Girona', 3, 'lloret_de_mar.jpg'),
(60, 'Águilas', 'Murcia', 4, 'aguilas.jpg'),
(61, 'Cartagena', 'Murcia', 4, 'cartagena.jpg'),
(62, 'Muros', 'A Coruña', 5, 'muros.jpg'),
(63, 'Muxía', 'A Coruña', 5, 'muxia.jpg'),
(64, 'Roquetas de Mar', 'Almería', 6, 'roquetas_de_mar.jpg'),
(65, 'San Vicente de la Barquera', 'Cantabria', 7, 'san_vicente_de_la_barquera.jpg'),
(66, 'Oropesa del Mar', 'Castellón', 8, 'oropesa_del_mar.jpg'),
(67, 'Peñíscola', 'Castellón', 8, 'penyiscola.jpg'),
(68, 'Vinarós', 'Castellón', 8, 'vinaros.jpg'),
(69, 'El Puerto de Santa María', 'Cádiz', 9, 'el_puerto_de_santa_maria.jpg'),
(70, 'Isla Cristina', 'Huelva', 9, 'isla_cristina.jpg'),
(71, 'Gandía', 'Valencia', 10, 'gandia.jpg'),
(72, 'Estepona', 'Málaga', 11, 'estepona.jpg'),
(73, 'Marbella', 'Málaga', 11, 'marbella.jpg'),
(74, 'Torremolinos', 'Málaga', 11, 'torremolinos.jpg'),
(75, 'Cambrils', 'Tarragona', 12, 'cambrils.jpg'),
(76, 'Salou', 'Tarragona', 12, 'salou.jpg'),
(77, 'Almuñecar', 'Granada', 13, 'almuñecar.jpg'),
(78, 'Motril', 'Granada', 13, 'motril.jpg'),
(79, 'Hondarríbia', 'Gipuzkoa', 14, 'hondarribia.jpg'),
(80, 'Gijón', 'Asturias', 15, 'gijon.jpg'),
(81, 'Ribadesella', 'Asturias', 15, 'ribadesella.jpg'),
(82, 'Ribadeo', 'Lugo', 16, 'ribadeo.jpg'),
(83, 'Viveiro', 'Lugo', 16, 'viveiro.jpg'),
(84, 'Ferrol', 'A Coruña', 17, 'ferrol.jpg'),
(85, 'Sanxenxo', 'Pontevedra', 18, 'sanxenxo.jpg'),
(86, 'Vigo', 'Pontevedra', 18, 'vigo.jpg');

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
(1, 'Ascensor', 'ascensor.png'),
(2, 'Parking', 'parking.png'),
(3, 'Trastero', 'trastero.png'),
(4, 'Balcón', 'balcon.png'),
(5, 'Terraza', 'terraza.png'),
(6, 'Piscina', 'piscina.png'),
(7, 'Jardín', 'jardin.png'),
(8, 'Amueblado', 'amueblado.png'),
(9, 'Sin amueblar', 'sin_amueblar.png'),
(10, 'Electrodomésticos', 'electrodomesticos.png'),
(11, 'Aire acondicionado', 'aire_acondicionado.png'),
(12, 'Calefacción', 'calefaccion.png');

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
(1, 'real_estate_1_1.jpg', 1),
(2, 'real_estate_1_2.jpg', 1),
(3, 'real_estate_1_3.jpg', 1),
(4, 'real_estate_1_4.jpg', 1),
(5, 'real_estate_2_1.jpg', 2),
(6, 'real_estate_2_2.jpg', 2),
(7, 'real_estate_2_3.jpg', 2),
(8, 'real_estate_2_4.jpg', 2),
(9, 'real_estate_3_1.jpg', 3),
(10, 'real_estate_3_2.jpg', 3),
(11, 'real_estate_3_3.jpg', 3),
(12, 'real_estate_3_4.jpg', 3),
(13, 'real_estate_4_1.jpg', 4),
(14, 'real_estate_4_2.jpg', 4),
(15, 'real_estate_4_3.jpg', 4),
(16, 'real_estate_4_4.jpg', 4),
(17, 'real_estate_5_1.jpg', 5),
(18, 'real_estate_5_2.jpg', 5),
(19, 'real_estate_5_3.jpg', 5),
(20, 'real_estate_5_4.jpg', 5),
(21, 'real_estate_6_1.jpg', 6),
(22, 'real_estate_6_2.jpg', 6),
(23, 'real_estate_6_3.jpg', 6),
(24, 'real_estate_6_4.jpg', 6),
(25, 'real_estate_7_1.jpg', 7),
(26, 'real_estate_7_2.jpg', 7),
(27, 'real_estate_7_3.jpg', 7),
(28, 'real_estate_7_4.jpg', 7),
(29, 'real_estate_8_1.jpg', 8),
(30, 'real_estate_8_2.jpg', 8),
(31, 'real_estate_8_3.jpg', 8),
(32, 'real_estate_8_4.jpg', 8),
(33, 'real_estate_9_1.jpg', 9),
(34, 'real_estate_9_2.jpg', 9),
(35, 'real_estate_9_3.jpg', 9),
(36, 'real_estate_9_4.jpg', 9),
(37, 'real_estate_10_1.jpg', 10),
(38, 'real_estate_10_2.jpg', 10),
(39, 'real_estate_10_3.jpg', 10),
(40, 'real_estate_10_4.jpg', 10),
(41, 'real_estate_10_5.jpg', 10),
(42, 'real_estate_10_6.jpg', 10),
(43, 'real_estate_10_7.jpg', 10),
(44, 'real_estate_11_1.jpg', 11),
(45, 'real_estate_11_2.jpg', 11),
(46, 'real_estate_11_3.jpg', 11),
(47, 'real_estate_11_4.jpg', 11),
(48, 'real_estate_11_5.jpg', 11),
(49, 'real_estate_11_6.jpg', 11),
(50, 'real_estate_12_1.jpg', 12),
(51, 'real_estate_12_2.jpg', 12),
(52, 'real_estate_12_3.jpg', 12),
(53, 'real_estate_12_4.jpg', 12),
(54, 'real_estate_12_5.jpg', 12),
(55, 'real_estate_12_6.jpg', 12),
(56, 'real_estate_12_7.jpg', 12),
(57, 'real_estate_12_8.jpg', 12),
(58, 'real_estate_12_9.jpg', 12),
(59, 'real_estate_12_10.jpg', 12),
(60, 'real_estate_12_11.jpg', 12),
(61, 'real_estate_13_1.jpg', 13),
(62, 'real_estate_13_2.jpg', 13),
(63, 'real_estate_13_3.jpg', 13),
(64, 'real_estate_13_4.jpg', 13),
(65, 'real_estate_13_5.jpg', 13),
(66, 'real_estate_13_6.jpg', 13),
(67, 'real_estate_13_7.jpg', 13),
(68, 'real_estate_13_8.jpg', 13),
(69, 'real_estate_13_9.jpg', 13),
(70, 'real_estate_13_10.jpg', 13),
(71, 'real_estate_14_1.jpg', 14),
(72, 'real_estate_14_2.jpg', 14),
(73, 'real_estate_14_3.jpg', 14),
(74, 'real_estate_14_4.jpg', 14),
(75, 'real_estate_14_5.jpg', 14),
(76, 'real_estate_14_6.jpg', 14),
(77, 'real_estate_14_7.jpg', 14),
(78, 'real_estate_14_8.jpg', 14);

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
(5, 1, 1200000),
(6, 2, 50),
(7, 1, 42000),
(8, 4, 200),
(9, 2, 650),
(10, 1, 265000),
(11, 2, 599),
(12, 1, 375000),
(13, 3, 550),
(14, 2, 680);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `like`
--

DROP TABLE IF EXISTS `like`;
CREATE TABLE IF NOT EXISTS `like` (
  `uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `id_realestate` int NOT NULL,
  PRIMARY KEY (`uid`,`id_realestate`),
  KEY `like_ibfk_1` (`id_realestate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `like`
--

INSERT INTO `like` (`uid`, `id_realestate`) VALUES
('2', 1),
('kXT9J3iHG7Zjosxk0zc5RDhreWG3', 1),
('2', 2),
('8a2447f38f24', 2),
('yqvEAax2vBfTehHsK3gpAIkcEbn1', 2),
('4', 3),
('yqvEAax2vBfTehHsK3gpAIkcEbn1', 3),
('3', 4),
('kXT9J3iHG7Zjosxk0zc5RDhreWG3', 4),
('2', 7),
('2', 9),
('3', 9),
('4', 9),
('3', 10),
('4', 11),
('8a2447f38f24', 11),
('kXT9J3iHG7Zjosxk0zc5RDhreWG3', 13),
('2', 14),
('3', 14),
('4', 14);

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
(1, 'Compra', 'compra.jpg'),
(2, 'Alquiler', 'alquiler.jpg'),
(3, 'Alquiler con opción a compra', 'alquiler_compra.jpg'),
(4, 'Compartir', 'compartir.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_log`
--

DROP TABLE IF EXISTS `purchase_log`;
CREATE TABLE IF NOT EXISTS `purchase_log` (
  `date_time` datetime NOT NULL,
  `id_realestate` int NOT NULL,
  `uid` varchar(100) COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `purchase_log`
--

INSERT INTO `purchase_log` (`date_time`, `id_realestate`, `uid`, `quantity`) VALUES
('2024-06-01 22:17:36', 3, '2', 1),
('2024-06-01 22:17:40', 7, '2', 2),
('2024-06-01 22:17:56', 12, '2', 1),
('2024-06-02 23:06:38', 2, '3', 1),
('2024-06-02 23:06:43', 9, '3', 1),
('2024-06-01 22:20:15', 3, '2', 2),
('2024-06-01 22:20:17', 4, '2', 1),
('2024-06-03 07:47:52', 3, '2', 1),
('2024-06-03 07:47:39', 5, '2', 1),
('2024-06-03 07:47:45', 14, '2', 2),
('2024-06-04 11:00:26', 1, '3', 1),
('2024-06-04 11:00:26', 3, '3', 1),
('2024-06-04 11:00:26', 7, '3', 1),
('2024-06-10 11:55:04', 6, '8a2447f38f24', 2),
('2024-06-10 11:55:04', 7, '8a2447f38f24', 2),
('2024-06-10 11:55:04', 12, '8a2447f38f24', 1),
('2024-06-10 21:45:19', 2, '2', 4),
('2024-06-10 21:45:19', 4, '2', 4),
('2024-06-10 21:45:19', 10, '2', 1),
('2024-06-10 21:45:19', 11, '2', 2);

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
  `stock` int NOT NULL,
  PRIMARY KEY (`id_realestate`),
  UNIQUE KEY `cadastre` (`cadastre`),
  KEY `city` (`id_city`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `real_estate`
--

INSERT INTO `real_estate` (`id_realestate`, `cadastre`, `lat`, `lng`, `id_city`, `area`, `rooms`, `bathrooms`, `floor`, `description`, `publication_date`, `visited`, `stock`) VALUES
(1, '7003051YJ4480S0045AA', 43.461647033691406, -3.819044351577759, 39, 150, 0, 1, 'Planta baja', 'Descubre el potencial de este local comercial a reformar en Santander. Con una ubicación estratégica que garantiza visibilidad y acceso, este espacio ofrece la oportunidad perfecta para dar vida a tu propio negocio. Transforma este local según tus necesidades.', '15-04-2017', 428, 3),
(2, '8204201YJ5210S0087BB', 41.38701164491393, 2.1701472867137825, 6, 15, 0, 0, 'Subsótano', 'Se alquila plaza de garaje en el corazón de Barcelona con acceso cómodo y directo. Esta plaza ofrece conveniencia y seguridad para tu vehículo en una de las zonas más céntricas de la ciudad. Aprovecha esta oportunidad y asegura tu espacio de estacionamiento.', '10-11-2023', 257, 0),
(3, '1234201YJ5210S0087CC', 37.38540018023119, -5.99179181508895, 41, 145, 0, 2, '2ª planta', 'Oportunidad única para establecer tu negocio en el corazón de Sevilla. Estas oficinas amuebladas ofrecen un entorno profesional y moderno con calefacción y aire acondicionado. Ubicación estratégica, mobiliario elegante y servicios esenciales hacen de este espacio la elección perfecta.', '22-07-2010', 301, 3),
(4, '6845201YJ5210S0087DD', 38.3589899089205, -0.4674039924655232, 2, 140, 3, 2, '4ª planta', 'Elegante y moderno dúplex en Alicante. Con un diseño contemporáneo y equipamiento de alta gama, este hogar ofrece una experiencia de vida excepcional. Disfruta de vistas impresionantes al mar que complementan a la perfección la calidad interior del espacio.', '27-12-2023', 315, 0),
(5, '2666201YJ5210S0087EE', 40.43789739994751, -3.632903906632869, 26, 500, 5, 4, '0', 'Esta casa excepcional, ubicada en una zona residencial de lujo en Madrid, redefine el concepto de elegancia y confort. Completamente equipada con detalle, esta propiedad ofrece un estilo de vida exclusivo. Disfruta de la privacidad y la sofisticación en cada rincón de esta residencia.', '02-12-2023', 508, 1),
(6, '9736201YJ5210S0087FF', 39.02725747002513, -3.9403544312211514, 12, 15, 0, 0, '4ª planta', 'Pon orden en tu vida con nuestro trastero. Ubicado en una zona de fácil acceso, este espacio ofrece comodidad y seguridad para almacenar tus pertenencias. Ya sea para liberar espacio en casa u organizar herramientas y equipos, este trastero es la solución perfecta.', '02-12-2023', 45, 3),
(7, '4852201YJ5210S0087GG', 42.36339348927319, -3.773002201902397, 8, 1000, 0, 0, '0', 'Descubre la serenidad de este amplio terreno, situado en una zona tranquila con vistas impresionantes. Disfruta de la amplitud, la privacidad y la belleza de un bonito jardín que complementa perfectamente el paisaje. Ideal para construir la casa de tus sueños.', '02-12-2023', 69, 5),
(8, '5928522YJ5210S0496HH', 39.48681669185842, -0.36291043111424803, 46, 110, 4, 2, '3ª planta', 'Este acogedor piso en Valencia ofrece la solución perfecta para estudiantes que buscan comodidad y tranquilidad. Con 4 habitaciones bien equipadas y ubicado en una zona serena. Cada habitación está disponible individualmente a un precio asequible.', '12-01-2024', 58, 3),
(9, '2470522YJ5210S0496XA', 37.418264266530805, -1.5616969248006018, 60, 75, 1, 1, '1ª planta', '¡Descubre el encanto del Mediterráneo en este apartamento frente a la playa en Águilas! Con vistas impresionantes y todas las comodidades necesarias, es el refugio perfecto para unas vacaciones inolvidables junto al mar.', '08-03-2020', 21, 3),
(10, '3582522YJ5210S0496BR', 41.26676629490557, 1.9674137631022395, 54, 85, 2, 1, '2ª planta', 'Apartamento frente a la playa en Castelldefels. Vistas al mar desde todas las habitaciones, terraza privada y acceso directo a la playa. Moderno y luminoso, perfecto para disfrutar del estilo de vida mediterráneo.', '09-05-2021', 844, 0),
(11, '7294522YJ5210S0496BZ', 37.19770063490664, -7.306096003374809, 70, 75, 1, 1, '1ª planta', 'Este encantador loft en Isla Cristina ofrece una experiencia costera incomparable, con vistas impresionantes del mar. Con un diseño moderno y acogedor, es perfecto para aquellos que buscan relajarse y disfrutar de las maravillas naturales de la costa.', '08-03-2018', 7, 0),
(12, '5922522YJ5210S0496GH', 41.69850900898505, 2.846702596383006, 59, 200, 4, 3, '0', 'Esta impresionante casa, ubicada a pocos pasos de la playa, ofrece un estilo de vida costero incomparable. Con amplios espacios interiores y todas las comodidades modernas, esta propiedad es perfecta para quienes buscan confort y lujo en un entorno relajado junto al mar.', '22-07-2022', 19, 5),
(13, '6578522YJ5210S0496UI', 43.326087675807315, -1.9719089752672998, 37, 145, 3, 2, 'Planta baja', 'Este amplio y luminoso apartamento te ofrece la oportunidad de disfrutar de la belleza del mar Cantábrico desde la comodidad de tu hogar. Situado en primera línea de playa, este apartamento es el refugio perfecto para relajarte y disfrutar de todo lo que San Sebastián tiene para ofrecer', '14-10-2021', 25, 0),
(14, '7856522YJ5210S0496CF', 41.12373402265875, 1.2872493152878055, 43, 140, 2, 1, '0', 'Esta encantadora casa, ubicada a pocos pasos de la playa, ofrece la combinación perfecta de comodidad y tranquilidad. Con una piscina reluciente, una amplia terraza para disfrutar del sol y un exuberante jardín, esta propiedad es un paraíso para los amantes del aire libre.', '05-08-2019', 60, 1);

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
(1, 'Costa Barcelona', 'costa_barcelona.jpg', 'mapa_costa_barcelona.png'),
(2, 'Costa Blanca', 'costa_blanca.jpg', 'mapa_costa_blanca.png'),
(3, 'Costa Brava', 'costa_brava.jpg', 'mapa_costa_brava.png'),
(4, 'Costa Cálida', 'costa_calida.jpg', 'mapa_costa_calida.png'),
(5, 'Costa da Morte', 'costa_da_morte.jpg', 'mapa_costa_da_morte.png'),
(6, 'Costa de Almería', 'costa_almeria.jpg', 'mapa_costa_almeria.png'),
(7, 'Costa de Cantabria', 'costa_cantabria.jpg', 'mapa_costa_cantabria.png'),
(8, 'Costa de Castellón', 'costa_castellon.jpg', 'mapa_costa_castellon.png'),
(9, 'Costa de la Luz', 'costa_de_la_luz.jpg', 'mapa_costa_de_la_luz.png'),
(10, 'Costa de Valencia', 'costa_valencia.jpg', 'mapa_costa_valencia.png'),
(11, 'Costa del Sol', 'costa_del_sol.jpg', 'mapa_costa_del_sol.png'),
(12, 'Costa Dorada', 'costa_dorada.jpg', 'mapa_costa_dorada.png'),
(13, 'Costa Tropical', 'costa_tropical.jpg', 'mapa_costa_tropical.png'),
(14, 'Costa Vasca', 'costa_vasca.jpg', 'mapa_costa_vasca.png'),
(15, 'Costa Verde', 'costa_verde.jpg', 'mapa_costa_verde.png'),
(16, 'Mariña Lucense', 'marinya_lucense.jpg', 'mapa_marinya_lucense.png'),
(17, 'Rías Altas', 'rias_altas.jpg', 'mapa_rias_altas.png'),
(18, 'Rías Baixas', 'rias_baixas.jpg', 'mapa_rias_baixas.png');

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
(1, 'Vivienda', 'Inmueble', 'vivienda.jpg'),
(2, 'Habitación', 'Inmueble', 'habitacion.jpg'),
(3, 'Garaje', 'Inmueble', 'garaje.jpg'),
(4, 'Trastero', 'Inmueble', 'trastero.jpg'),
(5, 'Oficina', 'Inmueble', 'oficina.jpg'),
(6, 'Local o nave', 'Inmueble', 'local_nave.jpg'),
(7, 'Terreno', 'Inmueble', 'terreno.jpg'),
(8, 'Edificio', 'Inmueble', 'edificio.jpg'),
(9, 'Apartamento', 'Piso', 'apartamento.jpg'),
(10, 'Ático', 'Piso', 'atico.jpg'),
(11, 'Dúplex', 'Piso', 'duplex.jpg'),
(12, 'Estudio', 'Piso', 'estudio.jpg'),
(13, 'Loft', 'Piso', 'loft.jpg'),
(14, 'Planta baja', 'Piso', 'planta_baja.jpg'),
(15, 'Planta intermedia', 'Piso', 'plantas_intermedias.jpg'),
(16, 'Casa', 'Casa', 'casa.jpg'),
(17, 'Casa adosada', 'Casa', 'casa_adosada.jpg'),
(18, 'Finca rústica', 'Casa', 'finca_rustica.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `type_user` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `provider` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `isActive` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`uid`, `type_user`, `provider`, `isActive`) VALUES
('1', 'admin', 'local', 'true'),
('2', 'client', 'local', 'true'),
('3', 'client', 'local', 'true'),
('4', 'client', 'local', 'true'),
('85d96a8e2733', 'client', 'local', 'true'),
('8a2447f38f24', 'client', 'local', 'true'),
('kXT9J3iHG7Zjosxk0zc5RDhreWG3', 'client', 'google.com', 'true'),
('yqvEAax2vBfTehHsK3gpAIkcEbn1', 'client', 'github.com', 'true');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_github.com`
--

DROP TABLE IF EXISTS `user_github.com`;
CREATE TABLE IF NOT EXISTS `user_github.com` (
  `uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `username` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `user_github.com`
--

INSERT INTO `user_github.com` (`uid`, `username`, `email`, `phone`, `avatar`) VALUES
('yqvEAax2vBfTehHsK3gpAIkcEbn1', 'f.montesdoria', 'f.montesdoria@gmail.com', '0', 'https://avatars.githubusercontent.com/u/157259692?v=4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_google.com`
--

DROP TABLE IF EXISTS `user_google.com`;
CREATE TABLE IF NOT EXISTS `user_google.com` (
  `uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `username` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `user_google.com`
--

INSERT INTO `user_google.com` (`uid`, `username`, `email`, `phone`, `avatar`) VALUES
('kXT9J3iHG7Zjosxk0zc5RDhreWG3', 'framondo', 'framondo@gmail.com', '0', 'https://lh3.googleusercontent.com/a/ACg8ocIMvfu5CqThupT8IbC0p0TpvkuhS_--mETr1QxO8hFjsbKWdw=s96-c');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_local`
--

DROP TABLE IF EXISTS `user_local`;
CREATE TABLE IF NOT EXISTS `user_local` (
  `uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `username` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `user_local`
--

INSERT INTO `user_local` (`uid`, `username`, `password`, `email`, `phone`, `avatar`) VALUES
('1', 'montes', '$2y$12$1Ux.AVRkbJYX/KhGQhR7gOkd7cZyVHeEDNOnGKPm1ibcGUvXHEOpa', 'framondo@gmail.com', '0', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=montes'),
('2', 'framondo', '$2y$12$oDMuVzJGM2DWy4yN.A10GOk5SFYyTD5Nnk8EIortEzJv/5ET/ptrS', 'framondo@hotmail.com', '+34625761313', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=framondo'),
('3', 'miguel', '$2y$12$HjfLgcfAZLpTcwvC4K5MaOZdPXvq9bnlhaBLIqe1O9EW7wLrdCmna', 'miguel@hotmail.com', '0', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=miguel'),
('4', 'laura', '$2y$12$aYcY6yKQiNOREGPkjeP8tOIXeBN0V7foeG/oo5mehXnAPLim/SKdC', 'laura@hotmail.com', '0', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=laura'),
('85d96a8e2733', 'alicia', '$2y$12$Mf3oOX2.z6uJ7nyiHEOkGOViEoMHqVyl3O.gCISFF65uJXQhE6N66', 'alcia@email.com', '+34625761313', '66688578333cb7.90180243.png'),
('8a2447f38f24', 'guido', '$2y$12$xJEvdIoPgs97GW2Ltz3W.uHhBor1w/U0DotvjHgx2EXgtkDyVhBwC', 'guido@hotmail.com', '0', 'https://api.dicebear.com/8.x/initials/svg?backgroundColor=2eca6a&size=40&scale=110&radius=50&seed=guido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_log`
--

DROP TABLE IF EXISTS `user_log`;
CREATE TABLE IF NOT EXISTS `user_log` (
  `uid` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci NOT NULL,
  `login_time` int NOT NULL,
  `login_attempts` int NOT NULL,
  `otp` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci DEFAULT NULL,
  `otp_attempts` int NOT NULL,
  `expired_token` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_es_trad_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_trad_0900_ai_ci;

--
-- Volcado de datos para la tabla `user_log`
--

INSERT INTO `user_log` (`uid`, `login_time`, `login_attempts`, `otp`, `otp_attempts`, `expired_token`) VALUES
('1', 0, 0, NULL, 0, NULL),
('2', 1716660865, 0, 'NULL', 0, 'NULL'),
('3', 0, 0, NULL, 0, NULL),
('4', 0, 0, NULL, 0, NULL),
('85d96a8e2733', 1718083759, 1, 'NULL', 0, 'NULL'),
('8a2447f38f24', 0, 0, NULL, 0, NULL),
('kXT9J3iHG7Zjosxk0zc5RDhreWG3', 0, 0, NULL, 0, NULL),
('yqvEAax2vBfTehHsK3gpAIkcEbn1', 0, 0, NULL, 0, NULL);

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
-- Filtros para la tabla `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Filtros para la tabla `bill_detail`
--
ALTER TABLE `bill_detail`
  ADD CONSTRAINT `bill_detail_ibfk_1` FOREIGN KEY (`id_bill`) REFERENCES `bill` (`id_bill`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `bill_detail_ibfk_2` FOREIGN KEY (`id_realestate`) REFERENCES `real_estate` (`id_realestate`);

--
-- Filtros para la tabla `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`id_realestate`) REFERENCES `real_estate` (`id_realestate`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

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
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Filtros para la tabla `real_estate`
--
ALTER TABLE `real_estate`
  ADD CONSTRAINT `real_estate_ibfk_2` FOREIGN KEY (`id_city`) REFERENCES `city` (`id_city`);

--
-- Filtros para la tabla `user_github.com`
--
ALTER TABLE `user_github.com`
  ADD CONSTRAINT `user_github.com_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Filtros para la tabla `user_google.com`
--
ALTER TABLE `user_google.com`
  ADD CONSTRAINT `user_google.com_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Filtros para la tabla `user_local`
--
ALTER TABLE `user_local`
  ADD CONSTRAINT `user_local_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);

--
-- Filtros para la tabla `user_log`
--
ALTER TABLE `user_log`
  ADD CONSTRAINT `user_log_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `user` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
