<div class="page_title"><?php echo $pagetitle; ?></div>

<!-- Display any error messages  -->
<?php if(isset($message) && $message){ 
        echo "<div class='alert alert-danger' style='margin-bottom:10px;'>";
        echo $message; 
        echo "</div>";
        } ?>


<?php echo (isset($edit) && $edit) 
            ? form_open_multipart(base_url().'admin/products/edit/'.$item->pid)
            : form_open_multipart(base_url().'admin/products/addnew'); ?>

<?php if(!empty($imgfiles) && is_array($imgfiles)){ ?>
	<div class="clearfix list_div">
		<?php foreach($imgfiles AS $imgs){
			echo $imgs."<br />";
		}?>
	</div>
	
<?php } ?>

<?php if(@$item->img1 != ""){ ?>    
<div class="clearfix list_div bottom_space" style="border-bottom:1px solid #cacaca; margin-bottom: 10px;">	
	
	
	<div class="go_left label2">Main Photo<br />
		<img src='/media/images/products/<?php echo $item->img1; ?>' style="max-width:150px;" />		
	</div>
	
	<?php if($item->img2 != ""){ ?>
	<div class="go_left label2">Image 2<br />
		<img src='/media/images/products/<?php echo $item->img2; ?>' style="max-width:150px;" />		
	</div>
	<?php } ?>
	<?php if($item->img3 != ""){ ?>
	<div class="go_left label2">Image 3<br />
		<img src='/media/images/products/<?php echo $item->img3; ?>' style="max-width:150px;" />		
	</div>
	<?php } ?>
	<?php if($item->img4 != ""){ ?>
	<div class="go_left label2">Image 4<br />
		<img src='/media/images/products/<?php echo $item->img4; ?>' style="max-width:150px;" />		
	</div>
	<?php } ?>	
</div>
<?php } ?>

<div class="note">Fill in all required fields</div>        
<div>
	<label class="wid150">Name:</label><input type="text" name="name" value="<?php echo @$item->name; ?>"/>
</div>

<div>
	<label class="wid150">Main Photo:</label><input type="file" name="img1" />
	<input type="hidden" name="current_img1" value="<?php echo @$item->img1; ?>" />
</div>
<div>
	<label class="wid150">Image 2:</label><input type="file" name="img2" />
	<input type="hidden" name="current_img2" value="<?php echo @$item->img2 ?>" />
</div>
<div> 
	<label class="wid150">Image 3:</label><input type="file" name="img3" />
	<input type="hidden" name="current_img3" value="<?php echo @$item->img3; ?>" />
</div>
<div> 
	<label class="wid150">Image 4:</label><input type="file" name="img4" />
	<input type="hidden" name="current_img4" value="<?php echo @$item->img4; ?>" />
</div>
<div>
	<label class="wid150">Price:</label><input type="text" name="price" value="<?php echo @$item->price; ?>" />
</div>
<div>
	<label class="wid150">Category:</label><input type="text" name="category" value="<?php echo @$item->category; ?>" />
</div>
<div>
	<label class="wid150">Sub Category:</label><input type="text" name="subcategory" value="<?php echo @$item->sub_cat; ?>" />
</div>

Description
<div class="go_left block740"><textarea cols="20" id="desc" rows="5" name="desc"><?php echo @$item->desc; ?></textarea></div>
<script>
// Turn off automatic editor creation first.
CKEDITOR.replace( 'desc' );
</script>
<div>
	<label class="wid150">Stock: </label><input type="text" name="stock" class="wid150" value="<?php echo $item->stock; ?>" />
</div>

	<?php 
	if (isset($edit) && $edit){ ?>
		<input type="submit" name="update" class="btn btn-primary btn-small" value="Update" />
		<?php 
	} else { ?>
		<input type="submit" name="upload" class="btn btn-primary btn-small" value="Upload" />
		<?php 
	} ?> 
<?php echo form_close(); ?>

