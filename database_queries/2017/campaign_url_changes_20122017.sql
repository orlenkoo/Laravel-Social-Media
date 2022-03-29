CREATE TABLE `campaign_urls` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`campaign_id` INT NULL,
	`website_url` TEXT NULL,
	`campaign_source` TEXT NULL,
	`campaign_medium` TEXT NULL,
	`campaign_name` TEXT NULL,
	`campaign_term` TEXT NULL,
	`campaign_content` TEXT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `campaign_id` (`campaign_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
