<div class="page_title"><?php echo $pagetitle; ?></div>
<?php
if (is_array($result) && !empty($result)){ ?>
    <ul id="contentLeft" style="padding:0px;">
    <?php 
    foreach($result AS $output){ ?>
	<li id="carousel_<?php echo $output['id']; ?>" class="bottom_space">
	    <div class="clearfix">
        	<div style="width:500px;" class="go_left">Name: <?php echo $output['name']; ?></div>
        	<div style="width:50px;" class="go_left">
		    <a href="javascript:void(0);" onclick="edit('<?php echo $output['id']; ?>');">Edit</a>
		</div>
        	<div style="width:50px;" class="go_left">
		    <a href="javascript:void(0);" onclick="delete_item('<?php echo $output['id']; ?>','<?php echo $output['imgname']; ?>');">Delete</a>
		</div>
	    </div>

	    <!-- sub content -->
	    <div id="sub_content<?php echo $output['id']; ?>" style="display:none; padding:10px;" class="clearfix">
        	<div class="go_left" style="width:250px; margin-right:15px; min-height:200px;">
		    <img src="/media/images/carousel/<?php echo $output['imgname']; ?>" style="max-width:250px; max-height:150px;" />
        	</div>       	
        	<div class="go_left block150">Name:</div>
		<div class="go_left block250">
		    <input type="text" id="name_<?php echo $output['id']; ?>" value="<?php echo $output['name']; ?>" />
		</div><br />
        	<div class="go_left block150">Description:</div>
		<div class="go_left block250">
		    <textarea id="desc_<?php echo $output['id']; ?>"><?php echo $output['desc']; ?></textarea>
		</div><br/>
        	<div class="go_left block150">Price:</div>
		<div class="go_left block250">
		    <input type="text" id="price_<?php echo $output['id']; ?>" value="<?php echo $output['price']; ?>" />
		</div><br/>
        	<div class="go_left block150">Position:</div>
		<div class="go_left block250">
		    <input type="text" id="position_<?php echo $output['id']; ?>" value="<?php echo $output['position']; ?>" />
		</div><br/><br/>
        	<div class="go_left block150">
		    <input type="button" name="update" value="Update" onclick="update_item('<?php echo $output['id']; ?>');" />
		</div>
	    </div>
	</li>
<?php } ?>
    </ul>
<?php
}

?>

<script>
function edit(id){
    if($('#sub_content'+id).is(':visible')){
        $('#sub_content'+id).slideUp();
    } else{
        $('#sub_content'+id).slideDown('slow');
    }
}

function update_item(id){
    var name = $('#name_'+id).val();
    var desc = $('#desc_'+id).val();
    var price = $('#price_'+id).val();
    var pos = $('#position_'+id).val();
    var url = '/admin/carousel/update_carousel';
    

    $.post(url, {'id': id, 'name': name, 'desc': desc, 'price': price, 'pos':pos}, function(data){
	if(data =="true")
	    alert('Updated!');	
	else 
	    alert(data);
    
    });
}

function delete_item(id, old_img){
    var response = confirm('Are you sure you want to delete?');
    
    if(response){
        var url = "/admin/carousel/delete_carousel";

        $.post(url, {'id': id, 'old_img': old_img}, function(data){
            if(data =="true"){
                alert('Deleted!');
                $('#carousel_'+id).hide('slow');
            } else {
                alert(data);
            }

        });
    }
    
}
</script>
