ALTER TABLE `leads`
	ADD COLUMN `customer_id` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `organization_id`,
	ADD INDEX `customer_id` (`customer_id`);

ALTER TABLE `customers`
	CHANGE COLUMN `account_name` `customer_name` TEXT NOT NULL AFTER `account_owner_id`,
	CHANGE COLUMN `road_name` `address_line_1` TEXT NULL AFTER `industry_id`,
	CHANGE COLUMN `unit_number` `address_line_2` TEXT NULL AFTER `address_line_1`,
	CHANGE COLUMN `building_name` `city` TEXT NULL AFTER `address_line_2`,
	CHANGE COLUMN `country` `postal_code` TEXT NULL AFTER `city`,
	CHANGE COLUMN `postcode` `state` TEXT NULL AFTER `postal_code`,
	ADD COLUMN `country_id` INT NULL AFTER `state`,
	DROP COLUMN `account_source`,
	DROP COLUMN `affiliate_partner_id`,
	DROP COLUMN `parent_account_id`,
	DROP COLUMN `formerly_known_as`,
	DROP COLUMN `myob_id`,
	DROP COLUMN `status`,
	DROP COLUMN `billing_road_name`,
	DROP COLUMN `billing_unit_number`,
	DROP COLUMN `billing_building_name`,
	DROP COLUMN `billing_country`,
	DROP COLUMN `billing_postcode`,
	DROP COLUMN `assigned_sales_person`,
	DROP COLUMN `duplicate_account`,
	DROP COLUMN `last_view_date_time`,
	DROP COLUMN `verified`,
	ADD INDEX `country_id` (`country_id`);

RENAME TABLE `event360_countries` TO `countries`;

