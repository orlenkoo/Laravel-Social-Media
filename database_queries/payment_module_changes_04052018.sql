CREATE TABLE `web360_modules` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`module_name` TEXT NULL,
	`module_rate` DECIMAL(10,2) NULL,
	`module_rate_base` TEXT NULL,
	`status` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
;

CREATE TABLE `web360_module_organization_assignments` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`web360_module_id` INT NULL,
	`organization_id` INT NULL,
	`enabled_date_time` DATETIME NULL,
	`disabled_date_time` DATETIME NULL,
	`status` TEXT NULL,
	`enabled_by` INT NULL,
	`disabled_by` INT NULL,
	`assigned_employee_count` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `web360_module_id` (`web360_module_id`),
	INDEX `organization_id` (`organization_id`),
	INDEX `enabled_by` (`enabled_by`),
	INDEX `disabled_by` (`disabled_by`)
)
COLLATE='latin1_swedish_ci'
;

CREATE TABLE `web360_module_employee_assignments` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`web360_module_id` INT NULL,
	`employee_id` INT NULL,
	`enabled_date_time` DATETIME NULL,
	`disabled_date_time` DATETIME NULL,
	`status` TEXT NULL,
	`enabled_by` INT NULL,
	`disabled_by` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `web360_module_id` (`web360_module_id`),
	INDEX `employee_id` (`employee_id`),
	INDEX `enabled_by` (`enabled_by`),
	INDEX `disabled_by` (`disabled_by`)
)
COLLATE='latin1_swedish_ci'
;

CREATE TABLE `web360_organization_invoice_master_records` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`organization_id` INT NULL,
	`invoice_number` TEXT NULL,
	`invoice_date` DATE NULL,
	`gross_total` DECIMAL(10,2) NULL,
	`taxes` DECIMAL(10,2) NULL,
	`discount` DECIMAL(10,2) NULL,
	`net_total` DECIMAL(10,2) NULL,
	`status` TEXT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `organization_id` (`organization_id`)
)
COLLATE='latin1_swedish_ci'
;

CREATE TABLE `web360_organization_invoice_detail_records` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`web360_organization_invoice_master_record_id` INT NULL,
	`item` TEXT NULL,
	`description` TEXT NULL,
	`amount` DECIMAL(10,2) NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	INDEX `web360_organization_invoice_master_record_id` (`web360_organization_invoice_master_record_id`),
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
;

CREATE TABLE `organization_payment_methods` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`organization_id` INT NULL,
	`credit_card_type` TEXT NULL,
	`credit_card_number` TEXT NULL,
	`expiry_month` TEXT NULL,
	`expiry_year` TEXT NULL,
	`cvv` TEXT NULL,
	`card_owner_name` TEXT NULL,
	`card_owner_address` TEXT NULL,
	`primary_card` INT NULL,
	`status` TEXT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	INDEX `organization_id` (`organization_id`),
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
;

ALTER TABLE `web360_organization_invoice_master_records`
	ADD COLUMN `title` TEXT NULL DEFAULT NULL AFTER `invoice_date`;
