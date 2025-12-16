<?php
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><?php echo $this->vars['page_title']; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $lm->language('general', 'charset'); ?>"/>
<?php if (isset($page_refresh)) { ?>
<meta http-equiv="Refresh" content="2;URL=<?php echo $this->vars['page_refresh']; ?>" />
<?php } ?>



<link rel="alternate" type="application/rdf+xml" title="Latest Downloads" href="rss.php" />
<link href="templates/olate/global/core.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="templates/olate/global/core.js"></script>
</head>

<body>

<div id="wrapper">

	<div id="logo"><img src="templates/olate/images/logo.gif" width="190" height="68" alt="<?php echo $lm->language('frontend', 'logo'); ?>"/></div>	
	
	<?php echo $this->vars['page_content']; ?>
	
	<div id="footer">
		Powered by <a style="color:#FFFFFF" href="http://www.olate.co.uk">Olate Download</a> <?php echo $this->vars['global_vars']['version']; ?> 
		<p>
<?php if ( $site_config['allow_user_lang']) { ?>
		<a style="color:#FFFFFF" href="language.php"><?php echo $lm->language('frontend', 'change_language'); ?></a>
<?php } ?>
<?php if ($global_vars['enable_rss'] == 1) { ?>
<?php if ( $site_config['allow_user_lang']) { ?>
			|
<?php } ?>
			<a style="color:#FFFFFF" href="<?php echo $this->vars['global_vars']['url']; ?>rss.php">RSS</a>
<?php } ?> 
<?php if ($global_vars['debug'] == 1) { ?>
			| Execution Time: <?php echo $this->vars['global_vars']['exec_time']; ?> | Query Count: <?php echo $this->vars['global_vars']['queries']; ?>
<?php } ?>
		</p>
	</div>
	
</div>

</body>
</html>