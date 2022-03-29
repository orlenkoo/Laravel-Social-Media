ALTER TABLE `campaigns`
	ALTER `organization_id` DROP DEFAULT,
	ALTER `start_date` DROP DEFAULT,
	ALTER `end_date` DROP DEFAULT,
	ALTER `status` DROP DEFAULT;
ALTER TABLE `campaigns`
	CHANGE COLUMN `organization_id` `organization_id` INT(11) NULL AFTER `id`,
	CHANGE COLUMN `campaign_name` `campaign_name` TEXT NULL AFTER `organization_id`,
	CHANGE COLUMN `campaign_content` `campaign_content` TEXT NULL AFTER `campaign_name`,
	ADD COLUMN `call_tracking_number` TEXT NULL AFTER `campaign_content`,
	CHANGE COLUMN `cost` `cost` TEXT NULL AFTER `call_tracking_number`,
	CHANGE COLUMN `start_date` `start_date` DATE NULL AFTER `cost`,
	CHANGE COLUMN `end_date` `end_date` DATE NULL AFTER `start_date`,
	CHANGE COLUMN `status` `status` INT(11) NULL AFTER `end_date`,
	CHANGE COLUMN `point_of_contact` `point_of_contact` TEXT NULL AFTER `status`,
	CHANGE COLUMN `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `point_of_contact`,
	CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NULL DEFAULT '0000-00-00 00:00:00' AFTER `created_at`;

	ALTER TABLE `leads`
	ADD COLUMN `gclid` TEXT NULL AFTER `utm_content`;

	ALTER TABLE `leads`
	ADD COLUMN `fbclid` TEXT NULL AFTER `gclid`;

ALTER TABLE `tasks`
	ADD COLUMN `customer_id` INT NULL AFTER `title`,
	ADD COLUMN `contact_id` INT NULL AFTER `customer_id`,
	ADD INDEX `organization_id` (`organization_id`),
	ADD INDEX `customer_id` (`customer_id`),
	ADD INDEX `contact_id` (`contact_id`);

