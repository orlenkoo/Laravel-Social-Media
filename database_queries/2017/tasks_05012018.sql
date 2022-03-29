CREATE TABLE `tasks` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`organization_id` INT(11) NULL DEFAULT NULL,
	`title` TEXT NULL,
	`created_by` INT(11) NULL DEFAULT NULL,
	`assigned_to` INT(11) NULL DEFAULT NULL,
	`from_date_time` DATETIME NULL DEFAULT NULL,
	`to_date_time` DATETIME NULL DEFAULT NULL,
	`location` TEXT NULL,
	`description` TEXT NULL,
	`status` INT(11) NULL DEFAULT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`),
	INDEX `created_by` (`created_by`),
	INDEX `assigned_to` (`assigned_to`),
	INDEX `organization_id` (`organization_id`)
);

CREATE TABLE `task_reminders` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`task_id` INT(11) NULL DEFAULT NULL,
	`reminder_type` TEXT NULL,
	`time` INT(11) NULL DEFAULT NULL,
	`time_unit` TEXT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`),
	INDEX `task_id` (`task_id`)
);

CREATE TABLE `task_guests` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`task_id` INT(11) NULL DEFAULT NULL,
	`guest_email` TEXT NULL,
	`created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	PRIMARY KEY (`id`),
	INDEX `task_id` (`task_id`)
);

