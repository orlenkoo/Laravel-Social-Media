
ALTER TABLE `quotations`
	CHANGE COLUMN `net_total` `net_total` DECIMAL(15,2) NULL AFTER `fax`,
	CHANGE COLUMN `taxes` `taxes` DECIMAL(15,2) NULL AFTER `net_total`,
	CHANGE COLUMN `sub_total` `sub_total` DECIMAL(15,2) NULL AFTER `taxes`;

	ALTER TABLE `organization_preferences`
	CHANGE COLUMN `tax_percentage` `tax_percentage` DECIMAL(15,2) NULL AFTER `terms_and_conditions`;

