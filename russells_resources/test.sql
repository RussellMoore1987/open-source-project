

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


SELECT * FROM labels WHERE title LIKE '%GoGo%'  LIMIT 10 OFFSET 10;
SELECT * FROM labels WHERE title LIKE '%GoGo%'  LIMIT 10 OFFSET 0;
SELECT COUNT(*) FROM `labels` WHERE title LIKE '%GoGo%'; 



SELECT * FROM labels  LIMIT 10 OFFSET 20;
SELECT COUNT(*) FROM `labels`; 



SELECT * FROM users WHERE firstName IN ( 'sammy', 'ma', 'ca' ) LIMIT 10 OFFSET 0;

SELECT * FROM users WHERE tagIds LIKE '%18%'  AND catIds LIKE '%20%'  AND id IN ( '30', '22', '1', '2', '3', '4', '5' ) AND firstName LIKE '%sa%' OR firstName LIKE '%Mo%' OR firstName LIKE '%Na%' OR firstName LIKE '%ma%' OR firstName LIKE '%ca%'  AND lastName LIKE '%Mu%'  AND note LIKE '%Praesent%'  AND createdDate >= '2000-06-18'  ORDER BY createdDate DESC, firstName DESC, lastName LIMIT 3 OFFSET 0; 

SELECT * FROM users WHERE tagIds LIKE '%18%'  AND catIds LIKE '%20%'  AND id IN ( '30', '22', '1', '2', '3', '4', '5' ) AND (firstName LIKE '%sa%' OR firstName LIKE '%Mo%' OR firstName LIKE '%Na%' OR firstName LIKE '%ma%' OR firstName LIKE '%ca%')  AND (lastName LIKE '%Mu%'  AND note LIKE '%Praesent%')  AND createdDate >= '2000-06-18'  ORDER BY createdDate DESC, firstName DESC, lastName LIMIT 3 OFFSET 0; 

SELECT * FROM users WHERE (tagIds LIKE '%18%' ) AND (catIds LIKE '%20%' ) AND id IN ( '30', '22', '1', '2', '3', '4', '5' ) AND (firstName LIKE '%sa%' OR firstName LIKE '%Mo%' OR firstName LIKE '%Na%' OR firstName LIKE '%ma%' OR firstName LIKE '%ca%' ) AND (lastName LIKE '%Mu%' ) AND (note LIKE '%Praesent%' ) AND (createdDate >= '2000-06-18' ) ORDER BY createdDate DESC, firstName DESC, lastName LIMIT 3 OFFSET 0







SELECT * FROM users WHERE address LIKE '%1%' AND emailAddress LIKE '%jakubowski.ena@yahoo.com%' AND id = '12' AND (firstName LIKE '%sa%' OR firstName LIKE '%Mo%' OR firstName LIKE '%Na%' OR firstName LIKE '%ma%' OR firstName LIKE '%ca%' OR firstName LIKE '%er%' OR firstName LIKE '%my%' ) AND lastName LIKE '%er%' AND mediaContentId = '3' AND note LIKE '%ne%' AND phoneNumber LIKE '%844%' AND title LIKE '%Glazier%' AND createdDate >= '2000-06-18' AND createdDate <= '2019-06-03' AND (title LIKE '%Myrtis%' OR note LIKE '%Myrtis%' OR firstName LIKE '%Myrtis%' OR lastName LIKE '%Myrtis%' OR phoneNumber LIKE '%Myrtis%' ) ORDER BY createdDate DESC, firstName DESC, lastName LIMIT 3 OFFSET 0 

SELECT *
FROM posts_to_media_content
INNER JOIN table_name
    ON postId = 222;


   
SELECT media_content.id, media_content.alt, media_content.name 
FROM media_content
INNER JOIN posts_to_media_content 
ON posts_to_media_content.mediaContentId = media_content.id 
WHERE posts_to_media_content.postId = 222 
AND media_content.type IN ('PNG', 'JPEG', 'JPG', 'GIF') ;
