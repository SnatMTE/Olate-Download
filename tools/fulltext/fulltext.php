<?php
/**********************************
* Olate Download 3.3.1-Beta
* http://www.olate.co.uk/od3
**********************************
* Copyright Olate Ltd 2005
*
* @author $Author: dsalisbury $ (Olate Ltd)
* @version $Revision: 141 $
* @package od
*
* Updated: $Date: 2005-10-27 19:00:15 +0100 (Thu, 27 Oct 2005) $
*/

// Initialisation
require_once('./includes/init.php');

// Everything seems ok	
// Detect MySQL version - greater than 4.0.2?
$version = $dbim->query('SELECT VERSION() AS version');
$version = $dbim->fetch_array($version);
			
$explode = explode('.', $version['version']);
$version['major'] = $explode[0];
$version['minor'] = $explode[1];
$version['patch'] = $explode[2];
			
$explode = explode('-', $version['patch']);
$version['patch'] = $explode[0];
			
if(($version['major'] >= 4 && $version['minor'] >= 0 && $version['patch'] >= 2) || $version['major'] > 4)
{
	// Add FULLTEXT support
	$dbim->query('ALTER TABLE '.DB_PREFIX.'files ADD FULLTEXT (
					name,
					description_small,
					description_big )');
	
	echo '<p style="font:10pt verdana, tahoma, sans-serif">FULLTEXT has now been enabled.</p>';
}
else
{
	// No
	echo '<p style="font:10pt verdana, tahoma, sans-serif">You are running MySQL '.$version['major'].'.'.$version['minor'].'.'.$version['patch'].'. FULLTEXT can only be used on MySQL 4.0.2+.</p>';
}
?>