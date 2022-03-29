ALTER TABLE `quotations`
	ADD COLUMN `organization_id` INT(11) NULL DEFAULT NULL AFTER `customer_id`,
	ADD INDEX `organization_id` (`organization_id`);