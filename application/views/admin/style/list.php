<div class="page_title"><?php echo $pagetitle; ?></div>

<?php
echo "<div class='error'>".$this->session->flashdata('message')."</div>";
?>

<?php
if(is_array($stylelist) && !empty($stylelist)):
	
	foreach($stylelist AS $list): ?>
		
		<div class="clearfix block_top_bord">
			<div class="go_left block250">
			<?php echo $list['style_name']."<br />"; ?>
			</div>
			<!--<div class="go_right block75 deletebutton" data-style-id="<?php echo $list['id']; ?>">
				<!--<img src="../../media/images/icons/deleteicon.jpg" />
			</div>-->
			<div class="go_right block75">
				<a href="#" class="button">Delete</a>
			</div>
			<div class="go_right block75">
				<a href="edit_style/<?php echo $list['id']; ?>" class="button">Edit</a>
			</div>
		</div>
		<?php
	endforeach;
	
	
	
endif;


?>


<script>
	$('.deletebutton').click(function(){
		var response = confirm('Are you sure you want to delete this style?');
		
		if(response){
			var styleid = $(this).data('style-id');
			alert(styleid);
		}
	});
</script>