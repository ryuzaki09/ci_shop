<!DOCTYPE html>
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
	<title><?php if($pagetitle){ echo $pagetitle;} else {  echo base_url(); }  ?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="/css/images/favicon.ico" />
	<link rel="stylesheet" href="/css/shopstyle.css" type="text/css" media="all" />
	<link rel="stylesheet" href="/js/bootstrap/css/bootstrap.css" type="text/css" media="all" />
	
	<?php
    if (isset($css) && $css){                    
        foreach($css AS $style => $value):
            echo $value;
        endforeach;
    }
	?>
	<script src="/js/jquery-1.9.0.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/jquery-ui-1.10.0.custom.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="/js/bootstrap/js/bootstrap.js" type="text/javascript" charset="utf-8"></script>	
	<?php
    if (isset($js) && $js){                    
        foreach($js AS $script => $key):
            echo $key;
        endforeach;
    }
	//sessiondata
	$sessiondata = $this->session->all_userdata();
    ?>
</head>
<body>
<!-- Wrapper -->
<div id="wrapper">
    <!-- Header -->
    <div id="top">
        <div class="shell">
            <div class="top-nav">
                <ul>
                	<?php if(@$sessiondata['is_logged_in']){ ?>
	                    <li class="first nobg"><?php echo "Welcome ".$sessiondata['customer']."!"; ?></a></li>
	                    <li><a href="/user/logout" title="Logout">Logout</a></li>
	                    <li><a href="/account/profile/<?php echo $sessiondata['uid']; ?>" title="My Account">My Account</a></li>
                    <?php } else { ?>
                    	<li><a href="/user/login" title="Login">Login</a></li>
                    	<li><a href="/user/register" title="Register">Register</a></li>
                    <?php } ?>
                    <li><a href="/" title="Logout">Home</a></li>
                    
                    <?php if(is_basket()) { ?>
                    	<li>
							<a href="<?php echo base_url(); ?>basket">
								<span id="total_qty"><?php echo $this->cart->total_items(); ?></span> item(s) in the basket</a>
						</li>	
                    <?php 
                    } ?>
                </ul>
            </div>
            <?php if(is_basket()) { ?>
                    <a href="<?php echo base_url(); ?>products/empty_basket" class="empty_basket">Empty Basket</a>
            <?php } ?>
            <!-- <div id="search"> -->
            <!--         <form action="" method="post"> -->
            <!--                 <input type="text" class="field" value="Quick search..." title="Quick search..." style="margin&#45;top:&#45;5px; "/> -->
            <!--         </form> -->
            <!-- </div> -->
            <div class="cl">&nbsp;</div>
        </div>
        <!-- End Shell -->
    </div>
    <!-- End Top -->
