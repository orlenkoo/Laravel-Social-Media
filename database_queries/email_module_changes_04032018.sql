CREATE TABLE `email_module_email_campaigns` (
	`id` INT NULL,
	`campaign_name` TEXT NULL,
	`subject` TEXT NULL,
	`from_name` TEXT NULL,
	`from_email_address` TEXT NULL,
	`status` TEXT NULL,
	`start_date_time` TEXT NULL,
	`end_date_time` TEXT NULL,
	`start_automatically` INT NULL DEFAULT '1',
	`email_module_email_list_id` INT NULL,
	`email_content` LONGTEXT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL
)
COLLATE='latin1_swedish_ci'
;

ALTER TABLE `email_module_email_campaigns`
	CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	ADD PRIMARY KEY (`id`),
	ADD INDEX `email_module_email_list_id` (`email_module_email_list_id`);


CREATE TABLE `email_module_email_lists` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`email_list_name` TEXT NULL,
	`status` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`)
)
COLLATE='latin1_swedish_ci'
;


CREATE TABLE `email_module_email_list_contacts_assignments` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`email_module_email_list_id` INT NULL,
	`contact_id` INT NULL,
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	PRIMARY KEY (`id`),
	INDEX `email_module_email_list_id` (`email_module_email_list_id`),
	INDEX `contact_id` (`contact_id`)
)
COLLATE='latin1_swedish_ci'
;

ALTER TABLE `email_module_email_campaigns`
	ADD COLUMN `organization_id` INT(11) NULL AFTER `id`,
	ADD INDEX `organization_id` (`organization_id`);

ALTER TABLE `email_module_email_lists`
	ADD COLUMN `organization_id` INT(11) NULL AFTER `id`,
	ADD INDEX `organization_id` (`organization_id`);

ALTER TABLE `email_module_email_campaigns`
	ADD COLUMN `campaign_id` INT(11) NULL DEFAULT NULL AFTER `organization_id`,
	ADD INDEX `campaign_id` (`campaign_id`);
