-- --------------------------------------------------------
-- Host:                         localhost
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

-- Dumping structure for table loan.report_types
CREATE TABLE IF NOT EXISTS `report_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `uri` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table loan.report_types: ~29 rows (approximately)
DELETE FROM `report_types`;
/*!40000 ALTER TABLE `report_types` DISABLE KEYS */;
INSERT INTO `report_types` (`id`, `type`, `name`, `url`, `uri`, `status`) VALUES
	(1, 'COMPUSCAN_FILE', 'Compuscan File', 'https://test.pulamaliboho.sbs-eazy.loans/', 'api/schedular/system-file-transfer', 1),
	(2, 'BALANCE_SHEET', 'Balance Sheet', 'https://test.pulamaliboho.sbs-eazy.loans/', '', 1),
	(3, 'DAILY_TRIAL_BALANCE', 'Daily Trial Balance', 'https://test.pulamaliboho.sbs-eazy.loans/', '', 1),
	(4, 'DISBURSED_LOANS', 'Disbursed Loans Report', 'https://test.pulamaliboho.sbs-eazy.loans/', '', 1),
	(5, 'PRODUCTS_SUMMARY', 'Products Summary Report', 'https://test.pulamaliboho.sbs-eazy.loans/', '', 1),
	(6, 'paymentDue-warning', 'Due Loans Tomorrow', 'https://test.pulamaliboho.sbs-eazy.loans/', 'api/schedular/sms-sender', 1),
	(7, 'ACTIVE_CLIENTS', 'Client Numbers Report', 'https://test.pulamaliboho.sbs-eazy.loans/', '', 1),
	(8, 'paymentOverdue-warning', 'Overdue Loans (Missed Payments)', 'https://test.pulamaliboho.sbs-eazy.loans/', 'api/schedular/sms-sender', 1),
	(9, 'LOAN_OFFICER_REPORT', 'Loan Officer Report', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(10, 'PROFIT_AND_LOSS', 'Profit and Loss', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(11, 'CLIENTS_OVERVIEW', 'Clients Overview', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(12, 'DAILY_EXPECTED_PAYMENTS', 'Expected Repayments', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(13, 'REPAYMETS_REPORT', 'Repayments Report', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(14, 'CASHFLOW', 'Cashflow', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(15, 'WRITE_OFFS', 'Write Offs', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(16, 'OUTSTANDING_LOANS_REPORT', 'Outstanding Loans Report', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(17, 'CLIENT_LOAN_STATEMENT', 'Clients Monthly Statement', 'https://test.pulamaliboho.sbs-eazy.loans/', 'api/schedular/system-mailer', 1),
	(18, 'INCOME_STATEMENT', 'Historical Income Statement', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(19, 'BRANCH_CLIENTS', 'Branch Client Numbers', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(20, 'RECOVERIES', 'Recovery On Past Due Report', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(21, 'PAID_UP_ACCOUNTS', 'Closed Client Report', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(22, 'CUSTOMER_INDICATOR', 'Indicator Report (Individual Accounts)', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(23, 'ACCRIED_INTEREST', 'Accrued Interest Report', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(24, 'JOURNAL_TRANSACTIONS', 'Journals Report', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(25, 'CENTRAL_BANK_REPORT', 'Central Bank Of Lesotho Report', 'https://test.pulamaliboho.sbs-eazy.loans/', 'api/schedular/system-mailer', 1),
	(26, 'INSURANCE-REPORT', 'Third Party Insurance', 'https://test.pulamaliboho.sbs-eazy.loans/', 'api/schedular/system-mailer', 1),
	(27, 'AGED_ANALYSIS', 'Aged Analysis', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(28, 'CDAS_FILE', 'CDAS Output File', 'https://test.pulamaliboho.sbs-eazy.loans/', NULL, 1),
	(29, 'INVOICE', 'System Invoice Charge', 'https://test.pulamaliboho.sbs-eazy.loans/', 'api/schedular/system-mailer', 1);
/*!40000 ALTER TABLE `report_types` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
