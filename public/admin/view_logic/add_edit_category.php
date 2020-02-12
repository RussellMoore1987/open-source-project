<?php
    // @ logic for add_edit_category.php start
        // set page title
        $pageTitle = "Add/Edit Category";

        // set defaults
        $categoryId = $_GET["categoryId"] ?? "add";
        // if not add make number
        if (!($categoryId == "add")) {
            // this forces the $categoryId to be an integer
            $categoryId = (int) $categoryId;
        }
        // ctr, make number
        // * collection_type_reference, located at: root/private/rules_docs/reference_information.php
        $ctr = get_url_ctr();

        // # check to see if we have a real ID
            if (!($categoryId == "add")) {
                // this forces the $categoryId to be an integer
                $categoryId = (int) $categoryId;
                // get post for editing
                $Category_obj = Category::find_by_id($categoryId);
                // error handling, if not there, throw an error
                if (!$Category_obj) {
                    $Category_obj = new Category();
                    $Category_obj->errors[] = "No category with the ID of {$categoryId} exists";
                    $categoryId = "add";

                }
            } else {
                // create empty objects so page dose not brake
                $Category_obj = new Category();
            }

        // # delete category
            // check to see if we have a valid number
            if (isset($_GET["delete"]) && is_int($categoryId) && $categoryId > 0 && !$Category_obj->errors) {
                // delete record
                $Category_obj->delete();
                // set ctr
                $ctr = (int) $Category_obj->useCat;
                // create new record
                $Category_obj = new Category();
                // switch categoryId to add
                $categoryId = "add";
            }

        // # if post request
            if (is_post_request() && isset($_POST["category"])) { 
                // populate new object
                $Category_obj = new Category($_POST["category"]);
                // echo "Category_obj *************";
                // var_dump($Category_obj);
                // validate and save
                $Category_obj->save();
                // var_dump($Category_obj);
                // set id
                $categoryId = (int) $Category_obj->id;
                // echo $categoryId. "**************";
                // set ctr
                $ctr = (int) $Category_obj->useCat;
                // check to see if we have in ID, set to add mode
                if (!($categoryId === 0 || is_blank($categoryId)) && !$Category_obj->errors) {
                    // create new record
                    $Category_obj = new Category();
                    // switch categoryId to add
                    $categoryId = "add";
                }
            }
        
        // # page info
            // get and filter array, get back array of object arrays 
            $Categories_array = Category::get_all_categories_sorted();
            
            // get correct info for possible "subs of" info, the correct selection of categories
            if (is_blank($Category_obj->useCat)) {
                $Category_obj->useCat = $ctr;
            }

            // get info for JavaScript
            $postJsCategories_array = get_key_value_array($Categories_array['postCategories_array']);
            $mediaContentJsCategories_array = get_key_value_array($Categories_array['mediaContentCategories_array']);
            $usersJsCategories_array = get_key_value_array($Categories_array['usersCategories_array']);
            $contentJsCategories_array = get_key_value_array($Categories_array['contentCategories_array']);

            // get selection
            $subsOfCategories_array = [];
            switch ($Category_obj->useCat) {
                case 1: $subsOfCategories_array = $Categories_array['postCategories_array']; break;
                case 2: $subsOfCategories_array = $Categories_array['mediaContentCategories_array']; break;
                case 3: $subsOfCategories_array = $Categories_array['usersCategories_array']; break;
                case 4: $subsOfCategories_array = $Categories_array['contentCategories_array']; break;
            } 

    // @ logic for add_edit_category.php END
?>