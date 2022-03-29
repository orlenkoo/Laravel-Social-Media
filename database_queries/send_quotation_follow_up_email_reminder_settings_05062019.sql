ALTER TABLE `organization_preferences`
	ADD COLUMN `send_quotation_follow_up_email_reminder` TINYINT(4) NULL DEFAULT '1' AFTER `send_quotation_for_approval`;