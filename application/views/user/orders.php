<div class="container clearfix">
    <?php
    $this->load->view('user/leftpane');
    ?>
    <div class="contentpane">
	<?php echo $this->session->flashdata('message'); ?>
	<div class="darkgreytitle">My Account</div>
	<div>
	<label>Order No:</label>
	</div>
	<div>
	<label>Date ordered:</label>
	</div>

    </div>
	
	
</div>
