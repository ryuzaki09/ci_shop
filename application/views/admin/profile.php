<div class="page_title bottom_space"><?php echo $pagetitle; ?></div>
<div class="clearfix bottom_space">
    <div class="go_left" style="width:150px;">New Password</div>
    <div class="go_left"><input type="password" id="pwd1" /></div>
</div>
<div class="clearfix bottom_space">
    <div class="go_left" style="width:150px;">Confirm Password</div>
    <div class="go_left"><input type="password" id="pwd2" /></div>
</div>
<div class="clearfix bottom_space">        
    <div class="go_left"><input type="button" id="change" value="Change Password!" class="btn btn-primary btn-small" /></div>
</div>

<script>
$('#change').click(function(){
    var pwd1 = $('#pwd1').val();
    var pwd2 = $('#pwd2').val();

    var url = "<?php echo base_url(); ?>admin/profile/process";
    
    if(pwd1 == "" || pwd2 == ""){
        alert('Please fill in both fields');
    } else if(pwd1 != pwd2) {
        alert('Passwords do not match!');
    } else if (pwd1 == pwd2) {
        $.post(url, {'pwd1': pwd1}, function(data){
           if(data == "true"){
               alert('Password Changed');
               window.location.reload();
           } else {
               alert('Cannot change password');
           }
        });
    }

});
</script>
