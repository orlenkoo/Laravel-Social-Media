ALTER TABLE `quotations`
    ADD COLUMN `quotation_closed_at` DATETIME NULL AFTER `quotation_status`

UPDATE quotations SET quotation_closed_at = updated_at WHERE quotation_status = 'Closed';