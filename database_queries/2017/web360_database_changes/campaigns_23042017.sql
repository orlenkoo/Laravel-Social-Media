CREATE TABLE `campaigns` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`campaign_name` TEXT NOT NULL,
	`cost` TEXT NOT NULL,
	`start_date` DATE NOT NULL,
	`end_date` DATE NOT NULL,
	`status` INT NOT NULL,
	`created_at` TIMESTAMP NOT NULL,
	`updated_at` TIMESTAMP NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `media_channels` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`media_channel` TEXT NOT NULL,
	`status` INT NOT NULL,
	`created_at` TIMESTAMP NOT NULL,
	`updated_at` TIMESTAMP NOT NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `campaign_media_channels` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`campaign_id` INT NOT NULL,
	`media_channel_id` INT NOT NULL,
	`created_at` TIMESTAMP NOT NULL,
	`updated_at` TIMESTAMP NOT NULL,
	INDEX `campaign_id` (`campaign_id`),
	INDEX `media_channel_id` (`media_channel_id`),
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

ALTER TABLE `campaigns`
	ADD COLUMN `point_of_contact` TEXT NOT NULL AFTER `status`;

