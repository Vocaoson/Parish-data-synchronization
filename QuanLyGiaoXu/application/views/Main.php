<!DOCTYPE html>
<html lang="en">
<head>
	<?php 
	$this->load->view('Head');
	?>
</head>
<body>
	<div class="top">
		<?php 
		$this->load->view('top');
		?>
	</div>
	<div class="left">
		<?php 
		$this->load->view('left');
		?>
	</div>
	<?php
	if (isset($subNotifi)) {
		$this->load->view($subNotifi);
	} 
	if (isset($subview)) {
		$this->load->view($subview);
	}
	?>
	<script type="text/javascript" src="<?= base_url() ?>SupportWeb/vendor/bootstrap.js"></script>
<script type="text/javascript" src="<?= base_url() ?>SupportWeb/1.js"></script>
</body>
</html>