<div class="page_title"><?php echo $pagetitle; ?></div>
<?php if (isset($message) && $message){ echo $message; } ?>

<form>
<div>
    <label class="wid250">Voucher amount value:</label><input type="text" name="voucher_amount" class="price" required />
</div>
<div>
    <label class="wid250">Enter Minimum amount to purchase:</label><input type="text" name="min_amount" class="price" required />
</div>

<div>
    <label class="wid250">Enter expiry date:</label><input type="text" name="expiry_date" class="datepick" required />
</div>

<div>
    <input type="submit" name="generate" value="Generate Voucher" class="btn btn-primary btn-small" />
</div>
</form>
<script src="/js/jquery.validate.min.js"></script>
<script>
$('form').validate();
</script>
