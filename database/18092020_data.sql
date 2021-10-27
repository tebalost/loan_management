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


-- Dumping structure for table sbseazyl_pfsdemo.aboutus
CREATE TABLE IF NOT EXISTS `aboutus` (
  `abid` int(11) NOT NULL AUTO_INCREMENT,
  `about` text NOT NULL,
  PRIMARY KEY (`abid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.aboutus: ~0 rows (approximately)
DELETE FROM `aboutus`;
/*!40000 ALTER TABLE `aboutus` DISABLE KEYS */;
/*!40000 ALTER TABLE `aboutus` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.additional_fees
CREATE TABLE IF NOT EXISTS `additional_fees` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `fee` varchar(200) NOT NULL,
  `Amount` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.additional_fees: ~0 rows (approximately)
DELETE FROM `additional_fees`;
/*!40000 ALTER TABLE `additional_fees` DISABLE KEYS */;
/*!40000 ALTER TABLE `additional_fees` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.affordability_check
CREATE TABLE IF NOT EXISTS `affordability_check` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` varchar(100) DEFAULT NULL,
  `endpoint` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.affordability_check: ~0 rows (approximately)
DELETE FROM `affordability_check`;
/*!40000 ALTER TABLE `affordability_check` DISABLE KEYS */;
INSERT INTO `affordability_check` (`id`, `provider`, `endpoint`, `username`, `password`, `status`) VALUES
	(1, 'CDAS', 'https://www.cdas.co.ls/api/', 'api.admin', 'admin', 'Active');
/*!40000 ALTER TABLE `affordability_check` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.attachment
CREATE TABLE IF NOT EXISTS `attachment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `attached_file` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.attachment: ~0 rows (approximately)
DELETE FROM `attachment`;
/*!40000 ALTER TABLE `attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `attachment` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.backup
CREATE TABLE IF NOT EXISTS `backup` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `tracking_id` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.backup: ~4 rows (approximately)
DELETE FROM `backup`;
/*!40000 ALTER TABLE `backup` DISABLE KEYS */;
INSERT INTO `backup` (`id`, `tracking_id`, `amount`, `address`, `date_time`) VALUES
	(10, 'Cryptos?rid=782752', '0.1', '134N7BmQZHSj2WU7kUaN8fFada32GpBXbg', '2017-04-03 16:37:40'),
	(11, 'Cryptos?rid=782752', '0.1', '134N7BmQZHSj2WU7kUaN8fFada32GpBXbg', '2017-04-03 17:14:12'),
	(15, 'Cryptos?rid=782752', '0.1', '134N7BmQZHSj2WU7kUaN8fFada32GpBXbg', '2017-04-03 18:30:28'),
	(18, 'Cryptos?rid=782752', '0.15', '134N7BmQZHSj2WU7kUaN8fFada32GpBXbg', '2017-04-03 19:59:36');
/*!40000 ALTER TABLE `backup` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.banner
CREATE TABLE IF NOT EXISTS `banner` (
  `banaid` int(11) NOT NULL AUTO_INCREMENT,
  `bannar` text NOT NULL,
  PRIMARY KEY (`banaid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.banner: ~0 rows (approximately)
DELETE FROM `banner`;
/*!40000 ALTER TABLE `banner` DISABLE KEYS */;
INSERT INTO `banner` (`banaid`, `bannar`) VALUES
	(3, 'bannar/sld2.jpg');
/*!40000 ALTER TABLE `banner` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.battachment
CREATE TABLE IF NOT EXISTS `battachment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `attached_file` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `document_type` varchar(50) DEFAULT NULL,
  `file_size` varchar(50) DEFAULT NULL,
  `file_ext` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=235 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.battachment: ~0 rows (approximately)
DELETE FROM `battachment`;
/*!40000 ALTER TABLE `battachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `battachment` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.borrowers
CREATE TABLE IF NOT EXISTS `borrowers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(200) NOT NULL,
  `lname` varchar(200) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `phone` varchar(200) DEFAULT NULL,
  `addrs1` text NOT NULL,
  `addrs2` text NOT NULL,
  `district` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `comment` text DEFAULT NULL,
  `account` varchar(200) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(200) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `id_number` varchar(25) DEFAULT NULL,
  `passport` varchar(50) DEFAULT NULL,
  `credit_score` varchar(25) DEFAULT NULL,
  `employment_status` varchar(25) DEFAULT NULL,
  `employer` varchar(50) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `modified_by` varchar(50) NOT NULL,
  `balance` double(16,2) NOT NULL DEFAULT 0.00,
  `emp_code` varchar(50) DEFAULT NULL,
  `salary` double(16,2) DEFAULT NULL,
  `disposable_income` double(16,2) DEFAULT NULL,
  `occupation` varchar(50) DEFAULT NULL,
  `modified_on` timestamp NULL DEFAULT NULL,
  `postal` int(11) DEFAULT NULL,
  `ownershipType` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Index 2` (`employer`,`emp_code`)
) ENGINE=InnoDB AUTO_INCREMENT=287 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.borrowers: ~0 rows (approximately)
DELETE FROM `borrowers`;
/*!40000 ALTER TABLE `borrowers` DISABLE KEYS */;
/*!40000 ALTER TABLE `borrowers` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.branches
CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `sub_account` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `BranchCode_NAME` (`sub_account`,`name`,`code`,`location`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.branches: ~0 rows (approximately)
DELETE FROM `branches`;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.bureau_submissions
CREATE TABLE IF NOT EXISTS `bureau_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `batch` int(11) DEFAULT NULL,
  `loan_records` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Scheduled',
  `action_date` date DEFAULT NULL,
  `action_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Unique_Day_Of_Month` (`action_date`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.bureau_submissions: ~0 rows (approximately)
DELETE FROM `bureau_submissions`;
/*!40000 ALTER TABLE `bureau_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `bureau_submissions` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.collateral
CREATE TABLE IF NOT EXISTS `collateral` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `idm` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type_of_collateral` varchar(200) NOT NULL,
  `model` varchar(200) NOT NULL,
  `make` varchar(200) NOT NULL,
  `serial_number` varchar(200) NOT NULL,
  `estimated_price` varchar(200) NOT NULL,
  `proof_of_ownership` text NOT NULL,
  `cimage` text NOT NULL,
  `observation` text NOT NULL,
  `loan` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.collateral: ~0 rows (approximately)
DELETE FROM `collateral`;
/*!40000 ALTER TABLE `collateral` DISABLE KEYS */;
/*!40000 ALTER TABLE `collateral` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(55) NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account` varchar(100) NOT NULL,
  `customer` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.comments: ~0 rows (approximately)
DELETE FROM `comments`;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.countries
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `alpha_2` varchar(200) NOT NULL DEFAULT '',
  `alpha_3` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=250 DEFAULT CHARSET=utf8;

-- Dumping data for table sbseazyl_pfsdemo.countries: 249 rows
DELETE FROM `countries`;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` (`id`, `name`, `alpha_2`, `alpha_3`) VALUES
	(1, 'Afghanistan', 'fl', 'afg'),
	(2, 'Aland Islands', 'ax', 'ala'),
	(3, 'Albania', 'al', 'alb'),
	(4, 'Algeria', 'dz', 'dza'),
	(5, 'American Samoa', 'as', 'asm'),
	(6, 'Andorra', 'ad', 'and'),
	(7, 'Angola', 'ao', 'ago'),
	(8, 'Anguilla', 'ai', 'aia'),
	(9, 'Antarctica', 'aq', 'ata'),
	(10, 'Antigua and Barbuda', 'ag', 'atg'),
	(11, 'Argentina', 'ar', 'arg'),
	(12, 'Armenia', 'am', 'arm'),
	(13, 'Aruba', 'aw', 'abw'),
	(14, 'Australia', 'au', 'aus'),
	(15, 'Austria', 'at', 'aut'),
	(16, 'Azerbaijan', 'az', 'aze'),
	(17, 'Bahamas', 'bs', 'bhs'),
	(18, 'Bahrain', 'bh', 'bhr'),
	(19, 'Bangladesh', 'bd', 'bgd'),
	(20, 'Barbados', 'bb', 'brb'),
	(21, 'Belarus', 'by', 'blr'),
	(22, 'Belgium', 'be', 'bel'),
	(23, 'Belize', 'bz', 'blz'),
	(24, 'Benin', 'bj', 'ben'),
	(25, 'Bermuda', 'bm', 'bmu'),
	(26, 'Bhutan', 'bt', 'btn'),
	(27, 'Bolivia, Plurinational State of', 'bo', 'bol'),
	(28, 'Bonaire, Sint Eustatius and Saba', 'bq', 'bes'),
	(29, 'Bosnia and Herzegovina', 'ba', 'bih'),
	(30, 'Botswana', 'bw', 'bwa'),
	(31, 'Bouvet Island', 'bv', 'bvt'),
	(32, 'Brazil', 'br', 'bra'),
	(33, 'British Indian Ocean Territory', 'io', 'iot'),
	(34, 'Brunei Darussalam', 'bn', 'brn'),
	(35, 'Bulgaria', 'bg', 'bgr'),
	(36, 'Burkina Faso', 'bf', 'bfa'),
	(37, 'Burundi', 'bi', 'bdi'),
	(38, 'Cambodia', 'kh', 'khm'),
	(39, 'Cameroon', 'cm', 'cmr'),
	(40, 'Canada', 'ca', 'can'),
	(41, 'Cape Verde', 'cv', 'cpv'),
	(42, 'Cayman Islands', 'ky', 'cym'),
	(43, 'Central African Republic', 'cf', 'caf'),
	(44, 'Chad', 'td', 'tcd'),
	(45, 'Chile', 'cl', 'chl'),
	(46, 'China', 'cn', 'chn'),
	(47, 'Christmas Island', 'cx', 'cxr'),
	(48, 'Cocos (Keeling) Islands', 'cc', 'cck'),
	(49, 'Colombia', 'co', 'col'),
	(50, 'Comoros', 'km', 'com'),
	(51, 'Congo', 'cg', 'cog'),
	(52, 'Congo, The Democratic Republic of the', 'cd', 'cod'),
	(53, 'Cook Islands', 'ck', 'cok'),
	(54, 'Costa Rica', 'cr', 'cri'),
	(55, 'Cote d\'Ivoire', 'ci', 'civ'),
	(56, 'Croatia', 'hr', 'hrv'),
	(57, 'Cuba', 'cu', 'cub'),
	(58, 'Curacao', 'cw', 'cuw'),
	(59, 'Cyprus', 'cy', 'cyp'),
	(60, 'Czech Republic', 'cz', 'cze'),
	(61, 'Denmark', 'dk', 'dnk'),
	(62, 'Djibouti', 'dj', 'dji'),
	(63, 'Dominica', 'dm', 'dma'),
	(64, 'Dominican Republic', 'do', 'dom'),
	(65, 'Ecuador', 'ec', 'ecu'),
	(66, 'Egypt', 'eg', 'egy'),
	(67, 'El Salvador', 'sv', 'slv'),
	(68, 'Equatorial Guinea', 'gq', 'gnq'),
	(69, 'Eritrea', 'er', 'eri'),
	(70, 'Estonia', 'ee', 'est'),
	(71, 'Ethiopia', 'et', 'eth'),
	(72, 'Falkland Islands (Malvinas)', 'fk', 'flk'),
	(73, 'Faroe Islands', 'fo', 'fro'),
	(74, 'Fiji', 'fj', 'fji'),
	(75, 'Finland', 'fi', 'fin'),
	(76, 'France', 'fr', 'fra'),
	(77, 'French Guiana', 'gf', 'guf'),
	(78, 'French Polynesia', 'pf', 'pyf'),
	(79, 'French Southern Territories', 'tf', 'atf'),
	(80, 'Gabon', 'ga', 'gab'),
	(81, 'Gambia', 'gm', 'gmb'),
	(82, 'Georgia', 'ge', 'geo'),
	(83, 'Germany', 'de', 'deu'),
	(84, 'Ghana', 'gh', 'gha'),
	(85, 'Gibraltar', 'gi', 'gib'),
	(86, 'Greece', 'gr', 'grc'),
	(87, 'Greenland', 'gl', 'grl'),
	(88, 'Grenada', 'gd', 'grd'),
	(89, 'Guadeloupe', 'gp', 'glp'),
	(90, 'Guam', 'gu', 'gum'),
	(91, 'Guatemala', 'gt', 'gtm'),
	(92, 'Guernsey', 'gg', 'ggy'),
	(93, 'Guinea', 'gn', 'gin'),
	(94, 'Guinea-Bissau', 'gw', 'gnb'),
	(95, 'Guyana', 'gy', 'guy'),
	(96, 'Haiti', 'ht', 'hti'),
	(97, 'Heard Island and McDonald Islands', 'hm', 'hmd'),
	(98, 'Holy See (Vatican City State)', 'va', 'vat'),
	(99, 'Honduras', 'hn', 'hnd'),
	(100, 'Hong Kong', 'hk', 'hkg'),
	(101, 'Hungary', 'hu', 'hun'),
	(102, 'Iceland', 'is', 'isl'),
	(103, 'India', 'in', 'ind'),
	(104, 'Indonesia', 'id', 'idn'),
	(105, 'Iran, Islamic Republic of', 'ir', 'irn'),
	(106, 'Iraq', 'iq', 'irq'),
	(107, 'Ireland', 'ie', 'irl'),
	(108, 'Isle of Man', 'im', 'imn'),
	(109, 'Israel', 'il', 'isr'),
	(110, 'Italy', 'it', 'ita'),
	(111, 'Jamaica', 'jm', 'jam'),
	(112, 'Japan', 'jp', 'jpn'),
	(113, 'Jersey', 'je', 'jey'),
	(114, 'Jordan', 'jo', 'jor'),
	(115, 'Kazakhstan', 'kz', 'kaz'),
	(116, 'Kenya', 'ke', 'ken'),
	(117, 'Kiribati', 'ki', 'kir'),
	(118, 'Korea, Democratic People\'s Republic of', 'kp', 'prk'),
	(119, 'Korea, Republic of', 'kr', 'kor'),
	(120, 'Kuwait', 'kw', 'kwt'),
	(121, 'Kyrgyzstan', 'kg', 'kgz'),
	(122, 'Lao People\'s Democratic Republic', 'la', 'lao'),
	(123, 'Latvia', 'lv', 'lva'),
	(124, 'Lebanon', 'lb', 'lbn'),
	(125, 'Lesotho', 'ls', 'lso'),
	(126, 'Liberia', 'lr', 'lbr'),
	(127, 'Libyan Arab Jamahiriya', 'ly', 'lby'),
	(128, 'Liechtenstein', 'li', 'lie'),
	(129, 'Lithuania', 'lt', 'ltu'),
	(130, 'Luxembourg', 'lu', 'lux'),
	(131, 'Macao', 'mo', 'mac'),
	(132, 'Macedonia, The former Yugoslav Republic of', 'mk', 'mkd'),
	(133, 'Madagascar', 'mg', 'mdg'),
	(134, 'Malawi', 'mw', 'mwi'),
	(135, 'Malaysia', 'my', 'mys'),
	(136, 'Maldives', 'mv', 'mdv'),
	(137, 'Mali', 'ml', 'mli'),
	(138, 'Malta', 'mt', 'mlt'),
	(139, 'Marshall Islands', 'mh', 'mhl'),
	(140, 'Martinique', 'mq', 'mtq'),
	(141, 'Mauritania', 'mr', 'mrt'),
	(142, 'Mauritius', 'mu', 'mus'),
	(143, 'Mayotte', 'yt', 'myt'),
	(144, 'Mexico', 'mx', 'mex'),
	(145, 'Micronesia, Federated States of', 'fm', 'fsm'),
	(146, 'Moldova, Republic of', 'md', 'mda'),
	(147, 'Monaco', 'mc', 'mco'),
	(148, 'Mongolia', 'mn', 'mng'),
	(149, 'Montenegro', 'me', 'mne'),
	(150, 'Montserrat', 'ms', 'msr'),
	(151, 'Morocco', 'ma', 'mar'),
	(152, 'Mozambique', 'mz', 'moz'),
	(153, 'Myanmar', 'mm', 'mmr'),
	(154, 'Namibia', 'na', 'nam'),
	(155, 'Nauru', 'nr', 'nru'),
	(156, 'Nepal', 'np', 'npl'),
	(157, 'Netherlands', 'nl', 'nld'),
	(158, 'New Caledonia', 'nc', 'ncl'),
	(159, 'New Zealand', 'nz', 'nzl'),
	(160, 'Nicaragua', 'ni', 'nic'),
	(161, 'Niger', 'ne', 'ner'),
	(162, 'Nigeria', 'ng', 'nga'),
	(163, 'Niue', 'nu', 'niu'),
	(164, 'Norfolk Island', 'nf', 'nfk'),
	(165, 'Northern Mariana Islands', 'mp', 'mnp'),
	(166, 'Norway', 'no', 'nor'),
	(167, 'Oman', 'om', 'omn'),
	(168, 'Pakistan', 'pk', 'pak'),
	(169, 'Palau', 'pw', 'plw'),
	(170, 'Palestinian Territory, Occupied', 'ps', 'pse'),
	(171, 'Panama', 'pa', 'pan'),
	(172, 'Papua New Guinea', 'pg', 'png'),
	(173, 'Paraguay', 'py', 'pry'),
	(174, 'Peru', 'pe', 'per'),
	(175, 'Philippines', 'ph', 'phl'),
	(176, 'Pitcairn', 'pn', 'pcn'),
	(177, 'Poland', 'pl', 'pol'),
	(178, 'Portugal', 'pt', 'prt'),
	(179, 'Puerto Rico', 'pr', 'pri'),
	(180, 'Qatar', 'qa', 'qat'),
	(181, 'Reunion', 're', 'reu'),
	(182, 'Romania', 'ro', 'rou'),
	(183, 'Russian Federation', 'ru', 'rus'),
	(184, 'Rwanda', 'rw', 'rwa'),
	(185, 'Saint Barthelemy', 'bl', 'blm'),
	(186, 'Saint Helena, Ascension and Tristan Da Cunha', 'sh', 'shn'),
	(187, 'Saint Kitts and Nevis', 'kn', 'kna'),
	(188, 'Saint Lucia', 'lc', 'lca'),
	(189, 'Saint Martin (French Part)', 'mf', 'maf'),
	(190, 'Saint Pierre and Miquelon', 'pm', 'spm'),
	(191, 'Saint Vincent and The Grenadines', 'vc', 'vct'),
	(192, 'Samoa', 'ws', 'wsm'),
	(193, 'San Marino', 'sm', 'smr'),
	(194, 'Sao Tome and Principe', 'st', 'stp'),
	(195, 'Saudi Arabia', 'sa', 'sau'),
	(196, 'Senegal', 'sn', 'sen'),
	(197, 'Serbia', 'rs', 'srb'),
	(198, 'Seychelles', 'sc', 'syc'),
	(199, 'Sierra Leone', 'sl', 'sle'),
	(200, 'Singapore', 'sg', 'sgp'),
	(201, 'Sint Maarten (Dutch Part)', 'sx', 'sxm'),
	(202, 'Slovakia', 'sk', 'svk'),
	(203, 'Slovenia', 'si', 'svn'),
	(204, 'Solomon Islands', 'sb', 'slb'),
	(205, 'Somalia', 'so', 'som'),
	(206, 'South Africa', 'za', 'zaf'),
	(207, 'South Georgia and The South Sandwich Islands', 'gs', 'sgs'),
	(208, 'South Sudan', 'ss', 'ssd'),
	(209, 'Spain', 'es', 'esp'),
	(210, 'Sri Lanka', 'lk', 'lka'),
	(211, 'Sudan', 'sd', 'sdn'),
	(212, 'Suriname', 'sr', 'sur'),
	(213, 'Svalbard and Jan Mayen', 'sj', 'sjm'),
	(214, 'Swaziland', 'sz', 'swz'),
	(215, 'Sweden', 'se', 'swe'),
	(216, 'Switzerland', 'ch', 'che'),
	(217, 'Syrian Arab Republic', 'sy', 'syr'),
	(218, 'Taiwan, Province of China', 'tw', 'twn'),
	(219, 'Tajikistan', 'tj', 'tjk'),
	(220, 'Tanzania, United Republic of', 'tz', 'tza'),
	(221, 'Thailand', 'th', 'tha'),
	(222, 'Timor-Leste', 'tl', 'tls'),
	(223, 'Togo', 'tg', 'tgo'),
	(224, 'Tokelau', 'tk', 'tkl'),
	(225, 'Tonga', 'to', 'ton'),
	(226, 'Trinidad and Tobago', 'tt', 'tto'),
	(227, 'Tunisia', 'tn', 'tun'),
	(228, 'Turkey', 'tr', 'tur'),
	(229, 'Turkmenistan', 'tm', 'tkm'),
	(230, 'Turks and Caicos Islands', 'tc', 'tca'),
	(231, 'Tuvalu', 'tv', 'tuv'),
	(232, 'Uganda', 'ug', 'uga'),
	(233, 'Ukraine', 'ua', 'ukr'),
	(234, 'United Arab Emirates', 'ae', 'are'),
	(235, 'United Kingdom', 'gb', 'gbr'),
	(236, 'United States', 'us', 'usa'),
	(237, 'United States Minor Outlying Islands', 'um', 'umi'),
	(238, 'Uruguay', 'uy', 'ury'),
	(239, 'Uzbekistan', 'uz', 'uzb'),
	(240, 'Vanuatu', 'vu', 'vut'),
	(241, 'Venezuela, Bolivarian Republic of', 've', 'ven'),
	(242, 'Viet Nam', 'vn', 'vnm'),
	(243, 'Virgin Islands, British', 'vg', 'vgb'),
	(244, 'Virgin Islands, U.S.', 'vi', 'vir'),
	(245, 'Wallis and Futuna', 'wf', 'wlf'),
	(246, 'Western Sahara', 'eh', 'esh'),
	(247, 'Yemen', 'ye', 'yem'),
	(248, 'Zambia', 'zm', 'zmb'),
	(249, 'Zimbabwe', 'zw', 'zwe');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.documents_required
CREATE TABLE IF NOT EXISTS `documents_required` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.documents_required: ~0 rows (approximately)
DELETE FROM `documents_required`;
/*!40000 ALTER TABLE `documents_required` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents_required` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.emp_permission
CREATE TABLE IF NOT EXISTS `emp_permission` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tid` varchar(200) NOT NULL,
  `module_name` varchar(350) NOT NULL,
  `pcreate` varchar(20) NOT NULL,
  `pread` varchar(20) NOT NULL,
  `pupdate` varchar(20) NOT NULL,
  `pdelete` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=310 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.emp_permission: ~35 rows (approximately)
DELETE FROM `emp_permission`;
/*!40000 ALTER TABLE `emp_permission` DISABLE KEYS */;
INSERT INTO `emp_permission` (`id`, `tid`, `module_name`, `pcreate`, `pread`, `pupdate`, `pdelete`) VALUES
	(239, 'Loan=21319585', 'Email Panel', '1', '0', '0', '0'),
	(240, 'Loan=21319585', 'Borrower Details', '0', '0', '0', '0'),
	(241, 'Loan=21319585', 'Employee Wallet', '0', '0', '0', '0'),
	(242, 'Loan=21319585', 'Loan Details', '0', '0', '0', '0'),
	(243, 'Loan=21319585', 'Internal Message', '0', '0', '0', '0'),
	(244, 'Loan=21319585', 'Missed Payment', '0', '0', '0', '0'),
	(245, 'Loan=21319585', 'Payment', '0', '0', '0', '0'),
	(246, 'Loan=21319585', 'Employee Details', '0', '0', '0', '0'),
	(247, 'Loan=21319585', 'Module Permission', '0', '0', '0', '0'),
	(248, 'Loan=21319585', 'Savings Account', '0', '0', '0', '0'),
	(249, 'Loan=21319585', 'General Settings', '0', '0', '0', '0'),
	(286, 'Loan=21319580', 'Email Panel', '1', '1', '1', '1'),
	(287, 'Loan=21319580', 'Borrower Details', '1', '1', '1', '1'),
	(288, 'Loan=21319580', 'Employee Wallet', '1', '1', '1', '1'),
	(289, 'Loan=21319580', 'Loan Details', '1', '1', '1', '1'),
	(290, 'Loan=21319580', 'Internal Message', '1', '1', '1', '1'),
	(291, 'Loan=21319580', 'Missed Payment', '1', '1', '1', '1'),
	(292, 'Loan=21319580', 'Payment', '1', '1', '1', '1'),
	(293, 'Loan=21319580', 'Employee Details', '1', '1', '1', '1'),
	(294, 'Loan=21319580', 'Module Permission', '1', '1', '1', '1'),
	(295, 'Loan=21319580', 'Savings Account', '1', '1', '1', '1'),
	(296, 'Loan=21319580', 'General Settings', '1', '1', '1', '1'),
	(297, 'Loan=21319580', 'Loans Approval', '1', '1', '1', '1'),
	(298, 'Loan=165567234', 'Email Panel', '1', '1', '1', '1'),
	(299, 'Loan=165567234', 'Borrower Details', '1', '1', '1', '1'),
	(300, 'Loan=165567234', 'Employee Wallet', '1', '1', '1', '1'),
	(301, 'Loan=165567234', 'Loan Details', '1', '1', '1', '1'),
	(302, 'Loan=165567234', 'Internal Message', '1', '1', '1', '1'),
	(303, 'Loan=165567234', 'Missed Payment', '1', '1', '1', '1'),
	(304, 'Loan=165567234', 'Payment', '1', '1', '1', '1'),
	(305, 'Loan=165567234', 'Employee Details', '1', '1', '1', '1'),
	(306, 'Loan=165567234', 'Module Permission', '1', '1', '1', '1'),
	(307, 'Loan=165567234', 'Savings Account', '1', '1', '1', '1'),
	(308, 'Loan=165567234', 'General Settings', '1', '1', '1', '1'),
	(309, 'Loan=165567234', 'Loans Approval', '1', '0', '0', '0');
/*!40000 ALTER TABLE `emp_permission` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.emp_role
CREATE TABLE IF NOT EXISTS `emp_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.emp_role: ~0 rows (approximately)
DELETE FROM `emp_role`;
/*!40000 ALTER TABLE `emp_role` DISABLE KEYS */;
/*!40000 ALTER TABLE `emp_role` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.etemplates
CREATE TABLE IF NOT EXISTS `etemplates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender` varchar(200) NOT NULL,
  `receiver_email` varchar(350) NOT NULL,
  `subject` varchar(350) NOT NULL,
  `msg` text NOT NULL,
  `time_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.etemplates: ~0 rows (approximately)
DELETE FROM `etemplates`;
/*!40000 ALTER TABLE `etemplates` DISABLE KEYS */;
/*!40000 ALTER TABLE `etemplates` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.faqs
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.faqs: ~0 rows (approximately)
DELETE FROM `faqs`;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` (`id`, `topic`, `content`) VALUES
	(1, 'Please type the subject here', '<p>Please Update Faqs Here</p>\r\n');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.fin_info
CREATE TABLE IF NOT EXISTS `fin_info` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `occupation` varchar(25) NOT NULL DEFAULT '',
  `mincome` varchar(200) NOT NULL,
  `frequency` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `get_id_tid_occupation_mincome` (`get_id`,`tid`,`occupation`,`mincome`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.fin_info: ~0 rows (approximately)
DELETE FROM `fin_info`;
/*!40000 ALTER TABLE `fin_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `fin_info` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.footer
CREATE TABLE IF NOT EXISTS `footer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) NOT NULL,
  `pho` varchar(200) NOT NULL,
  `face` varchar(200) NOT NULL,
  `webs` varchar(200) NOT NULL,
  `conh` varchar(200) NOT NULL,
  `twi` varchar(200) NOT NULL,
  `gplus` varchar(200) NOT NULL,
  `ins` varchar(200) NOT NULL,
  `yous` varchar(200) NOT NULL,
  `about` text NOT NULL,
  `apply` text NOT NULL,
  `mission` text NOT NULL,
  `objective` text NOT NULL,
  `map` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.footer: ~0 rows (approximately)
DELETE FROM `footer`;
/*!40000 ALTER TABLE `footer` DISABLE KEYS */;
INSERT INTO `footer` (`id`, `email`, `pho`, `face`, `webs`, `conh`, `twi`, `gplus`, `ins`, `yous`, `about`, `apply`, `mission`, `objective`, `map`) VALUES
	(2, 'info@bequesters.com', '+233808883675466', 'www.facebook.com/info@bequesters', 'www.bequesters.com', 'Lasvegas USA', 'www.twitter.com/info@bequesters', 'www.googleplus.com/oinfo@bequesters', 'www.in.com/info@bequesters', 'www.youtube.com/info@bequesters', 'About the system here. Thanks, We are just testing the software and we discover that the software is errors free. Thanks once again.', 'Who may apply here. Thabnks', 'Mission here. Thanks', 'System OBJECTIVE HERE. Thanks', '');
/*!40000 ALTER TABLE `footer` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.hiw
CREATE TABLE IF NOT EXISTS `hiw` (
  `hid` int(11) NOT NULL AUTO_INCREMENT,
  `hiw` text NOT NULL,
  PRIMARY KEY (`hid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.hiw: ~0 rows (approximately)
DELETE FROM `hiw`;
/*!40000 ALTER TABLE `hiw` DISABLE KEYS */;
INSERT INTO `hiw` (`hid`, `hiw`) VALUES
	(1, '<p>We Provide Loans For Individual, Coperate and Many</p>\r\n');
/*!40000 ALTER TABLE `hiw` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.loanfees
CREATE TABLE IF NOT EXISTS `loanfees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_fees` blob DEFAULT NULL,
  `insurance_on_total_loan` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.loanfees: ~0 rows (approximately)
DELETE FROM `loanfees`;
/*!40000 ALTER TABLE `loanfees` DISABLE KEYS */;
/*!40000 ALTER TABLE `loanfees` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.loan_additional_settings
CREATE TABLE IF NOT EXISTS `loan_additional_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_description` varchar(50) DEFAULT NULL,
  `percentage` double(16,2) DEFAULT NULL,
  `fixed_amount` double(16,2) DEFAULT NULL,
  `is_penalty` int(11) DEFAULT NULL,
  `is_initial_deduction` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Unique_Fee_Description` (`fee_description`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.loan_additional_settings: ~0 rows (approximately)
DELETE FROM `loan_additional_settings`;
/*!40000 ALTER TABLE `loan_additional_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_additional_settings` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.loan_fees
CREATE TABLE IF NOT EXISTS `loan_fees` (
  `loan_fees_id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_name` varchar(50) DEFAULT NULL,
  `fee_amount` double(16,2) DEFAULT NULL,
  `loan` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT NULL,
  `added_by` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`loan_fees_id`),
  KEY `Loan` (`loan`),
  CONSTRAINT `Loan` FOREIGN KEY (`loan`) REFERENCES `loan_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.loan_fees: ~0 rows (approximately)
DELETE FROM `loan_fees`;
/*!40000 ALTER TABLE `loan_fees` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_fees` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.loan_fees_settings
CREATE TABLE IF NOT EXISTS `loan_fees_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_name` varchar(50) NOT NULL,
  `fee_amount` double(16,2) NOT NULL,
  `min_loan` double(16,2) NOT NULL,
  `max_loan` double(16,2) NOT NULL,
  `deductible` smallint(6) NOT NULL,
  `active_status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.loan_fees_settings: ~0 rows (approximately)
DELETE FROM `loan_fees_settings`;
/*!40000 ALTER TABLE `loan_fees_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_fees_settings` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.loan_guarantors
CREATE TABLE IF NOT EXISTS `loan_guarantors` (
  `guarantor_id` int(11) NOT NULL AUTO_INCREMENT,
  `borrower` int(11) NOT NULL DEFAULT 0,
  `loan_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL DEFAULT '',
  `relationship` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `remarks` varchar(50) NOT NULL DEFAULT '',
  `image` blob NOT NULL DEFAULT '',
  `address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`guarantor_id`),
  KEY `borrower` (`borrower`),
  CONSTRAINT `borrower` FOREIGN KEY (`borrower`) REFERENCES `borrowers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.loan_guarantors: ~0 rows (approximately)
DELETE FROM `loan_guarantors`;
/*!40000 ALTER TABLE `loan_guarantors` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_guarantors` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.loan_info
CREATE TABLE IF NOT EXISTS `loan_info` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `borrower` int(11) NOT NULL DEFAULT 0,
  `baccount` varchar(200) NOT NULL,
  `reason` text NOT NULL,
  `amount` double(16,2) NOT NULL DEFAULT 0.00,
  `application_date` timestamp NULL DEFAULT NULL,
  `agent` varchar(200) NOT NULL,
  `loan_product` text NOT NULL,
  `repayment_remark` varchar(200) NOT NULL,
  `amount_topay` double(16,2) NOT NULL DEFAULT 0.00,
  `pay_date` date DEFAULT NULL,
  `balance` double(16,2) NOT NULL DEFAULT 0.00,
  `teller` varchar(200) NOT NULL,
  `status` varchar(200) NOT NULL,
  `loan_num_of_repayments` varchar(200) NOT NULL,
  `loan_payment_scheme` varchar(200) NOT NULL,
  `loan_duration_period` varchar(200) NOT NULL,
  `loan_duration` varchar(50) NOT NULL DEFAULT '0',
  `loan_interest_period` varchar(200) NOT NULL,
  `loan_interest` int(11) NOT NULL DEFAULT 0,
  `loan_interest_type` varchar(200) NOT NULL,
  `loan_interest_method` varchar(200) NOT NULL,
  `date_release` date DEFAULT NULL,
  `loan_disbursed_by_id` varchar(200) NOT NULL,
  `upstatus` varchar(50) DEFAULT NULL,
  `loan_maturity` date DEFAULT NULL,
  `modified_date` timestamp NULL DEFAULT NULL,
  `modified_by` varchar(50) DEFAULT NULL,
  `fees` double(16,2) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  `loan_repayment_method` varchar(10) DEFAULT NULL,
  `ownership_type` varchar(10) DEFAULT NULL,
  `payment_reference` varchar(50) DEFAULT NULL,
  `status_reason` varchar(100) DEFAULT NULL,
  `disbursed_amount` double(16,2) DEFAULT NULL,
  `interest_value` double(16,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Unique_Account` (`baccount`),
  KEY `loan_branch` (`branch`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.loan_info: ~0 rows (approximately)
DELETE FROM `loan_info`;
/*!40000 ALTER TABLE `loan_info` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_info` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.loan_settings
CREATE TABLE IF NOT EXISTS `loan_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `interest_rate` double DEFAULT NULL,
  `minimum_loan` int(11) DEFAULT NULL,
  `maximum_loan` int(11) DEFAULT NULL,
  `default_duration` int(11) DEFAULT NULL,
  `payment_cycle` varchar(50) DEFAULT NULL,
  `interest_method` varchar(50) DEFAULT NULL,
  `loan_insurance` double(16,2) DEFAULT NULL,
  `collateral` varchar(10) DEFAULT NULL,
  `minimum_loan_collateral` int(11) DEFAULT NULL,
  `penalty_fees` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.loan_settings: ~0 rows (approximately)
DELETE FROM `loan_settings`;
/*!40000 ALTER TABLE `loan_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_settings` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.loan_statuses
CREATE TABLE IF NOT EXISTS `loan_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) DEFAULT NULL,
  `added_by` varchar(50) DEFAULT NULL,
  `added_date` timestamp NULL DEFAULT current_timestamp(),
  `loan` int(11) DEFAULT NULL,
  `reason` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loanStatus` (`loan`),
  CONSTRAINT `loanStatus` FOREIGN KEY (`loan`) REFERENCES `loan_info` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.loan_statuses: ~0 rows (approximately)
DELETE FROM `loan_statuses`;
/*!40000 ALTER TABLE `loan_statuses` DISABLE KEYS */;
/*!40000 ALTER TABLE `loan_statuses` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.message
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `sender_id` varchar(200) NOT NULL,
  `sender_name` varchar(200) NOT NULL,
  `msg_to` varchar(200) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.message: ~0 rows (approximately)
DELETE FROM `message`;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.mywallet
CREATE TABLE IF NOT EXISTS `mywallet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(200) NOT NULL,
  `t_to` varchar(200) NOT NULL,
  `Amount` varchar(200) NOT NULL,
  `Desc` varchar(200) NOT NULL,
  `wtype` varchar(200) NOT NULL,
  `tdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.mywallet: ~1 rows (approximately)
DELETE FROM `mywallet`;
/*!40000 ALTER TABLE `mywallet` DISABLE KEYS */;
/*!40000 ALTER TABLE `mywallet` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tid` varchar(200) NOT NULL,
  `account` varchar(200) NOT NULL,
  `balance` double(16,2) NOT NULL DEFAULT 0.00,
  `customer` varchar(200) NOT NULL,
  `loan` double(16,2) NOT NULL DEFAULT 0.00,
  `pay_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `amount_to_pay` double(16,2) NOT NULL DEFAULT 0.00,
  `remarks` text NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `Unique Payment` (`account`,`amount_to_pay`,`reference`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.payments: ~0 rows (approximately)
DELETE FROM `payments`;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.payment_schedule
CREATE TABLE IF NOT EXISTS `payment_schedule` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `idm` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `term` varchar(200) NOT NULL,
  `day` varchar(200) NOT NULL,
  `schedule` varchar(200) NOT NULL,
  `interest` varchar(200) NOT NULL,
  `penalty` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.payment_schedule: ~0 rows (approximately)
DELETE FROM `payment_schedule`;
/*!40000 ALTER TABLE `payment_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_schedule` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.pay_schedule
CREATE TABLE IF NOT EXISTS `pay_schedule` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `schedule` varchar(200) NOT NULL,
  `balance` double(16,2) NOT NULL DEFAULT 0.00,
  `interest` double(16,2) NOT NULL DEFAULT 0.00,
  `payment` double(16,2) NOT NULL DEFAULT 0.00,
  `principal_due` double(16,2) DEFAULT NULL,
  `pay_type` varchar(50) DEFAULT NULL,
  `fees` double(16,2) DEFAULT NULL,
  `total_due` double(16,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=496 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.pay_schedule: ~0 rows (approximately)
DELETE FROM `pay_schedule`;
/*!40000 ALTER TABLE `pay_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `pay_schedule` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.products
CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(50) NOT NULL,
  `product_type` varchar(50) NOT NULL,
  `product_configuration` longblob NOT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `uniqueProduct` (`product_name`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COMMENT='table for all company products (Loan, Savings)';

-- Dumping data for table sbseazyl_pfsdemo.products: ~1 rows (approximately)
DELETE FROM `products`;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.sms
CREATE TABLE IF NOT EXISTS `sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_gateway` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `api` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.sms: ~0 rows (approximately)
DELETE FROM `sms`;
/*!40000 ALTER TABLE `sms` DISABLE KEYS */;
INSERT INTO `sms` (`id`, `sms_gateway`, `username`, `password`, `api`, `status`) VALUES
	(1, 'SMSTEAMS', 'optimum', 'optimum', 'http://smsteams.com/components/com_spc/smsapi.php?', 'NotActivated');
/*!40000 ALTER TABLE `sms` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.systemset
CREATE TABLE IF NOT EXISTS `systemset` (
  `sysid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `footer` text NOT NULL,
  `abb` varchar(200) NOT NULL,
  `fax` text NOT NULL,
  `currency` text NOT NULL,
  `website` text NOT NULL,
  `mobile` text NOT NULL,
  `image` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `email` text NOT NULL,
  `map` text NOT NULL,
  `stamp` varchar(350) NOT NULL,
  `timezone` text NOT NULL,
  `sms_charges` varchar(200) NOT NULL,
  `trading_name` varchar(50) DEFAULT NULL,
  `srn` varchar(50) DEFAULT NULL,
  `recipient` varchar(50) DEFAULT NULL,
  `submission_cycle` varchar(50) DEFAULT NULL,
  `day_of_submission` int(11) DEFAULT NULL,
  `sftp_url` varchar(100) DEFAULT NULL,
  `sftp_port` int(11) DEFAULT NULL,
  `bureau_email` varchar(50) DEFAULT NULL,
  `submission_method` varchar(50) DEFAULT NULL,
  `bureau_submission` int(11) DEFAULT NULL,
  `scoring` int(11) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `sftp_password` varchar(100) DEFAULT NULL,
  `sftp_username` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`sysid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.systemset: ~0 rows (approximately)
DELETE FROM `systemset`;
/*!40000 ALTER TABLE `systemset` DISABLE KEYS */;
INSERT INTO `systemset` (`sysid`, `title`, `name`, `footer`, `abb`, `fax`, `currency`, `website`, `mobile`, `image`, `address`, `email`, `map`, `stamp`, `timezone`, `sms_charges`, `trading_name`, `srn`, `recipient`, `submission_cycle`, `day_of_submission`, `sftp_url`, `sftp_port`, `bureau_email`, `submission_method`, `bureau_submission`, `scoring`, `file_type`, `sftp_password`, `sftp_username`) VALUES
	(1, 'SBS Loan / Lending Management System', 'SBS Eazy Loan Management', 'All rights reserved. Serumula Business Solutions 2020 (c)', 'SBS Loan', '+27 82 207 2730', 'M', 'https://www.serumula.com', '+27 82 207 2730', '../img/1589737531WhatsaPP.png', '2nd Street, Ranjespark, Midrand								', 'info@serumula.com', '		', 'smls.png', '2', '20', 'SBS Eazy Loan', 'LS0064', 'T', 'D', 0, 'ftp.eazyman.net', 0, '', 'Email', 1, 1, 'T702', 'passwrd', 'admin@admin.com');
/*!40000 ALTER TABLE `systemset` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.temp_borrowers
CREATE TABLE IF NOT EXISTS `temp_borrowers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `employer` varchar(50) DEFAULT NULL,
  `salary` double(16,2) DEFAULT NULL,
  `disposable` double(16,2) DEFAULT NULL,
  `session` varchar(50) DEFAULT NULL,
  `agent` varchar(50) DEFAULT NULL,
  `emp_code` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UniqueValues_Temp` (`lname`,`emp_code`,`fname`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=511 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table sbseazyl_pfsdemo.temp_borrowers: ~1 rows (approximately)
DELETE FROM `temp_borrowers`;
/*!40000 ALTER TABLE `temp_borrowers` DISABLE KEYS */;
INSERT INTO `temp_borrowers` (`id`, `fname`, `lname`, `employer`, `salary`, `disposable`, `session`, `agent`, `emp_code`) VALUES
	(510, '', '', '', 0.00, 0.00, 'tjmtqkt3bsb1uqcse0t67vl1be', 'Loan=21319580', '');
/*!40000 ALTER TABLE `temp_borrowers` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.transaction
CREATE TABLE IF NOT EXISTS `transaction` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `txid` varchar(200) NOT NULL,
  `t_type` varchar(200) NOT NULL COMMENT 'Deposit OR Withdraw',
  `acctno` varchar(200) NOT NULL,
  `fn` varchar(200) NOT NULL,
  `ln` varchar(200) NOT NULL,
  `email` varchar(300) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.transaction: ~0 rows (approximately)
DELETE FROM `transaction`;
/*!40000 ALTER TABLE `transaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaction` ENABLE KEYS */;

-- Dumping structure for table sbseazyl_pfsdemo.twallet
CREATE TABLE IF NOT EXISTS `twallet` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `tid` varchar(200) NOT NULL,
  `Total` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.twallet: ~0 rows (approximately)
DELETE FROM `twallet`;
/*!40000 ALTER TABLE `twallet` DISABLE KEYS */;
/*!40000 ALTER TABLE `twallet` ENABLE KEYS */;

-- Dumping structure for event sbseazyl_pfsdemo.update_profit
DELIMITER //
CREATE EVENT `update_profit` ON SCHEDULE EVERY 1 DAY STARTS '2017-03-08 20:45:36' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE ph_list SET Percentage = '727.2' WHERE tracking_id = 'Cryptos?rid=782752'//
DELIMITER ;

-- Dumping structure for table sbseazyl_pfsdemo.user
CREATE TABLE IF NOT EXISTS `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `id_number` int(12) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `addr1` text NOT NULL,
  `addr2` text NOT NULL,
  `district` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `comment` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `id` varchar(200) NOT NULL,
  `image` text DEFAULT NULL,
  `role` varchar(200) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `passport` varchar(50) DEFAULT NULL,
  `branch` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `UniqueUsername` (`username`,`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=510 DEFAULT CHARSET=latin1;

-- Dumping data for table sbseazyl_pfsdemo.user: ~2 rows (approximately)
DELETE FROM `user`;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`userid`, `name`, `email`, `gender`, `id_number`, `phone`, `addr1`, `addr2`, `district`, `country`, `comment`, `username`, `password`, `id`, `image`, `role`, `date_of_birth`, `passport`, `branch`) VALUES
	(482, 'Admin', 'admin@admin.com', '', 0, '08101750845', 'address1', 'address2', 'city', 'US', ' comment', 'admin', 'MTIzNDU2', 'Loan=21319580', 'img/1589737531WhatsaPP.png', 'admin', NULL, NULL, '10280061'),
	(503, 'SBS Super Admin', 'admin@admin.com', '', 0, '08101750845', 'address1', 'address2', 'city', 'US', ' comment', 'superadmin', 'MTIzNDU2', 'Loan=21319585', 'img/1589737531WhatsaPP.png', 'admin', NULL, NULL, '10280061');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
