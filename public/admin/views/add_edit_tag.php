<div class="temp_flex_sb">
    <!-- tag form -->
    <form method="post" action='add_edit_tag<?php if($tagId != 'add' && is_int($tagId)) { echo "?tagId={$tagId}";} ?>'>
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
        <!-- main form -->
        <div>
            <label for="tag[title]">Title/Name</label>
            <br>
            <!-- minlength="2" maxlength="50" required -->
            <input type="text" name="tag[title]" value="<?php echo $Tag_obj->title ?>" >
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
                echo "<a href='add_edit_tag?tagId={$key}'>{$value}</a><br>";
            }
        ?>
    </div>
    <!-- media content tags -->
    <div>
        <h2>Media Content Tags</h2>
        <?php
            foreach ($mediaContentTags_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "<a href='add_edit_tag?tagId={$key}'>{$value}</a><br>";
            }
        ?>
    </div>
    <!-- users tags -->
    <div>
        <h2>Users Tags</h2>
        <?php
            foreach ($usersTags_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "<a href='add_edit_tag?tagId={$key}'>{$value}</a><br>";
            }
        ?>
    </div>
    <!-- content tags -->
    <div>
        <h2>Content Tags</h2>
        <?php
            foreach ($contentTags_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "<a href='add_edit_tag?tagId={$key}'>{$value}</a><br>";
            }
        ?>
    </div>
</div>