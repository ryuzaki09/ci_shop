<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
    <title><?php echo $email_title; ?></title>

</head>
<body>
    <?php
    if(@$page == "forgot_password"){ ?>
	You have requested to reset your password, please click on the link below to continue. <br />
	http://shoplongdestiny.dev/user/reset_password/<?php echo $uid; ?>/<?php echo $reset_code; ?>

	<?php
    } else {
	?>
	Thank you registering! Please click on the link below to activate your account. <br />
	http://shoplongdestiny.dev/user/verify_email/<?php echo $uid; ?>/<?php echo $code; ?>
	<?php 
    }
    ?>
</body>
</html>
