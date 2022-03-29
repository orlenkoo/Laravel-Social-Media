CREATE TABLE `quotation_attachments` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`quotation_id` INT(11) NULL DEFAULT NULL,
	`title` TEXT NULL,
	`description` TEXT NULL,
	`quotation_gcs_file_url` TEXT NULL,
	`quotation_file_url` TEXT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `quotation_id` (`quotation_id`)
);