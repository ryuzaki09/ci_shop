<div class="container">
    <div class="basket_container">
	<div class="basket_title">Your Shopping Basket</div>
		
	<?php echo form_open(base_url().'basket/shoppingbasket'); ?>
	<table width="100%" border="0" class="collapse basket-table">
	    <tr class="basket_head">
			<td width="10%">Qty</td>
			<td style="text-align:left">Product</td>
			<td width="15%">Price</td>
			<td width="15%">Total</td>
	    </tr>
	    <?php 
	    //IF the shopping basket is not empty
	    if($this->cart->contents()){
		$i = 1;
		foreach ($this->cart->contents() as $items): 
			    
		    echo form_hidden($i.'[rowid]', $items['rowid']); ?>
			    
		    <tr class="tr_bot_border basket-list">
				<td>
					<?php 
					echo $items['qty'];
					// echo form_input(array('name' => $i.'[qty]', 
					// 			'value' => $items['qty'], 
					// 			'maxlength' => '3', 
					// 			'size' => '5', 
					// 			'class' => 'qty_field')); 

					echo form_hidden($i.'[pid]', $items['id']); 
					echo form_hidden($i.'[oldqty]', $items['qty']);
					?>
				</td>
				<td>
				<?php 
				echo "<a href='/products/item/".$items['id']."' class='cart-prod-name'>".$items['name']."</a>";
				echo "<a href= '#' data-pid='".$items['id']."' data-rowid='".$items['rowid']."' class='basket-btn remove'>Remove</a>"; 
				?>	
				<?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
					<p>
					<?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
					
						<strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
					
					<?php endforeach; ?>
					</p>
					
				<?php endif; ?>
				
				</td>
				<td>&pound;<?php echo $this->cart->format_number($items['price']); ?></td>
				<td>&pound;<?php echo $this->cart->format_number($items['subtotal']); ?></td>
		    </tr>	
		    <?php 
		    $i++; 
			    
		endforeach; ?>
			    
		<tr class="tr_bot_border basket-list">
		    <td> </td>
			<td> </td>
		    <td><strong>Total</strong></td>
		    <td>&pound;<?php echo $this->cart->format_number($this->cart->total()); ?></td>
		</tr>
	    </table>
	    <br />
	    <div class="clearfix">
			<!-- <div class="block200 go_left"><?php echo form_submit('update', 'Update Cart'); ?></div> -->
			<div class="block150 right">
				<button>
					<a href="/basket/checkout">Checkout</a>
				</button>
				<!-- <input type="button" id="checkout" value="Checkout" /> -->
			</div>
	    </div>		
	    <?php
	} else { //Shopping Basket is empty then show this
	    echo "<tr class='tr_bot_border'><td colspan='4' align='center'>There are no items in your basket</td></tr>";
	    echo "</table>";
	}
	?>
    </div>
</div>

