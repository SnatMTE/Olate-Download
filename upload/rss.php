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

// Initialisation
require('./includes/init.php');

if ($site_config['enable_rss'])
{	
	validate_types($_REQUEST, array('mode' => 'INT', 'cat' => 'INT', 'file' => 'INT'));
	
	// Doctype
	header('Content-Type: text/xml');
	?>
	<rss version="0.91">
		
		<channel>
		
		<title><?php echo TITLE_PREFIX; ?> RSS Feed</title>
		<link><?php echo $site_config['url']; ?></link>
	<?php	
	// 3 modes - display latest files, specific file stats or general stats
	if (empty($_REQUEST['mode']) || $_REQUEST['mode'] == 1)
	{
		if (!empty($_REQUEST['cat']))
		{
			$category = $_REQUEST['cat'];
		}
		else
		{
			$category = false;
		}
															
		// Get files data
		$latest_files = $fldm->get_files('date DESC', $category, $site_config['latest_files']);
										
		// Display
		foreach ($latest_files as $file)
		{
		?>
		
		<item>
		<title><?php echo htmlspecialchars($file['name']); ?></title>
		<description><?php echo htmlspecialchars($file['description_small']); ?></description>
		<link><?php echo $site_config['url'].'details.php?file='.$file['id']; ?></link>
		<downloads><?php echo $file['downloads']; ?></downloads>
		</item>
		
		<?php
		}
	}
	elseif ($_REQUEST['mode'] == 2 && !empty($_REQUEST['file']))
	{	
		$file = $fldm->get_details($_REQUEST['file']);
	?>
		<item>
		<title><?php echo htmlspecialchars($file['name']); ?></title>
		<description><?php echo htmlspecialchars($file['description_small']); ?></description>
		<link><?php echo $site_config['url'].'details.php?file='.$file['id']; ?></link>
		<downloads><?php echo $file['downloads']; ?></downloads>
		</item>
	<?php
	}
	elseif ($_REQUEST['mode'] == 3)
	{
		// Count files
		$count_result = $dbim->query('SELECT COUNT(*) AS files
										FROM '.DB_PREFIX.'files
										WHERE (status = 1)');	
		$count = $dbim->fetch_array($count_result);
		$files = $count['files'];
		
		// Count downloads
		$count_result = $dbim->query('SELECT COUNT(*) AS downloads
										FROM '.DB_PREFIX.'stats');	
		$count = $dbim->fetch_array($count_result);
		$downloads = $count['downloads'];
	?>	
		<item>
		<title><?php echo $lm->language('admin', 'total_files'); ?></title>
		<count><?php echo $files; ?></count>
		<link><?php echo $site_config['url']; ?></link>
		</item>
		<item>
		<title><?php echo $lm->language('admin', 'total_downloads'); ?></title>
		<count><?php echo $downloads; ?></count>
		<link><?php echo $site_config['url']; ?></link>
		</item>
	<?php
	}
	?>	
		</channel>		
	</rss>
<?php
}
else
{
	$lm->language('frontend', 'rss_disabled');
}
?>