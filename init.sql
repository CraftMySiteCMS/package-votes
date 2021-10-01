CREATE TABLE if not exists `cms_votes_sites`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `title` VARCHAR(255) NOT NULL ,
    `url` VARCHAR(255) NOT NULL ,
    `time` INT(10) UNSIGNED NOT NULL ,
    `id_unique` VARCHAR(10) NOT NULL ,
    `rewards_id` INT(11) NULL ,
    `date_create` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE if not exists `cms_votes_votes` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `pseudo` VARCHAR(255) NOT NULL ,
    `ip` VARCHAR(39) NOT NULL ,
    `id_site` INT(3) UNSIGNED NOT NULL ,
    `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `active` INT(1) NOT NULL , PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE if not exists `cms_votes_config` (
    `top_show` INT(10) UNSIGNED NOT NULL DEFAULT '10' ,
    `reset` INT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '1 = reset tous les mois\r\n0 = pas de reset mensuel' ,
    `auto_top_reward_active` INT(1) NULL DEFAULT '0' COMMENT '0 = pas de récompenses automatiques\r\n1 = récompenses automatiques activés',
    `auto_top_reward` TEXT NULL COMMENT 'Récompenses automatiques pour les x premiers (JSON)'
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

CREATE TABLE if not exists`cms_votes_rewards` (
    `rewards_id` INT(11) NOT NULL  ,
    `title` VARCHAR(255) NOT NULL ,
    `action` TEXT NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;


CREATE TABLE if not exists `cms_votes_logs_rewards` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `pseudo` VARCHAR(255) NOT NULL ,
    `reward_title` VARCHAR(255) NOT NULL ,
    `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    PRIMARY KEY (`id`)
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



#Generate default config
INSERT INTO `cms_votes_config` (`top_show`, `reset`, `auto_top_reward_active`, `auto_top_reward`) VALUES ('10', '1', '0', NULL);

