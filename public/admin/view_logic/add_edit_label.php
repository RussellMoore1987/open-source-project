<?php
    // @ logic for add_edit_label.php start
        // set page title
        $pageTitle = "Add/Edit Labels";

        // set defaults
        $labelId = $_GET["labelId"] ?? "add";
        

        // # check to see if we have a real ID
            if (!($labelId == "add")) {
                // this forces the $labelId to be an integer
                $labelId = (int) $labelId;
                // get post for editing
                $Label_obj = Label::find_by_id($labelId);
                // error handling, if not there, throw an error
                if (!$Label_obj) {
                    $Label_obj = new Label();
                    $Label_obj->errors[] = "No label with the ID of {$labelId} exists";
                    $labelId = "add";

                }
            } else {
                // create empty objects so page dose not brake
                $Label_obj = new Label();
            }

        // # if post request
            if (is_post_request() && isset($_POST["label"])) { 
                // populate new object
                $Label_obj = new Label($_POST["label"]);
                // echo "Label_obj *************";
                // var_dump($Label_obj);
                // validate and save
                $Label_obj->save();
                // var_dump($Label_obj);
                // set id
                $labelId = (int) $Label_obj->get_id();
                // echo $labelId. "**************";
                // check to see if we have in ID
                if (!($labelId === 0 || is_blank($labelId)) && !$Label_obj->errors) {
                    // get full label object
                    $Label_obj = Label::find_by_id($labelId);
                }
            }
        
        // # page info
            // get all labels, then sort them
            $allLabels_array = Label::find_all();
            // make arrays us them below
            $postLabels_array = [];
            $mediaContentLabels_array = [];
            $usersLabels_array = [];
            $contentLabels_array = [];
            // sort them, they should fit into one of these arrays
            foreach ($allLabels_array as $Label) {
                switch ($Label->useLabel) {
                    case 1: $postLabels_array[$Label->get_id()] = $Label->title; break;
                    case 2: $mediaContentLabels_array[$Label->get_id()] = $Label->title; break;
                    case 3: $usersLabels_array[$Label->get_id()] = $Label->title; break;
                    case 4: $contentLabels_array[$Label->get_id()] = $Label->title; break;
                }
            }
            // sort alphabetically
            asort($postLabels_array);
            asort($mediaContentLabels_array);
            asort($usersLabels_array);
            asort($contentLabels_array);
            
    // @ logic for add_edit_label.php END
?>