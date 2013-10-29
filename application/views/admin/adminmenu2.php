<div id="accordion" style="width:140px; float:left; text-align:center; font-size:12px; margin-right:20px; padding:10px 0;">
    <h4><a href="#">Admin Home</a></h4>
    <div class="marg7_0">
        <a href="<?php echo base_url(); ?>admin/home">Home</a>
    </div>
    
          
    <?php
    	
	$menu_count2=0;
	while($menu_count2 < count($menu_array)): //looop through parent
		
		echo "<h4><a href='#'>".$menu_array[$menu_count2]['link_name']."</a></h4>";
		echo "<div class='marg7_0'>";
		$subcount2=0;
		//$total_subcount = count($menu_array[$menu_count2]['submenu']);
				
		while($subcount2 < count($menu_array[$menu_count2]['submenu'])): //loop through sub menus
				
			//if there are submenus
			//if($total_subcount >0){
				echo "<a href='".base_url().$menu_array[$menu_count2]['submenu'][$subcount2]['url']."'>".$menu_array[$menu_count2]['submenu'][$subcount2]['link_name']."</a><br />";
			
			//}
									 
			$subcount2++;
			
		endwhile;
		echo "</div>";
		$menu_count2++;
	endwhile;    
    
    ?>
    <h4><a href="#">Admin menus</a></h4>
    <div class="marg7_0">
        <a href="/admin/home/menusetup">Menu setup</a><br />
		<a href="/admin/home/menulist">Menu List</a><br />
    </div>
</div>


<script>
$(function() {
    $( "#accordion" ).accordion({ active:true });    
});
</script>
<div class="content_block go_left">