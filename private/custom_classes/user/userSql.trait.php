<?php
    trait UserSql {
        // Main SQL Structure
        static protected $sqlStructure = "
            CREATE TABLE IF NOT EXISTS users ( 
                id INT(10) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT, 
                username VARCHAR(35) NOT NULL UNIQUE, 
                hashedPassword VARCHAR(50) NOT NULL, 
                firstName VARCHAR(25) NOT NULL, 
                lastName VARCHAR(25) NOT NULL, 
                address VARCHAR(150) DEFAULT NULL, 
                phoneNumber VARCHAR(25) DEFAULT NULL, 
                emailAddress VARCHAR(150) NOT NULL UNIQUE, 
                title VARCHAR(45) DEFAULT NULL, 
                mediaContentId INT(10) UNSIGNED DEFAULT NULL, 
                adminNote VARCHAR(255) DEFAULT NULL, 
                note VARCHAR(255) DEFAULT NULL, 
                showOnWeb TINYINT(1) UNSIGNED NOT NULL DEFAULT 0, 
                createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0, 
                createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00', 
                imageName VARCHAR(150) DEFAULT NULL, 
                catIds VARCHAR(255) DEFAULT NULL, 
                tagIds VARCHAR(255) DEFAULT NULL, 
                labelIds VARCHAR(255) DEFAULT NULL, 
                KEY createdBy (createdBy),
                KEY firstName (firstName),
                KEY lastName (lastName)
            ) ENGINE=InnoDB;
        ";

        // connecting tables
        static protected $otherTables = [
            "users_to_tags" => "
                CREATE TABLE IF NOT EXISTS users_to_tags ( 
                    userId INT(10) UNSIGNED NOT NULL, 
                    tagId INT(10) UNSIGNED NOT NULL, 
                    PRIMARY KEY (userId, tagId), 
                    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE, 
                    FOREIGN KEY (tagId) REFERENCES tags(id) ON DELETE CASCADE ) ENGINE=InnoDB
            ",
            "users_to_labels" => "
                CREATE TABLE IF NOT EXISTS users_to_labels ( 
                    userId INT(10) UNSIGNED NOT NULL, 
                    labelId INT(10) UNSIGNED NOT NULL, 
                    PRIMARY KEY (userId, labelId), 
                    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE, 
                    FOREIGN KEY (labelId) REFERENCES labels(id) ON DELETE CASCADE ) ENGINE=InnoDB
            ",
            "users_to_categories" => "
                CREATE TABLE IF NOT EXISTS users_to_categories ( 
                    userId INT(10) UNSIGNED NOT NULL, 
                    categoryId INT(10) UNSIGNED NOT NULL, 
                    PRIMARY KEY (userId, categoryId), 
                    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE, 
                    FOREIGN KEY (categoryId) REFERENCES categories(id) ON DELETE CASCADE ) ENGINE=InnoDB
            "
        ];
    }
?>
