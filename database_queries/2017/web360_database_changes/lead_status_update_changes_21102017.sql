ALTER TABLE `quotations`
	ADD COLUMN `lead_id` INT(11) NULL DEFAULT NULL AFTER `organization_id`,
	ADD INDEX `lead_id` (`lead_id`);