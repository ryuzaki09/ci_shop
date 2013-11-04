<!--<div class="content_block go_left">-->
    <div class='page_title'><?php echo $pagetitle; ?></div>
    <div class="bottom_space"><b>Note: </b><span class="note">All fields are required.</span></div>
    <?php if(isset($imgfiles) && $imgfiles){ echo $imgfiles; } ?>
    <?php if(isset($message) && $message){ 
        echo "<div class='error' style='margin-bottom:10px;'>";
        echo $message; 
        echo "</div>";
        } ?>
    
    <?php 
    echo(isset($edit))
        //echo form_open_multipart(base_url().'admin/fpwindows/cropfile'); }
        ? form_open_multipart(base_url().'admin/fpwindows/edit/'.$item->uid)
        : form_open_multipart(base_url().'admin/fpwindows/addnew'); ?>
    
    <div class="clearfix">
        <input type="hidden" id="id" value="<?php echo @$item->id;  ?>" />
        <div class="block150 go_left">Name</div>
        <div class="block250 go_left"><input type="text" id="firstname" name="firstname" value="<?php echo @$item->firstname;  ?>" /></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Last Name</div>
        <div class="block250 go_left"><input type="text" id="lastname" name="lastname" value="<?php echo @$item->lastname; ?>" /></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Email</div>        
        <div class="block250 go_left"><input type="text" id="email" name="email" value="<?php echo @$item->email; ?>" /></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Address 1</div>        
        <div class="block250 go_left"><input type="text" id="email" name="address1" value="<?php echo @$item->address1; ?>" /></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Address 2</div>        
        <div class="block250 go_left"><input type="text" id="email" name="address2" value="<?php echo @$item->address2; ?>" /></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Postcode</div>        
        <div class="block250 go_left"><input type="text" id="email" name="postcode" value="<?php echo @$item->postcode; ?>" /></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Password</div>        
        <div class="block250 go_left"><input type="password" id="password" name="password" value="<?php echo @$item->sub_title; ?>" /></div>
    </div>
    
      
    
    <div class="clearfix">
    <?php if(isset($edit)&& $edit){ ?>
        <div class="block250 go_left"><input type="submit" name="update" value="Update" /></div>
    <?php } else { ?>
        <div class="block250 go_left"><input type="submit" name="submit" value="Add" /></div>
    <?php } ?>
    </div>
    <?php echo form_close(); ?>
<!--</div><!-- contentblock-->
<script>    
    function preview(img, selection){
        var scaleX = 100 / selection.width;
        var scaleY = 100 / selection.height;
        
        var orig_w = document.getElementById('img2crop').width;
	var orig_h = document.getElementById('img2crop').height;
        
        $('#img2crop + div > img').css({
            width: Math.round(scaleX * orig_w) + 'px',
            height: Math.round(scaleY * orig_h) + 'px',
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
        });
        
        $('#x1').val(selection.x1);
        $('#y1').val(selection.y1);
        $('#x2').val(selection.x2);
        $('#y2').val(selection.y2);
        $('#w').val(selection.width);
        $('#h').val(selection.height);
    }
    
    $(document).ready(function () { 
		$('#crop').click(function() {
                    var x1 = $('#x1').val();
                    var y1 = $('#y1').val();
                    var x2 = $('#x2').val();
                    var y2 = $('#y2').val();
                    var w = $('#w').val();
                    var h = $('#h').val();
                    var current_img = $('#current_img').val();
	                
			if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
				alert("You must make a selection first");
				return false;
			} else {
	            var url = "<?php echo base_url(); ?>admin/fpwindows/cropfile";
	            $.post(url, {'x1': x1, 'y1': y1, 'x2': x2, 'y2': y2, 'w': w, 'h': h, 'current_img': current_img}, function(data){
		            if(data){
		            	alert(data);
		            }
	        	});
			}
		});
    });
    
    $(window).load(function () {  
        $("#img2crop").imgAreaSelect({ aspectRatio: "1:1", onSelectChange: preview });  
    });
    
    
</script>
