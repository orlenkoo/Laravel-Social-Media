CREATE TABLE `landing_pages` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`organization_id` INT(11) NULL DEFAULT '0',
	`page_name` TEXT NULL,
	`url` TEXT NULL,
	`status` TEXT NULL,
	`campaign_id` INT(11) NULL DEFAULT '0',
	`ftp_host` TEXT NULL,
	`ftp_port` TEXT NULL,
	`ftp_user_name` TEXT NULL,
	`ftp_password` TEXT NULL,
	`ftp_path` TEXT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `campaign_id` (`campaign_id`),
	INDEX `organization_id` (`organization_id`)
);
