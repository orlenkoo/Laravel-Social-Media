ALTER TABLE `landing_pages`
	ADD COLUMN `landing_page_html` TEXT NULL AFTER `ftp_path`,
	ADD COLUMN `landing_page_css` TEXT NULL AFTER `landing_page_html`;
