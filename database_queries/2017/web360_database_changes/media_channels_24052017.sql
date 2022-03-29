ALTER TABLE `media_channels`
	ADD COLUMN `organization_id` INT(11) NOT NULL AFTER `id`,
	ADD INDEX `organization_id` (`organization_id`);

ALTER TABLE `campaigns`
	ADD COLUMN `organization_id` INT(11) NOT NULL AFTER `id`,
	ADD INDEX `organization_id` (`organization_id`);
