<!-- Main -->
<div id="main">
    <!-- Shell -->
    <div class="shell">
        <!-- Header -->
		<!--
        <div id="header">
            <h1 id="logo"><a href="#" class="notext" title="Shopper Friend">Shopper Friend</a></h1>
            <div id="navigation">
                    <ul>
                        <li><a href="#" class="active" title="For Boys"><span>For Boys</span></a></li>
                        <li><a href="#" title="For Girls"><span>For Girls</span></a></li>
                        <li><a href="#" title="Common"><span>Common</span></a></li>
                    </ul>
            </div>
            <div class="cl">&nbsp;</div>
        </div>
		-->
        <!-- End Header -->
        
        <div id="myCarousel" class="carousel slide">
			<ol class="carousel-indicators">
			<?php
			$i=0;
			while($i < count($carousel)):
				echo ($i==0)
				? "<li data-target='#myCarousel' data-slide-to='".$i."' class='active'></li>"
				: "<li data-target='#myCarousel' data-slide-to='".$i."'></li>";
				$i++;
			endwhile;
			
			?>
			<!-- <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1"></li>
			<li data-target="#myCarousel" data-slide-to="2"></li> -->
			</ol>
			<!-- Carousel items -->
			<div class="carousel-inner">
			<?php
			$z=0;
			if(is_array($carousel) && !empty($carousel)): 
				foreach($carousel AS $slider):
						
					echo ($z==0)
					? "<div class='active item'><img src='/media/images/carousel/".$slider['imgname']."' /></div>"
					: "<div class='item'><img src='/media/images/carousel/".$slider['imgname']."' /></div>";          	

					$z++;		
				endforeach;
			endif;
			?>
		</div>
	    <!-- Carousel nav -->
	    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
	    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
	</div>
    
	<!-- Content -->
	<div id="content">
	    <!-- Case -->
	    <div class="case">
			<h3>newest</h3>
			<!-- Row -->
			<div class="row">
				<ul>
				<?php
				if(is_array($products)){
					foreach($products AS $item): ?>
					<li>
						<a href="products/item/<?php echo $item['pid']; ?>" class="product" title="<?php echo ucfirst($item['name']); ?>">
						<div style="width:204px; height:165px; overflow:hidden;">
							<img src="/media/images/products/<?php echo $item['img1']; ?>" style="max-width:204px;" alt="Product Image 1" />
						</div>
						<div class="order model"><?php echo ucfirst($item['name']); ?></div>
						<!-- <span class="order">catalog number: <span class="number">1925</span></span> -->
						<div class="order">
							<span class="buy-text">Only</span>
							<span class="price">
							<span class="dollar">&pound;</span><?php echo number_format($item['price'], 2); ?>
							<!-- <span class="sub&#45;text">.99</span> -->
							</span>
						</div>
						</a>                                        
					</li>                                    
						
					<?php        
					endforeach;
				}
				?>
				</ul>
				<div class="cl">&nbsp;</div>
				<ul>
					<li>
						<a href="#" class="product" title="Product 1">
						<img src="../media/images/product-1.jpg" alt="Product Image 1" />
							<span class="order model">Model Name</span>
							<span class="order">
							<span class="buy-text">Only</span>
							<span class="price">
								<span class="dollar">&pound;</span>599
								<span class="sub-text">.99</span>
								</span>
							</span>
							</a>
						</li>
						<li>
							<a href="#" class="product" title="Product 2">
							<img src="../media/images/product-2.jpg" alt="Product Image 2" />
							<span class="order model">Model Name</span>
							<span class="order">
								<span class="buy-text">Only</span>
								<span class="price">
								<span class="dollar">&pound;</span>599
								<span class="sub-text">.99</span>
								</span>
							</span>
							</a>	
						</li>
						<li>
							<a href="#" class="product" title="Product 3">
							<img src="../media/images/product-3.jpg" alt="Product Image 3" />
							<span class="order model">Model Name</span>
							<span class="order">
								<span class="buy-text">Only</span>
								<span class="price">
								<span class="dollar">&pound;</span>599
								<span class="sub-text">.99</span>
								</span>
							</span>
							</a>	
						</li>
						<li>
							<a href="#" class="product" title="Product 4">
							<img src="../media/images/product-4.jpg" alt="Product Image 4" />
							<span class="order model">Model Name</span>
							<span class="order">
								<span class="buy-text">Only</span>
								<span class="price">
								<span class="dollar">&pound;</span>599
								<span class="sub-text">.99</span>
								</span>
							</span>
							</a>	
						</li>
					</ul>
					<div class="cl">&nbsp;</div>
				</div>
				<!-- End Row -->
			</div>
			<!-- End Case -->
		    
	    </div>
		<!-- End Content -->
	</div>
        <!-- End Shell -->
</div>
<!-- End Main -->

