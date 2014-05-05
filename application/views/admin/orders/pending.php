<div class="page_title"><?php echo $pagetitle; ?></div>

<?php
if($this->session->flashdata("message"))
    echo $this->session->flashdata("message");

if(is_array($result) && !empty($result)){
    foreach($result AS $data):
		?>
		<div class="clearfix list_div top_border">
			<div class="block350 go_left">
			Order No. <a href="details/<?php echo $data['order_no'];?>"><?php echo $data['order_no']; ?></a><br />
			Total Price: &pound;<?php echo $data['total_price']; ?><br />
			Order Created: <?php echo $data['order_created']; ?>
			</div>
			<div class="block300 go_left">
				Delivery Address:
				<div>
					<?php
					if($data['delivery_address']){
						$delivery_info = json_decode($data['delivery_address']);
						echo $delivery_info->name."<br />".$delivery_info->address."<br />".$delivery_info->postcode;
					}
					?>
				</div>
			</div>
		</div>
		<?php
    endforeach;
}
?>
