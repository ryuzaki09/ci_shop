<div class="page_title"><?php echo $pagetitle; ?></div>

<form method="POST">
    <div>
	<label class="wid150">Start time</label><input type="text" class="datepick" name="start_time" value="" />
    </div>
    <div>
	<label class="wid150">End time</label><input type="text" class="datepick" name="end_time" value="" />
    </div>
    <div>
	<label class="wid150">Sort by</label>
	<select name="sort_by">
	    <option></option>
	    <option value="create_time">Create Time</option>
	    <option value="update_time">Update Time</option>
	</select>
    </div>
    <div>
	<label class="wid150">Sort order</label>
	<select name="sort_order">
	    <option value="asc">Ascending</option>
	    <option value="desc">Descending</option>
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
	<div>Name: <?php echo @$payments->payer->payer_info->first_name." ".@$payments->payer->payer_info->last_name; ?></div>


	<?php
    endforeach;
}
?>


<script>
$(function(){

    $('.datepick').datepicker();

});

</script>
