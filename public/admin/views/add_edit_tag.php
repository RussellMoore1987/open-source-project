<div class="temp_flex_sb">
    <!-- tag form -->
    <form method="post" action='add_edit_tag<?php if($tagId != 'add' && $tagId > 0) { echo "?tagId={$tagId}";} ?>'>
        <div class="error">
            <?php
                // check for errors
                if ($Tag_obj->errors) {
                    foreach ($Tag_obj->errors as $error) {
                        echo h($error) . "<br>";
                    }
                }   
            ?>
        </div>
        <div>
            <a href='add_edit_tag'>Add New Tag</a>
        </div>
        <!-- main form -->
        <div>
            <label for="tag[title]">Title/Name</label>
            <br>
            <!-- minlength="2" maxlength="50" required -->
            <input id="title" type="text" name="tag[title]" value="<?php echo $Tag_obj->title ?>" >
        </div>
        <br>

        <div>
            <label for="tag[note]">Note</label>
            <br>
            <!-- maxlength="255"-->
            <textarea name="tag[note]" cols="30" rows="10"><?php echo $Tag_obj->note ?></textarea>
        </div>
        <br>

        <div>
            <label for="tag[useTag]">Use Tag in...</label>
            <br>
            <!-- required -->
            <select name="tag[useTag]">
                <option <?php if ($Tag_obj->useTag == 1) { echo "selected";} ?> value="1">Post</option>
                <option <?php if ($Tag_obj->useTag == 2) { echo "selected";} ?> value="2">Media Content</option>
                <option <?php if ($Tag_obj->useTag == 3) { echo "selected";} ?> value="3">Users</option>
                <option <?php if ($Tag_obj->useTag == 4) { echo "selected";} ?> value="4">Content</option>
            </select>
        </div>
        <br>

        <!-- hidden form fields -->
        <input type="hidden" name="tag[id]" value="<?php echo $tagId == "add" ? NULL : $tagId; ?>">

         <!-- submit button -->
         <div>
            <button type="submit"><?php echo $tagId == "add" ? "ADD" : "EDIT"; ?> TAG</button>
        </div>
    </form>

    <!-- post tags -->
    <div>
        <h2>Post Tags</h2>
        <?php
            foreach ($postTags_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "
                    <a href='add_edit_tag?tagId={$key}'>{$value}</a>
                    [<a href='add_edit_tag?tagId={$key}'>edit</a>
                    <a href='add_edit_tag?tagId={$key}&delete=yes'>delete</a>]<br>
                ";
            }
        ?>
        <a href="add_edit_tag?ctr=1">Add New Post Tag</a>
    </div>
    <!-- media content tags -->
    <div>
        <h2>Media Content Tags</h2>
        <?php
            foreach ($mediaContentTags_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "
                    <a href='add_edit_tag?tagId={$key}'>{$value}</a>
                    [<a href='add_edit_tag?tagId={$key}'>edit</a>
                    <a href='add_edit_tag?tagId={$key}&delete=yes'>delete</a>]<br>
                ";
            }
        ?>
        <a href="add_edit_tag?ctr=2">Add New Media Content Tag</a>
    </div>
    <!-- user tags -->
    <div>
        <h2>User Tags</h2>
        <?php
            foreach ($usersTags_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "
                    <a href='add_edit_tag?tagId={$key}'>{$value}</a>
                    [<a href='add_edit_tag?tagId={$key}'>edit</a>
                    <a href='add_edit_tag?tagId={$key}&delete=yes'>delete</a>]<br>
                ";
            }
        ?>
        <a href="add_edit_tag?ctr=3">Add New User Tag</a>
    </div>
    <!-- content tags -->
    <div>
        <h2>Content Tags</h2>
        <?php
            foreach ($contentTags_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "
                    <a href='add_edit_tag?tagId={$key}'>{$value}</a>
                    [<a href='add_edit_tag?tagId={$key}'>edit</a>
                    <a href='add_edit_tag?tagId={$key}&delete=yes'>delete</a>]<br>
                ";
            }
        ?>
        <a href="add_edit_tag?ctr=4">Add New Content Tag</a>
    </div>
</div>