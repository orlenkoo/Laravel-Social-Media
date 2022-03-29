CREATE TABLE `quotations` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) DEFAULT NULL,
  `quoted_datetime` datetime DEFAULT NULL,
  `quoted_by` int(11) DEFAULT NULL,
  `project_quote` text,
  `company_name` text,
  `address` text,
  `contact_person` text,
  `email` text,
  `phone_number` text,
  `fax` text,
  `net_total` text,
  `taxes` text,
  `sub_total` text,
  `payment_terms` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `quoted_by` (`quoted_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `quotation_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` int(11) DEFAULT NULL,
  `description` text,
  `cost` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_id` (`quotation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `quotations`
	ADD COLUMN `quotation_status` TEXT NULL AFTER `payment_terms`;
