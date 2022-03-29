CREATE TABLE `notifications` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`employee_id` INT NULL,
	`type_of_alert` INT NULL,
	`sms` TINYINT(1) NULL,
	`email` TINYINT(1) NULL,
	`mobile_app_notification` TINYINT(1) NULL,
	`status` TINYINT(1) NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `employee_id` (`employee_id`)
)
COLLATE='latin1_swedish_ci'
;

ALTER TABLE `notifications`
	CHANGE COLUMN `sms` `sms` TINYINT(1) NULL DEFAULT '0' AFTER `type_of_alert`,
	CHANGE COLUMN `email` `email` TINYINT(1) NULL DEFAULT '0' AFTER `sms`,
	CHANGE COLUMN `mobile_app_notification` `mobile_app_notification` TINYINT(1) NULL DEFAULT '0' AFTER `email`,
	CHANGE COLUMN `status` `status` TINYINT(1) NULL DEFAULT '1' AFTER `mobile_app_notification`;

ALTER TABLE `notifications`
	CHANGE COLUMN `type_of_alert` `type_of_alert` TEXT NULL DEFAULT NULL AFTER `employee_id`;

ALTER TABLE `notifications`
	ADD COLUMN `organization_id` INT(11) NULL DEFAULT NULL AFTER `employee_id`,
	ADD INDEX `organization_id` (`organization_id`);
