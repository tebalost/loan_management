-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 22, 2020 at 08:23 PM
-- Server version: 10.2.33-MariaDB-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sbseazyl_pfsdemo`
--

-- --------------------------------------------------------

--
-- Table structure for table `aboutus`
--

CREATE TABLE `aboutus` (
  `abid` int(11) NOT NULL,
  `about` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `additional_fees`
--

CREATE TABLE `additional_fees` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `fee` varchar(200) NOT NULL,
  `Amount` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `affordability_check`
--

CREATE TABLE `affordability_check` (
  `id` int(11) NOT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `endpoint` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `affordability_check`
--

INSERT INTO `affordability_check` (`id`, `provider`, `endpoint`, `username`, `password`, `status`) VALUES
(1, 'CDAS', 'https://www.cdas.co.ls/api/', 'api.admin', 'admin', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE `attachment` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `attached_file` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `backup`
--

CREATE TABLE `backup` (
  `id` int(200) NOT NULL,
  `tracking_id` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `banner`
--

CREATE TABLE `banner` (
  `banaid` int(11) NOT NULL,
  `bannar` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `battachment`
--

CREATE TABLE `battachment` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `attached_file` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `document_type` varchar(50) DEFAULT NULL,
  `file_size` varchar(50) DEFAULT NULL,
  `file_ext` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `borrowers`
--

CREATE TABLE `borrowers` (
  `id` int(11) NOT NULL,
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
  `ownershipType` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `sub_account` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `bureau_submissions`
--

CREATE TABLE `bureau_submissions` (
  `id` int(11) NOT NULL,
  `batch` int(11) DEFAULT NULL,
  `loan_records` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Scheduled',
  `action_date` date DEFAULT NULL,
  `action_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `collateral`
--

