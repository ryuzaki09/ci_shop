<div class="page_title"><?php echo $pagetitle; ?></div>

<form method="POST">
    <div>
	<label class="wid150">Start time</label>
	<input type="text" class="datepick" name="start_time" value="<?php echo set_value('start_time'); ?>" />
    </div>
    <div>
	<label class="wid150">End time</label>
	<input type="text" class="datepick" name="end_time" value="<?php echo set_value('end_time'); ?>" />
    </div>
    <div>
	<label class="wid150">Sort by</label>
	<select name="sort_by">
	    <option></option>
	    <option value="create_time" <?php echo set_select('sort_by', "create_time"); ?>>Create Time</option>
	    <option value="update_time" <?php echo set_select("sort_by", "update_time"); ?>>Update Time</option>
	</select>
    </div>
    <div>
	<label class="wid150">Sort order</label>
	<select name="sort_order">
	    <option value="asc" <?php echo set_select("sort_order", "asc"); ?>>Ascending</option>
	    <option value="desc" <?php echo set_select("sort_order", "desc"); ?>>Descending</option>
	</select>
	</div>
    <div>
	<input type="submit" name="search" value="List Payments!" class="btn btn-primary btn-small" />
    </div>
</form>

<?php
if(@$result && !empty($result)){
    foreach($result->payments AS $payments):
	?>
	<div class="top_border list_div">
	    <div>
		<label class="wid150">Name:</label>
		<?php echo @$payments->payer->payer_info->first_name." ".@$payments->payer->payer_info->last_name; ?>
	    </div>
	    <div>
		<label class="wid150">Create time:</label>
		<?php $createtime = str_replace(array("T", "Z"), " ", $payments->create_time); echo $createtime; ?>
	    </div>
	    <div>
		<label class="wid150">Total price:</label>
		<?php echo $payments->transactions[0]->amount->total." ".$payments->transactions[0]->amount->currency; ?>
	    </div>
	    <div>
		<label class="wid150">Status:</label>
		<?php echo $payments->state; ?>
	    </div>
	</div>

	<?php
    endforeach;
}
?>


