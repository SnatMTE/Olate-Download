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
				<li class="done">Server Requirements</li>
				<li class="done">License Agreement</li>
			</ul>
		<li class="first"><span>Configuration</span></li>
			<ul class="second">
				<li class="error">Database Settings</li>
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
				<h1>Database Settings</h1>
				<p id="intro">Now you are ready to upgrade Olate Download.</p>
				<br />
						
				<div id="options">
					<h3>Settings</h3>			
					<p>Please fill out the information below. The installer will then connect to your database and create the tables based on the data you supply then convert your Olate Download 2.2 data to this version. If you are unsure of your access details, you should contact your web hosting company.</p>		
					<form name="database" id="database" method="post" action="3.php">
						<table width="100%"  border="0" cellspacing="1" cellpadding="4">
							<tr>
								<td>Database Server:</td>
								<td><input name="server" type="text" id="server" value="localhost" size="15" /></td>
							</tr>
							<tr>
								<td>Database Name:</td>
								<td><input name="name" type="text" id="name" size="15" /></td>
							</tr>
							<tr>
								<td width="16%">Database User Name:</td>
								<td width="84%"><input name="username" type="text" id="username" size="15" /></td>
							</tr>
							<tr>
								<td>Database Password: </td>
								<td><input name="password" type="password" id="password" size="15" /></td>
							</tr>
							<tr>
								<td>OD2.2 Table Prefix: </td>
								<td><input name="od2_prefix" type="text" id="od2_prefix" value="downloads_" size="15" /> 
								<span class="small">(the table prefix used on your existing installation)</span></td>
							</tr>
							<tr>
								<td>New Table Prefix: </td>
								<td><input name="prefix" type="text" id="prefix" value="od3_" size="15" /></td>
							</tr>
						</table>
					<input type="submit" name="Submit" value="Continue" />
					</form>
				</div>
	</td>
</tr>
</table>

</body>
</html>