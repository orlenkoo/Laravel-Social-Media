ALTER TABLE `quotation_items`
	ADD COLUMN `taxable` TINYINT NULL DEFAULT '0' AFTER `unit_cost`;

	ALTER TABLE `quotation_items`
	ADD COLUMN `tax` DECIMAL(10,2) NULL DEFAULT NULL AFTER `taxable`;


ALTER TABLE `quotations`
	CHANGE COLUMN `taxes` `total_taxes` DECIMAL(15,2) NULL DEFAULT NULL AFTER `discount_value`;
