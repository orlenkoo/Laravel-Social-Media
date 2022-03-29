ALTER TABLE `leads`
	ADD COLUMN `lead_capture_method` TEXT NULL AFTER `lead_source_id`;
