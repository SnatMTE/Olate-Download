<?php
/**********************************
* Olate Download 3.3.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 125 $
* @package od
*
* Updated: $Date: 2005-10-15 22:16:22 +0100 (Sat, 15 Oct 2005) $
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Olate Download 3 - Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link href="../setup.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><img src="../images/logo.gif" width="190" height="68" alt="Logo"/></td></tr>
<tr id="top">
	<td id="left">Install Progress</td>
	<td id="right">Olate Download 3 Setup</td>
</tr>

<tr>
	<td id="progress">
		<ul>
		<li class="first"><span>Start</span></li>
			<ul class="second">
				<li class="done">Action Selection</li>
				<li class="error">Server Requirements</li>
				<li>License Agreement</li>
			</ul>
		<li class="first"><span>Configuration</span></li>
			<ul class="second">
				<li>Database Settings</li>
				<li>General Settings</li>
			</ul>
		<li class="first"><span>Post-Install</span></li>
			<ul class="second">
				<li>Remove Installer Files</li>
				<li>Login</li>
			</ul>
		<li class="complete">Complete!</li>
		</ul>	
	</td>
	<td id="content">
		<h1>Server Requirements </h1>
		<p id="intro">Olate Download has a number of minimum requirements for installation. These requirements are checked below, and once satisfied you will be able to continue with the installation process.</p>
		<br />
				
		<div id="options">
			<h3>Server Requirements</h3>			
			<p>The following are required before you can install Olate Download 3:</p>
			<?php
			// PHP Version Checking
			if (phpversion() >= '4.3.0')
			{
			?>
				<p>&#8226; PHP 4.3.0+ <span style="color:#009900">Test Passed - You are running PHP <?php echo phpversion(); ?></span></p>
			<?php
			}
			else
			{
				$requirements = false;
			?>
				<p>&#8226; PHP 4.3.0+ <span style="color:#CC0000">Test Failed - You are running PHP <?php echo phpversion(); ?></span></p>
			<?php
			}
			
			// MySQL Version Checking
			if (extension_loaded('mysql'))
			{
			?>
				<p>&#8226; MySQL <span style="color:#009900">Test Passed - MySQL is available. (Version 4.0.2+ is required for search functionality and will be auto detected).</span></p>
			<?php
			}
			else
			{
				$requirements = false;
			?>
				<p>&#8226; MySQL <span style="color:#CC0000">Test Failed - MySQL is not available.</span></p>
			<?php
			}
			
			// Check config.php is writable
			if (is_writable('../../includes/config.php') && file_exists('../../includes/config.php'))
			{
			?>
				<p>&#8226; includes/config.php server writable <span style="color:#009900">Test Passed</span></p>
			<?php
			}
			else
			{
				$requirements = false;
			?>
				<p>&#8226; includes/config.php server writable <span style="color:#CC0000">Test Failed - You need to chmod this file to 777 and/or change file permissions to allow server writing.</span></p>
			<?php
			}
			
			// Check config.php is writable
			if (is_writable('../../uploads'))
			{
			?>
				<p>&#8226; uploads/ server writable <span style="color:#009900">Test Passed</span></p>
			<?php
			}
			else
			{
				$requirements = false;
			?>
				<p>&#8226; uploads/ server writable <span style="color:#CC0000">Test Failed - You need to chmod this directory to 777 and/or change file permissions to allow server writing for file uploads.</span></p>
			<?php
			}
			
			if (!isset($requirements))
			{
			?>
				<div class="begin"><a href="1.php">Continue</a></div><br />
			<?php
			}
			else
			{
			?>
				<p>You need to fix the above problem(s) before you can continue with the installation. If you need assistance, please <a href="http://www.olate.co.uk/support" target="_blank">get help</a>.</p>
			<?php
			}
			?>				
		</div>
	</td>
</tr>
</table>

</body>
</html>