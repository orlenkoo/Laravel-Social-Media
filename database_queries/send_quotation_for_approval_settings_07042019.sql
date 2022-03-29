ALTER TABLE `organization_preferences`
	ADD COLUMN `send_quotation_for_approval` TINYINT NULL DEFAULT '1' AFTER `logo_image_url`;
