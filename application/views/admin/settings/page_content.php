<div class="page_title"><?php echo $pagetitle; ?></div>

<?php
if($pagetitle == "Edit Page" || $pagetitle == "Create Page"){
    ?>
    <form method="post">
	<div>
	    <label class="wid150">Page Name: </label><input type="text" name="page_name" value="<?php echo $result->page_name; ?>" />
	</div>
	<label class="wid150">Page Content:</label>
	<textarea id="page_content" name="page_content"><?php echo $result->page_content; ?>
	</textarea>
	<script>
	CKEDITOR.replace('page_content');
	</script>
	<div>
	    <input type="submit" class="btn btn-primary btn-small" name="save" value="Save" />
	</div>
    </form>

    <?php
} else {
    foreach($result AS $list):
	echo "<div><label class='wid250'>Page Name: ".$list['page_name']."</label><a href='edit_page/".$list['id']."'>Edit</a></div>"; 

    endforeach;
}
?>
