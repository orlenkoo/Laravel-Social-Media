ALTER TABLE `leads`
	ADD COLUMN `assigned_to` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `campaign_id`,
	ADD COLUMN `last_assignment_datetime` DATETIME NULL DEFAULT NULL AFTER `assigned_to`,
	ADD INDEX `assigned_to` (`assigned_to`);


CREATE TABLE `lead_assignment_history` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`lead_id` INT NULL,
	`assigned_to` INT NULL,
	`assigned_datetime` DATETIME NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `lead_id` (`lead_id`),
	INDEX `assigned_to` (`assigned_to`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

RENAME TABLE `lead_assignment_history` TO `lead_assignments`;
