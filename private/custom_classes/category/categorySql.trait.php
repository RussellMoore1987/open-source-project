<?php
    trait CategorySql {
        // Main SQL Structure
        static protected $sqlStructure = "
            CREATE TABLE IF NOT EXISTS categories ( 
                id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(50) NOT NULL,
                subCatId INT(10) UNSIGNED NOT NULL DEFAULT 0,
                note VARCHAR(255) DEFAULT NULL,
                useCat TINYINT(1) UNSIGNED NOT NULL,
                KEY subCatId (subCatId) 
            ) ENGINE=InnoDB
        ";
        // for useCat
        // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
    }
?>
