<div class="page_title"><?php echo $pagetitle; ?></div>

<form method="POST">





<input type="submit" name="search" value="List Payments!" class="btn btn-primary btn-small" />
</form>

<?php
if(@$result && !empty($result)){
    foreach($result->payments AS $payments):
	?>
	<div><?php echo @$payments->payer->payer_info->first_name." ".@$payments->payer->payer_info->last_name; ?></div>


	<?php
    endforeach;
}
?>
