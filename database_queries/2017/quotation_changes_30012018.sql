ALTER TABLE `quotation_items`
	ADD COLUMN `unit_of_measure` TEXT NULL AFTER `description`,
	ADD COLUMN `no_of_units` DECIMAL(10,2) NULL AFTER `unit_of_measure`,
	ADD COLUMN `unit_cost` DECIMAL(10,2) NULL AFTER `no_of_units`,
	CHANGE COLUMN `cost` `cost` DECIMAL(10,2) NULL AFTER `unit_cost`;
