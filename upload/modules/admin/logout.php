<?php
/**********************************
* Olate Download 3.4.0
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 197 $
* @package od
*
* Updated: $Date: 2005-12-17 11:22:39 +0000 (Sat, 17 Dec 2005) $
*/

$uam->user_logout();

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();
		
// Show message
$message = $uim->fetch_template('admin/logged_out');
$message->show();
		
$end = $uim->fetch_template('global/end');
$end->show();
		
$uim->generate($lm->language('admin', 'logged_out'));
?>