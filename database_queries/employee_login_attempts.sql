ALTER TABLE webtics_product_live.employees ADD login_attempts INT DEFAULT 0 NOT NULL;
ALTER TABLE webtics_product_live.employees CHANGE login_attempts login_attempts INT DEFAULT 0 NOT NULL AFTER took_the_guide;


ALTER TABLE webtics_product_live.employees ADD last_login_attempt_time DATETIME NULL;
ALTER TABLE webtics_product_live.employees CHANGE last_login_attempt_time last_login_attempt_time DATETIME NULL AFTER login_attempts;
