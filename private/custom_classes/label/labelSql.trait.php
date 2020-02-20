<?php
    trait LabelSql {
        // Main SQL Structure
        static protected $sqlStructure = "
            CREATE TABLE IF NOT EXISTS labels ( 
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(50) NOT NULL,
                note VARCHAR(255) DEFAULT NULL,
                useLabel TINYINT(1) UNSIGNED DEFAULT 1
            ) ENGINE=InnoDB
        ";
        // for useLabel
        // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
    }
?>
