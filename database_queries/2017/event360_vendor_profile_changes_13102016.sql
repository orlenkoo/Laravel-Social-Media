CREATE TABLE `event360_vendor_profile_changes` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`event360_vendor_profile_id` INT(11) NOT NULL,
	`changed_model` TEXT NOT NULL,
	`changed_model_id` INT(11) NOT NULL,
	`before_snapshot` TEXT NOT NULL,
	`after_snapshot` TEXT NOT NULL,
	`changed_by` INT(11) NOT NULL,
	`changed_on` DATETIME NOT NULL,
	`status` TEXT NOT NULL,
	`event360_remarks` TEXT NOT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`),
	INDEX `event360_vendor_profile_id` (`event360_vendor_profile_id`),
	INDEX `changed_by` (`changed_by`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

ALTER TABLE `event360_vendor_profile_changes`
	ADD COLUMN `status_changed_by` INT(11) NOT NULL AFTER `status`,
	ADD COLUMN `status_changed_on` DATETIME NOT NULL AFTER `status_changed_by`,
	ADD INDEX `status_changed_by` (`status_changed_by`);
