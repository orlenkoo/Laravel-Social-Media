ALTER TABLE `employees`
	ADD COLUMN `new_lead_alert` INT NULL DEFAULT '1' AFTER `profile_image_file_url`,
	ADD COLUMN `lead_assignment_alert` INT NULL DEFAULT '1' AFTER `new_lead_alert`,
	ADD COLUMN `quotation_status_update_alert` INT NULL DEFAULT '1' AFTER `lead_assignment_alert`;
