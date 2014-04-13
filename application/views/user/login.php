<div class="container">
    <?php 
    if(isset($loginpage) && $loginpage =="forgotpassword"){ ?>
	<form method="POST" action="forgot_password">    
	<div class="loginblock">
	    <div class="boldtitle">Forgot Password</div>
	    <div class="bottom_space">
		Please enter your email address and we will send you a link to reset your password:
	    </div>
	    <div class="bottom_space"><input type="text" size="25" name="email" /></div>
	    <?php if(isset($message))
		    echo "<div class='error bottom_space'>".$message."</div>";
	    ?>
	    <div class="bottom_space">
		<input type="submit" name="send_email" value="Send Email" />
	    </div>
	</div>
	</form>	
	<?php
    } else { ?>
	<form method="POST" action="login">    
	<div class="loginblock">
	    <?php if(isset($message)){ echo "<div class='error'>".$message."</div>"; } ?>
	    <div class="boldtitle">Customer Login</div>
	    <div class="bottom_space">Email:</div>
	    <div class="bottom_space"><input type="text" size="25" name="email" /></div>
	    <div class="bottom_space">Password:</div>
	    <div class="bottom_space"><input type="password" size="25" name="password" /></div>
	    <div class="bottom_space"><input type="submit" name="submit" value="Login" /></div>
	    <div><a href="/user/forgot_password">Forgot your password?</a></div>
	    
	</div>
	</form>
    <?php } ?>
</div>
