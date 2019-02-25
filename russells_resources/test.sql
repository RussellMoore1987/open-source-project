SELECT mc.alt, mc.name
FROM media_content AS mc
INNER JOIN posts_to_media_content AS ptmc
    ON ptmc.postId = 22
WHERE mc.sort = 1
LIMIT 1;

