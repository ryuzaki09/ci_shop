<div class="page_title"><?php echo $pagetitle; ?></div>

<?php

if(is_array($result) && !empty($result)){

    foreach($result AS $data):
	?>
	<div class="list_div top_border" id="list_<?php echo $data['oid']; ?>">
	    Order No. <a href="#"><?php echo $data['order_no']; ?></a><br />
	    Total Price: &pound;<?php echo $data['total_price']; ?><br />
	    Order Created: <?php echo $data['order_created']; ?><br />
	    <input type="button" class="btn btn-primary btn-small" value="Disapprove" data-oid="<?php echo $data['oid']; ?>" />
	</div>
	<?php
    endforeach;
}
?>

<script>
$('input[type=button]').click(function(){
    var oid = $(this).data('oid');
    $('#list_'+oid).slideUp();
});

</script>
