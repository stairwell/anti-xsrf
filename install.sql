CREATE TABLE `testdb`.`anti_xsrf` IF NOT EXISTS (
  `token_id` INT NOT NULL AUTO_INCREMENT,
  `token_string` VARCHAR(255) NULL,
  `token_expires` INT NULL,
  `token_used` INT NULL DEFAULT 0,
  PRIMARY KEY (`token_id`),
  UNIQUE INDEX `token_string_UNIQUE` (`token_string` ASC)
);
