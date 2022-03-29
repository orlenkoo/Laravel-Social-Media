ALTER TABLE `meetings`
	ALTER `created_datetime` DROP DEFAULT,
	ALTER `meeting_date` DROP DEFAULT,
	ALTER `assigned_to` DROP DEFAULT;
ALTER TABLE `meetings`
	CHANGE COLUMN `created_datetime` `created_datetime` DATETIME NULL AFTER `customer_id`,
	CHANGE COLUMN `agenda` `agenda` TEXT NULL AFTER `created_datetime`,
	CHANGE COLUMN `venue` `venue` TEXT NULL AFTER `agenda`,
	CHANGE COLUMN `summary` `summary` TEXT NULL AFTER `venue`,
	CHANGE COLUMN `meeting_date` `meeting_date` DATE NULL AFTER `summary`,
	CHANGE COLUMN `assigned_to` `assigned_to` INT(11) NULL AFTER `meeting_date`,
	CHANGE COLUMN `task` `task` INT(11) NULL DEFAULT '0' AFTER `assigned_to`,
	CHANGE COLUMN `scheduled_start_time` `scheduled_start_time` TEXT NULL AFTER `task`,
	CHANGE COLUMN `scheduled_end_time` `scheduled_end_time` TEXT NULL AFTER `scheduled_start_time`,
	ADD COLUMN `meeting_status` TEXT NULL AFTER `meeting_person`;

CREATE TABLE `meeting_status_logs` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`meeting_id` INT NULL,
	`status_updated_on` TIMESTAMP NULL,
	`status` TEXT NULL,
	`latitude` TEXT NULL,
	`longitude` TEXT NULL,
	`address` TEXT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `meeting_id` (`meeting_id`)
)
COLLATE='latin1_swedish_ci'
;
