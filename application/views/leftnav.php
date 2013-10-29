<div id="accordion" style="width:140px; float:left; text-align:center; font-size:12px; margin-right:20px; padding:10px 0; min-height:300px;">
    <h4><a href="#">Admin Home</a></h4>
    <div class="marg7_0">
        <a href="<?php echo base_url(); ?>admin/home">Home</a>
    </div>
    
    <h4><a href="#">Front Page</a></h4>
    <div class="marg7_0">
        <a href="<?php echo base_url(); ?>admin/fpwindows/addnew">Add new window</a><br/>
        <a href="<?php echo base_url(); ?>admin/fpwindows/listing">Windows List</a>
    </div>
    
    <h4><a href="#">Photos</a></h4>
    <div class="marg7_0">
        <a href="<?php echo base_url(); ?>admin/photos/addnew">Add Photo</a><br/>
        <a href="<?php echo base_url(); ?>admin/photos/albumlist">List Albums</a>
    </div>
    
    <h4><a href="#">Portfolio</a></h4>
    <div class="marg7_0">
        <a href="/admin/portfolio/addnew">Add Portfolio</a><br/>
        <a href="/admin/portfolio/listing">List Portfolio</a>
    </div>
</div>


<script>
$(function() {
    $( "#accordion" ).accordion({ active:false });    
});
</script>