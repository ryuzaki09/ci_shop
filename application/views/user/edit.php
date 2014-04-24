<div class="container clearfix">
	<?php
	$this->load->view('user/leftpane');
	?>
	<div class="contentpane">
		<form method="post" action="<?php echo $form_action; ?>">
		<div class="darkgreytitle">My Account</div>
		<?php
		$message = $this->session->flashdata("message");
		if($message) 
			echo "<div class='alert alert-error'>".$message."</div>";
		?>
		<div class="bottom_space">
			<label class="wid150">First Name</label>
			<input type="text" name="firstname" value="<?php echo $userdata->firstname; ?>"  />
		</div>
		<div class="bottom_space">
			<label class="wid150">Last Name</label>
			<input type="text" name="lastname" value="<?php echo $userdata->lastname; ?>" />
		</div>
		<div class="bottom_space">
			<label class="wid150">Address 1</label>
			<input type="text" name="address1" value="<?php echo $userdata->address1; ?>" />
		</div>
		<div class="bottom_space">
			<label class="wid150">Address 2</label>
			<input type="text" name="address2" value="<?php echo $userdata->address2; ?>" />
		</div>
		<div class="bottom_space">
			<label class="wid150">Post Code</label>
			<input type="text" name="postcode" value="<?php echo $userdata->postcode; ?>" />
		</div>
		<div class="bottom_space">
			<label class="wid150">Email</label>
			<?php echo $userdata->email; ?>
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
		firstname: {
			required: true,
			minlength: 2,
		},
		lastname: {
			required: true,
			minlength: 2,
		},
		address1: {
			required: true,
			minlength: 6,
		},
		address2: {
			required: true,
			minlength: 5,
		},
		postcode: {
			required: true,
			minlength: 5,
		}

    },
	messages: {
		firstname: {
			minlength: "You need to enter at least 2 chars"
		}
	}
});

</script>
