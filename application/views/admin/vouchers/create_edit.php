<div class="page_title"><?php echo $pagetitle; ?></div>
<?php if (isset($message) && $message){ echo $message; } ?>

<form method="post">
<div>
    <label class="wid250">Voucher amount value:</label>
    <input type="text" name="voucher_amount" class="price" />
</div>
<div>
    <label class="wid250">Enter Minimum amount to purchase:</label>
    <input type="text" name="min_amount" class="price" />
</div>

<div>
    <label class="wid250">Enter expiry date:</label>
    <input type="text" id="expiry_date" name="expiry_date" class="datepick" />
</div>

<div>
    <input type="submit" name="generate" value="Generate Voucher" class="btn btn-primary btn-small" />
</div>
</form>
<script src="/js/jquery.validate.min.js"></script>
<script>
$('form').validate({
    rules: {
	expiry_date: {
	    required: true,
	    date: true
	},
	min_amount: {
	    required: true,
	    number: true
	},
	voucher_amount: {
	    required: true,
	    number: true
	}
    }
});
</script>
