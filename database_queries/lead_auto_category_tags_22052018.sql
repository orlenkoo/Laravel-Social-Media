CREATE TABLE `lead_auto_category_tags` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`lead_id` INT(11) NULL DEFAULT NULL,
	`category_tag` TEXT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `lead_id` (`lead_id`)
);