<?php
    // @ logic for add_edit_category.php start
        // set page title
        $pageTitle = "Add/Edit Category";

        // set defaults
        $categoryId = $_GET["categoryId"] ?? "add";
        // ctr, make number
        // * collection_type_reference, located at: root/private/reference_information.php
        $categoryCtr = $_GET["ctr"] ?? 1;
        $categoryCtr = (int) $categoryCtr;

        

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
                $categoryId = (int) $Category_obj->get_id();
                // echo $categoryId. "**************";
                // check to see if we have in ID
                if (!($categoryId === 0 || is_blank($categoryId)) && !$Category_obj->errors) {
                    // get full category object
                    $Category_obj = Category::find_by_id($categoryId);
                }
            }
        
        // # page info
            // get and filter array, get back array of object arrays 
            $Categories_array = Category::get_all_categories_sorted();
            
            // get correct info for possible "subs of" info, the correct selection of categories
            if (is_blank($Category_obj->useCat)) {
                $Category_obj->useCat = $categoryCtr;
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