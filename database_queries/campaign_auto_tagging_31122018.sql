ALTER TABLE `leads`
	ADD COLUMN `auto_tagged_campaign` TEXT NULL DEFAULT NULL AFTER `lead_rating_updated_by`;
