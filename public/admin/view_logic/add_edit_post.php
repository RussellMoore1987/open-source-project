<?php
    // todo: clean up echos, var_dumps, and var_dump, to remove it from the function/validation function page
    // @ logic for add_edit_post.php start
        // set page title
        $pageTitle = "Add/Edit Posts";

        // set defaults
        $postId = $_GET["postId"] ?? "add";
        // if not add make number
        if (!($postId == "add")) {
            // this forces the $postId to be an integer
            $postId = (int) $postId;
        }

        // # check to see if we have a real ID
            if (!($postId == "add")) {
                // this forces the $postId to be an integer
                $postId = (int) $postId;
                // get post for editing
                $Post_obj = Post::find_by_id($postId);
                // error handling, if not there, throw an error
                if (!$Post_obj) {
                    $Post_obj = new Post();
                    $Post_obj->errors[] = "No post with the ID of {$postId} exists";
                    $postId = "add";
                }
            } else {
                // create empty objects so page dose not brake
                $Post_obj = new Post();
            }

        // # if post request
            if (is_post_request() && isset($_POST["post"])) { 
                // if we are in add mode
                if ($postId == "add") {
                    $_POST["post"]['createdDate'] = date("Y-m-d");
                    // todo: need to replace with session value
                    $_POST["post"]['createdBy'] = 1;
                } 
                // if imageName is passed through set it
                if (!is_blank($_POST["post"]['possibleImageName'])) {
                    $_POST["post"]['imageName'] = $_POST["post"]['possibleImageName'];
                }
                // echo "post info ****************";
                // var_dump($_POST["post"]);
                // populate new object
                $Post_obj = new Post($_POST["post"]);
                // echo "Post_obj info ***********";
                // var_dump($Post_obj);
                // validate and save
                $Post_obj->save();
                // set id
                $postId = $Post_obj->id;

                // check to see if we have an ID
                if (!($postId === 0 || $postId === NULL) && !$Post_obj->errors) {
                    // get full post object
                    $Post_obj = Post::find_by_id($postId);
                }
            }

            // echo "Post_obj info ***********";
            // var_dump($Post_obj);

        // # get all extended info
            $postExtendedInfo_array = $Post_obj->get_extended_info();
            // get post categories
                $postCategories_array = get_key_value_array($postExtendedInfo_array['categories']);
                // echo "postCategories_array info ***********";
                // var_dump($postCategories_array);
            // get post labels
                $postLabels_array = get_key_value_array($postExtendedInfo_array['labels']);
            // get post tags
                $postTags_array = get_key_value_array($postExtendedInfo_array['tags']);
            // get post images, array of objects
                $tempPostMediaContent_array = $postExtendedInfo_array['images'];
                // create an array to use later
                $postMediaContent_array= [];
                // loop through result to create a associative array
                foreach ($tempPostMediaContent_array as $MediaContent) {
                    $id = $MediaContent->id; 
                    $name = $MediaContent->name; 
                    $postMediaContent_array[$id] = $name; 
                }

        // # get post possibilities
            // get all categories
                $possibleCategories_array = Post::get_possible_categories();
                // echo "possibleCategories_array info ***********";
                // var_dump($possibleCategories_array);
            // get all labels
                $possibleLabels_array = Post::get_possible_labels();
            // get all tags
                $possibleTags_array = Post::get_possible_tags();
            // get all users
                $possibleUsers_array = User::get_users_for_select();
            // get all images // ! temp info it's to be replaced eventually with the correct method to show images
                $sql = "SELECT * FROM media_content WHERE type IN ('PNG', 'JPEG', 'JPG', 'GIF') ";
                $possibleMediaContent_array = MediaContent::find_by_sql($sql); 

        // # set some data valus and possible data corrections
            // create logic to set correct list options below
            $catIds = implode(",",array_keys($postCategories_array));
            $catIdsOld = implode(",",array_keys($postCategories_array));

            $labelsIds = implode(",",array_keys($postLabels_array));
            $labelsIdsOld = implode(",",array_keys($postLabels_array));
            
            $tagsIds = implode(",",array_keys($postTags_array));
            $tagsIdsOld = implode(",",array_keys($postTags_array));
            
            // if $_POST and error, pass correct info, this is to make sure it their selection does not revert when form does not pass
            if (is_post_request() && isset($_POST["post"]) && $Post_obj->errors) {
                // make array from ids
                $postCategories_array = list_to_array($Post_obj->catIds);
                $postLabels_array = list_to_array($Post_obj->labelIds);
                $postTags_array = list_to_array($Post_obj->tagIds);
                // pass in form list data
                $catIds = $Post_obj->catIds;
                $labelsIds = $Post_obj->labelIds;
                $tagsIds = $Post_obj->tagIds;
            }
    // @ logic for add_edit_post.php END
?>