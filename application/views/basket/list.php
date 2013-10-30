<div class="container">
	<div class="basket_container">
		<div class="basket_title">Your Shopping Basket</div>
		
		<?php echo form_open(base_url().'basket/shoppingbasket'); ?>
		<table width="100%" border="0" class="collapse">
			
			<tr>
				<td width="10%" class="basket_headtd">Qty</td>
				<td class="basket_headtd">Product</td>
				<td width="15%" class="basket_headtd" align="right">Price</td>
				<td width="15%" class="basket_headtd" align="right">Total</td>
			</tr>
			<?php $i = 1; 
			
			foreach ($this->cart->contents() as $items): 
			
				echo form_hidden($i.'[rowid]', $items['rowid']); ?>
			
				<tr class="tr_bot_border">
				  <td><?php echo form_input(array('name' => $i.'[qty]', 'value' => $items['qty'], 'maxlength' => '3', 'size' => '5', 'class' => 'qty_field')); ?></td>
				  <td>
					<?php echo $items['name']; ?>
			
						<?php if ($this->cart->has_options($items['rowid']) == TRUE): ?>
			
							<p>
								<?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
			
									<strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br />
			
								<?php endforeach; ?>
							</p>
			
						<?php endif; ?>
			
				  </td>
				  <td style="text-align:right">&pound;<?php echo $this->cart->format_number($items['price']); ?></td>
				  <td style="text-align:right">&pound;<?php echo $this->cart->format_number($items['subtotal']); ?></td>
				</tr>
			
			<?php $i++; ?>
			
			<?php endforeach; ?>
			
			<tr class="tr_bot_border">
	 			<td colspan="2"> </td>
	  			<td align="right"><strong>Total</strong></td>
	  			<td align="right">&pound;<?php echo $this->cart->format_number($this->cart->total()); ?></td>
			</tr>
	
		</table>
		<br />
		<div class="clearfix">
			<div class="block200 go_left"><?php echo form_submit('update', 'Update Cart'); ?></div>
			<div class="block150 right"><input type="button" id="checkout" value="Checkout" /></div>
		</div>	
		
	</div>
</div>

<script>
	$('#checkout').click(function(){
		window.location.href='/basket/checkout';
	});
	
</script>
