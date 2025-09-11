<!DOCTYPE html>
<!-- saved from url=(0056)https://livedemo00.template-help.com/wt_62438/index.html -->
<html class="wide wow-animation desktop landscape rd-navbar-static-linked js-focus-visible" lang="en" data-js-focus-visible=""><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Site Title-->
    <title>Home</title>
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php echo $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')); ?>    
    
    <script type="text/javascript">
		var SITEURL = '<?php echo SITEURL; ?>';
	</script>
  		
   	<?= $this->fetch('content') ?>

</body></html>