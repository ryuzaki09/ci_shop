<h2>Admin Login</h2>
<div id="adminlogin">    
    <p class="error"><?php if (@$message){ echo($message); } ?></p>
	<ul>
		<li>Username: </li><input type="text" name="username" id="username" placeholder="Username..." /><br /><br />
		<li>Password: </li><input type="password" name="password" id="password" placeholder="Enter password..." /><br /><br />
		<li><input type="button" onclick="loginbtn();" id="login" class="btn btn-primary btn-small" value="Login" /></li>
                
	</ul>    
<br /><br />
</div>
<br /> <br />
<script type="text/javascript">
    $("#password").keypress(function(event){
		if(event.keyCode == 13 && ($('#username').is(':focus') || $('#password').is(':focus'))){
			$("#login").click();
			// loginbtn();
		}
    });
    
    //$('#login').click(function(){
    function loginbtn(){
        var uname = $('#username').val();
        var pwd = $('#password').val();
        var url = "login/process_login";
        
        $.post(url, {username: uname, password: pwd}, function(data){
            if (data == 'true'){            
                window.location ="home";
            } else {
                alert('Login Failed');
            }        
        });
        
    }
</script>
