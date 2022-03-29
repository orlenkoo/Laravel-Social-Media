ALTER TABLE `contacts`
	ADD COLUMN `primary_contact` INT NULL DEFAULT '0' AFTER `contact_status`,
	DROP COLUMN `customer_name`;

ALTER TABLE `contacts`
	CHANGE COLUMN `phone_no` `phone_number` TEXT NULL AFTER `designation`,
	CHANGE COLUMN `other_phone` `other_phone_number` TEXT NULL AFTER `phone_number`,
	CHANGE COLUMN `mobile` `mobile_number` TEXT NULL AFTER `other_phone_number`;

ALTER TABLE `customers`
	CHANGE COLUMN `phone` `phone_number` TEXT NULL AFTER `country_id`,
	CHANGE COLUMN `fax` `fax_number` TEXT NULL AFTER `phone_number`;
