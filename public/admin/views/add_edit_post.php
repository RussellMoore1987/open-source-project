<div>
    <div class="error">
        <?php
            // check for errors
            if ($Post_obj->errors) {
                foreach ($Post_obj->errors as $error) {
                    echo h($error) . "<br>";
                }
            }   
        ?>
    </div>
    <div>
        <a href='add_edit_post'>Add New Post</a>
    </div>
    <p>Comment Count: <?php echo $Post_obj->comments ?? "none"; ?></p>
    <form method="post" action='add_edit_post<?php if($postId != 'add' && $postId > 0) { echo "?postId={$postId}";} ?>'>
        <!-- main form -->
        <div>
            <label for="post[title]">Post Title</label>
            <!-- maxlength="50" minlength="2" required -->
            <input id="title" type="text" name="post[title]" value="<?php echo $Post_obj->title ?>" >
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
                <input type="hidden" name="post[catIds]" value="<?php echo $catIds;?>">
            </div>
            <!-- old list to compare -->
            <input type="hidden" name="post[catIdsOld]" value="<?php echo $catIdsOld; ?>">
        </div>
        <br>

        <div>
            <label for="post[author]">Post Author</label>
            <select name="post[author]">
                <?php
                    // creating a count variable to use later
                    $count = 1;
                    // showing possible users as well as selected tags
                    
                    foreach ($possibleUsers_array as $User) {
                        // make a default a username variable to insert on add mode, // todo: switch to session default is the user
                        if (!is_post_request() && $postId == "add" && $count == 1) {
                            // get the first user
                            $addUserDefault = $User->fullName;
                            $count++;
                        }
                        // set default selected value
                        $selected = "";
                        // check to see if the post has any categories attached to it
                        if ($User->id === $Post_obj->author) {
                            $selected = "selected";
                        }
                        echo "<option value='{$User->id}' {$selected}>{$User->fullName}</option>";
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
                <input type="hidden" name="post[tagIds]" value="<?php echo $tagsIds; ?>">
            </div>
            <!-- old list to compare -->
            <input type="hidden" name="post[tagIdsOld]" value="<?php echo $tagsIdsOld; ?>">
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
                <!-- get list -->
                <input type="hidden" name="post[labelIds]" value="<?php echo $labelsIds;  ?>">
            </div>
            <!-- old list to compare -->
            <input type="hidden" name="post[labelIdsOld]" value="<?php echo $labelsIdsOld; ?>">
        </div>
        <br>

        <div>
            <label for="post[mediaContentIds]">Post Media Content</label>
            <div class="multiSelect">        
                <?php
                    // showing possible mediaContent as well as selected mediaContent
                    foreach ($possibleMediaContent_array as $MediaContent) {
                        // set default selected value
                        $active = "";
                        // check to see if the post has any mediaContent attached to it
                        if (isset($postMediaContent_array[$MediaContent->id])) {
                            $active = "active";
                        }
                        echo "<span id='{$MediaContent->id}' class='{$active}'><img src='{$MediaContent->get_image_path('original')}'></span>";
                    }
                ?>
                <input type="hidden" name="post[mediaContentIds]" value="<?php echo implode(",",array_keys($postMediaContent_array)); ?>">
            </div>
            <!-- old list to compare -->
            <input type="hidden" name="post[mediaContentIdsOld]" value="<?php echo implode(",",array_keys($postMediaContent_array)); ?>">
        </div>
        <br>

        <div>
            <label for="post[content]">Post Content</label>
            <br>
            <!-- minlength="10" maxlength="65000" required -->
            <textarea name="post[content]" cols="30" rows="10"><?php echo $Post_obj->content ?></textarea>
        </div>
        <br>

        <!-- hidden form fields -->
        <input type="hidden" name="post[id]" value="<?php echo $postId == "add" ? NULL : $postId; ?>">
        <input type="hidden" name="post[authorName]" value="<?php echo $Post_obj->authorName ?? $addUserDefault ?? "";?>">
        <input type="hidden" name="post[possibleImageName]" value="<?php echo $Post_obj->imageName;?>">
        

        <!-- submit button -->
        <div>
            <button type="submit"><?php echo $postId == "add" ? "ADD" : "EDIT"; ?> POST</button>
        </div>
    </form>
</div>