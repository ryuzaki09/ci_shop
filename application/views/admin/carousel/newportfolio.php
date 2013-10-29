<div class="content_block go_left">
<div class="page_title"><?php echo $pagetitle; ?></div>

<?php echo form_open_multipart(base_url().'admin/portfolio/addnew'); ?>
<?php if(isset($message) && $message){ echo $message; } ?>
<div class="clearfix">
    <div class="block150 go_left">Image <br/>
        <span class="note">(480x260 or 960x520)</span>
    </div>
    <div class="block200 go_left"><input type="file" name="image" /></div>
</div>
<div class="clearfix">
    <div class="block150 go_left">Title</div>
    <div class="block200 go_left"><input type="text" name="title" /></div>
</div>
<div class="clearfix">
    <div class="block150 go_left">Link</div>
    <div class="block200 go_left"><input type="text" name="link" /></div>
</div>
<div class="clearfix">
    <div class="block150 go_left">Position</div>
    <div class="block200 go_left"><input type="text" name="position" /></div>
</div>
<div class="clearfix">    
    <div class="block200"><input type="submit" name="add" value="Add!" /></div>
</div>
<?php echo form_close(); ?>
</div>