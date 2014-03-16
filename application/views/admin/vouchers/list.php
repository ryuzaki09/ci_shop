<div class="page_title"><?php echo $pagetitle; ?></div>
<?php if (isset($message) && $message){ echo $message; } ?>

<?php

if(is_array($result) && !empty($result)){
    foreach($result AS  $list):
	?>
	<div class="top_border list_div">
	    <div>
		<label class="wid150">Voucher Code:</label><?php echo $list['voucher_code']; ?>
	    </div>
	    <div>
		<label class="wid150">Date created:</label><?php echo $list['date_created']; ?>
	    </div>
	    <div>
		<label class="wid150">Expiry date:</label><?php echo $list['expiry_date']; ?>
	    </div>
	    <div>
		<label class="wid150">Voucher value:</label><?php echo $list['voucher_value']; ?>
	    </div>
	    <div>
		<label class="wid150">Minimum purchase amount:</label><?php echo $list['min_purchase']; ?>
	    </div>
	</div>
	<?php
    endforeach;
}
?>
