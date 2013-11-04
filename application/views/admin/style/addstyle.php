<div class="page_title"><?php echo $pagetitle; ?></div>
<?php if(@$message){ echo "<div class='error'>".$message."</div>"; } ?>

<?php echo ($pagetitle == "Edit Style") 
            ? form_open_multipart(base_url().'admin/home/edit_style/'.$style->id)
            : form_open_multipart(base_url().'admin/home/addstyle'); ?>
<form method="POST" action="addstyle" enctype="multipart/form-data">
<div class="clearfix">
	<div class="block150 go_left">Name of this global style: </div>
	<div class="block250 go_left"><input type="text" name="style_name" value="<?php echo @$style->style_name; ?>" /></div>
</div>
	
<div class="clearfix">
	<div class="block150 go_left">Background Colour: </div>
	<div class="block250 go_left"><input type="text" name="bg_color" value="<?php echo @$style->background_color; ?>" /></div>
</div>

<div class="clearfix">
	<div class="block150 go_left">Background Image: </div>
	<div class="block250 go_left">
		<input type="file" name="bg_file" /><br />
		<?php if($pagetitle == "Edit Style"){ ?>
			<div><a href="../../../media/images/background/<?php echo $style->image; ?>" target="_blank">Uploaded image</a></div>
		<?php } ?>
	</div>
</div>
	
<div class="clearfix">
	<div class="block150 go_left">Background Position: </div>
	<select class="block150 go_left" name="bg_position">
		<option <?php if(@$style->position == "None"){ echo "selected"; } ?>>None</option>
		<option <?php if(@$style->position == "Fixed"){ echo "selected"; } ?>>Fixed</option>
		<option <?php if(@$style->position == "Relative"){ echo "selected"; } ?>>Relative</option>
	</select>
</div>
<input type="hidden" name="oldfile" value="<?php echo @$style->image; ?>" />
<input type="submit" name="save" value="Save Style" />
</form>
