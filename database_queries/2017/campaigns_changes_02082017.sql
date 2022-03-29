ALTER TABLE `campaigns`
	ADD COLUMN `campaign_content` TEXT NOT NULL AFTER `campaign_name`;

ALTER TABLE `leads`
	ADD COLUMN `utm_content` TEXT NULL AFTER `utm_campaign`;