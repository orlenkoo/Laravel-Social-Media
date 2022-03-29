ALTER TABLE `employees`
	ADD COLUMN `old_layout_notice` INT(11) NULL DEFAULT '0' AFTER `status`;

ALTER TABLE `employees`
	CHANGE COLUMN `old_layout_notice` `use_old_layout` INT(11) NULL DEFAULT '0' AFTER `status`,
	ADD COLUMN `took_the_guide` INT(11) NULL DEFAULT '0' AFTER `use_old_layout`;

ALTER TABLE `employees`
	ADD COLUMN `selected_the_layout_once` INT(11) NULL DEFAULT '0' AFTER `status`;

