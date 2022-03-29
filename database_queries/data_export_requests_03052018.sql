CREATE TABLE `data_export_requests` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `organization_id` INT(11) NOT NULL,
    `export_type` TEXT NULL,
    `generated_date` DATETIME NULL DEFAULT NULL,
    `status` TEXT NULL,
    `export_parameters` TEXT NULL,
    `download_link` TEXT NULL,
    `requested_by` INT(11) NULL DEFAULT NULL,
    `no_of_records` INT(11) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    `updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`),
    INDEX `organization_id` (`organization_id`, `requested_by`)
);