<?php
    // @ logic for add_edit_post.php start
        // get post id or set default
        $postId = $_GET["postId"] ?? "add";

        // check to see if we have a real ID
        if (!($postId == "add")) {
            $postId = (int) $postId;
            // get post for editing
            $Post_obj = Post::find_by_id($postId);
            // error handling
            if (!$Post_obj) {
                $Post_obj = new Post();
                $Post_obj->errors[] = "No post with the ID of {$postId} exists";
                $postId = "add";

            }
        } else {
            // create empty objects so page dose not brake
            $Post_obj = new Post();
        }

        // if post request
        if (is_post_request() && isset($_POST["post"])) {
            // populate new object
            $Post_obj = new Post($_POST["post"]);
            // $Post_obj->save();
            echo $Post_obj->title;
            echo "<br>";
            echo $Post_obj->postDate;
            echo "<br>";
            echo $Post_obj->fullDate;
            echo "<br>";
            echo $Post_obj->shortDate;
            echo "<br>";
            echo $Post_obj->status;
            echo "<br>";
            echo $Post_obj->get_api_data();
        }

        // get post categories
            $postCategories_array = $Post_obj->get_post_categories();
            // var_dump($postCategories_array);
        // get post tags
            // $postTags_array = $Post_obj->get_post_tags();
            // var_dump($postTags_array);
        // get post labels
            // $postLabels_array = $Post_obj->get_post_labels();

        // get all categories
            $possibleCategories_array = Post::get_possible_categories();
        // get all tags
        // $possibleTags_array = Post::get_possible_tags();
        // get all labels
        // $possibleLabels_array = Post::get_possible_labels();
    // @ logic for add_edit_post.php END
?>



<div>
    <?php
        // check for errors
        if ($Post_obj->errors) {
            foreach ($Post_obj->errors as $error) {
                echo $error;
            }
        }   
    ?>
    <p>Comment Count: <?php echo $Post_obj->get_comments() ?? "none"; ?></p>
    <form method="post" action="add_edit_post.php?postId=<?php echo $postId ?>">
        <!-- main form -->
        <div>
            <label for="post[title]">Post Title</label>
            <input type="text" name="post[title]" value="<?php echo $Post_obj->title ?>" maxlength="50" minlength="2" required>
        </div>
        <br>

       <div>
            <label for="post[postDate]">Post Date</label>
            <input type="text" name="post[postDate]" value="<?php echo $Post_obj->postDate ?>" required>
       </div>
       <br>

        <div>
            <label for="post[catIds]">Post Categories</label>
            <select name="post[catIds]">
                <?php
                    // showing possible categories as well as selected categories
                    foreach ($possibleCategories_array as $key => $value) {
                        // set default selected value
                        $selected = "";
                        // check to see if the post has any categories attached to it
                        if (isset($postCategories_array[$key])) {
                            $selected = "selected";
                        }
                        echo "<option value='{$key}' {$selected}>{$value}</option>";
                    }
                ?>
                <option value="12">Volvo</option>
                <option value="2">Saab</option>
                <option value="3">Opel</option>
                <option value="4">Audi</option>
            </select>
        </div>
        <br>

        <div>
            <label for="post[author]">Post Author</label>
            <select name="post[author]">
                <option value="22">Russell Moore</option>
                <option value="15">Sabrina Smith</option>
                <option value="26">Alexander Hamilton</option>
                <option value="35">Stephanie Wardlaw</option>
            </select>
        </div>
        <br>

        <div>
            <label for="post[status]">Post Status</label>
            <select name="post[status]">
                <option <?php if ($Post_obj->status == 0) { echo "selected";} ?> value="0">Draft</option>
                <option <?php if ($Post_obj->status == 1) { echo "selected";} ?> value="1">Published</option>
            </select>
        </div>
        <br>

        <div>
            <label for="post[tagIds]">Post Tags</label>
            <select name="post[tagIds]">
                <option value="23">Four-door</option>
                <option value="3">Two doors</option>
                <option value="2">Pink</option>
                <option value="4">Purple</option>
                <option value="5">Brown</option>
            </select>
        </div>
        <br>

        <div>
            <label for="post[labelIds]">Post Labels</label>
            <select name="post[labelIds]">
                <option value="2">Good condition</option>
                <option value="33">Poor condition</option>
                <option value="45">Lopsided</option>
                <option value="1">No ceiling</option>
            </select>
        </div>
        <br>

        <div>
            <label for="post[content]">Post Content</label>
            <textarea name="post[content]" cols="30" rows="10"><?php echo $Post_obj->content ?></textarea>
        </div>
        <br>

        <!-- submit button -->
        <div>
            <button type="submit"><?php echo $postId == "add" ? "ADD" : "EDIT" ?> POST</button>
        </div>
    </form>
</div>