<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
	    <div id="footer">
	    <div class="block150 go_left left20">Shop Longdestiny</div>
	    <div class="block250 go_right right20">
		Copyright &copy; <?php echo date('Y'); ?> |
		<a href="/shop/tnc">T & C</a> | 
		<a href="/contact">Contact</a>
	    </div>
	</div>
    </div>
    <!-- End Wrapper -->
	<?php
	if (isset($js) && $js){                    
		foreach($js AS $script => $key):
			echo $key;
		endforeach;
	}
	?>
</body>
</html>
