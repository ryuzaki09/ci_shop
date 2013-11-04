<script src="<?php echo base_url(); ?>js/ckeditor/ckeditor.js"></script>
<!--<div class="content_block go_left">-->
<div class="page_title"><?php echo $pagetitle; ?></div>

<!-- Display any error messages  -->
<?php if(isset($message) && $message){ 
        echo "<div class='error' style='margin-bottom:10px;'>";
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
<div class="clearfix list_div">
	<div class="go_left label2">Name</div>
	<div class="go_left"><input type="text" name="name" value="<?php echo @$item->name; ?>"/></div>
</div>

<div class="clearfix list_div">
	<div class="go_left label2">Main Photo</div>
	<div class="go_left"><input type="file" name="img1" /></div>
        <input type="hidden" name="current_img1" value="<?php echo @$item->img1; ?>" />
</div>
<div class="clearfix list_div">
	<div class="go_left label2">Image 2</div>
	<div class="go_left"><input type="file" name="img2" /></div>
        <input type="hidden" name="current_img2" value="<?php echo @$item->img2 ?>" />
</div>
<div class="clearfix list_div">
	<div class="go_left label2">Image 3</div>
	<div class="go_left"><input type="file" name="img3" /></div>
        <input type="hidden" name="current_img3" value="<?php echo @$item->img3; ?>" />
</div>
<div class="clearfix list_div">
	<div class="go_left label2">Image 4</div>
	<div class="go_left"><input type="file" name="img4" /></div>
        <input type="hidden" name="current_img4" value="<?php echo @$item->img4; ?>" />
</div>
<div class="clearfix list_div">
	<div class="go_left label2">Price</div>
	<div class="go_left"><input type="text" name="price" value="<?php echo @$item->price; ?>" /></div>
</div>
<div class="clearfix list_div">
	<div class="go_left label2">Category</div>
	<div class="go_left"><input type="text" name="category" value="<?php echo @$item->category; ?>" /></div>
</div>
<div class="clearfix list_div">
	<div class="go_left label2">Sub Category</div>
	<div class="go_left"><input type="text" name="subcategory" value="<?php echo @$item->sub_cat; ?>" /></div>
</div>

<div class="clearfix list_div">
	<div class="go_left label2">Description</div>
	<div class="go_left block740"><textarea cols="20" id="desc" rows="5" name="desc"><?php echo @$item->desc; ?></textarea></div>
	<!--<div class="go_left" id="editable" contenteditable="true"><?php echo $item->desc; ?></div>-->
	<script>
    // Turn off automatic editor creation first.
    CKEDITOR.replace( 'desc' );
	</script>
</div>
<div class="clearfix list_div">
<?php if (isset($edit) && $edit){ ?>
        <input type="submit" name="update" value="Update" />
<?php } else { ?>
	<input type="submit" name="upload" value="Upload" />
<?php } ?> 
</div>
<?php echo form_close(); ?>


<!--<div id="edit" class="clearfix list_div">
	<a href="<?php echo base_url().'admin/fpwindows/listing'; ?>">Windows list</a><br/>
	<a href="http://www.facebook.com">Facebook</a>
</div>
<div class="clearfix list_div">
	<a href="<?php echo base_url().'admin/fpwindows/listing'; ?>">Windows list</a><br/>
	<a href="http://www.facebook.com">Facebook</a>
</div>-->
<!--</div><!-- content_block -->

<!--<script>
//find all href links except self domain
$("#edit > a[href^='http:']:not([href*='" + window.location.host + "'])").each(function() {               
        $(this).attr("target", "_blank");
});
//checks for external websites and make it open in new window
</script>-->

