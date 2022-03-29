CREATE TABLE `organization_configurations` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`configuration` TEXT NULL,
	`status` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

CREATE TABLE `organization_configuration_mappings` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`organization_configuration_id` INT NULL,
	`organization_id` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `organization_configuration_id` (`organization_configuration_id`),
	INDEX `organization_id` (`organization_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;
