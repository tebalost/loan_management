-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.11-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for loan
CREATE DATABASE IF NOT EXISTS `loan` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `loan`;

-- Dumping structure for table loan.affordability_check
CREATE TABLE IF NOT EXISTS `affordability_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` varchar(100) DEFAULT NULL,
  `endpoint` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table loan.affordability_check: ~2 rows (approximately)
DELETE FROM `affordability_check`;
/*!40000 ALTER TABLE `affordability_check` DISABLE KEYS */;
INSERT INTO `affordability_check` (`id`, `provider`, `endpoint`, `username`, `password`, `status`) VALUES
	(2, 'CDAS', 'https://www.cdas.co.ls/api/', 'api.admin', 'admin', 'Active'),
	(3, 'Compuscan', 'https://webservices.compuscan.co.ls/NormalSearchStreamService?wsdl', '40140-2', 'PFSls@2020', 'Active');
/*!40000 ALTER TABLE `affordability_check` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
