<div class="page_title"><?php echo $pagetitle; ?></div>
<?php if ($this->session->flashdata('message')){ echo $this->session->flashdata('message'); } ?>

<?php

if(is_array($result) && !empty($result)){
    foreach($result AS  $list):
	?>
	<div class="top_border list_div">
	    <div>
		<label class="wid250">Voucher Code:</label><?php echo $list['voucher_code']; ?>
	    </div>
	    <div>
		<label class="wid250">Date created:</label><?php echo $list['date_created']; ?>
	    </div>
	    <div>
		<label class="wid250">Expiry date:</label><?php echo $list['expiry_date']; ?>
	    </div>
	    <div>
		<label class="wid250">Voucher value:</label><?php echo $list['voucher_value']; ?>
	    </div>
	    <div>
		<label class="wid250">Minimum purchase amount:</label><?php echo $list['min_purchase']; ?>
	    </div>
	</div>
	<?php
    endforeach;
}
?>