CREATE TABLE `collateral` (
  `id` int(20) NOT NULL,
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
  `loan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `tid` varchar(55) NOT NULL,
  `comment` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `account` varchar(100) NOT NULL,
  `customer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `alpha_2` varchar(200) NOT NULL DEFAULT '',
  `alpha_3` varchar(200) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `countries`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `documents_required`
--

CREATE TABLE `documents_required` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `emp_permission`
--

CREATE TABLE `emp_permission` (
  `id` int(20) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `module_name` varchar(350) NOT NULL,
  `pcreate` varchar(20) NOT NULL,
  `pread` varchar(20) NOT NULL,
  `pupdate` varchar(20) NOT NULL,
  `pdelete` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `emp_permission`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `emp_role`
--

CREATE TABLE `emp_role` (
  `id` int(11) NOT NULL,
  `role` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `etemplates`
--

CREATE TABLE `etemplates` (
  `id` int(11) NOT NULL,
  `sender` varchar(200) NOT NULL,
  `receiver_email` varchar(350) NOT NULL,
  `subject` varchar(350) NOT NULL,
  `msg` text NOT NULL,
  `time_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `topic` text NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fin_info`
--

CREATE TABLE `fin_info` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `occupation` varchar(25) NOT NULL,
  `mincome` varchar(200) NOT NULL,
  `frequency` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `footer`
--

CREATE TABLE `footer` (
  `id` int(11) NOT NULL,
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
  `map` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `hiw`
--

CREATE TABLE `hiw` (
  `hid` int(11) NOT NULL,
  `hiw` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loanfees`
--

CREATE TABLE `loanfees` (
  `id` int(11) NOT NULL,
  `loan_fees` blob DEFAULT NULL,
  `insurance_on_total_loan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_additional_settings`
--

CREATE TABLE `loan_additional_settings` (
  `id` int(11) NOT NULL,
  `fee_description` varchar(50) DEFAULT NULL,
  `percentage` double(16,2) DEFAULT NULL,
  `fixed_amount` double(16,2) DEFAULT NULL,
  `is_penalty` int(11) DEFAULT NULL,
  `is_initial_deduction` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_fees`
--

CREATE TABLE `loan_fees` (
  `loan_fees_id` int(11) NOT NULL,
  `fee_name` varchar(50) DEFAULT NULL,
  `fee_amount` double(16,2) DEFAULT NULL,
  `loan` int(11) DEFAULT NULL,
  `date_added` timestamp NULL DEFAULT NULL,
  `added_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_fees_settings`
--

CREATE TABLE `loan_fees_settings` (
  `id` int(11) NOT NULL,
  `fee_name` varchar(50) NOT NULL,
  `fee_amount` double(16,2) NOT NULL,
  `min_loan` double(16,2) NOT NULL,
  `max_loan` double(16,2) NOT NULL,
  `deductible` smallint(6) NOT NULL,
  `active_status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_guarantors`
--

CREATE TABLE `loan_guarantors` (
  `guarantor_id` int(11) NOT NULL,
  `borrower` int(11) NOT NULL DEFAULT 0,
  `loan_id` int(11) NOT NULL DEFAULT 0,
  `name` varchar(100) NOT NULL DEFAULT '',
  `relationship` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `remarks` varchar(50) NOT NULL DEFAULT '',
  `image` blob NOT NULL DEFAULT '',
  `address` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_info`
--

CREATE TABLE `loan_info` (
  `id` int(20) NOT NULL,
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
  `disbursed_amount` double(16,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loan_settings`
--

CREATE TABLE `loan_settings` (
  `id` int(11) NOT NULL,
  `interest_rate` double DEFAULT NULL,
  `minimum_loan` int(11) DEFAULT NULL,
  `maximum_loan` int(11) DEFAULT NULL,
  `default_duration` int(11) DEFAULT NULL,
  `payment_cycle` varchar(50) DEFAULT NULL,
  `interest_method` varchar(50) DEFAULT NULL,
  `loan_insurance` double(16,2) DEFAULT NULL,
  `collateral` varchar(10) DEFAULT NULL,
  `minimum_loan_collateral` int(11) DEFAULT NULL,
  `penalty_fees` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `loan_statuses`
--

CREATE TABLE `loan_statuses` (
  `id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `added_by` varchar(50) DEFAULT NULL,
  `added_date` timestamp NULL DEFAULT current_timestamp(),
  `loan` int(11) DEFAULT NULL,
  `reason` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(200) NOT NULL,
  `sender_id` varchar(200) NOT NULL,
  `sender_name` varchar(200) NOT NULL,
  `msg_to` varchar(200) NOT NULL,
  `subject` varchar(300) NOT NULL,
  `message` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mywallet`
--

CREATE TABLE `mywallet` (
  `id` int(11) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `t_to` varchar(200) NOT NULL,
  `Amount` varchar(200) NOT NULL,
  `Desc` varchar(200) NOT NULL,
  `wtype` varchar(200) NOT NULL,
  `tdate` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(20) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `account` varchar(200) NOT NULL,
  `balance` double(16,2) NOT NULL DEFAULT 0.00,
  `customer` varchar(200) NOT NULL,
  `loan` double(16,2) NOT NULL DEFAULT 0.00,
  `pay_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `amount_to_pay` double(16,2) NOT NULL DEFAULT 0.00,
  `remarks` text NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_schedule`
--

CREATE TABLE `payment_schedule` (
  `id` int(20) NOT NULL,
  `idm` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `term` varchar(200) NOT NULL,
  `day` varchar(200) NOT NULL,
  `schedule` varchar(200) NOT NULL,
  `interest` varchar(200) NOT NULL,
  `penalty` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pay_schedule`
--

CREATE TABLE `pay_schedule` (
  `id` int(20) NOT NULL,
  `get_id` varchar(200) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `schedule` varchar(200) NOT NULL,
  `balance` double(16,2) NOT NULL DEFAULT 0.00,
  `interest` double(16,2) NOT NULL DEFAULT 0.00,
  `payment` double(16,2) NOT NULL DEFAULT 0.00,
  `principal_due` double(16,2) DEFAULT NULL,
  `pay_type` varchar(50) DEFAULT NULL,
  `fees` double(16,2) DEFAULT NULL,
  `total_due` double(16,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `product_type` varchar(50) NOT NULL,
  `product_configuration` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='table for all company products (Loan, Savings)';

-- --------------------------------------------------------

--
-- Table structure for table `sms`
--

CREATE TABLE `sms` (
  `id` int(11) NOT NULL,
  `sms_gateway` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `api` text NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `systemset`
--

CREATE TABLE `systemset` (
  `sysid` int(11) NOT NULL,
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
  `sftp_username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `systemset`
--

INSERT INTO `systemset` (`sysid`, `title`, `name`, `footer`, `abb`, `fax`, `currency`, `website`, `mobile`, `image`, `address`, `email`, `map`, `stamp`, `timezone`, `sms_charges`, `trading_name`, `srn`, `recipient`, `submission_cycle`, `day_of_submission`, `sftp_url`, `sftp_port`, `bureau_email`, `submission_method`, `bureau_submission`, `scoring`, `file_type`, `sftp_password`, `sftp_username`) VALUES
(1, 'SBS Loan / Lending Management System', 'SBS Eazy Loan Management', 'All rights reserved. Serumula Business Solutions 2020 (c)', 'SBS Loan', '+27 82 207 2730', 'M', 'https://www.serumula.com', '+27 82 207 2730', '../img/1589737531WhatsaPP.png', '2nd Street, Ranjespark, Midrand								', 'info@serumula.com', '		', 'smls.png', '2', '20', 'SBS Eazy Loan', '0', '0', '0', 0, '0', 0, '0', '0', 0, 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `temp_borrowers`
--

CREATE TABLE `temp_borrowers` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) DEFAULT NULL,
  `lname` varchar(50) DEFAULT NULL,
  `employer` varchar(50) DEFAULT NULL,
  `salary` double(16,2) DEFAULT NULL,
  `disposable` double(16,2) DEFAULT NULL,
  `session` varchar(50) DEFAULT NULL,
  `agent` varchar(50) DEFAULT NULL,
  `emp_code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(20) NOT NULL,
  `txid` varchar(200) NOT NULL,
  `t_type` varchar(200) NOT NULL COMMENT 'Deposit OR Withdraw',
  `acctno` varchar(200) NOT NULL,
  `fn` varchar(200) NOT NULL,
  `ln` varchar(200) NOT NULL,
  `email` varchar(300) NOT NULL,
  `phone` varchar(200) NOT NULL,
  `amount` varchar(200) NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `twallet`
--

CREATE TABLE `twallet` (
  `id` int(20) NOT NULL,
  `tid` varchar(200) NOT NULL,
  `Total` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL,
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
  `branch` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `name`, `email`, `gender`, `id_number`, `phone`, `addr1`, `addr2`, `district`, `country`, `comment`, `username`, `password`, `id`, `image`, `role`, `date_of_birth`, `passport`, `branch`) VALUES
(482, 'Admin', 'admin@admin.com', '', 0, '08101750845', 'address1', 'address2', 'city', 'US', ' comment', 'admin', 'MTIzNDU2', 'Loan=21319580', 'img/1589737531WhatsaPP.png', 'admin', NULL, NULL, '10280061'),
(503, 'SBS Super Admin', 'admin@admin.com', '', 0, '08101750845', 'address1', 'address2', 'city', 'US', ' comment', 'superadmin', 'MTIzNDU2', 'Loan=21319585', 'img/1589737531WhatsaPP.png', 'admin', NULL, NULL, '10280061');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aboutus`
--
ALTER TABLE `aboutus`
  ADD PRIMARY KEY (`abid`);

--
-- Indexes for table `additional_fees`
--
ALTER TABLE `additional_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `affordability_check`
--
ALTER TABLE `affordability_check`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `backup`
--
ALTER TABLE `backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banner`
--
ALTER TABLE `banner`
  ADD PRIMARY KEY (`banaid`);

--
-- Indexes for table `battachment`
--
ALTER TABLE `battachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowers`
--
ALTER TABLE `borrowers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Index 2` (`employer`,`emp_code`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `BranchCode_NAME` (`sub_account`,`name`,`code`,`location`) USING BTREE;

--
-- Indexes for table `bureau_submissions`
--
ALTER TABLE `bureau_submissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Unique_Day_Of_Month` (`action_date`) USING BTREE;

--
-- Indexes for table `collateral`
--
ALTER TABLE `collateral`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documents_required`
--
ALTER TABLE `documents_required`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_permission`
--
ALTER TABLE `emp_permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_role`
--
ALTER TABLE `emp_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `etemplates`
--
ALTER TABLE `etemplates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fin_info`
--
ALTER TABLE `fin_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `get_id_tid_occupation_mincome` (`get_id`,`tid`,`occupation`,`mincome`) USING HASH;

--
-- Indexes for table `footer`
--
ALTER TABLE `footer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hiw`
--
ALTER TABLE `hiw`
  ADD PRIMARY KEY (`hid`);

--
-- Indexes for table `loanfees`
--
ALTER TABLE `loanfees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_additional_settings`
--
ALTER TABLE `loan_additional_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Unique_Fee_Description` (`fee_description`) USING BTREE;

--
-- Indexes for table `loan_fees`
--
ALTER TABLE `loan_fees`
  ADD PRIMARY KEY (`loan_fees_id`),
  ADD KEY `Loan` (`loan`);

--
-- Indexes for table `loan_fees_settings`
--
ALTER TABLE `loan_fees_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_guarantors`
--
ALTER TABLE `loan_guarantors`
  ADD PRIMARY KEY (`guarantor_id`),
  ADD KEY `borrower` (`borrower`);

--
-- Indexes for table `loan_info`
--
ALTER TABLE `loan_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Unique_Account` (`baccount`),
  ADD KEY `loan_branch` (`branch`);

--
-- Indexes for table `loan_settings`
--
ALTER TABLE `loan_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_statuses`
--
ALTER TABLE `loan_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loanStatus` (`loan`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mywallet`
--
ALTER TABLE `mywallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `Unique Payment` (`account`,`amount_to_pay`,`reference`) USING BTREE;

--
-- Indexes for table `payment_schedule`
--
ALTER TABLE `payment_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pay_schedule`
--
ALTER TABLE `pay_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `uniqueProduct` (`product_name`);

--
-- Indexes for table `sms`
--
ALTER TABLE `sms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `systemset`
--
ALTER TABLE `systemset`
  ADD PRIMARY KEY (`sysid`);

--
-- Indexes for table `temp_borrowers`
--
ALTER TABLE `temp_borrowers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UniqueValues_Temp` (`lname`,`emp_code`,`fname`) USING BTREE;

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `twallet`
--
ALTER TABLE `twallet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `UniqueUsername` (`username`,`email`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aboutus`
--
ALTER TABLE `aboutus`
  MODIFY `abid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `additional_fees`
--
ALTER TABLE `additional_fees`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `affordability_check`
--
ALTER TABLE `affordability_check`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attachment`
--
ALTER TABLE `attachment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `backup`
--
ALTER TABLE `backup`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner`
--
ALTER TABLE `banner`
  MODIFY `banaid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `battachment`
--
ALTER TABLE `battachment`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `borrowers`
--
ALTER TABLE `borrowers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bureau_submissions`
--
ALTER TABLE `bureau_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `collateral`
--
ALTER TABLE `collateral`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `documents_required`
--
ALTER TABLE `documents_required`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emp_permission`
--
ALTER TABLE `emp_permission`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;

--
-- AUTO_INCREMENT for table `emp_role`
--
ALTER TABLE `emp_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `etemplates`
--
ALTER TABLE `etemplates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fin_info`
--
ALTER TABLE `fin_info`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer`
--
ALTER TABLE `footer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hiw`
--
ALTER TABLE `hiw`
  MODIFY `hid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loanfees`
--
ALTER TABLE `loanfees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_additional_settings`
--
ALTER TABLE `loan_additional_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_fees`
--
ALTER TABLE `loan_fees`
  MODIFY `loan_fees_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_fees_settings`
--
ALTER TABLE `loan_fees_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_guarantors`
--
ALTER TABLE `loan_guarantors`
  MODIFY `guarantor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_info`
--
ALTER TABLE `loan_info`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `loan_settings`
--
ALTER TABLE `loan_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_statuses`
--
ALTER TABLE `loan_statuses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mywallet`
--
ALTER TABLE `mywallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `payment_schedule`
--
ALTER TABLE `payment_schedule`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pay_schedule`
--
ALTER TABLE `pay_schedule`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms`
--
ALTER TABLE `sms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `systemset`
--
ALTER TABLE `systemset`
  MODIFY `sysid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temp_borrowers`
--
ALTER TABLE `temp_borrowers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `twallet`
--
ALTER TABLE `twallet`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=510;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan_fees`
--
ALTER TABLE `loan_fees`
  ADD CONSTRAINT `Loan` FOREIGN KEY (`loan`) REFERENCES `loan_info` (`id`);

--
-- Constraints for table `loan_guarantors`
--
ALTER TABLE `loan_guarantors`
  ADD CONSTRAINT `borrower` FOREIGN KEY (`borrower`) REFERENCES `borrowers` (`id`);

--
-- Constraints for table `loan_statuses`
--
ALTER TABLE `loan_statuses`
  ADD CONSTRAINT `loanStatus` FOREIGN KEY (`loan`) REFERENCES `loan_info` (`id`);

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`sbseazyl`@`localhost` EVENT `update_profit` ON SCHEDULE EVERY 1 DAY STARTS '2017-03-08 20:45:36' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE ph_list SET Percentage = '727.2' WHERE tracking_id = 'Cryptos?rid=782752'$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
