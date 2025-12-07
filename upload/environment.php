<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Olate Environment Survey</title>
</head>
<style>
body { font-family:Tahoma, Sans; font-size:0.9em; }
</style>

<body>
<h1>Olate Environment Survey</h1>
<p style="margin-top: -15px; margin-left:2px;"><a href="http://www.olate.co.uk">http://www.olate.co.uk</a></p>
<?php if (!isset($_POST['server'])): ?>
<p>This survey will look at your server and determine what software is in use, and what versions are installed. This allows us to decide which versions we need to support for our existing and future products. All information is voluntary, will not identify you or your server and is held under our standard <a href="http://www.olate.co.uk/legal/privacy.php">privacy policy</a>.</p>
<p>To continue, please fill out the details below. Your login access details will not be saved or sent to us.</p>

<h3>Database Login Details</h3>
<form action="environment.php" method="post">
<p><label for="username">Username: </label><input name="username" type="text" size="15" /></p>
<p><label for="password">Password: </label><input name="password" type="password" size="15" /></p>
<p><label for="server">Server Address: </label><input name="server" type="text" value="localhost" size="15" /></p>
<p><input name="submit" type="submit" value="Continue" /></p>
</form>
<?php else: ?>
<p>The following information has been generated. To send it to Olate, please click the button below. If you have any comments, please provide them in the appropriate field.</p>
<form action="http://www.olate.co.uk/development/environmentsurvey.php" method="post">
	<p><label for="comment">Comments:</label></p>
	<p><textarea name="comments" cols="45" rows="3"></textarea></p>
	<p><input name="Submit" type="submit" value="Send to Olate" />
	
	<h3>PHP Information</h3>
	
	<p>PHP Version: <?php echo phpversion(); ?><input name="php_version" type="hidden" value="<?php echo phpversion(); ?>" /></p> 
	
	<p>Loaded modules:<input name="php_extensions" type="hidden" value="<?php echo base64_encode(serialize(get_loaded_extensions())); ?>" /></p>
	
	<ul>
		<?php
		$extensions = get_loaded_extensions();
		sort($extensions);
		foreach ($extensions as $extension)
		{
		?>
		<li><?php echo $extension; ?></li>
		<?php
		}
		?>
	</ul>
	
	<h3>Server Information</h3>
	<p>Gateway Information: <?php echo $_SERVER['GATEWAY_INTERFACE']; ?><input name="server_gateway" type="hidden" value="<?php echo $_SERVER['GATEWAY_INTERFACE']; ?>" /></p>
	<p>Server Software: <?php echo $_SERVER['SERVER_SOFTWARE']; ?><input name="server_software" type="hidden" value="<?php echo $_SERVER['SERVER_SOFTWARE']; ?>" /></p>
	<p>HTTP Encoding: <?php echo $_SERVER['HTTP_ACCEPT_ENCODING']; ?><input name="server_encoding" type="hidden" value="<?php echo $_SERVER['HTTP_ACCEPT_ENCODING']; ?>" /></p>
	<p>HTTP Accept Language: <?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE']; ?><input name="server_language" type="hidden" value="<?php echo $_SERVER['HTTP_ACCEPT_LANGUAGE']; ?>" /></p>
	<p>PHP_OS: <?php echo PHP_OS; ?><input name="server_php_os" type="hidden" value="<?php echo PHP_OS; ?>" /></p>
		
	<h3>MySQL Database Information</h3>
	<?php if(extension_loaded('mysql')): ?>
	<h4>mysql</h4>
		
		<?php if (!@mysql_connect($_POST['server'], $_POST['username'], $_POST['password'])): ?>
			<p>Unable to connect using the details provided (<?php echo mysql_error(); ?>). <a href="index.php">Click here to enter them again</a>.</p>
		<?php endif; ?>
	
	<p>Client Encoding: <?php echo mysql_client_encoding(); ?><input name="mysql_client_encoding" type="hidden" value="<?php echo mysql_client_encoding(); ?>" /></p>
	<p>Client Version: <?php echo mysql_get_client_info(); ?><input name="mysql_client_version" type="hidden" value="<?php echo mysql_get_client_info(); ?>" /></p>
	<p>Protocol Version: <?php echo mysql_get_proto_info(); ?><input name="mysql_protocol_version" type="hidden" value="<?php echo mysql_get_proto_info(); ?>" /></p>
	<p>Server Version: <?php echo mysql_get_server_info(); ?><input name="mysql_server_version" type="hidden" value="<?php echo mysql_get_server_info(); ?>" /></p>
	
	<?php endif; if(extension_loaded('mysqli')): ?>
	
	<h4>mysqli</h4>
	
	<?php $mysqli = new mysqli($_POST['server'], $_POST['username'], $_POST['password']); ?>
	
	<p>Client Encoding: <?php echo $mysqli->character_set_name(); ?><input name="mysqli_client_encoding" type="hidden" value="<?php echo $mysqli->character_set_name(); ?>" /></p>
	<p>Client Version: <?php echo $mysqli->client_info; ?><input name="mysqli_client_version" type="hidden" value="<?php echo $mysqli->client_info; ?>" /></p>
	<p>Protocol Version: <?php echo $mysqli->protocol_version; ?><input name="mysqli_protocol_version" type="hidden" value="<?php echo $mysqli->protocol_version; ?>" /></p>
	<p>Server Version: <?php echo $mysqli->server_info; ?><input name="mysqli_server_version" type="hidden" value="<?php echo $mysqli->server_info; ?>" /></p>
	
	<?php endif; if(extension_loaded('pdo_mysql')): ?>
	<h4>pdo_mysql</h4>
	
	<?php $pdo = new PDO('mysql:host='.$_POST['server'], $_POST['username'], $_POST['password']); ?>	
	<p>Client Version: <?php eval("echo $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION);"); ?><input name="pdo_client_version" type="hidden" value="<?php eval("echo $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION);"); ?>" /></p>
	<p>Server Version: <?php eval("echo $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);"); ?><input name="pdo_server_version" type="hidden" value="<?php eval("echo $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);"); ?>" /></p>
	<?php endif; ?>
</form>
<?php endif; ?>
</body>
</html>
