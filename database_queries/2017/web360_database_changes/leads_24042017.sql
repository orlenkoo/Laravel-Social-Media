ALTER TABLE `leads`
	ADD COLUMN `campaign_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `organization_id`,
	ADD INDEX `campaign_id` (`campaign_id`);
