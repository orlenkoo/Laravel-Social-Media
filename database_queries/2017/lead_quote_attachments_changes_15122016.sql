CREATE TABLE `event360_enquiry_lead_quote_attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event360_enquiry_lead_quote_id` int(11) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `title` text,
  `gcs_file_url` text,
  `image_url` text,
  `status` int(11) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event360_enquiry_lead_quote_id` (`event360_enquiry_lead_quote_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;