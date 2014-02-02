<div class="page_title"><?php echo $pagetitle; ?></div>
Order No: <?php echo $order_info[0]['order_no']; ?><br />
Total Price: &pound;<?php echo $order_info[0]['total']; ?><br />
Customer: <?php echo $order_info[0]['firstname']." ".$order_info[0]['lastname']; ?><br />
Order Created: <?php echo $order_info[0]['date_created']; ?><br /><br />
Payment Method: <?php echo $order_info[0]['method']; ?><br />
Payment Status: <?php if(!empty($paypal_result)) echo $paypal_result->state;  ?><br />

<?php
if(is_array($order_info) && !empty($order_info)){
	foreach($order_info AS $order):
		?>
		<div class="order_list_div">
		<?php
			echo "Product Name: ".$order['name']."<br />";
			echo "Quantity: ".$order['qty']."<br />";
			echo "Price: &pound;".$order['price']."<br />";
		?>
		</div>
		<?php
	endforeach;
}
?>
