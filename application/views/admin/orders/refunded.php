<div class="page_title"><?php echo $pagetitle; ?></div>

<?php

if(is_array($result) && !empty($result)){

    foreach($result AS $data):
        $external_ref = json_decode($data['external_ref']);
		?>
		<div class="list_div top_border" id="list_<?php echo $data['oid']; ?>">
			Order No. <a href="#"><?php echo $data['order_no']; ?></a><br />
			Total Price: &pound;<?php echo $data['total_price']; ?><br />
			Order Created: <?php echo $data['order_created']; ?><br /><br />
		</div>
		<?php
    endforeach;
}
?>


