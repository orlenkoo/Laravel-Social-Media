ALTER TABLE `employees`
	ADD COLUMN `demo_mode` INT(11) NULL DEFAULT NULL AFTER `project_contact_person`;

ALTER TABLE `employees`
	CHANGE COLUMN `demo_mode` `demo_mode` INT(11) NULL DEFAULT '0' AFTER `project_contact_person`;
