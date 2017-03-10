-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.19-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table darby_blog.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dumping data for table darby_blog.roles: ~2 rows (approximately)
DELETE FROM `roles`;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` (`id`, `role_name`) VALUES
	(1, 'user'),
	(2, 'admin');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Dumping structure for table darby_blog.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `real_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass_hash` varchar(75) NOT NULL,
  `pass_salt` varchar(60) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_token` varchar(255) DEFAULT NULL,
  `session_token_expire` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Dumping data for table darby_blog.users: ~5 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `username`, `real_name`, `email`, `pass_hash`, `pass_salt`, `creation_date`, `session_token`, `session_token_expire`) VALUES
	(1, 'worminer@gmail.com', '', 'worminer@gmail.com', '$2y$10$b5a76120605fc83387fa1unSmpTA182ZXjTTOjFboAF1ZGbaYIf7K', 'b5a76120605fc83387fa15d71f4e9319', '2017-03-03 16:59:38', '36e6632e9dd1e7bbb92b853b5fc51a2c52bf06d6d5b28983be6fbbdde4c58a0c', 1489173105),
	(2, 'awe3@abv.bg', '', 'awe3@abv.bg', '$2y$10$83e626014ced840d68825uGdcBbtbxQy8wNDOkuUWnMLb2f9KRiyW', '83e626014ced840d68825462fabf233b', '2017-03-03 22:11:34', 'e96f4eadd5ef7ff32c7fd50052534963b94561fd8ab9472d9c743a860063a3f6', 1489167002),
	(3, 'awe2@abv.bg', '', 'awe2@abv.bg', '$2y$10$6621a392195c382375cd6uCcCp56rkUh08vFp1/C7CR.NhhbkUZB6', '6621a392195c382375cd6873960445ad', '2017-03-03 22:12:49', 'fe349d788878c8eb73cf2e56b6c70ff2ae8048898468b8a7797905c8f1f1ddcf', 1489173802),
	(4, 'awe55@abv.bg', '', 'awe55@abv.bg', '$2y$10$368d45143654769dc33b9eBadmfTAqRJLa.B5o2sPeKDH44XLDGNe', '368d45143654769dc33b9ee3aa14d989', '2017-03-07 17:25:41', NULL, 0),
	(5, 'awe44@abv.bg', '', 'awe44@abv.bg', '$2y$10$4675983f02877d2683ddfe39orqkbtpIKFanqMJ5rAk1w8k96rve6', '4675983f02877d2683ddff0d66021953', '2017-03-07 17:26:28', NULL, 0),
	(6, 'test@mail.com', '', 'test@mail.com', '$2y$10$5d2a869ab29b7ce6cb077uGmEM48cKWlJRr908/qnBT5xEqoQN.Wm', '5d2a869ab29b7ce6cb0776b77352e458', '2017-03-10 20:22:11', NULL, 0),
	(7, 'test22@abv.bg', '', 'test22@abv.bg', '$2y$10$066446815362224745d0duCCaRQJb6EXWrgp.ohmko8GIXkLFwGim', '066446815362224745d0d733f513cb7b', '2017-03-10 20:40:16', NULL, 0),
	(8, 'test1234@abv.bg', '', 'test1234@abv.bg', '$2y$10$f4eec972541f42403c166OhRXFqjSewwyJclj.30yTSDpk9Ps13He', 'f4eec972541f42403c166d6887738c97', '2017-03-10 20:41:05', NULL, 0),
	(9, '123123@avasd.asd', '', '123123@avasd.asd', '$2y$10$26db0480623ec550e68deefRsXyXbvKJ0xgx3Q2TCgiGQZTmUZwj6', '26db0480623ec550e68dee557387a8a2', '2017-03-10 20:42:11', NULL, 0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Dumping structure for table darby_blog.user_roles
CREATE TABLE IF NOT EXISTS `user_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `roles_id` (`role_id`),
  CONSTRAINT `roles_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- Dumping data for table darby_blog.user_roles: ~0 rows (approximately)
DELETE FROM `user_roles`;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` (`id`, `user_id`, `role_id`) VALUES
	(1, 1, 1),
	(2, 2, 1),
	(3, 3, 1),
	(4, 4, 1),
	(5, 5, 1),
	(6, 1, 2),
	(7, 2, 2);
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
