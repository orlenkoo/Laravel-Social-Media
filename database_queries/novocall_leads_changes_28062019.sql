CREATE TABLE `novocall_leads` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`organization_id` INT NULL,
	`type_of_lead` TEXT NULL DEFAULT NULL,
	`lead_details` TEXT NULL DEFAULT NULL,
	`created_at` TIMESTAMP NULL DEFAULT NULL,
	`updated_at` TIMESTAMP NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	INDEX `organization_id` (`organization_id`)
)
COLLATE='latin1_swedish_ci'
;

ALTER TABLE `novocall_leads`
	ADD COLUMN `schedule_id` TEXT NULL DEFAULT NULL AFTER `organization_id`;

