ALTER TABLE `quotations`
	ADD COLUMN `quotation_closed_by` TEXT NULL AFTER `quotation_closed_at`;

UPDATE quotations SET quotation_closed_by = quoted_by WHERE quotation_status = 'Closed';