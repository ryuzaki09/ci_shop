<!--<div class="content_block go_left">-->
<div class="page_title"><?php echo $pagetitle; ?></div>
<?php if (isset($message) && $message){ echo $message; } ?>
<?php
//if (isset($result) && $result){
	echo form_open_multipart(base_url().'admin/carousel/addnew'); 
    //echo form_open_multipart(base_url().'admin/carousel/edit/'.$result->port_id); ?>    
    <div class="clearfix bottom_space">
        <div class="go_left">
            <!--<input type="hidden" name="old_image" value="<?php echo $result->port_img; ?>" />
            <img src="/media/images/portfolio/<?php echo $result->port_img; ?>" style="max-width:750px; max-height:450px;" />-->
        </div>
    </div>
    <div class="clearfix bottom_space">
        <div class="go_left block150">Image (953x409):</div> 
        <div class="go_left block250"><input type="file" name="image" /></div>
    </div>
    <div class="clearfix bottom_space">
        <div class="go_left block150">Name:</div> 
        <div class="go_left block250"><input type="text" name="name" value="<?php echo $result->name; ?>" /></div>
    </div>
    <div class="clearfix bottom_space">
        <div class="go_left block150">Description:</div>
        <div class="go_left block250"><textarea name="desc"><?php echo $result->desc; ?></textarea></div>
    </div>
    <div class="clearfix bottom_space">
        <div class="go_left block150">Price: (&pound;)</div> 
        <div class="go_left block250"><input type="text" name="price" size="3" value="<?php echo $result->price; ?>" /></div>
    </div>
    <div class="clearfix bottom_space">
        <div class="go_left block150">Position:</div>
        <div class="go_left block250"><input type="text" name="position" size="3" value="<?php echo $result->position; ?>" /></div>
    </div>
    <div class="clearfix bottom_space">
            <input type="submit" name="add" value="Add to Carousel" />
    </div>
<?php echo form_close();
// } ?>
<!--</div>-->