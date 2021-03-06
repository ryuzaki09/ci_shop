<div class="product_container" style="margin-top:60px;">
	<div class="clearfix bottom_space">
		
		<div class="nobreak block400" style="margin-right:30px; margin-bottom:20px;">
            <div class="clearfix">
			<div class="nobreak main_prod_img block350 top20">
				<img style="max-width:300px;" src="/media/images/products/<?php echo $product->img1; ?>" alt="Product Image 1" />
			</div>
			
			<?php if($product->img2) { ?>
			<div class="nobreak right10">
				<img style="max-width:100px;" src="/media/images/products/<?php echo $product->img2; ?>" alt="Product Image 2" />
			</div>
			<?php } ?>
			<?php if($product->img3) { ?>
			<div class="nobreak right10">
				<img style="max-width:100px;" src="/media/images/products/<?php echo $product->img3; ?>" alt="Product Image 3" />
			</div>
			<?php } ?>
			<?php if($product->img4) { ?>
			<div class="nobreak right10">
				<img style="max-width:100px;" src="/media/images/products/<?php echo $product->img4; ?>" alt="Product Image 4" />
			</div>
			<?php } ?>
						
            </div>
            <div class="clearfix top20 bottom_space bot_border">
                <div class="nobreak itemtitle" style="width:90px;">Description:</div>
                <div class="nobreak top20"><?php echo $product->desc; ?></div>
            </div>
		</div>
        <div id="prod_right_col">
            <div class="right_col_content">
			<div class="clearfix itemtitle bottom_space" style="padding-bottom:15px;">		
				<div class="nobreak" style="width:200px;"><?php echo $product->name; ?></div>
			</div>
			
			<div class="clearfix bot30">
				<div class="nobreak" style="width:90px;">Price:</div>
				<div class="nobreak" style="width:100px;">&pound;<?php echo $product->price; ?></div>
			</div>
			<?php
			if($product->stock > 0){
				?>
				<div class="clearfix" id="basket_div">
					<!-- <form method="POST" action="/products/item/<?php echo $product->pid; ?>">			 -->
					<div class="top_space">
						<input type="button" name="add_basket" id="add_to_basket" value="Add to Basket" />
					</div>
					<input type="hidden" name="rowID" id="rowId" value="<?php if(@$rowID){ echo $rowID; } ?>" />
					<input type="hidden" name="pid" id="pid" value="<?php echo $product->pid; ?>" />
					<input type="hidden" name="pname" id="pname" value="<?php echo $product->name; ?>" />
					<input type="hidden" name="price" id="price" value="<?php echo $product->price; ?>" />
					<!-- </form> -->
				</div>
				<?php
			} else {
				echo "Out of Stock";
			}
			?>

            <!--
			<div class="clearfix">
				<div class="go_right">
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_cart">
					<input type="hidden" name="upload" value="1">
					<input type="hidden" name="business" value="2XPWA6XZYS7ZJ">
					<input type="hidden" name="currency_code" value="GBP">
					<input type="hidden" name="lc" value="GB">
					<input type="hidden" name="return" value="http://shop.longdestiny.com/"> 
					<input type="hidden" name="item_name_1" value="<?php echo $product->name; ?>">
					<input type="hidden" name="amount_1" value="<?php echo $product->price; ?>">
					
					<!--<input type="hidden" name="button_subtype" value="services">-->
					
					<!--
					<input type="hidden" name="item_name_2" value="Naruto tshirt">
					<input type="hidden" name="amount_2" value="9.99">
					
					<!--<input type="hidden" name="shipping" value="2.99">-->
					<!--
					<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
					<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal — The safer, easier way to pay online.">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
					</form>
				</div>
			</div>-->	
		    </div><!-- right_col_content -->	
		</div><!-- prod_right_col -->
		
	</div>
</div>

