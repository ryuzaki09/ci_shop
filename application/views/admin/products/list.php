<div class="page_title"><?php echo $pagetitle; ?></div>
<?php
echo $this->session->flashdata("message");

if (is_array($products)){
    
    foreach($products AS $list): ?>
        
		 <div id="item_<?php echo $list['pid']; ?>" class="list_div clearfix" style="border-top:1px solid #cacaca;">
			<div class="go_left marg_right list_product" style="width:200px;">
				<img src="<?php echo base_url().'media/images/products/'.$list['img1'] ?>" />
			</div>
			<div class="go_left marg_right list_product" style="width:400px;">
				Name: <?php echo $list['name'] ?><br />
				Description: <?php echo substr($list['desc'], 0, 200); ?>...<br /><br />
				Price: &pound;<?php echo $list['price']; ?><br />
			</div>        
			<div class="go_left marg_right list_product" style="width:120px;">
				<a href="/admin/products/edit/<?php echo $list['pid']; ?>">Edit</a><br />
				<a href="#" data-pid="<?php echo $list['pid']; ?>" data-img1="<?php echo $list['img1']; ?>" class="delete">Delete</a><br />
				<a href="/admin/products/addoption/<?php echo $list['pid']; ?>">Add Option</a><br />
				<a href="/admin/products/viewOptions/<?php echo $list['pid']; ?>">View Options</a>
			</div>
			<br/>
		 </div>

		<?php 
	endforeach; 
    
} ?>
<script>
$('.delete').click(function(){
    var response = confirm('Delete product?');
    
    if(response){
		var id = $(this).data('pid');
		var imgname = $(this).data('img1');
        var url = "<?php echo base_url(); ?>admin/products/delete_product";
        
        $.post(url, {'id': id, 'imgname': imgname}, function(data){
            if(data == "true"){
                alert('Product deleted');
                $('#item_'+id).hide('slow');
            } else {
                alert(data);
            }
        });
        
    }
});
</script>
