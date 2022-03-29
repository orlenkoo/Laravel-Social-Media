DROP TABLE `designations`;

DROP TABLE `contracts`;

DROP TABLE `customer_remarks`;

DROP TABLE `lead_sources`;

DROP TABLE `account_sources`;

DROP TABLE `stage_histories`;

DROP TABLE `stages`;

DROP TABLE `attachment_types`;

DROP TABLE `attachments`;

DROP TABLE `purchase_request_approvals`;

DROP TABLE `purchase_requests`;

DROP TABLE `tags`;

DROP TABLE `customer_tags`;

DROP TABLE `alert_assignees`;

DROP TABLE `alerts`;

ALTER TABLE `employees`
	CHANGE COLUMN `user_level_id` `user_level` TEXT NULL DEFAULT NULL AFTER `surname`,
	CHANGE COLUMN `designation_title` `designation` TEXT NULL AFTER `user_level`,
	DROP COLUMN `salutation`,
	DROP COLUMN `designation_id`,
	DROP COLUMN `username`,
	DROP COLUMN `extension`,
	DROP COLUMN `nickname`,
	DROP COLUMN `signature_url`,
	DROP COLUMN `resigned`,
	DROP COLUMN `employment_status`,
	DROP COLUMN `join_date`,
	DROP COLUMN `demo_mode`;

ALTER TABLE `employees`
	ADD COLUMN `project_contact_person` INT NULL DEFAULT '1' AFTER `password`;

