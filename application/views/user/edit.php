<div class="container clearfix">
	<?php
	$this->load->view('user/leftpane');
	?>
	<div class="contentpane">
		<form method="post" action="/account/update_details">
		<div class="darkgreytitle">My Account</div>
		<div class="clearfix bottom_space">
			<div class="block100 go_left">First Name</div>
			<div class="block200 go_left"><input type="text" name="firstname" value="<?php echo $userdata->firstname; ?>"  required /></div>
		</div>
		<div class="clearfix bottom_space">
			<div class="block100 go_left">Last Name</div>
			<div class="block200 go_left"><input type="text" name="lastname" value="<?php echo $userdata->lastname; ?>" required /></div>
		</div>
		<div class="clearfix bottom_space">
			<div class="block100 go_left">Address 1</div>
			<div class="block200 go_left"><input type="text" name="address1" value="<?php echo $userdata->address1; ?>" required /></div>
		</div>
		<div class="clearfix bottom_space">
			<div class="block100 go_left">Address 2</div>
			<div class="block200 go_left"><input type="text" name="address2" value="<?php echo $userdata->address2; ?>" required /></div>
		</div>
		<div class="clearfix bottom_space">
			<div class="block100 go_left">Post Code</div>
			<div class="block200 go_left"><input type="text" name="postcode" value="<?php echo $userdata->postcode; ?>" required /></div>
		</div>
		<div class="clearfix bottom_space">
			<div class="block100 go_left">Email</div>
			<div class="block200 go_left"><?php echo $userdata->email; ?></div>
		</div>
		<div class="clearfix bottom_space">
			<input type="hidden" name="uid" value="<?php echo $userdata->uid; ?>" />
			<input type="submit" name="update" value="Update" />
		</div>
		</form>
	</div>
	
	
</div>
<script>
$('form').validate({
    rules: {
	expiry_date: {
	    required: true,
	    date: true
	},
	min_amount: {
	    required: true,
	    number: true
	},
	voucher_amount: {
	    required: true,
	    number: true
	}
    }
});

</script>
