<div class="container">
    <div class="register_block">
	<div class="boldtitle">Create New Account</div>
	<?php  echo @$message; ?>		
	<form method="POST" action="/user/register">
	<div class="clearfix bottom_space">
	    <div class="block150 go_left">Email Address: </div>
	    <div class="block150 go_left"><input type="text" name="email" value="<?php echo set_value('email'); ?>" /></div>
	    <?php echo form_error('email'); ?>
	</div>
	<div class="clearfix bottom_space">
	    <div class="block150 go_left">Password: </div>
	    <div class="block150 go_left"><input type="password" name="password1" /></div>
	    <?php echo form_error('password1'); ?>
	</div>
	<div class="clearfix bottom_space">
	    <div class="block150 go_left">Confirm Password: </div>
	    <div class="block150 go_left"><input type="password" name="password2" /></div>
	    <?php echo form_error('password2'); ?>
	</div>
	<div class="user_details">
	    <div class="clearfix bottom_space">
		<div class="block150 go_left">First Name: </div>
		<div class="block150 go_left"><input type="text" name="firstname"  value="<?php echo set_value('firstname'); ?>" /></div>
		<?php echo form_error('firstname'); ?>
	    </div>
	    <div class="clearfix bottom_space">
		<div class="block150 go_left">Last Name: </div>
		<div class="block150 go_left"><input type="text" name="lastname"  value="<?php echo set_value('lastname'); ?>" /></div>
		<?php echo form_error('lastname'); ?>
	    </div>
	    <div class="clearfix bottom_space">
		<input type="submit" name="create" value="Create Account!" />
	    </div>	
	</div>
	</form>
    </div>
</div>
