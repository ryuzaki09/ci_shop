<div class="container">
    <div class="borded_container">
	<div class="basket_title">Order Completed</div>
    <?php
    if($order_info){
	// echo "<pre>";
	// print_r($order_info);
	// echo "</pre>";
	?>
	Thank you for your order! Your Order Number is <strong><?php echo $order_info['order_no']; ?></strong>!

	<?php
	// echo "<pre>";
	// print_R($this->session->all_userdata());
	// echo "</pre>";
    }

    ?>




    </div>
</div>
