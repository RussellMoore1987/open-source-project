<div class="temp_flex_sb">
    <!-- category form -->
    <form method="post" action='add_edit_category<?php if($categoryId != 'add' && $categoryId > 0) { echo "?categoryId={$categoryId}";} ?>'>
        <div class="error">
            <?php
                // check for errors
                if ($Category_obj->errors) {
                    foreach ($Category_obj->errors as $error) {
                        echo h($error) . "<br>";
                    }
                }   
            ?>
        </div>
        <!-- main form -->
        <div>
            <a href="add_edit_category">Add New Category</a>
        </div>
        <div>
            <label for="category[title]">Title/Name</label>
            <br>
            <!-- minlength="2" maxlength="50" required -->
            <input id="title" type="text" name="category[title]" value="<?php echo $Category_obj->title ?>" >
        </div>
        <br>

        <!-- Sub category of -->
        <div>
            <label for="category[subCatId]">Sub category of...</label>
            <br>
            <select name="category[subCatId]">
                <option <?php if ($Category_obj->subCatId == 0) { echo "selected";} ?> value="0">None</option>
                <?php
                    foreach ($subsOfCategories_array as $Category) {
                        $selected = "";
                        if ($Category_obj->subCatId == $Category->id) {
                             $selected = "selected";
                        }
                        echo "<option {$selected} value='{$Category->id}'>{$Category->title}</option>";
                    }
                ?>
            </select>
            <input type="hidden" name="category[subCatIdOld]" value="<?php echo $Category_obj->subCatId ?? 0; ?>">
        </div>
        <br>

        <div>
            <label for="category[note]">Note</label>
            <br>
            <!-- maxlength="255"-->
            <textarea name="category[note]" cols="30" rows="10"><?php echo $Category_obj->note ?></textarea>
        </div>
        <br>

        <div>
            <label for="category[useCat]">Use category in...</label>
            <br>
            <!-- required -->
            <select name="category[useCat]">
                <option <?php if ($Category_obj->useCat == 1) { echo "selected";} ?> value="1">Post</option>
                <option <?php if ($Category_obj->useCat == 2) { echo "selected";} ?> value="2">Media Content</option>
                <option <?php if ($Category_obj->useCat == 3) { echo "selected";} ?> value="3">Users</option>
                <option <?php if ($Category_obj->useCat == 4) { echo "selected";} ?> value="4">Content</option>
            </select>
            <input type="hidden" name="category[useCatOld]" value="<?php echo $Category_obj->useCat ?? $ctr; ?>">
        </div>
        <br>

        <!-- hidden form fields -->
        <input type="hidden" name="category[id]" value="<?php echo $categoryId == "add" ? NULL : $categoryId; ?>">

         <!-- submit button -->
         <div>
            <button type="submit"><?php echo $categoryId == "add" ? "ADD" : "EDIT"; ?> CATEGORY</button>
        </div>
    </form>

    <!-- post categories -->
    <div>
        <h2>Post Categories</h2>
        <?php Category::layout_categoryStructure($Categories_array, 1); ?>
        <a href="add_edit_category?ctr=1">Add New Post Category</a>
    </div>
    <!-- media content categories -->
    <div>
        <h2>Media Content Categories</h2>
        <?php Category::layout_categoryStructure($Categories_array, 2); ?>
        <a href="add_edit_category?ctr=2">Add New Media Content Category</a>
    </div>
    <!-- users categories -->
    <div>
        <h2>User Categories</h2>
        <?php Category::layout_categoryStructure($Categories_array, 3); ?>
        <a href="add_edit_category?ctr=3">Add New User Category</a>

    </div>
    <!-- content categories -->
    <div>
        <h2>Content Categories</h2>
        <?php Category::layout_categoryStructure($Categories_array, 4); ?>
        <a href="add_edit_category?ctr=4">Add New Content Category</a>

    </div>
</div>

<!-- set js variables -->
<script> 
    postCategories_obj = <?php echo json_encode($postJsCategories_array)?>;
    mediaContentCategories_obj = <?php echo json_encode($mediaContentJsCategories_array)?>;
    usersCategories_obj = <?php echo json_encode($usersJsCategories_array)?>;
    contentCategories_obj = <?php echo json_encode($contentJsCategories_array)?>;
</script>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>