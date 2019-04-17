

-- conect
-- devPass1!
-- devteam




SELECT mc.alt, mc.name
FROM media_content AS mc
INNER JOIN posts_to_media_content AS ptmc
    ON ptmc.postId = 22
WHERE mc.sort = 1
LIMIT 1;




SELECT id, note, subCatId, title, useCat FROM categories WHERE useCat = 1;

INSERT INTO table_name (column1_name, column2_name)
VALUES (column1_value_num, 'column1_value_string');


SET FOREIGN_KEY_CHECKS = 1;

-- show table columns
SHOW COLUMNS FROM posts_to_categories;
-- or
DESC posts_to_categories;
ALTER TABLE posts_to_categories ENGINE=Innodb;

SHOW CREATE TABLE posts_to_categories;
SHOW CREATE TABLE `posts`;

INSERT INTO `posts_to_categories` (postId, categoryId)
VALUES (782, 10);

SELECT * FROM posts_to_media_content;

DESC posts_to_media_content;
SHOW CREATE TABLE posts_to_media_content;



INSERT INTO posts_to_media_content (postId, mediaContentId) VALUES (307, 3)
mediaContentId
mediaContentId

`postId` int(10) unsigned NOT NULL, `mediaContentId` int(10) unsigned NOT NULL, PRIMARY KEY (`postId`,`mediaContentId`), KEY `mediaContentId` (`mediaContentId`), CONSTRAINT `posts_to_media_content_ibfk_1` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`), CONSTRAINT `posts_to_media_content_ibfk_2` FOREIGN KEY (`mediaContentId`) REFERENCES `media_content` (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=latin1

SELECT *
FROM table_name
INNER JOIN table_name
    ON column_name = column_name;