<?php if(@$message){ echo "<div class='error'>".$message."</div>"; } ?>

<?php
if($pagetitle == "Admin Menu Setup"){
    ?>
	
    <?php echo form_open(); ?>
    <div class="clearfix">
	<div class="block150 go_left">Name of link: </div>
	<div class="block250 go_left"><input type="text" name="linkname" /></div>
    </div>
    <div class="clearfix">
	<div class="block150 go_left">Parent Menu? </div>
	<div class="block250 go_left">
	    <select id="parentmenu" name="parentmenu" onchange="submenu();">
		<option>Yes</option>
		<option>No</option>		
	    </select>
	</div>
    </div>
    <div style="display:none;" id="submenu_div"><!-- Show Hide Sub Menu fields -->
	<div class="clearfix">
	    <div class="block150 go_left">Which Parent Menu is this part of? </div>
	    <div class="block250 go_left">
		<select name="parent_id">
		    <option value=""></option>
		    <?php 
		    if(is_array($parentmenus) && !empty($parentmenus)):
			foreach($parentmenus AS $parent):
			    echo "<option value='".$parent['parent_id']."'>".$parent['link_name']."</option>";
			
			endforeach;
		    endif;
		    ?>
		</select>
	    </div>
	</div>
	    <div class="clearfix">
		<div class="block150 go_left">Url: <br /><span class="note"><?php echo base_url(); ?></span></div>
		<div class="block250 go_left"><input type="text" name="linkurl" /></div>
	    </div>
    </div>
	
    <div class="clearfix">
	<div class="block250 go_left"><input type="submit" name="add_menu" value="Add Menu" class="btn btn-primary btn-small" /></div>
    </div>
    <?php echo form_close(); ?>
    <?php
}

if($pagetitle == "Admin Menu List"){ //ADMIN MENU LIST PAGE

    if(is_array($menu_array)){
	echo $this->session->flashdata('message');
	
	echo "To re-arrange the order, simply drag and drop.";
	//WRAPPER
	echo "<div class='block_top_bord' id='parentlist'>";
	
	$counter=0;
	while($counter < count($menu_array)): //loop through parent
	    //PARENT MENUS
	    echo "<div class='menu_wrapper' id='parent_".$menu_array[$counter]['id']."'>"; //Parent Menu wrapper including sub menu window
	    echo "<div class='clearfix title_parent'>"; //Parent Menu Row
	    echo "<div class='block250 go_left'>".$menu_array[$counter]['link_name']."</div>";
	    echo "<div class='block100 go_left'><a class='show_parent' data-parent='".$menu_array[$counter]['id']."'>Show/Hide</a></div>";
	    echo "<div class='block100 go_left'><a id='edit_parent' data-parent-item='".$menu_array[$counter]['id']."' data-parent-name='".$menu_array[$counter]['link_name']."'>Edit</a></div>";
	    echo "<div class='block100 go_left'><a id='delete_parent' data-menu-id='".$menu_array[$counter]['id']."' data-parent-id='".$menu_array[$counter]['parent_id']."'>Delete</a></div>";
	    echo "</div>"; // end of parent menu row div
	    
	    
	    echo "<div id='sub_window_".$menu_array[$counter]['id']."'  class='sub_menu'>"; //sub window wrapper

	    $counter2=0;
						    
	    while($counter2 < count(@$menu_array[$counter]['submenu'])): //loop through sub menus
		    ?>					
		    <!--IF THERE ARE SUB MENUS -->				
		    <div class='clearfix bot5' id='sub_menulist_<?php echo $menu_array[$counter]['submenu'][$counter2]['id']; ?>'> <!-- Sub Menu Row -->
			    <div class='pad2 block250 go_left'><?php echo $menu_array[$counter]['submenu'][$counter2]['link_name']; ?></div>
			    <div class='block100 go_left'>
				    <a class='show_sub' data-submenu_id='<?php echo $menu_array[$counter]['submenu'][$counter2]['id']; ?>' 
					    data-submenu_name='<?php echo $menu_array[$counter]['submenu'][$counter2]['link_name']; ?>'
					    data-submenu_url='<?php echo $menu_array[$counter]['submenu'][$counter2]['url']; ?>'>Edit</a>
			    </div>
			    <div class='block100 go_left'>
				    <a id='delete_sub' data-menu_id='<?php echo $menu_array[$counter]['submenu'][$counter2]['id']; ?>'>Delete</a>
			    </div>
		    </div><!-- End of Sub Menu Row -->
		    
		    <?php	
		    $counter2++;
		    
	    endwhile;
	    
	    echo "</div>"; //close subwindow div
	    echo "</div>";
	    $counter++;
		
	endwhile;
	
	echo "</div>";	 
    }

}
?>
<div id="parent_dialog" title="Edit Parent Menu"><!-- PARENT MENU DIALOG -->
    <form method="POST" action="update_menu">
	<div class="clearfix">
	    <div class="block150 go_left">Link Name: </div><input type="text" id="parentname" name="parentname" class="block200 go_left" />		
	</div>	
	<input type="hidden" id="parentmenuid" name="parentmenuid" />
	<input type="submit" name="update_menu" value="Update" class="btn btn-primary btn-small" />
    </form>
