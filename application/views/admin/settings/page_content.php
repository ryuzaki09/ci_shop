<div class="page_title"><?php echo $pagetitle; ?></div>
<div>
<label class="wid150">Page Name: </label><input type="text" name="page_name" />
</div>
<label class="wid150">Page Content:</label>
<textarea id="page_content" name="page_content">
</textarea>
<script>
CKEDITOR.replace('page_content');
</script>
<div>
<input type="submit" class="btn btn-primary btn-small" name="save" value="Save" />
</div>
