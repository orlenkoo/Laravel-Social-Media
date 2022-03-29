ALTER TABLE `delacon_properties`
	ADD COLUMN `delacon_cid` TEXT NULL AFTER `delacon_tracking_numbers`;

ALTER TABLE `event360_calls`
	ADD COLUMN `delacon_cid` TEXT NULL AFTER `id`,
	ADD COLUMN `delacon_call_id` TEXT NULL AFTER `delacon_cid`;

ALTER TABLE `event360_calls`
	ADD COLUMN `api_call_type` TEXT NULL AFTER `recording_url`;