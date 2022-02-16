CREATE TABLE IF NOT EXISTS `cms_votes_sites`
(
    `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(255)     NOT NULL,
    `url`         VARCHAR(255)     NOT NULL,
    `time`        INT(10) UNSIGNED NOT NULL,
    `id_unique`   VARCHAR(255)     NOT NULL,
    `rewards_id`  INT(11)          NULL,
    `date_create` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE IF NOT EXISTS `cms_votes_votes`
(
    `id`      int(10) UNSIGNED NOT NULL,
    `id_user` int(11)          NOT NULL,
    `ip`      varchar(39)      NOT NULL,
    `id_site` int(10) UNSIGNED NOT NULL,
    `date`    timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE IF NOT EXISTS `cms_votes_config`
(
    `top_show`               INT(10) UNSIGNED NOT NULL DEFAULT '10',
    `reset`                  INT(1) UNSIGNED  NOT NULL DEFAULT '1' COMMENT '1 = reset tous les mois\r\n0 = pas de reset mensuel',
    `auto_top_reward_active` INT(1)           NULL     DEFAULT '0' COMMENT '0 = pas de récompenses automatiques\r\n1 = récompenses automatiques activés',
    `auto_top_reward`        TEXT             NULL COMMENT 'Récompenses automatiques pour les x premiers (JSON)'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `cms_votes_rewards`
(
    `rewards_id` INT(11)      NOT NULL,
    `title`      VARCHAR(255) NOT NULL,
    `action`     TEXT         NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE IF NOT EXISTS `cms_votes_logs_rewards`
(
    `id`        int(10) UNSIGNED NOT NULL,
    `user_id`   int(11)          NOT NULL,
    `reward_id` int(11)                   DEFAULT NULL,
    `date`      timestamp        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE IF NOT EXISTS `cms_votes_votepoints`
(
    `id`      int(11) NOT NULL,
    `id_user` int(11) NOT NULL,
    `amount`  int(11) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

ALTER TABLE `cms_votes_rewards`
    ADD PRIMARY KEY (`rewards_id`),
    ADD UNIQUE KEY `rewards_id` (`rewards_id`);

ALTER TABLE `cms_votes_rewards`
    MODIFY `rewards_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cms_votes_sites`
    ADD KEY `rewards_id` (`rewards_id`);

ALTER TABLE `cms_votes_sites`
    ADD CONSTRAINT `fk_cms_votes_rewards` FOREIGN KEY (`rewards_id`) REFERENCES `cms_votes_rewards` (`rewards_id`);

ALTER TABLE `cms_votes_votes`
    ADD PRIMARY KEY (`id`),
    ADD KEY `id_user` (`id_user`),
    ADD KEY `id_site` (`id_site`);

ALTER TABLE `cms_votes_votes`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `cms_votes_votes`
    ADD CONSTRAINT `cms_votes_votes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `cms_users` (`user_id`),
    ADD CONSTRAINT `cms_votes_votes_ibfk_2` FOREIGN KEY (`id_site`) REFERENCES `cms_votes_sites` (`id`);
COMMIT;

ALTER TABLE `cms_votes_logs_rewards`
    ADD PRIMARY KEY (`id`),
    ADD KEY `reward_id` (`reward_id`),
    ADD KEY `user_id` (`user_id`);

ALTER TABLE `cms_votes_logs_rewards`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `cms_votes_logs_rewards`
    ADD CONSTRAINT `cms_votes_logs_rewards_ibfk_1` FOREIGN KEY (`reward_id`) REFERENCES `cms_votes_rewards` (`rewards_id`) ON DELETE SET NULL ON UPDATE SET NULL,
    ADD CONSTRAINT `cms_votes_logs_rewards_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `cms_users` (`user_id`);
COMMIT;

ALTER TABLE `cms_votes_votepoints`
    ADD PRIMARY KEY (`id`),
    ADD UNIQUE KEY `id_user` (`id_user`);

ALTER TABLE `cms_votes_votepoints`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `cms_votes_votepoints`
    ADD CONSTRAINT `cms_votes_votepoints_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `cms_users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;


#Generate default config
INSERT INTO `cms_votes_config` (`top_show`, `reset`, `auto_top_reward_active`, `auto_top_reward`)
VALUES ('10', '1', '0', NULL);

