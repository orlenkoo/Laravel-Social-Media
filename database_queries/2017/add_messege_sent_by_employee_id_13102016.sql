ALTER TABLE `event360_messenger_thread_messages`
ADD COLUMN `sent_by_employee_id` INT NULL AFTER `sent_by`,
ADD INDEX `sent_by_employee_id` (`sent_by_employee_id`);