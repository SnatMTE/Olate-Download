<?php
/**********************************
* Olate Download 3.5.0
* https://github.com/SnatMTE/Olate-Download/
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 197 $
* @package od
*
* Original Author: Olate Download
* Updated by: Snat
* Last-Edited: 2025-12-16
*/

// Start admin cp
$start = $uim->fetch_template('admin/start');
$start->show();
		
// Template
$license = $uim->fetch_template('admin/od_license');
$license->show();
		
$end = $uim->fetch_template('global/end');
$end->show();
		
$uim->generate($lm->language('admin', 'admin_cp').' - '.$lm->language('general', 'title').' - '.$lm->language('admin', 'license'), false);
?>