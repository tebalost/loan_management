CREATE TABLE `borrowers_salaries` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`borrower` INT(11) NULL DEFAULT NULL,
	`basic_pay` DOUBLE(16,2) NULL DEFAULT NULL,
	`additional_fixed_allowance` DOUBLE(16,2) NULL DEFAULT NULL,
	`gross_pay` DOUBLE(16,2) NULL DEFAULT NULL,
	`statutory_deductions` DOUBLE(16,2) NULL DEFAULT NULL,
	`loan_instalments` DOUBLE(16,2) NULL DEFAULT NULL,
	`net_pay` DOUBLE(16,2) NULL DEFAULT NULL,
	`other_bank_loans` DOUBLE(16,2) NULL DEFAULT NULL,
	`monthly_living_expenses` DOUBLE(16,2) NULL DEFAULT NULL,
	`max_available` DECIMAL(16,2) NULL DEFAULT NULL,
	PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;


CREATE TABLE `next_of_kin_details` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`borrower` INT(11) NULL DEFAULT NULL,
`names` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
`address` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
`contact` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
`email` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
`employer` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
PRIMARY KEY (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=0
;


CREATE TABLE `employer_details` (
`id` INT(3) NOT NULL,
`employee_no` BIGINT(8) NULL DEFAULT NULL,
`employer_name` VARCHAR(30) NOT NULL COLLATE 'utf8mb4_general_ci',
`department` VARCHAR(50) NOT NULL COLLATE 'utf8mb4_general_ci',
`employer_code` VARCHAR(12) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
`designation` VARCHAR(30) NOT NULL COLLATE 'utf8mb4_general_ci',
`engagement_date` DATE NULL DEFAULT NULL,
`employment_status` VARCHAR(30) NOT NULL COLLATE 'utf8mb4_general_ci',
`retirement` DATE NULL DEFAULT NULL,
`employer_contact` BIGINT(12) NULL DEFAULT NULL,
`telephone` INT(12) NULL DEFAULT NULL,
`employer_designation` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci'
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
;

/*Store the ID from SACCOS Table under credit score to ease records update, take all address to comment for eazy modification*/
insert into borrowers (fname, middlename,lname, email, phone,telephone, addrs1, addrs2, comment, date_time, status, date_of_birth, passport,member,credit_score) SELECT firstname, middlename,surname, email, mobilenumber,home_phone,physicalAddress, postalAddress, address,lastmodified,member_status, dateofbirt, idNumber,1,id FROM `saccos_members`