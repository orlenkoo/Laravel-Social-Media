ALTER TABLE `quotations`
	ADD COLUMN `discount_percentage` DECIMAL(15,2) NULL DEFAULT NULL AFTER `net_total`,
	ADD COLUMN `discount_value` DECIMAL(15,2) NULL DEFAULT NULL AFTER `discount_percentage`;


ALTER TABLE `quotations`
	CHANGE COLUMN `sub_total` `sub_total` DECIMAL(15,2) NULL DEFAULT NULL AFTER `fax`,
	CHANGE COLUMN `net_total` `net_total` DECIMAL(15,2) NULL DEFAULT NULL AFTER `taxes`;
