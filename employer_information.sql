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