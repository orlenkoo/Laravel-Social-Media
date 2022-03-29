ALTER TABLE `employees`
    ADD COLUMN `profile_image_gcs_file_url` TEXT NULL AFTER `receive_sms_alert`,
    ADD COLUMN `profile_image_file_url` TEXT NULL AFTER `profile_image_gcs_file_url`;

ALTER TABLE `employees`
	ADD COLUMN `country_code` TEXT NULL AFTER `designation`;