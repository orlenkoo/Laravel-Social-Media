CREATE TABLE `email_attachments` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`email_id` INT(11) NULL DEFAULT NULL,
	`title` TEXT NULL,
	`description` TEXT NULL,
	`email_attachment_gcs_file_url` TEXT NULL,
	`email_attachment_file_url` TEXT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `email_id` (`email_id`)
);