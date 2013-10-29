<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <title>3D Gallery with CSS3 and jQuery</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="3D Gallery with CSS3 and jQuery" />
        <meta name="keywords" content="3d, gallery, jquery, css3, auto, slideshow, navigate, mouse scroll, perspective" />
        <meta name="author" content="Codrops" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="/css/3dgallery/3dgallery.css" />
        <link rel="stylesheet" type="text/css" href="/css/3dgallery/3dgallery_style.css" />
		<script type="text/javascript" src="/js/3dgallery.js"></script>
    </head>
    <body>
        <div class="container">
			<!-- Codrops top bar -->
            <div class="codrops-top">                
                <div style="float:left; color:#000; margin:0 15px;">
                    <strong>LONGDESTINY: </strong>Portfolio
                </div>
                
                <span class="right">					
                    <a href="<?php echo base_url(); ?>">
                        <strong>Back to HOME</strong>
                    </a>
                </span>
                <div class="clr"></div>
            </div><!--/ Codrops top bar -->
			<header>
				<h1>Portfolio Gallery <span style="font-size:14px;">with CSS3 &amp; jQuery</span></h1>
				<!--<nav class="codrops-demos">
					<a class="current-demo" href="index.html">Navigation</a>
					<a href="index2.html">Auto-Slideshow</a>					
				</nav>-->
			</header>
			<section id="dg-container" class="dg-container">
				<div class="dg-wrapper">
                                    <?php if(is_array($result) && !empty($result)){ 
                                            foreach($result AS $portfolio){ ?>
                                            <a href="http://<?php echo $portfolio['port_link']; ?>" target="_blank"><img style="max-width:480px; max-height:260px;" src="/media/images/portfolio/<?php echo $portfolio['port_img']; ?>" alt="image01"><div><?php echo $portfolio['port_link']; ?></div></a>
                                    
                                    <?php   } 
                                        } ?>
					<a href="#"><img src="/media/images/portfolio/1.jpg" alt="image01"><div>http://www.colazionedamichy.it/</div></a>
					<a href="#"><img src="/media/images/portfolio/2.jpg" alt="image02"><div>http://www.percivalclo.com/</div></a>
					<a href="#"><img src="/media/images/portfolio/3.jpg" alt="image03"><div>http://www.wanda.net/fr</div></a>					
														
				</div>
				<nav>	
					<span class="dg-prev">&lt;</span>
					<span class="dg-next">&gt;</span>
				</nav>
			</section>
        </div>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
		<script type="text/javascript" src="/js/jquery.gallery.js"></script>
		<script type="text/javascript">
			$(function() {
				$('#dg-container').gallery(/*{
                                    autoplay : true
                                }*/);
			});
		</script>
    </body>
</html>