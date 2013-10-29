<div class="container">
	<?php
	if($successpage == "account activated"){
	?>
		<div class="bottom_space">Account Activated!</div>
		<div>Thanks again for registering! Click <a href="/user/login">here</a> to continue to the login page.</div>
	<? } else { ?>
		<div class="bottom_space">Success! Thank you for registering! Please check your email for an account activation link, once verified, you can start to login with the details you just signed up with.</div>
		<div>Note: If you do not find it in your inbox folder, please be sure to check the junk/spam folder as we cannot control the flow of the email.</div>
		
	<?php	
	} ?>
</div>
