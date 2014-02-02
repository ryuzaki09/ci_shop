<div class="page_title"><?php echo $pagetitle; ?></div>
<?php
if (is_array($products)){
    
    foreach($products AS $list){ ?>
        
     <div id="item_<?php echo $list['pid']; ?>" class="list_div clearfix" style="border-top:1px solid #cacaca;">
        <div class="go_left marg_right list_product" style="width:200px;"><img src="<?php echo base_url().'media/images/products/'.$list['img1'] ?>" /></div>
        <div class="go_left marg_right list_product" style="width:400px;">
            Name: <?php echo $list['name'] ?><br />
            Description: <?php echo substr($list['desc'], 0, 200); ?>...<br /><br />
            Price: &pound;<?php echo $list['price']; ?><br />
        </div>        
        <div class="go_left marg_right list_product" style="width:120px;"><a href="<?php echo base_url().'admin/products/edit/'.$list['pid']; ?>">Edit</a><br />
        <!--<a href="album/<?php echo $list['pid']; ?>">View Product</a><br />-->
        <a href="javascript:void(0);" onclick="delete_item('<?php echo $list['pid']; ?>','<?php echo $list['img1']; ?>');">Delete</a><br />
        </div>
        <br/>
     </div>

        <!--- Sub items --->
     <!--   <div style="display:none;" class="list_div" id="sub_window_<?php echo $list['pid']; ?>">
            <div class="clearfix">
                <input type="hidden" id="old_album_name_<?php echo $list['pid']; ?>" value="<?php echo $list['name']; ?>" />
                <div class="block150 go_left">Title</div>
                <div class="block200 go_left"><input type="text" id="new_album_name_<?php echo $list['pid']; ?>" value="<?php echo $list['name']; ?>" /></div>
            </div>
            <div class="clearfix">
                <div class="go_left"><input type="button" id="update_title" value="Update" onclick="update_title('<?php echo $list['pid']; ?>');" /></div>
            </div>
        </div>
     </div> -->
    
<?php } 
    
} ?>
<!--</div>-->
<script>
/*function edit(id){
    if($('#sub_window_'+id).is(':visible')){
        $('#sub_window_'+id).slideUp();
    } else{
        $('#sub_window_'+id).slideDown('slow');
    }
}

function update_title(id){
    var old_album_name = $('#old_album_name_'+id).val();
    var new_album_name = $('#new_album_name_'+id).val();
    var url = "/admin/photos/update_album";
    
    $.post(url, {'albumID': id, 'old_album_name': old_album_name, 'new_album_name': new_album_name}, function(data){
       if(data =="true"){
           alert('Updated!');
       } else {
           alert(data);
       }
    });
    
}*/
    
function delete_item(id, imgname){
    var response = confirm('Delete product?');
    
    if(response){
        var url = "<?php echo base_url(); ?>admin/products/delete_product";
        
        $.post(url, {'id': id, 'imgname': imgname}, function(data){
            if(data == "true"){
                alert('Product deleted');
                $('#item_'+id).hide('slow');
            //} else if(data == "noimages"){
            //    alert('Album deleted, there are no images inside to delete');
            //    $('#item_'+id).hide('slow');
            } else {
                alert(data);
            }
        });
        
    }
}
</script>
