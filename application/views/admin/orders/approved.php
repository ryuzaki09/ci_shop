<div class="page_title"><?php echo $pagetitle; ?></div>

<?php

if(is_array($result) && !empty($result)){
	var_dump($result);

    foreach($result AS $data):
        $external_ref = json_decode($data['external_ref']);
		?>
		<div class="list_div top_border" id="list_<?php echo $data['oid']; ?>">
			Order No. <a href="#"><?php echo $data['order_no']; ?></a><br />
			Total Price: &pound;<?php echo $data['total_price']; ?><br />
			Order Created: <?php echo $data['order_created']; ?><br /><br />
			<input type="button" class="btn btn-primary btn-small disapprove" value="Disapprove"
					data-oid="<?php echo $data['oid']; ?>" />
			<input type="button" class="btn btn-primary btn-small refund" value="Refund" data-oid="<?php echo $data['oid']; ?>" 
					data-saleid="<?php echo $external_ref->sales_id; ?>" data-total="<?php echo $data['total_price']; ?>" 
					data-currency="<?php echo $data['currency']; ?>" />
		</div>
		<?php
    endforeach;
}
?>

<script>
$('.disapprove').click(function(){
    var oid = $(this).data('oid');
    var response = confirm("Disapprove order "+oid+"?");
    if(response){
	$.post("disapprove_order", {"oid": oid}, function(data){
	    if(data == "true")
		$('#list_'+oid).slideUp();
	    else
		alert("Cannot disapprove order "+oid);
	});
    }
});

$('.refund').click(function(){
    var response = confirm("Refund this sale?");
    var oid = $(this).data('oid');
    var saleid = $(this).data('saleid');
	var total = $(this).data('total');
	var currency = $(this).data('currency');
    if(response){
       window.location.href="/admin/orders/paypalrefund/"+oid+"/"+saleid+"/"+total+"/"+currency;
    }
});
</script>
