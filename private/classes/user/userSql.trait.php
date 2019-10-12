<?php
    trait UserSql {
        // SQL structure
        static protected $sqlStructure = "
            CREATE TABLE IF NOT EXISTS users ( 
            id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, 
            username VARCHAR(35) NOT NULL UNIQUE, 
            password VARCHAR(50) NOT NULL, 
            firstName VARCHAR(25) NOT NULL, 
            lastName VARCHAR(25) NOT NULL, 
            address VARCHAR(150) DEFAULT NULL, 
            phoneNumber VARCHAR(25) DEFAULT NULL, 
            emailAddress VARCHAR(150) NOT NULL, 
            title VARCHAR(45) DEFAULT NULL, 
            mediaContentId INT(10) UNSIGNED NOT NULL DEFAULT 0, 
            adminNote VARCHAR(255) DEFAULT NULL, 
            note VARCHAR(255) DEFAULT NULL, 
            showOnWeb TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, 
            createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0, 
            createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', 
            imageName VARCHAR(150) DEFAULT NULL, 
            catIds VARCHAR(255) DEFAULT NULL, 
            tagIds VARCHAR(255) DEFAULT NULL, 
            labelIds VARCHAR(255) DEFAULT NULL, 
            KEY createdBy (createdBy)) ENGINE=InnoDB
        ";
    }
?>