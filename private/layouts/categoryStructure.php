<?php
    // set default variables
    // * collection_type_reference, located at: root/private/reference_information.php
    switch ($ctr) {
        case 1: $parent = "postParentCategories_array"; $sub = "postSubCategories_array"; break;
        case 2: $parent = "mediaContentParentCategories_array"; $sub = "mediaContentSubCategories_array"; break;
        case 3: $parent = "usersParentCategories_array"; $sub = "usersSubCategories_array"; break;
        case 4: $parent = "contentParentCategories_array"; $sub = "contentSubCategories_array"; break;
    }
    
    // check to see if we have the correct information, if yes start performing the layout
    if (isset($Categories_array[$parent])) {
        // parent layer 
        foreach ($Categories_array[$parent] as $Category) {
            // escape potential html characters
            $value = h($Category->title);
            echo "
                <a href='add_edit_category?categoryId={$Category->get_id()}'>{$value}</a> 
                [<a href='add_edit_category?categoryId={$Category->get_id()}'>edit</a> 
                <a href='add_edit_category?categoryId={$Category->get_id()}&delete=yes'>delete</a>]<br>
            ";
            // check to see if we even have any subs
            if (isset($Categories_array[$sub])) {
                // get sub cats, sub layer one
                foreach ($Categories_array[$sub] as $SubCategory1) {
                    if ($Category->get_id() == $SubCategory1->subCatId) {
                        // escape potential html characters
                        $value = h($SubCategory1->title);
                        echo "
                            <a href='add_edit_category?categoryId={$SubCategory1->get_id()}'>--{$value}</a>
                            [<a href='add_edit_category?categoryId={$SubCategory1->get_id()}'>edit</a> 
                            <a href='add_edit_category?categoryId={$SubCategory1->get_id()}&delete=yes'>delete</a>]<br>
                        ";
                        // sub layer two
                        foreach ($Categories_array[$sub] as $SubCategory2) {
                            if ($SubCategory1->get_id() == $SubCategory2->subCatId) {
                                // escape potential html characters
                                $value = h($SubCategory2->title);
                                echo "
                                    <a href='add_edit_category?categoryId={$SubCategory2->get_id()}'>----{$value}</a>
                                    [<a href='add_edit_category?categoryId={$SubCategory2->get_id()}'>edit</a> 
                                    <a href='add_edit_category?categoryId={$SubCategory2->get_id()}&delete=yes'>delete</a>]<br>
                                ";
                                // sub layer three
                                foreach ($Categories_array[$sub] as $SubCategory3) {
                                    if ($SubCategory2->get_id() == $SubCategory3->subCatId) {
                                        // escape potential html characters
                                        $value = h($SubCategory3->title);
                                        echo "
                                            <a href='add_edit_category?categoryId={$SubCategory3->get_id()}'>------{$value}</a>
                                            [<a href='add_edit_category?categoryId={$SubCategory3->get_id()}'>edit</a> 
                                            <a href='add_edit_category?categoryId={$SubCategory3->get_id()}&delete=yes'>delete</a>]<br>
                                        ";
                                        // sub layer four
                                        foreach ($Categories_array[$sub] as $SubCategory4) {
                                            if ($SubCategory3->get_id() == $SubCategory4->subCatId) {
                                                // escape potential html characters
                                                $value = h($SubCategory4->title);
                                                echo "
                                                    <a href='add_edit_category?categoryId={$SubCategory4->get_id()}'>--------{$value}</a>
                                                    [<a href='add_edit_category?categoryId={$SubCategory4->get_id()}'>edit</a> 
                                                    <a href='add_edit_category?categoryId={$SubCategory4->get_id()}&delete=yes'>delete</a>]<br>
                                                ";
                                                // sub layer five
                                                foreach ($Categories_array[$sub] as $SubCategory5) {
                                                    if ($SubCategory4->get_id() == $SubCategory5->subCatId) {
                                                        // escape potential html characters
                                                        $value = h($SubCategory5->title);
                                                        echo "
                                                            <a href='add_edit_category?categoryId={$SubCategory5->get_id()}'>----------{$value}</a>
                                                            [<a href='add_edit_category?categoryId={$SubCategory5->get_id()}'>edit</a> 
                                                            <a href='add_edit_category?categoryId={$SubCategory5->get_id()}&delete=yes'>delete</a>]<br>
                                                        ";
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
?>