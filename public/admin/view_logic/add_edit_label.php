<?php
    // @ logic for add_edit_label.php start
        // set page title
        $pageTitle = "Add/Edit Labels";

        // set defaults
        $labelId = $_GET["labelId"] ?? "add";
        // if not add make number
        if (!($labelId == "add")) {
            // this forces the $labelId to be an integer
            $labelId = (int) $labelId;
        }
        // ctr, make number
        // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
        $ctr = get_url_ctr();
        

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

        // # delete label
            // check to see if we have a valid number
            if (isset($_GET["delete"]) && is_int($labelId) && $labelId > 0 && !$Label_obj->errors) {
                // delete record
                $Label_obj->delete();
                // set ctr
                $ctr = (int) $Label_obj->useLabel;
                // create new record
                $Label_obj = new Label();
                // switch labelId to add
                $labelId = "add";
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
                $labelId = (int) $Label_obj->id;
                // echo $labelId. "**************";
                // set ctr
                $ctr = (int) $Label_obj->useLabel;
                // check to see if we have in ID
                if (!($labelId === 0 || is_blank($labelId)) && !$Label_obj->errors) {
                    // create new record
                    $Label_obj = new Label();
                    // switch labelId to add
                    $labelId = "add";
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
                    case 1: $postLabels_array[$Label->id] = $Label->title; break;
                    case 2: $mediaContentLabels_array[$Label->id] = $Label->title; break;
                    case 3: $usersLabels_array[$Label->id] = $Label->title; break;
                    case 4: $contentLabels_array[$Label->id] = $Label->title; break;
                }
            }
            // sort alphabetically
            natcasesort($postLabels_array);
            natcasesort($mediaContentLabels_array);
            natcasesort($usersLabels_array);
            natcasesort($contentLabels_array);

            // set ctr correctly
            $Label_obj->useLabel = $Label_obj->useLabel ?? $ctr;
            
    // @ logic for add_edit_label.php END
?>