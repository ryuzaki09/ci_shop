<?php $this->load->view('adminheader'); ?>
<?php if($adminmenu) { echo $adminmenu; } ?>
<?php $this->load->view($page); ?>

<?php $this->load->view('adminfooter'); ?>