<?php
    trait PostSql {
        // Main SQL Structure
        static protected $sqlStructure = "
            CREATE TABLE IF NOT EXISTS posts (
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                author INT(10) UNSIGNED NOT NULL DEFAULT 0,
                authorName VARCHAR(50) NOT NULL,
                comments INT(10) UNSIGNED NOT NULL DEFAULT 0,
                content TEXT NOT NULL,
                createdBy INT(10) UNSIGNED NOT NULL,
                createdDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00',
                postDate DATE NOT NULL DEFAULT '0001-01-01 00:00:00',
                status TINYINT(1) UNSIGNED NOT NULL DEFAULT 0,
                title VARCHAR(50) NOT NULL,
                catIds VARCHAR(255) DEFAULT NULL,
                tagIds VARCHAR(255) DEFAULT NULL,
                labelIds VARCHAR(255) DEFAULT NULL,
                imageName VARCHAR(150) DEFAULT NULL,
                mediaContentIds VARCHAR(255) DEFAULT NULL,
                KEY author (author),
                KEY createdBy (createdBy)
            ) ENGINE=InnoDB
        ";

        // connecting tables
        static protected $otherTables = [
            "posts_to_media_content" => "
                CREATE TABLE IF NOT EXISTS posts_to_media_content ( 
                    postId INT(10) UNSIGNED NOT NULL,
                    mediaContentId INT(10) UNSIGNED NOT NULL,
                    PRIMARY KEY (postId, mediaContentId),
                    FOREIGN KEY (postId) REFERENCES posts(id) ON DELETE CASCADE,
                    FOREIGN KEY (mediaContentId) REFERENCES media_content(id) ON DELETE CASCADE 
                ) ENGINE=InnoDB
            ",
            "posts_to_tags" => "
                CREATE TABLE IF NOT EXISTS posts_to_tags (
                    postId INT(10) UNSIGNED NOT NULL,   
                    tagId INT(10) UNSIGNED NOT NULL,
                    PRIMARY KEY (postId, tagId),
                    FOREIGN KEY (postId) REFERENCES posts(id) ON DELETE CASCADE,
                    FOREIGN KEY (tagId) REFERENCES tags(id) ON DELETE CASCADE
                ) ENGINE=InnoDB
            ",
            "posts_to_labels" => "
                CREATE TABLE IF NOT EXISTS posts_to_labels ( 
                    postId INT(10) UNSIGNED NOT NULL,
                    labelId INT(10) UNSIGNED NOT NULL,
                    PRIMARY KEY (postId, labelId),
                    FOREIGN KEY (postId) REFERENCES posts(id) ON DELETE CASCADE,
                    FOREIGN KEY (labelId) REFERENCES labels(id) ON DELETE CASCADE
                ) ENGINE=InnoDB
            ",
            "posts_to_categories" => "
                CREATE TABLE IF NOT EXISTS posts_to_categories ( 
                    postId INT(10) UNSIGNED NOT NULL,
                    categoryId INT(10) UNSIGNED NOT NULL,
                    PRIMARY KEY (postId, categoryId),
                    FOREIGN KEY (postId) REFERENCES posts(id) ON DELETE CASCADE, 
                    FOREIGN KEY (categoryId) REFERENCES categories(id) ON DELETE CASCADE
                ) ENGINE=InnoDB
            "
        ];
    }
?>
