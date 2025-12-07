<?php
/**********************************
* Olate Download 3.3.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 192 $
* @package od
*
* Updated: $Date: 2005-12-15 17:20:25 +0000 (Thu, 15 Dec 2005) $
*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Olate Download 3 - Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<link href="setup.css" rel="stylesheet" type="text/css" />
</head>
<body>

<table cellspacing="0" cellpadding="0" id="main">
<tr id="logo"><td colspan="2"><img src="images/logo.gif" width="190" height="68" alt="Logo"/></td></tr>
<tr id="top">
	<td id="left">Install Progress</td>
	<td id="right">Olate Download 3 Setup</td>
</tr>

<tr>
	<td id="progress">
		<ul>
		<li class="first"><span>Start</span></li>
			<ul class="second">
				<li class="error">Action Selection</li>
				<li>Server Requirements</li>
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
		<h1>Welcome!</h1>
		<p id="intro">Welcome and thank you for using Olate Download 3! This setup system will guide you through the few steps needed to get up and running. If you have any problems during this process, please do not hesitate to <a href="http://www.olate.com/scripts/support" target="_blank">get help</a>.</p>
		<br />
		
		<h2>What would you like to do?</h2>
		
		<div id="options">
			<h3>New Installation</h3>
			
			<p>If you have never installed Olate Download 3 before or wish to install a clean copy, select this option</p>
			
			<div class="begin"><a href="install/index.php">Begin Installation</a></div><br />
		</div>
		
		<div id="options">
			<h3>Upgrade from version 2.2</h3>
			
			<p>If you wish to upgrade from <strong>version 2.2</strong> (previous versions not supported) to 3.0, select this option</p>
			
			<div class="begin"><a href="upgrade/index.php">Begin Upgrade</a></div>
			<br />
		</div>
	  <div id="options">
			<h3>Upgrade from version 3.x</h3>
			
			<p>If you wish to upgrade from <strong>an existing 3.x.y installation </strong>to a new 3.x.z version, select this option</p>
			
			<div class="begin"><a href="upgrade/existing-3.0.0_to_3.1.0.php">1. Begin Upgrade (3.0.0 to 3.1.0) </a></div>
			<br />			
			<div class="begin"><a href="upgrade/existing-3.1.0_to_3.2.0.php">2. Begin Upgrade (3.1.0 to 3.2.0) </a></div>
	  		<br />			
			<div class="begin"><a href="upgrade/existing-3.2.0_to_3.2.1.php">3. Begin Upgrade (3.2.0 to 3.2.1) </a></div>
			<br />			
			<div class="begin"><a href="upgrade/existing-3.2.1_to_3.3.0.php">4. Begin Upgrade (3.2.1 to 3.3.0) </a></div>
			<br />
			<div class="begin"><a href="upgrade/existing-3.3.0-Alpha_to_3.3.0-Beta.php">5. Begin Upgrade (3.3.0-Alpha to 3.3.0-Beta) </a></div>
			<br />
			<div class="begin"><a href="upgrade/existing-3.3.0-Beta_to_3.3.0.php">6. Begin Upgrade (3.3.0-Beta to 3.3.0-Final) </a></div>
			<br />
			<div class="begin"><a href="upgrade/existing-3.3.0_to_3.3.1.php">7. Begin Upgrade (3.3.0 to 3.3.1) </a></div>
			<br />
			<div class="begin"><a href="upgrade/existing-3.3.1_to_3.4.0.php">8. Begin Upgrade (3.3.1 to 3.4.0) </a></div>
			
	  </div>
	</td>
</tr>
</table>

</body>
</html>