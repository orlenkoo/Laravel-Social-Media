CREATE TABLE `organization_preferences` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`organization_id` INT(11) NOT NULL,
	`payment_terms` TEXT NULL,
	`terms_and_conditions` TEXT NULL,
	`tax_percentage` TEXT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `organization_id` (`organization_id`)
)
