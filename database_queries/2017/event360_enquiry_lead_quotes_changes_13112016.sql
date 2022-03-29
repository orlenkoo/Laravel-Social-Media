CREATE TABLE `event360_enquiry_lead_quotes` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`lead_id` INT NULL,
	`event360_enquiry_required_sub_service_id` INT NULL,
	`quote_amount` TEXT NULL,
	`quote_date_time` DATETIME NULL,
	`quote_updated_by` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `lead_id` (`lead_id`),
	INDEX `event360_enquiry_required_sub_service_id` (`event360_enquiry_required_sub_service_id`),
	INDEX `quote_updated_by` (`quote_updated_by`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;


ALTER TABLE `event360_enquiry_required_sub_services`
	ADD COLUMN `event360_remarks` TEXT NULL DEFAULT NULL AFTER `event360_sub_service_category_id`,
	ADD COLUMN `event360_remarks_entered_by` INT(11) NULL DEFAULT NULL AFTER `event360_remarks`,
	ADD INDEX `event360_remarks_entered_by` (`event360_remarks_entered_by`);

ALTER TABLE `event360_enquiry_lead_quotes`
	DROP COLUMN `event360_enquiry_required_sub_service_id`,
	DROP COLUMN `quote_amount`,
	DROP COLUMN `quote_remarks_notes`;

CREATE TABLE `event360_enquiry_lead_quote_items` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`event360_enquiry_lead_quote_id` INT NULL,
	`event360_enquiry_required_sub_service_id` INT NULL,
	`quote_amount` TEXT NULL,
	`quote_remarks_notes` TEXT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `event360_enquiry_lead_quote_id` (`event360_enquiry_lead_quote_id`),
	INDEX `event360_enquiry_required_sub_service_id` (`event360_enquiry_required_sub_service_id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

