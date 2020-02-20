<?php
    trait MediaContentSql {
        // Main SQL Structure
        static protected $sqlStructure = "
            CREATE TABLE IF NOT EXISTS media_content (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                type VARCHAR(25) NOT NULL,
                note VARCHAR(255) DEFAULT NULL,
                alt VARCHAR(75) DEFAULT NULL,
                createdBy INT(10) UNSIGNED NOT NULL DEFAULT 0,
                createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00',
                KEY createdBy (createdBy)
            ) ENGINE=InnoDB
        ";
        // for type
        //  PNG,JPG,MP4

        // connecting tables
        static protected $otherTables = [
            "media_content_to_tags" => "
                CREATE TABLE IF NOT EXISTS media_content_to_tags ( 
                    mediaContentId INT(10) UNSIGNED NOT NULL,
                    tagId INT(10) UNSIGNED NOT NULL,
                    PRIMARY KEY (mediaContentId, tagId),
                    FOREIGN KEY (mediaContentId) REFERENCES media_content(id) ON DELETE CASCADE,
                    FOREIGN KEY (tagId) REFERENCES tags(id) ON DELETE CASCADE 
                ) ENGINE=InnoDB
            ",
            "media_content_to_labels" => "
                CREATE TABLE IF NOT EXISTS media_content_to_labels (
                    mediaContentId INT(10) UNSIGNED NOT NULL,
                    labelId INT(10) UNSIGNED NOT NULL,
                    PRIMARY KEY (mediaContentId, labelId),
                    FOREIGN KEY (mediaContentId) REFERENCES media_content(id) ON DELETE CASCADE,
                    FOREIGN KEY (labelId) REFERENCES labels(id) ON DELETE CASCADE 
                ) ENGINE=InnoDB
            ",
            "media_content_to_categories" => "
                CREATE TABLE IF NOT EXISTS media_content_to_categories ( 
                    mediaContentId INT(10) UNSIGNED NOT NULL,
                    categoryId INT(10) UNSIGNED NOT NULL,
                    PRIMARY KEY (mediaContentId, categoryId),
                    FOREIGN KEY (mediaContentId) REFERENCES media_content(id) ON DELETE CASCADE,
                    FOREIGN KEY (categoryId) REFERENCES categories(id) ON DELETE CASCADE 
                ) ENGINE=InnoDB
            "
        ];
    }
?>
