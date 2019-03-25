<?php
    // @ logic for add_edit_post.php start
        // set defaults
        $postId = $_GET["postId"] ?? "add";

        // check to see if we have a real ID
        if (!($postId == "add")) {
            // this forces the $postId to be an integer
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
            echo "Post_obj info ***********";
            var_dump($Post_obj);
            // validate and save
            $Post_obj->save();
        }

        // get all extended info
            $postExtendedInfo_array = $Post_obj->get_extended_info();
        // get post categories
            $postCategories_array = get_key_value_array($postExtendedInfo_array['categories']);
        // get post labels
            $postLabels_array = get_key_value_array($postExtendedInfo_array['labels']);
        // get post tags
            $postTags_array = get_key_value_array($postExtendedInfo_array['tags']);

        // get all categories
            $possibleCategories_array = Post::get_possible_categories();
        // get all labels
            $possibleLabels_array = Post::get_possible_labels();
        // get all tags
            $possibleTags_array = Post::get_possible_tags();
        // get all users
            $possibleUsers_array = User::get_users_for_select();
    // @ logic for add_edit_post.php END
?>

<div>
    <?php
        // check for errors
        if ($Post_obj->errors) {
            foreach ($Post_obj->errors as $error) {
                echo h($error) . "<br>";
            }
        }   
    ?>
    <p>Comment Count: <?php echo $Post_obj->get_comments() ?? "none"; ?></p>
    <form method="post" action="add_edit_post.php?postId=<?php echo $postId ?>">
        <!-- main form -->
        <div>
            <label for="post[title]">Post Title</label>
            <!-- maxlength="50" minlength="2" required -->
            <input type="text" name="post[title]" value="<?php echo $Post_obj->title ?>" >
        </div>
        <br>

       <div>
            <label for="post[postDate]">Post Date</label>
            <!-- required -->
            <input type="text" name="post[postDate]" value="<?php echo $Post_obj->postDate; ?>" >
       </div>
       <br>

        <div>
            <label for="post[catIds]">Post Categories</label>
            <div class="multiSelect">        
                <?php
                    // showing possible categories as well as selected categories
                    foreach ($possibleCategories_array as $key => $value) {
                        // set default selected value
                        $active = "";
                        // check to see if the post has any categories attached to it
                        if (isset($postCategories_array[$key])) {
                            $active = "active";
                        }
                        echo "<span id='{$key}' class='{$active}'>{$value}</span>";
                    }
                ?>
                <input type="hidden" name="post[catIds]" value="<?php echo $Post_obj->get_catIds(); ?>">
            </div>
            <!-- old list to compare -->
            <input type="hidden" name="post[catIdsOld]" value="<?php echo $Post_obj->get_catIds(); ?>">
        </div>
        <br>

        <div>
            <label for="post[author]">Post Author</label>
            <select name="post[author]">
                <?php
                    // showing possible users as well as selected tags
                    foreach ($possibleUsers_array as $User) {
                        // set default selected value
                        $selected = "";
                        // check to see if the post has any categories attached to it
                        if ($User->get_id() === $Post_obj->author) {
                            $selected = "selected";
                        }
                        echo "<option value='{$User->get_id()}' {$selected}>{$User->fullName}</option>";
                    }
                ?>
            </select>
        </div>
        <br>

        <div>
            <label for="post[status]">Post Status</label>
            <!-- required -->
            <select name="post[status]">
                <option <?php if ($Post_obj->status == 0) { echo "selected";} ?> value="0">Draft</option>
                <option <?php if ($Post_obj->status == 1) { echo "selected";} ?> value="1">Published</option>
            </select>
        </div>
        <br>

        <div>
            <label for="post[tagIds]">Post Tags</label>
            <div class="multiSelect">        
                <?php
                    // showing possible tags as well as selected tags
                    foreach ($possibleTags_array as $key => $value) {
                        // set default selected value
                        $active = "";
                        // check to see if the post has any tags attached to it
                        if (isset($postTags_array[$key])) {
                            $active = "active";
                        }
                        echo "<span id='{$key}' class='{$active}'>{$value}</span>";
                    }
                ?>
                <input type="hidden" name="post[tagIds]" value="<?php echo $Post_obj->get_tagIds(); ?>">
            </div>
            <!-- old list to compare -->
            <input type="hidden" name="post[tagIdsOld]" value="<?php echo $Post_obj->get_tagIds(); ?>">
        </div>
        <br>

        <div>
            <label for="post[labelIds]">Post Labels</label>
            <div class="multiSelect">        
                <?php
                    // showing possible labels as well as selected labels
                    foreach ($possibleLabels_array as $key => $value) {
                        // set default selected value
                        $active = "";
                        // check to see if the post has any labels attached to it
                        if (isset($postLabels_array[$key])) {
                            $active = "active";
                        }
                        echo "<span id='{$key}' class='{$active}'>{$value}</span>";
                    }
                ?>
                <input type="hidden" name="post[labelIds]" value="<?php echo $Post_obj->get_labelIds(); ?>">
            </div>
            <!-- old list to compare -->
            <input type="hidden" name="post[labelIdsOld]" value="<?php echo $Post_obj->get_labelIds(); ?>">
        </div>
        <br>

        <div>
            <label for="post[content]">Post Content</label>
            <br>
            <textarea name="post[content]" cols="30" rows="10"><?php echo $Post_obj->content ?></textarea>
        </div>
        <br>

        <!-- hidden form fields -->
        <input type="hidden" name="post[id]" value="<?php echo $postId == "add" ? NULL : $postId; ?>">
        <input type="hidden" name="post[authorName]" value="<?php echo $Post_obj->get_authorName();?>">

        <!-- submit button -->
        <div>
            <button type="submit"><?php echo $postId == "add" ? "ADD" : "EDIT"; ?> POST</button>
        </div>
    </form>
</div>

<!-- page level JavaScript/jQuery -->
<script>
    $(document).ready(function() {
        // on change reset author's name in hidden input field
        $('select[name="post[author]"]').on('change', function() {
            var name = $(this).find('option:selected').text();
            var input = $('input[name="post[authorName]"]');
            input.val(name);
        });
        
    });
</script>