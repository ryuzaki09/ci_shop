<div class="content_block go_left">
<div class="page_title"><?php echo $pagetitle; ?></div>
    
<!----- List of photos   --------->
<?php if(is_array($album_photos) && !empty($album_photos)){ ?>
<hr>
<!--<div class="clearfix">-->
    <?php $i = 0; $y=1; ?>
    <?php  
        while($i < count($album_photos)){ 
        //foreach($album_photos AS $photos){ 
        if ($y == 3 || $i == count($album_photos)){ $border=""; } else { $border = "border-right: 1px solid #cacaca"; } ?>
            <div class='go_left' id="photo_<?php echo $album_photos[$i]['pid']; ?>" style='padding-right:7px; margin-right:7px; width:240px; <?php echo $border; ?>'>
                <div style="width:240px; height:250px;">
                    <img style="max-height:240px; max-width:240px;" src='/media/images/<?php echo $album_name->folder_name; ?>/<?php echo $album_photos[$i]['imgname']; ?>' />
                </div>
                <div class="bottom_space"><input type="text" id="title_<?php echo $album_photos[$i]['pid']; ?>" size="27" value="<?php echo $album_photos[$i]['photo_title']; ?>" /></div>
                <div>
                    <input type='button' onclick='delete_photo("<?php echo $album_photos[$i]['pid']; ?>", "<?php echo $album_name->folder_name; ?>","<?php echo $album_photos[$i]['imgname']; ?>")' value='Delete' />
                    <input type='button' onclick='update_photo("<?php echo $album_photos[$i]['pid']; ?>");' value='Update' />
                </div>
            </div>
    <?php if($y==3){ echo "<div style='clear:both;'>&nbsp;</div><hr>"; } ?>
    <?php $i++; $y++;
          if ($y==4){ $y=1; }
        } ?>    
<!--</div>-->
    
<?php } ?>




</div><!--Content_block  -->

<script>

function delete_photo(id, foldername, imgname){
    var response = confirm('Delete Photo?');
    
    if (response){
        var url = "<?php echo base_url(); ?>admin/photos/delete_photo";

        $.post(url, {'id': id, 'foldername': foldername, 'imgname': imgname}, function(data){
            if(data == "true"){
                alert('Photo deleted');
                $('#photo_'+id).hide('slow');
            } else {
                alert(data);
            }
        });
    }
}

function update_photo(id){
    var title = $('#title_'+id).val();
    var url = "<?php echo base_url(); ?>admin/photos/update_photo";
    
    $.post(url, {'id': id, 'title': title}, function(data){
        if(data == "true"){
            alert('Updated');
        } else {
            alert(data);
        }
    });
    
}
</script>