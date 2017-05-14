CREATE TABLE `media` (
  `id`         INT(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(255)        NOT NULL,
  `path`       VARCHAR(255)        NOT NULL,
  `type`       VARCHAR(25)         NOT NULL,
  `extension`  VARCHAR(25)         NOT NULL,
  `id_user`    INT(11)             NOT NULL,
  `created_at` DATETIME            NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME,
  CONSTRAINT FK_MEDIA_USER FOREIGN KEY (id_user) REFERENCES user (id)
);