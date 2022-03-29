CREATE TABLE `lead_notes` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`lead_id` INT NULL,
	`note` TEXT NULL,
	`datetime` DATETIME NULL,
	`created_by` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	INDEX `created_by` (`created_by`),
	PRIMARY KEY (`id`),
	INDEX `lead_id` (`lead_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `lead_forwards` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`lead_id` INT NULL,
	`email` TEXT NULL,
	`message` TEXT NULL,
	`lead_forwarded_on` DATETIME NULL,
	`lead_forwarded_by` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `lead_id` (`lead_id`),
	INDEX `lead_forwarded_by` (`lead_forwarded_by`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

ALTER TABLE `event360_messenger_thread_messages`
	CHANGE COLUMN `timestamp` `timestamp` TIMESTAMP NULL DEFAULT NULL AFTER `sent_by`;
