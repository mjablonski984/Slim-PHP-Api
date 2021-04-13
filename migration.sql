CREATE DATABASE IF NOT EXISTS `users_database`;
USE `users_database`;


CREATE TABLE IF NOT EXISTS `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(50) NOT NULL,
    `last_name` VARCHAR(50) NOT NULL,
    `username` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `dark_mode` BOOLEAN NOT NULL DEFAULT false,
    PRIMARY KEY (`id`)
);


INSERT INTO `users` (`first_name`, `last_name`, `username`) VALUES
    ("John", "Doe", "Johndoe"),
    ("Jane", "Doe", "Janedoe"),
    ("Carl", "Doe", "Carldoe"),
    ("Bob", "Smith", "Bsmith"),
    ("Anna", "Smith", "Asmith");
