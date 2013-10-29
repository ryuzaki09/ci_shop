<!DOCTYPE html>
<html lang="en">
    <head>
        <title>LongDestiny Photos</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <meta name="description" content="Wave Display Effect with jQuery and CSS3" />
        <meta name="keywords" content="sinusoid, sine curve, jquery, thumbnails, portfolio, wave, images, css3, rotation, transform" />
        <meta name="author" content="Longdestiny" />
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="/css/wavegallery/ui-lightness/jquery-ui-1.8.16.custom.css" />
        <link rel="stylesheet" type="text/css" href="/css/wavegallery/demo.css" />
        <link rel="stylesheet" type="text/css" href="/css/wavegallery/style.css" />
        <link rel="stylesheet" type="text/css" href="/css/photosmenu.css" />
        <link href='http://fonts.googleapis.com/css?family=Terminal+Dosis' rel='stylesheet' type='text/css' />
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script id="fullscreenTmpl" type="text/x-jquery-tmpl">
		<div id="wd-overlay" class="wd-overlay">
                    <div class="wd-element">
			<span class="wd-close">x close</span>
                        <div class="wd-nav">
                               <span class="wd-nav-next">Next</span>
                               <span class="wd-nav-prev">Previous</span>
                        </div>
                        <img src="${source}" />
			<div class="wd-info">
                            <div class="wd-info-title">
				{{html title}}
                            </div>
                            <div class="wd-info-desc">
                            	{{html description}}
                            </div>
			</div>
                    </div>
		</div>
        </script>
    </head>
    <body>
        <div class="container">
            <div class="header" style="padding:5px;">
                <div class="go_left">                
                    <strong style="color:#fff;">ALBUM: </strong>		
                    
                </div>
                
                <ul id="nav" class="go_left">                    
                    <li><a href="#" class="selected">Select an album</a>
                        <ul>                            
                            <?php if(is_array($allalbums) && !empty($allalbums)){
                                foreach ($allalbums AS $albums){
                                    echo "<li><a href='/photos/".$albums['albumID']."'>".$albums['folder_name']."</a></li>";
                                }
                            } ?>
                        </ul>
                        <div class="clear"></div>
                    </li>
                    
                </ul>
                
		<span class="right">                    
                    <a href="<?php echo base_url(); ?>">
                        <strong>Back to HOME</strong>
                    </a>
		</span>
		<div class="clr"></div>
            </div><!-- header -->
            
            
            <h1><?php echo $single_album->folder_name; ?> </h1>
            <div class="more">
		
            </div>
            <div id="wd-wrapper" class="wd-wrapper">
			<div class="wd-slider"></div>
			<div class="wd-scroll-wrapper">
			<div class="wd-container">
                        
                        <?php if ($photos && is_array($photos)){ ?>    
			<?php foreach($photos AS $item){ ?>
                            <div class="wd-element">                            
                                <img src="/media/images/<?php echo $single_album->folder_name."/".$item['imgname']; ?>" />
                                <div class="wd-info">
                                    <div class="wd-info-title">
                                            <h2><?php echo $item['photo_title']; ?></h2>
                                    </div>
                                    <div class="wd-info-desc">
                                            <!--<p>Far far away, behind the word mountains, far from the countries Vokalia and 
                                                Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of 
                                                the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it 
                                                with the necessary regelialia.</p>-->
                                    </div>
                                </div>
                                
                            </div>
                        <?php } ?>
			<?php } ?>
                            
			<div class="wd-element">
				<img src="/media/1.jpg" />
				<div class="wd-info">
					<div class="wd-info-title">
						<h2>Beautiful Serendipity</h2>
					</div>
					<div class="wd-info-desc">
						<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean. A small river named Duden flows by their place and supplies it with the necessary regelialia.</p>
						<p>It is a paradisematic country, in which roasted parts of sentences fly into your mouth. Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name of Lorem Ipsum decided to leave for the far World of Grammar.</p>
					</div>
				</div>
			</div>
			<div class="wd-element">
				<img src="/media/2.jpg" />
					<div class="wd-info">
						<div class="wd-info-title">
							<h2>Lovely Scenery</h2>
						</div>
						<div class="wd-info-desc">
							<p>A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.</p>
                                                        <p>I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.</p>
                                                </div>
					</div>
			</div>
														
                        
                    </div><!-- wd-container -->
                </div><!-- wd-scroll-wrapper -->
            </div>
        </div><!-- container -->
        
        
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
        <script type="text/javascript" src="/js/wavegallery/jquery-ui-1.8.16.custom.min.js"></script>
	<script type="text/javascript" src="/js/jquery.tmpl.min.js"></script>
        <script type="text/javascript" src="/js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="/js/jquery.sinusoid.js"></script>
        <script type="text/javascript">
            $(function() {			
		$('#wd-wrapper').sinusoid();			
            });
            
            $(document).ready(function () {     
                $('#nav li').hover(function () {
                    //show its submenu
                    $('ul', this).slideDown(100);
                }, 
                function () {
                    //hide its submenu
                    $('ul', this).slideUp(100);         
                });
            });            
        </script>
    </body>
</html>	