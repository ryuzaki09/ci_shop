<?php

echo form_open();
$uname = array('name' => 'username', 'id' => 'username', 'value' =>'');
$submit = array('name' => 'submit', 'value' => 'Register');
        
?>
<ul>
<li>Username: <?php echo form_input($uname) ?></li>
<li><?php echo form_submit('formsubmit', 'Submit!'); ?></li>
</ul>
