<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: config.php
 * 	Configuration file for the File Manager Connector for PHP.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

// Include OD init so that site config can be got
$cwd = getcwd();
chdir('../../../../../../../');
include_once('includes/init.php');
chdir($cwd);

global $Config, $site_config ;

// SECURITY: You must explicitelly enable this "connector". (Set it to "true").
$Config['Enabled'] = true ;

// Path to user files relative to the document root.
$url = parse_url($site_config['url']);
$Config['UserFilesPath'] = $url['path'].'uploads/';

// Fill the following value it you prefer to specify the absolute path for the
// user files directory. Usefull if you are using a virtual directory, symbolic
// link or alias. Examples: 'C:\\MySite\\UserFiles\\' or '/root/mysite/UserFiles/'.
// Attention: The above 'UserFilesPath' must point to the same directory.
$Config['UserFilesAbsolutePath'] = $_SERVER['DOCUMENT_ROOT'].$Config['UserFilesPath'] ;

$Config['AllowedExtensions']['File']	= array() ;
$Config['DeniedExtensions']['File']		= array('php','php3','php5','phtml','asp','aspx','ascx','jsp','cfm','cfc','pl','bat','exe','dll','reg','cgi') ;

$Config['AllowedExtensions']['Image']	= array('jpg','gif','jpeg','png') ;
$Config['DeniedExtensions']['Image']	= array() ;

$Config['AllowedExtensions']['Flash']	= array('swf','fla') ;
$Config['DeniedExtensions']['Flash']	= array() ;

$Config['AllowedExtensions']['Media']	= array('swf','fla','jpg','gif','jpeg','png','avi','mpg','mpeg') ;
$Config['DeniedExtensions']['Media']	= array() ;

?>