<div class="temp_flex_sb">
    <!-- label form -->
    <form method="post" action='add_edit_label<?php if($labelId != 'add' && is_int($labelId)) { echo "?labelId={$labelId}";} ?>'>
        <div class="error">
            <?php
                // check for errors
                if ($Label_obj->errors) {
                    foreach ($Label_obj->errors as $error) {
                        echo h($error) . "<br>";
                    }
                }   
            ?>
        </div>
        <!-- main form -->
        <div>
            <label for="label[title]">Title/Name</label>
            <br>
            <!-- minlength="2" maxlength="50" required -->
            <input type="text" name="label[title]" value="<?php echo $Label_obj->title ?>" >
        </div>
        <br>

        <div>
            <label for="label[note]">Note</label>
            <br>
            <!-- maxlength="255"-->
            <textarea name="label[note]" cols="30" rows="10"><?php echo $Label_obj->note ?></textarea>
        </div>
        <br>

        <div>
            <label for="label[useLabel]">Use Label in...</label>
            <br>
            <!-- required -->
            <select name="label[useLabel]">
                <option <?php if ($Label_obj->useLabel == 1) { echo "selected";} ?> value="1">Post</option>
                <option <?php if ($Label_obj->useLabel == 2) { echo "selected";} ?> value="2">Media Content</option>
                <option <?php if ($Label_obj->useLabel == 3) { echo "selected";} ?> value="3">Users</option>
                <option <?php if ($Label_obj->useLabel == 4) { echo "selected";} ?> value="4">Content</option>
            </select>
        </div>
        <br>

        <!-- hidden form fields -->
        <input type="hidden" name="label[id]" value="<?php echo $labelId == "add" ? NULL : $labelId; ?>">

         <!-- submit button -->
         <div>
            <button type="submit"><?php echo $labelId == "add" ? "ADD" : "EDIT"; ?> LABEL</button>
        </div>
    </form>

    <!-- post labels -->
    <div>
        <h2>Post Labels</h2>
        <?php
            foreach ($postLabels_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "<a href='add_edit_label?labelId={$key}'>{$value}</a><br>";
            }
        ?>
    </div>
    <!-- media content labels -->
    <div>
        <h2>Media Content Labels</h2>
        <?php
            foreach ($mediaContentLabels_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "<a href='add_edit_label?labelId={$key}'>{$value}</a><br>";
            }
        ?>
    </div>
    <!-- users labels -->
    <div>
        <h2>Users Labels</h2>
        <?php
            foreach ($usersLabels_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "<a href='add_edit_label?labelId={$key}'>{$value}</a><br>";
            }
        ?>
    </div>
    <!-- content labels -->
    <div>
        <h2>Content Labels</h2>
        <?php
            foreach ($contentLabels_array as $key => $value) {
                // escape potential html characters
                $value = h($value);
                echo "<a href='add_edit_label?labelId={$key}'>{$value}</a><br>";
            }
        ?>
    </div>
</div>