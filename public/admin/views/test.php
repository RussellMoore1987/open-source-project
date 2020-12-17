<?php
  $Post_obj = Post::find_by_id(255);  
  var_dump($Post_obj);
  
  $Tag_obj = Tag::find_by_id(25);  
  var_dump($Tag_obj);
?>