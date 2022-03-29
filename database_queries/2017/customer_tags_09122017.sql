CREATE TABLE `customer_tags` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`organization_id` INT NULL,
	`tag` TEXT NULL,
	`status` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `organization_id` (`organization_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `customer_customer_tag_assignments` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`customer_id` INT NULL,
	`customer_tag_id` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `customer_id` (`customer_id`),
	INDEX `customer_tag_id` (`customer_tag_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

RENAME TABLE `customer_customer_tag_assignments` TO `customer_tag_assignments`;
