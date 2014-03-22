<div class="container clearfix">
    <?php
    $this->load->view('user/leftpane');
    ?>
    <div class="contentpane">
	<?php echo $this->session->flashdata('message'); ?>
	<div class="darkgreytitle">My Account</div>
	<?php
	if($result && is_array($result)){
	    foreach($result AS $list):
		?>
		<div class="list_list_bot_border">
		    <div>
			<label class="wid150">Order No:</label><?php echo $list['order_no']; ?>
		    </div>
		    <div>
			<label class="wid150">Date ordered:</label><?php echo $list['order_created']; ?>
		    </div>
		    <div>
			<label class="wid150">Status:</label><?php echo $list['status']; ?>
		    </div>
		    <div>
			<label class="wid150">Item:</label><?php echo $list['name']; ?>
		    </div>
		    <div>
			<label class="wid150">Price:</label><?php echo $list['price']; ?>
		    </div>
		</div>
		<?php
	    endforeach;
	}
	?>
	<div>
	<?php echo $this->pagination->create_links(); ?>
	</div>
    </div>
	
	
</div>
