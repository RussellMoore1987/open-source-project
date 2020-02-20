<?php
    trait TagSql {
        // Main SQL Structure
        static protected $sqlStructure = "
            CREATE TABLE IF NOT EXISTS tags ( 
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(50) NOT NULL,
                note VARCHAR(255) DEFAULT NULL,
                useTag TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 
            ) ENGINE=InnoDB
        ";
        // for useTag
        // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
    }
?>
