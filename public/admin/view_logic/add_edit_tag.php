<?php
    // @ logic for add_edit_tag.php start
        // set page title
        $pageTitle = "Add/Edit Tags";

        // set defaults
        $tagId = $_GET["tagId"] ?? "add";
        // if not add make number
        if (!($tagId == "add")) {
            // this forces the $tagId to be an integer
            $tagId = (int) $tagId;
        }
        // ctr, make number
        // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
        $ctr = get_url_ctr();
        

        // # check to see if we have a real ID
            if (!($tagId == "add")) {
                // this forces the $tagId to be an integer
                $tagId = (int) $tagId;
                // get post for editing
                $Tag_obj = Tag::find_by_id($tagId);
                // error handling, if not there, throw an error
                if (!$Tag_obj) {
                    $Tag_obj = new Tag();
                    $Tag_obj->errors[] = "No tag with the ID of {$tagId} exists";
                    $tagId = "add";

                }
            } else {
                // create empty objects so page dose not brake
                $Tag_obj = new Tag();
            }

        // # delete tag
            // check to see if we have a valid number
            if (isset($_GET["delete"]) && is_int($tagId) && $tagId > 0 && !$Tag_obj->errors) {
                // delete record
                $Tag_obj->delete();
                // set ctr
                $ctr = (int) $Tag_obj->useTag;
                // create new record
                $Tag_obj = new Tag();
                // switch tagId to add
                $tagId = "add";
            }

        // # if post request
            if (is_post_request() && isset($_POST["tag"])) { 
                // populate new object
                $Tag_obj = new Tag($_POST["tag"]);
                // echo "Tag_obj *************";
                // var_dump($Tag_obj);
                // validate and save
                $Tag_obj->save();
                // var_dump($Tag_obj);
                // set id
                $tagId = (int) $Tag_obj->id;
                // echo $tagId. "**************";
                // set ctr
                $ctr = (int) $Tag_obj->useTag;
                // check to see if we have in ID
                if (!($tagId === 0 || is_blank($tagId)) && !$Tag_obj->errors) {
                    // create new record
                    $Tag_obj = new Tag();
                    // switch tagId to add
                    $tagId = "add";
                }
            }
        
        // # page info
            // get all tags, then sort them
            $allTags_array = Tag::find_all();
            // make arrays us them below
            $postTags_array = [];
            $mediaContentTags_array = [];
            $usersTags_array = [];
            $contentTags_array = [];
            // sort them, they should fit into one of these arrays
            foreach ($allTags_array as $Tag) {
                switch ($Tag->useTag) {
                    case 1: $postTags_array[$Tag->id] = $Tag->title; break;
                    case 2: $mediaContentTags_array[$Tag->id] = $Tag->title; break;
                    case 3: $usersTags_array[$Tag->id] = $Tag->title; break;
                    case 4: $contentTags_array[$Tag->id] = $Tag->title; break;
                }
            }
            // sort alphabetically
            natcasesort($postTags_array);
            natcasesort($mediaContentTags_array);
            natcasesort($usersTags_array);
            natcasesort($contentTags_array);

            // set ctr correctly
            $Tag_obj->useTag = $Tag_obj->useTag ?? $ctr;
            
    // @ logic for add_edit_tag.php END
?>