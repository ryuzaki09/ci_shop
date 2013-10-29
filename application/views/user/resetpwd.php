<div class="container">
	<?php
	if($page == "reset_success"){ ?>
	Password has been successfully changed! You may login now with your new password!
	<?php		
	} else { ?>
	<form method="POST" action="/user/change_password">
	    <div class="loginblock">
	    	<div class="logintitle">Password Reset</div>
	    	<?php echo "<div class='error'>". $this->session->flashdata('error')."</div>"; ?>
	    	<div class="bottom_space" style="color:#0252AA;">Please enter your new password below:</div>
	    	New Password:
	    	<div class="bottom_space"><input type="password" name="pwd1" /></div>
	    	Confirm Password:
	    	<div class="bottom_space"><input type="password" name="pwd2" /></div>
	    	<input type="hidden" name="uid" value="<?php echo $uid; ?>" />
	    	<input type="hidden" name="code" value="<?php echo $reset_code; ?>" />
	    	<input type="hidden" name="forgot_id" value="<?php echo $id; ?>" />
	    	<input type="submit" name="password_submit" value="Submit New Password!" />
	    </div>
	</form>
	<?php } ?>
</div>