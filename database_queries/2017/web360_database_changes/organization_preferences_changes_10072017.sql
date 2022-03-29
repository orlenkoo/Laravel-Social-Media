ALTER TABLE `organization_preferences`
	ADD COLUMN `logo_gcs_file_url` TEXT NULL AFTER `tax_percentage`,
	ADD COLUMN `logo_image_url` TEXT NULL AFTER `logo_gcs_file_url`;