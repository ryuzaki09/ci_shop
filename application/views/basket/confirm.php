<div class="container">
    <div class="basket_container">
	<div class="basket_title">Delivery Address</div>
	<div class="block400 bot30">
		<div class="bottom_space">Name: <?php echo $userdata->firstname." ".$userdata->lastname; ?></div>
		<div class="bottom_space">Address: <?php echo $userdata->address1."<br />".$userdata->address2; ?></div>
		<div class="bottom_space">Postcode: <?php echo $userdata->postcode; ?></div>
		<div class="bottom_space">Email: <?php echo $userdata->email; ?></div>
		<button><a id="alt_address">Alternative delivery address</a></button>
	</div>
	<!-- Alternative Address -->
	<div class="block400" id="show_alt_address" style="display:none;">
		<label class="wid150">Address</label><span class="show_address"></span><input type="hidden" id="show_address" />
		<label class="wid150">Post code</label><span class="show_postcode"></span><input type="hidden" id="show_postcode" />
		<div>
			<button>Don't Use this address</button>
		</div>
	</div>
		
	<?php
	if($environment){
	    ?>
	    <form method="POST" action="process_checkout">
	    <?php
	}
	?>
	<!-- List Products -->
	<table width="100%" border="0" class="collapse bottom_space">
	    <tr>
		<td width="10%" class="basket_headtd">Qty</td>
		<td class="basket_headtd">Product</td>
		<td width="15%" class="basket_headtd" align="right">Price</td>
		<td width="15%" class="basket_headtd" align="right">Total</td>
	    </tr>
	    <?php 
	    $i = 1; 
			
	    foreach ($this->cart->contents() as $items): 
			//hidden form fields to send back to server to process	
			echo form_hidden($i.'[rowid]', $items['rowid']); 
			echo form_hidden($i.'[qty]', $items['qty']); 
			echo form_hidden($i.'[price]', $items['price']); 
			echo form_hidden($i.'[pid]', $items['id']); 
			echo form_hidden($i.'[name]', $items['name']); 
			echo form_hidden($i.'[subtotal]', $items['subtotal']); 
					
			?>
				
			<tr class="tr_bot_border">
				<td><?php echo $items['qty']; ?></td>
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
				
			<?php $i++; 
			
		endforeach; 
		?>
			
		<tr class="tr_bot_border">
		    <td colspan="2"> </td>
		    <td align="right"><strong>Total</strong></td>
		    <td align="right">&pound;<?php echo $this->cart->format_number($this->cart->total()); ?></td>
		</tr>
	    </table>
	    <div class="clearfix">
		<div class="block150 right"><input type="submit" id="payment" value="Proceed to Payment" /></div>
	    </div>

	    <?php
	    if($environment){
			?>
			</form>
			<?php
	    } else {
			// if(!$environment){
			?>
				
			<div class="clearfix">
				<div class="go_right">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_cart">
					<input type="hidden" name="upload" value="1">
					<input type="hidden" name="business" value="2XPWA6XZYS7ZJ">
					<input type="hidden" name="currency_code" value="GBP">
					<input type="hidden" name="lc" value="GB">
					<input type="hidden" name="return" value="http://shop.longdestiny.com/">
					<?php
					$count =1;
					foreach ($this->cart->contents() as $items2): 
						
						echo "<input type='hidden' name='item_name_".$count."' value='".$items2['name']."' />";
						echo "<input type='hidden' name='amount_".$count."' value='".$items2['price']."' />"; 
						echo "<input type='hidden' name='quantity_".$count."' value='".$items2['qty']."' />";
								
						$count++;
					endforeach;
					?>
							
					<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHosted">
					<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_buynowCC_LG.gif" border="0" 
								name="submit" alt="PayPal — The safer, easier way to pay online.">
					<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
				</form>
				</div>
			</div>
			<?php
	    }
	    ?>

    </div>
</div>

<!-- Dialog window for alternative deliver address -->
<div class="dialog" title="Alternate delivery address">
	<form id="alt_address_form">
		<div class="bottom_space">
			<label class="wid150">Address 1: </label><input type="text" name="alt_address1" id="alt_address1" />
			<label class="wid150">Address 2: </label><input type="text" name="alt_address2" id="alt_address2" />
			<label class="wid150">Post code: </label><input type="text" name="alt_postcode" id="alt_postcode" size="5" />
		</div>
		<button><a id="use_alt_address">Use this address</a></button>
	</form>
</div>
<?php


?>
<script>
$('#alt_address').click(function(){
	$('.dialog').dialog('open');
});

$(function(){
	$('.dialog').dialog({
	    autoOpen: false,
		width: 450,
		show: {
			effect: "blind",
			duration: 1000
	    },
	    hide: {
			effect: "explode",
			duration: 1000
	    }

	});

});

//form validate
jQuery.validator.setDefaults({
	debug: true,
	success: "valid"
});

var alt_form = $('#alt_address_form');

alt_form.validate({
	rules: {
		alt_address1: {
			required: true,
			minlength: 6
		},
		alt_address2: {
			required: true,
			minlength: 2
		},
		alt_postcode: {
			required: true,
			minlength: 5
		}
	},
	messages: {
		alt_address1: {
			// required: "This cannot be empty"
		}
	}

});

$('#use_alt_address').click(function(){
	if(alt_form.valid()){
		$('.show_address').html($('#alt_address1').val() + ", " +$('#alt_address2').val());
		$('.show_postcode').html($('#alt_postcode').val());
		$('#show_alt_address').show();
		$('.dialog').dialog('close');
	}
	// alert("valid: "+ alt_form.valid());
	// console.log(alt_form);
	// var display_values = alt_form[0];
	// console.log(display_values);
	// $('#show_alt_address').html(display_values);
	// alt_form.each(function(e){
		// console.log($(this));
	//});
});

</script>
