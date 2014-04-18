<div class="container clearfix">
    <?php
    $this->load->view('user/leftpane');
    ?>
    <div class="contentpane">
	<?php echo $this->session->flashdata('message'); ?>
	<div class="boldtitle">My Account</div>
	<div class="bottom_space">Name: <?php echo $userdata->firstname." ".$userdata->lastname; ?></div>
	<div class="bottom_space">Address: <?php if($userdata->address1){ echo $userdata->address1; } 
						if($userdata->address2){ echo ", ".$userdata->address2; } ?></div>
	<div class="bottom_space">Postcode: <?php echo $userdata->postcode; ?></div>
	<div class="bottom_space">Email: <?php echo $userdata->email; ?></div>
	    
    </div>
	
	
</div>
