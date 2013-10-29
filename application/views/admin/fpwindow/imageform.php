<div class="content_block go_left">
    <div class='page_title'><?php echo $pagetitle; ?></div>
    <div class="bottom_space"><b>Note: </b><span class="note">All fields are required.</span></div>
    <?php if(isset($imgfiles) && $imgfiles){ echo $imgfiles; } ?>
    <?php if(isset($message) && $message){ 
        echo "<div class='error' style='margin-bottom:10px;'>";
        echo $message; 
        echo "</div>";
        } ?>
    
    <?php 
    if(isset($edit)){
        echo form_open_multipart(base_url().'admin/fpwindows/cropfile'); }
        //: form_open_multipart(base_url().'admin/fpwindows/addnew'); */?>
    
    <div class="clearfix">
        <input type="hidden" id="id" value="<?php echo $item->id;  ?>" />
        <div class="block150 go_left">Title</div>
        <div class="block250 go_left"><input type="text" id="title" name="title" value="<?php echo $item->title;  ?>" /></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Sub title</div>
        <div class="block250 go_left"><input type="text" id="subtitle" name="subtitle" value="<?php echo $item->sub_title; ?>" /></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Front page description</div>        
        <div class="block250 go_left"><textarea id="desc1" cols="40" rows="4" name="desc1" ><?php echo $item->desc1; ?></textarea></div>
    </div>
    <div class="clearfix">
        <div class="block150 go_left">Second page description</div>        
        <div class="block250 go_left"><textarea id="desc2" cols="40" rows="4" name="desc2" ><?php echo $item->desc2; ?></textarea></div>
    </div>
    
    <!---If there is an image   ---->    
    <?php if(isset($item->image) && $item->image){ ?>
            
            <div class="clearfix">
            	<div class="block150 go_left">Current Image</div>
            	<input type="hidden" id="current_img" name='current_img' value='<?php echo $item->image; ?>' />
            	<div class="go_left">
                    <img class="bottom_space" src='/media/images/frontpage/<?php echo $item->image; ?>' id="img2crop" width='400' height='250' alt='<?php echo $item->image;?>' />
                    <div class="bottom_space" style="width:100px; height:100px; overflow:hidden;">
                        <img src='/media/images/frontpage/<?php echo $item->image; ?>' width='200' height='200' alt='<?php echo $item->image;?>' />
                    </div>
                </div>
                
            </div>
            <div class='clearfix'>                
            	<div class='go_left' style='margin-left:160px;'>X1<input type='text' name="x1" id='x1' size="2"/></div>
            	<div class='go_left' style='margin-left:10px;'>Y1<input type='text' name="y1" id='y1' size="2"/></div>
            	<div class='go_left' style='margin-left:10px;'>X2<input type='text' name="x2" id='x2' size="2"/></div>
            	<div class='go_left' style='margin-left:10px;'>Y2<input type='text' name="y2" id='y2' size="2"/></div>
            	<div class='go_left' style='margin-left:10px;'>W<input type='text' name="w" id='w' size="2"/></div>
            	<div class='go_left' style='margin-left:10px;'>H<input type='text' name="h" id='h' size="2"/></div>
            </div>
            
            <div class="clearfix bottom_space">
                <div class="go_left" style="margin-left:160px;"><input type="button" id="crop" value="Crop" /></div>
            </div>
            
    <?php } ?>
    
    
    <!--<div class="clearfix">
        <div class="block150 go_left">
            <?php /*if(isset($edit)&& $edit){ 
                      echo "New Image to replace<br/><span class='note'>(Expanded Image: Dimensions - 1100x733)</span>"; 
                  } else { 
                      echo "Upload Image<br/><span class='note'>(Expanded Image: Dimensions - 1100x733)</span>"; 
                  } */?>
        </div>
        <div class="block250 go_left"><input type="file" id="image1" name="image1"/></div>        
    </div>    
        
    <div class="clearfix">
    <?php //if(isset($edit)&& $edit){ ?>
        <div class="block250 go_left"><input type="submit" name="update" value="Update" /></div>
    <?php //} else { ?>
        <div class="block250 go_left"><input type="submit" name="submit" value="Add" /></div>
    <?php //} ?>
    </div>-->
    <?php echo form_close(); ?>
</div>
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

