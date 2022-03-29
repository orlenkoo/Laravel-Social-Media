ALTER TABLE `leads`
	ADD COLUMN `term` TEXT NULL AFTER `medium`;

ALTER TABLE `leads`
	CHANGE COLUMN `webtics_pixel_session_code` `webtics_pixel_session_id` TEXT NULL AFTER `term`;
