<div class="page_title"><?php echo $pagetitle; ?></div>

<?php
if(is_array($result) && !empty($result)){
	foreach($result AS $data):
	?>
	<div class="list_div top_border">
		Order No. <a href="details/<?php echo $data['order_no'];?>"><?php echo $data['order_no']; ?></a><br />
		Total Price: &pound;<?php echo $data['total_price']; ?><br />
		Order Created: <?php echo $data['order_created']; ?>

	</div>
	<?php
	endforeach;
}
?>
