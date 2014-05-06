<div class="page_title"><?php echo $pagetitle; ?></div>
<?php
if(isset($message))
	echo $message;
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
</script>