</div>

<div id="sub_dialog" title="Edit Sub Menu"><!-- SUB MENU DIALOG -->
    <form method="POST" action="update_menu">
	<div class="clearfix">
		<div class="block150 go_left">Link Name: </div><input type="text" id="subname" name="subname" class="block200 go_left" />		
	</div>
	<div class="clearfix">
	    <div class="block150 go_left">Url: <br /><span class="note"><?php echo base_url(); ?></span></div><input type="text" id="suburl" name="suburl" class="go_left block200" />
	</div>
	<input type="hidden" id="menuid" name="menuid" />
	<input type="submit" name="update_menu" value="Update" class="btn btn-primary btn-small" />
    </form>
</div>

<script>
//show/hide the dropdown option of all parent menus
function submenu(){
    var parent =$('#parentmenu').val();
    if(parent == "No")
	$('#submenu_div').slideDown();
    else 
	$('#submenu_div').slideUp();
    
}

//show/hide the sub window
$('.menu_wrapper a.show_parent').click(function(){
	var parentid = $(this).data('parent');
	
	if($('#sub_window_'+parentid).is(':visible')){
		$('#sub_window_'+parentid).slideUp();
	} else {
		$('#sub_window_'+parentid).slideDown();
	}
});

//Edit Parent Menu
$('.menu_wrapper #edit_parent').click(function(){
	$('#parentmenuid').val($(this).data('parent-item'));
	$('#parentname').val($(this).data('parent-name'));
	
	$('#parent_dialog').dialog('open');
});

//Delete parent
$('.menu_wrapper #delete_parent').click(function(){
	var menu_id = $(this).data('menu-id');
	var parentid = $(this).data('parent-id');
	var response = confirm('Delete this menu along with all sub menus?');
	
	if(response){
		$.post('delete_menu', {'parent_id': parentid}, function(data){
			if(data=="true"){
				alert('Deleted!');
				$('#parent_'+menu_id).hide('slow');
			} else {
				alert(data);
			}
			
		});
	}
});

//edit the sub menu
$('.sub_menu a.show_sub').click(function(){
	$('#menuid').val($(this).data('submenu_id'));
	$('#subname').val($(this).data('submenu_name'));
	$('#suburl').val($(this).data('submenu_url'));
	
	$('#sub_dialog').dialog('open');
});

//Delete sub menu
$('.sub_menu #delete_sub').click(function(){
	var menu_id = $(this).data('menu_id');
	var response = confirm('Delete this Sub Menu?');
	
	if(response){
		$.post('delete_menu', {'menu_id': menu_id}, function(data){
			if(data=="true"){
				alert('Deleted!');
				$('#sub_menulist_'+menu_id).hide('slow');
			} else {
				alert(data);
			}
			
		});
	}
});

$(document).ready(function(){
    $(function() {
	$( "#sub_dialog, #parent_dialog" ).dialog({
	    autoOpen: false,
	    width: 450,
	    show: {
		effect: "blind",
		duration: 1000
	    },
	    hide: {
		effect: "explode",
		duration: 1000
	    }
	});
	    
	//drag and drop
	$("#parentlist").sortable({ 
	    opacity: 0.6, 
	    cursor: 'move', 
	    update: function() {
		    var order = $(this).sortable("serialize") + '&action=updateRecordsListings';

		    $.post("menu_sorting", order, function(theResponse){
			    if(theResponse){
				    //alert(theResponse);
			    }
		    });

	    }

	});

    });

});

</script>
