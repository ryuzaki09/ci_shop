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
	    <input type="button" id="disapprove" class="btn btn-primary btn-small" value="Disapprove" data-oid="<?php echo $data['oid']; ?>" />
            <input type="button" id="refund" class="btn btn-primary btn-small" value="Refund" 
                data-oid="<?php echo $data['oid']; ?>" data-saleid="<?php echo $external_ref->sales_id; ?>" />
	</div>
	<?php
    endforeach;
}
?>

<script>
$('#disapprove').click(function(){
    var oid = $(this).data('oid');
    $('#list_'+oid).slideUp();
});

$('#refund').click(function(){
    var response = confirm("Refund this sale?");
    var oid = $(this).data('oid');
    var saleid = $(this).data('saleid');
    if(response){
       window.location.href="/admin/orders/paypalrefund/"+oid+"/"+saleid;
    }
});
</script>
