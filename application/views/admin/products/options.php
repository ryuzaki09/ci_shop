<div class="page_title"><?php echo $pagetitle; ?></div>
<?php
if(isset($message))
	echo $message;
?>
<?php
if($pagetitle == "Add Option"){
	?>
	Original Price: <?php echo $item->price; ?>
	<form method="POST">
		<div>
			<label class="wid150">Colour: </label><input type="text" name="color" />
		</div>
		<div>
			<label class="wid150">Size: </label>
			<select name="size">
				<option></option>
				<?php
				foreach($sizes AS $gender => $value):
					echo "<optgroup label='".$gender."'>";
					foreach($value AS $size){
						echo "<option>".$size."</option>";
					}
					echo "</optgroup>";
				endforeach;
				?>
			</select>
		</div>
		<div>
			<label class="wid150">Price: </label><input type="text" name="price" />
		</div>
		<input type="submit" name="addoption" value="Add product option" class="btn btn-primary btn-small" />
	</form>
	<?php
}

if($pagetitle == "Product Options"){
	if(is_array($item) && !empty($item)){
		echo "<div class='bot50'>";
		foreach($item AS $options):
			if(!is_null($options['po_id'])){
				echo "<div class='clearfix list_div top_border' id='option_".$options['po_id']."'>";
				echo "Color: ".$options['color']."<br />";
				echo "Size: ".$options['p_size']."<br />";
				echo "Price: ".$options['po_price']."<br /><br />";
				echo "<div><a href='#' class='delete btn btn-primary btn-small' data-option='".$options['po_id']."'>Delete</a></div>";
				echo "</div>";
			}

		endforeach;
		echo "</div>";
	}
	echo "<a href='/admin/products/listing' class='btn btn-primary btn-small'>Back</a>";
}
?>
<script>
$('form').validate({
	rules: {
		size: {
			required: true
		},
		price: {
			required: true
		}
	},
	messages: {
		price: {
			required: "A price is required for this option"
		}
	}

});

$('.delete').click(function(){
	var response = confirm("Delete this option?");
	if(response){
		var url = "/admin/product/deleteOption";
		var p_option = $(this).data('option');

		$.post(url, {"po_id": p_option }, function(data){
			if(data == "true")
				$('#option_'+p_option).slideUp();
		});

	}
});
</script>
