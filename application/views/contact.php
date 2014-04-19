<div class="container" style="margin-top:20px;">
	<div class="basket_title">Contact Us</div>
	<form method="POST">                                            
		<div class="bottom_space top20">
			<label class="wid150" for="name">Name:  <font size="1">(required)</font></label>
			<input type="text" class="blueborder" name="name" id="name" maxlength="30" size="30" />
		</div>
		<div class="bottom_space">  
			<label class="wid150" for="email">Email:  <font size="1">(required)</font></label>
			<input type="text" class="blueborder" name="email" id="email" size="30" />
		</div>
		<div class="bottom_space">
			<label class="wid150 top-align" for="message">Message:  <font size="1">(required)</font></label>
			<textarea style="padding:5px;" class="blueborder" name="message" id="message" rows="5" cols="40"></textarea>
		</div>
		<div class="bottom_space">
			<label class="wid150"> </label>
			<input type="checkbox" name="copy" id="copy" style="width:15px;" value="mailcopy" />&nbsp;
			<span for="copy">Send me a copy</span>
		</div>
		<div class="bottom_space">
			<div class="txtlabel205"><input type="submit" id="send" value="send" name="send" /></div>
			<div class="txtlabel205"><input type="button" id="reset_form" style="display:none;" onclick="this.form.reset();"></div>
		</div>
	</form>
</div>
<script>
$('form').validate({
	rules: {
		name: {
			required: true,
			minlength: 2,
		},
		email: {
			required: true,
			email: true,
		},
		message: {
			required: true,
			minlength: 10,
		}

	},
	messages: {
		name: {
			required: "This field cannot be blank",
			minlength: "You need to enter at least 2 chars"
		},
		email: {
			required: "You must enter an email address",
			email: "This email address is invalid"
		},
		message: {
			minlength: "You must enter at least 10 chars"
		}
	},
	submitHandler: function(){
		send_msg();
	}
});

function send_msg(){
    var name = $('#name').val();
    var email = $('#email').val();
    var website = $('#website').val();
    var msg = $('#message').val();
    if($('#copy:checked').val() == "mailcopy"){
        var copy = $('#copy').val();
    } else {
        var copy = "";
    }
    
	var url = "contact/contact_msg";
	
	$.post(url, {'name': name, 'email': email, 'website': website, 'msg': msg, 'copy': copy}, function(data){
	   if (data == "true"){
			   alert('Message sent!');
		   $('#reset_form').click();
		   
	   } else {
		   alert(data);
	   }
	});
}
</script>
