<!DOCTYPE html> 
<!-- <html xmlns="http://www.w3.org/1999/xhtml"> -->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="/css/admin.css" />
<link rel="stylesheet" href="/css/jquery.Jcrop.css" type="text/css" />
<link rel="stylesheet" href="/css/jquery-ui-1.10.0.custom.min.css" type="text/css" />
<link rel="stylesheet" href="/js/bootstrap/css/bootstrap.css" type="text/css" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<?php 
if(isset($css) && $css){
    foreach($css AS $style){
        echo $style;
    }
};

if(isset($js) && $js){
    foreach($js AS $script){
        echo $script;
    }
};

?>

<script type="text/javascript" src="/js/jquery-ui-1.10.0.custom.min.js"></script>

<title><?php echo($pagetitle) ?></title>
</head>
<body>
<a name="top"></a>
<div id="container">
	<div id="header">
        	<img src="/media/images/headerpic.jpg" />	
			<br/>
        </div>
        
