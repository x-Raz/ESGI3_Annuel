CREATE TABLE `page` (
  `id`               INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(255)        NOT NULL,
  `description`      VARCHAR(255)        NOT NULL,
  `url`              VARCHAR(255)        NOT NULL,
  `publish`          INT(11)             NOT NULL,
  `created_at`       TIMESTAMP            NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       TIMESTAMP
);