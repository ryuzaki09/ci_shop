<div class="container" style="margin-top:20px;">
<div class="basket_title">Contact Us</div>
<form method="POST" action="#" id="contactform">                                            
    <div class="clearfix bottom_space top20">
        <div class="label110"><label for="name">Name:  <font size="1">(required)</font></label></div>
        <div class="txtlabel205"><input type="text" class="blueborder" name="name" id="name" maxlength="30" size="30" /></div>&nbsp;&nbsp;<span class="error"><?php echo($message1); ?></span>
    </div>
    <div class="clearfix bottom_space">  
        <div class="label110"><label for="email">Email:  <font size="1">(required)</font></label></div>
        <div class="txtlabel205"><input type="text" class="blueborder" name="email" id="email" size="30" /></div>&nbsp;&nbsp;<span class="error"><?php echo($message2); ?></span>
    </div>
    <div class="clearfix bottom_space">
        <div class="label110"><label for="message">Message:  <font size="1">(required)</font></label></div>
        <div class="go_left" style="width:245px;">
        	<textarea style="padding:5px;" class="blueborder" name="message" id="message" rows="5" cols="40"></textarea>
        </div>&nbsp;&nbsp;<span class="error"><?php echo($message3); ?></span>
    </div>
    <div class="clearfix bottom_space">
        <div style="float:left; width: 220px;"><input type="checkbox" name="copy" id="copy" style="width:15px;" value="mailcopy" />&nbsp;<label for="copy">Send me a copy</label></div>                              
    </div>
    <div class="clearfix bottom_space">
        <div class="txtlabel205"><input type="button" id="send" value="send" name="send" /></div>
        <div class="txtlabel205"><input type="button" id="reset_form" style="display:none;" onclick="this.form.reset();"></div>
    </div>
</form>
</div>
<script>
$('#send').click(function(){    
    var name = $('#name').val();
    var email = $('#email').val();
    var website = $('#website').val();
    var msg = $('#message').val();
    if($('#copy:checked').val() == "mailcopy"){
        var copy = $('#copy').val();
    } else {
        var copy = "";
    }
    
    if (name =="" || email =="" || msg==""){
        alert('Please enter the required fields');
    } else {
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

});
    
</script>