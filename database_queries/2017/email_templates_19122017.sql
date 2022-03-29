CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `name` text,
  `subject` text,
  `body` text,
  `to` text,
  `cc` text,
  `bcc` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organization_id` (`organization_id`)
) COLLATE='latin1_swedish_ci' ENGINE=InnoDB;

CREATE TABLE `email_template_user_defined_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `tag` text,
  `value` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organization_id` (`organization_id`)
) COLLATE='latin1_swedish_ci' ENGINE=InnoDB;

CREATE TABLE `email_template_user_defined_action_buttons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organization_id` int(11) DEFAULT NULL,
  `button_name` text,
  `url` text,
  `value_text` text,
  `style` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `organization_id` (`organization_id`)
) COLLATE='latin1_swedish_ci' ENGINE=InnoDB;

CREATE TABLE `email_template_pre_defined_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` text,
  `value` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) COLLATE='latin1_swedish_ci' ENGINE=InnoDB;

CREATE TABLE `email_template_pre_defined_action_buttons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `button_name` text,
  `url` text,
  `value_text` text,
  `style` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) COLLATE='latin1_swedish_ci' ENGINE=InnoDB;

ALTER TABLE `employees` 
ADD COLUMN `signature_html` TEXT NULL DEFAULT NULL AFTER `profile_image_file_url`,
ADD COLUMN `signature_file_url` TEXT NULL DEFAULT NULL AFTER `signature_html`,
ADD COLUMN `signature_gcs_file_url` TEXT NULL DEFAULT NULL AFTER `signature_file_url`;

ALTER TABLE `email_template_pre_defined_tags`
    ADD COLUMN `name` TEXT NULL AFTER `tag`;

ALTER TABLE `email_template_user_defined_tags`
    ADD COLUMN `name` TEXT NULL AFTER `tag`;

ALTER TABLE `email_template_pre_defined_action_buttons`
    ADD COLUMN `button_tag` TEXT NULL AFTER `id`;

ALTER TABLE `email_template_user_defined_action_buttons`
    ADD COLUMN `button_tag` TEXT NULL AFTER `organization_id`;

ALTER TABLE `email_templates`
ADD COLUMN `status` INT(11) NULL DEFAULT NULL AFTER `bcc`;

ALTER TABLE `email_template_user_defined_action_buttons`
ADD COLUMN `status` INT(11) NULL DEFAULT NULL AFTER `style`;

ALTER TABLE `email_template_user_defined_tags`
ADD COLUMN `status` INT(11) NULL DEFAULT NULL AFTER `value`;


