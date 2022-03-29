ALTER TABLE `activity_logs`
	ALTER `date` DROP DEFAULT,
	ALTER `date_of_activity` DROP DEFAULT;
ALTER TABLE `activity_logs`
	CHANGE COLUMN `date` `created_datetime` DATETIME NOT NULL AFTER `customer_id`,
	CHANGE COLUMN `meeting` `summary` TEXT NOT NULL AFTER `venue`,
	CHANGE COLUMN `date_of_activity` `meeting_date` DATE NOT NULL AFTER `summary`,
	DROP COLUMN `parent_activity_logs_id`,
	DROP COLUMN `activity_type`,
	DROP COLUMN `activity_purpose`,
	DROP COLUMN `outcome`,
	DROP COLUMN `last_modified_date_time`,
	DROP COLUMN `meeting_status`,
	DROP COLUMN `start_time`,
	DROP COLUMN `end_time`,
	DROP COLUMN `start_latitude`,
	DROP COLUMN `start_longitude`,
	DROP COLUMN `end_latitude`,
	DROP COLUMN `end_longitude`,
	DROP COLUMN `start_address`,
	DROP COLUMN `end_address`,
	DROP COLUMN `google_calendar_event_object_json`;
RENAME TABLE `activity_logs` TO `meetings`;


CREATE TABLE `customer_time_line_items` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`customer_id` INT NULL,
	`time_line_item_source` TEXT NULL,
	`time_line_item_source_id` INT NULL,
	`datetime` DATETIME NULL,
	`created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `customer_id` (`customer_id`),
	INDEX `time_line_item_source_id` (`time_line_item_source_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `calls` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`customer_id` INT NULL,
	`created_datetime` DATETIME NULL,
	`agenda` TEXT NULL,
	`summary` TEXT NULL,
	`call_date` DATE NULL,
	`assigned_to` INT NULL,
	`task` INT NULL,
	`scheduled_start_time` TEXT NULL,
	`scheduled_end_time` TEXT NULL,
	`call_with` TEXT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `customer_id` (`customer_id`),
	INDEX `assigned_to` (`assigned_to`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

ALTER TABLE `customer_emails`
	DROP COLUMN `contract_id`,
	DROP COLUMN `email_type_id`,
	DROP COLUMN `email_type`,
	DROP COLUMN `new_date`;
RENAME TABLE `customer_emails` TO `emails`;

ALTER TABLE `emails`
	ALTER `sent_on` DROP DEFAULT;
ALTER TABLE `emails`
	CHANGE COLUMN `sent_on` `sent_on` DATETIME NOT NULL AFTER `sent_by_id`;


