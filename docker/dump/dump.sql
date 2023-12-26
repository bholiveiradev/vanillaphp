CREATE TABLE `products` (
    `id` INT (11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `stock` INT(8),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;