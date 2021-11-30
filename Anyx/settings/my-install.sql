INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('anyx', 'Anyx interface', 'the interface that help other resources to integrate with a social engine', '5.0.0', 1, 'extra') ;


ALTER TABLE `engine4_users` ADD `relational_id` INT(11) NULL AFTER `user_id`;