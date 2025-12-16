<?php

/**********************************
* Olate Download 3.4.0
* https://github.com/SnatMTE/Olate-Download/
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

// Categories
$fcm->show_cats();

// Function to return message and headers
function build_email_content($type, $data = false, $timestamp = 0)
{
	global $site_config, $file, $fldm;
	
	if ($type == 'confirm_recommendation')
	{
		$confirm_hash = md5($timestamp.mt_rand());
		
		$to = $_POST['sender_email'];
		$subject = 'Please confirm your recommendation of "'.$file['name'].'"';
		
		$message = "THIS IS AN AUTOMATED MESSAGE.  PLEASE DO NOT REPLY\n\n";
		$message .= 'Hello '.$_POST['sender_name'].",\n\n";
		$message .= 'Someone (possibly yourself) has chosen to recommend the file "'.$file['name'].'" to '.$_POST['rcpt_name'].' ('.$_POST['rcpt_email'].").  If you want to send this recommendation, please click the link below.  If that doesn't work then please copy and paste into your web browser's address bar.\n\n";
		$message .= 'Confirm: '.$site_config['url']."recommend.php?action=confirm&hash=$confirm_hash\n\n";
		$message .= "Sender's name: ".$_POST['sender_name']."\n\n";
		$message .= "Sender's email: ".$_POST['sender_email']."\n\n";
		$message .= "Recipient's name: ".$_POST['rcpt_name']."\n\n";
		$message .= "Recipient's email: ".$_POST['rcpt_email']."\n\n";
		$message .= "Message:\n\n";
		
		$_POST['message'] = str_replace('\r', "\r", $_POST['message']);
		$_POST['message'] = str_replace('\n', "\n", $_POST['message']);
		
		// Wordwrap the message
		$submitted_msg = wordwrap($_POST['message'], 65);
		
		$submitted_msg_lines = explode("\n", $submitted_msg);
		
		// Prefix each line
		foreach ($submitted_msg_lines as $key => $value)
		{
			$submitted_msg_lines[$key] = '>'.trim($value);
		}
		
		// Join the lines back together into one big happy famil... erm... string
		$submitted_msg = implode("\n", $submitted_msg_lines);
		
		$message .= "$submitted_msg\n\n";
		$message .= 'If you do not wish to recieve any more recommendations from users of '.$site_config['site_name'].' or do not want your email address ('.$_POST['sender_email'].") to be used as the address of the sender, please click the link below to block your address.  If that doesn't work then please copy and paste into your web browser's address bar.\n\n";
		$message .= 'Block address: '.$site_config['url'].'recommend.php?action=block_address&address='.$_POST['sender_email']."\n\n";
		$message .= "--\n";
		$message .= 'Date: '.date('l, d F Y', $timestamp)."\n";
		$message .= 'Time: '.date('H:i:s O', $timestamp)."\n";
		$message .= 'IP Address: '.$_SERVER['REMOTE_ADDR'];
		
		$headers = 'From: '.$site_config['admin_email']."\r\n";
		$headers .= 'Reply-To: '.$site_config['admin_email']."\r\n";
		$headers .= 'X-Mailer: Olate Download '.$site_config['version']." (Recommend a file to a friend module)\r\n";
	}
	elseif ($type == 'recommendation')
	{
		$confirm_hash = '';
		
		$to = $data['rcpt_email'];
		$subject = '"'.$file['name'].'" has been recommended to you';
		
		$message = "THIS IS AN AUTOMATED MESSAGE.\n\n";
		$message .= 'Hello '.$data['rcpt_name'].",\n\n";
		$message .= $data['sender_name'].' has recommended the file "'.$file['name']."\" to you.  The message accompanying this recommendation is as follows:\n\n";
		
		// Wordwrap the message
		$submitted_msg = wordwrap($data['message'], 69);
		
		$submitted_msg_lines = explode("\n", $submitted_msg);
		
		// Prefix each line
		foreach ($submitted_msg_lines as $key => $value)
		{
			$submitted_msg_lines[$key] = '>'.$value;
		}
		
		// Join the lines back together into one big happy famil... erm... string
		$submitted_msg = implode("\n", $submitted_msg_lines);
		
		$message .= "$submitted_msg\n\n";
		$message .= "Details of the file are as follows:\n\n";
		$message .= 'Name: '.$file['name']."\n";
		$message .= "Description:\n";
		
		$description = strip_tags($file['description_big']);
		
		// Wordwrap the description
		$wrapped_desc = wordwrap($description, 69);
		
		$wrapped_desc_lines = explode("\n", $wrapped_desc);
		
		// Prefix each line
		foreach ($wrapped_desc_lines as $key => $value)
		{
			$wrapped_desc_lines[$key] = '>'.$value;
		}
		
		// Join the lines back together into one big happy famil... erm... string
		$wrapped_desc = implode("\n", $wrapped_desc_lines);
		
		// Go back to building the message momentarily
		$message .= $wrapped_desc."\n";
		
		// Get filesize
		$filesize = $fldm->format_size($file['size']);
		
		$message .= 'Size: '.$filesize['size'].' '.$filesize['unit']."\n";
		
		if (intval($file['rating_votes']) == 0 || empty($file['rating_votes']))
		{
			$message .= "Rating: No rating yet\n\n";
		}
		else
		{
			$message .= 'Rating: '.$file['rating_value'].'/5.00 ('.$file['rating_votes']." vote(s))\n\n";
		}
		
		$message .= 'You can view the full details of the file at '.$site_config['url'].'details.php?file='.$file['id']."\n\n";
		
		$message .= 'If you do not wish to recieve any more recommendations from users of '.$site_config['site_name'].' or do not want your email address ('.$data['rcpt_email'].") to be used as the address of the sender, please click the link below to block your address.  If that doesn't work then please copy and paste into your web browser's address bar.\n\n";
		$message .= 'Block address: '.$site_config['url'].'recommend.php?action=block_address&address='.$data['rcpt_email']."\n\n";
		$message .= "--\n";
		$message .= 'Date: '.date('l, d F Y', $timestamp)."\n";
		$message .= 'Time: '.date('H:i:s O', $timestamp)."\n";
		$message .= 'IP Address: '.$_SERVER['REMOTE_ADDR'];
		
		$headers = 'From: '.$data['sender_email']."\r\n";
		$headers .= 'Reply-To: '.$data['sender_email']."\r\n";
		$headers .= 'X-Mailer: Olate Download ('.$site_config['version']."): Recommend a file to a friend module\r\n";
	}
	else
	{
		return false;
	}
	
	return array(
		'to' => $to,
		'subject' => $subject,
		'message' => $message,
		'headers' => $headers,
		'confirm_hash' => $confirm_hash
	);
}

if ($site_config['enable_recommend_friend'])
{
	validate_types($_REQUEST, array('file' => 'INT'));
	
	// Are we blocking an email address or confirming a message?
	if (!empty($_REQUEST['action']))
	{
		// What are we doing?
		if ($_REQUEST['action'] == 'block_address')
		{
			validate_types($_REQUEST, array('action' => 'STR', 'address' => 'STR',
							'confirm_block_yes' => 'STR', 'confirm_block_no' => 'STR'));
			
			// Template
			$template = $uim->fetch_template('files/recommend_block_form');
			
			if (empty($_REQUEST['address']))
			{
				// We don't need to show the form
				$template->assign_var('hide_form', true);
				
				// Error message
				$template->assign_var('message', $lm->language('frontend', 'must_specify_email'));
			}
			else
			{
				// Check for there already being an entry
				$sql = 'SELECT COUNT(*) as count
						FROM '.DB_PREFIX.'recommend_blocklist
						WHERE address = "'.$_REQUEST['address'].'"';
				
				$result = $dbim->query($sql);
				$row = $dbim->fetch_array($result);
				
				if (intval($row['count']) > 0)
				{
					// We don't need to show the form
					$template->assign_var('hide_form', true);
					
					$template->assign_var('message', $lm->language('frontend', 'address_exists'));
				}
				else
				{
					if (empty($_REQUEST['confirm_block_yes']) && empty($_REQUEST['confirm_block_no']))
					{
						$template->assign_var('address', $_REQUEST['address']);
					}
					elseif (!empty($_REQUEST['confirm_block_yes']))
					{
						// We don't need to show the form
						$template->assign_var('hide_form', true);
						
						
						$sql = 'INSERT INTO '.DB_PREFIX.'recommend_blocklist
									(address)
								VALUES
									("'.$_REQUEST['address'].'")';
						
						// Insert into database
						$dbim->query($sql);
						
						$template->assign_var('message', $lm->language('frontend', 'email_blocked'));
					}
					else
					{
						// We don't need to show the form
						$template->assign_var('hide_form', true);
						
						$template->assign_var('message', $lm->language('frontend', 'email_not_blocked'));
					}
				}
			}
			// Template
			$template->show();
		}
		elseif ($_REQUEST['action'] == 'confirm')
		{
			// Template
			$template = $uim->fetch_template('files/recommend_friend');
			
			// We don't need to show the form
			$hide_form = true;
			$template->assign_var('hide_form', true);
			
			validate_types($_REQUEST, array('hash' => 'STR'));
			
			if (empty($_REQUEST['hash']))
			{
				$template->assign_var('message', $lm->language('frontend', 'invalid_hash'));
			}
			else
			{
				// Find data relating to that hash
				$sql = 'SELECT id, timestamp, ip_address, file_id, sender_name, sender_email, rcpt_name, rcpt_email, message, confirmed 
						FROM '.DB_PREFIX.'recommend_log 
						WHERE (confirm_hash = "'.$_REQUEST['hash'].'")
						LIMIT 1';
				
				$result = $dbim->query($sql);
				
				if ($row = $dbim->fetch_array($result))
				{
					if (intval($row['confirmed']) == 1)
					{
						$template->assign_var('message', $lm->language('frontend', 'recommend_already_sent'));
					}
					else
					{
						$file = $fldm->get_details($row['file_id']);
						
						// Get mail variables
						$email_content = build_email_content('recommendation', $row, $row['timestamp']);
						
						// Messages
						$message_success = $lm->language('frontend', 'recommendation_sent');
						$message_failure = $lm->language('frontend', 'recommendation_failed');
						
						// Final wordwrap on message to make sure lines are <= 70 chars
						$wrapped_message = wordwrap($message, 70, "\n", 1);
						
						// Send email
						if (mail($email_content['to'], $email_content['subject'], $wrapped_message, $email_content['headers']))
						{
							$template->assign_var('message', $message_success);
							
							$dbim->query('UPDATE '.DB_PREFIX.'recommend_log
											SET confirmed = 1
											WHERE id = '.$row['id']);
						}
						else
						{
							$template->assign_var('message', $message_failure);
						}
					}
				}
				else
				{
					$template->assign_var('message', $lm->language('frontend', 'invalid_hash'));
				}
			}
			// Template
			$template->show();
		}
	}
	else
	{
		// Check specified file is valid
		if (empty($_REQUEST['file']))
		{
			$template = $uim->fetch_template('files/recommend_friend');
			
			$template->assign_var('message', $lm->language('frontend', 'invalid_file'));
			
			$template->assign_var('hide_form', true);
			$hide_form = true;
			
			$template->show();
		}
		else
		{
			// Check file exists.  If so, $file contains details about the file
			if (false === ($file = $fldm->get_details($_REQUEST['file'])))
			{
				$template = $uim->fetch_template('files/recommend_friend');
				
				$template->assign_var('message', $lm->language('frontend', 'invalid_file'));
				
				$template->assign_var('hide_form', true);
				$hide_form = true;
				
				$template->show();
			}
			else
			{
				$valid_file = true;
			}
		}
		
		// Has anything been submitted?
		if (!empty($_REQUEST['file']) && !empty($_REQUEST['submit']) && $valid_file)
		{
			validate_types($_POST, array('sender_name' => 'STR', 'sender_email' => 'STR',
											'rcpt_name' => 'STR', 'rcpt_email' => 'STR',
											'save_settings' => 'INT', 'message' => 'STR'));
			
			// Are all fields filled in?
			if ($_POST['sender_name'] == '' || $_POST['sender_email'] == '' ||
				$_POST['rcpt_name'] == '' || $_POST['rcpt_email'] == '' ||
				$_POST['message'] == '')
			{
				$error = $lm->language('admin', 'all_fields_filled');
			}
			// Does the sender email appear valid?
			elseif (!eregi(".+@.+\..{2,}", $_POST['sender_email']))
			{
				$error = $lm->language('frontend', 'invalid_sender_email');
			}
			// Does the recipient email appear valid?
			elseif (!eregi(".+@.+\..{2,}", $_POST['rcpt_email']))
			{
				$error = $lm->language('frontend', 'invalid_recipient_email');
			}
			// All is good so far :)
			else
			{
				// Template
				$template = $uim->fetch_template('files/recommend_friend');
				$template->assign_var('hide_form', 'true');
				$hide_form = true;
				
				$time = time();
				
				// Check sender/recipient addresses aren't blocked
				$sql = 'SELECT address, COUNT(*) AS count
						FROM '.DB_PREFIX.'recommend_blocklist
						WHERE (address = "'.$_REQUEST['sender_email'].'")
								OR (address = "'.$_REQUEST['rcpt_email'].'")
						GROUP BY address';
				
				$result = $dbim->query($sql);
				
				while ($row = $dbim->fetch_array($result))
				{
					if ($row['count'] > 0)
					{
						if ($row['address'] == $_REQUEST['sender_email'])
						{
							$template->assign_var('message', $lm->language('frontend', 'address_blocked_sender'));
						}
						else
						{
							$template->assign_var('message', $lm->language('frontend', 'address_blocked_rcpt'));
						}
						
						$blocked_address = true;
						
						break;
					}
				}
				
				// Don't continue if we have a blocked address
				if (empty($blocked_address) || $blocked_address !== true)
				{
					// Do these recommendations need to be confirmed by sender?
					if ($site_config['enable_recommend_confirm'])
					{
						$confirmed = 0;
						
						// Get mail variables
						$email_content = build_email_content('confirm_recommendation', false, $time);
						
						// Messages
						$message_success = $lm->language('frontend', 'need_to_confirm');
						$message_failure = $lm->language('frontend', 'confirm_message_failed');
					}
					else
					{
						$confirmed = 1;
						
						// Get mail variables
						$email_content = build_email_content('recommendation', $_POST, $time);
						
						// Messages
						$message_success = $lm->language('frontend', 'recommendation_sent');
						$message_failure = $lm->language('frontend', 'recommendation_failed');
					}
					
					// Final wordwrap on message to make sure lines are <= 70 chars
					$wrapped_message = wordwrap($email_content['message'], 70, "\n", 1);
					
					// Send email
					if (mail($email_content['to'], $email_content['subject'], $wrapped_message, $email_content['headers']))
					{
						$template->assign_var('message', $message_success);
						$success = true;
					}
					else
					{
						$template->assign_var('message', $message_failure);
					}
					
					// Insert into database
					$sql = 'INSERT INTO '.DB_PREFIX.'recommend_log
								SET timestamp = '.$time.',
									ip_address = "'.$_SERVER['REMOTE_ADDR'].'",
									file_id = '.$file['id'].',
									sender_name = "'.$_POST['sender_name'].'",
									sender_email = "'.$_POST['sender_email'].'",
									rcpt_name = "'.$_POST['rcpt_name'].'",
									rcpt_email = "'.$_POST['rcpt_email'].'",
									message = "'.$_POST['message'].'",
									confirm_hash = "'.$email_content['confirm_hash'].'",
									confirmed = '.$confirmed;
					
					$dbim->query($sql);
					
					// Template
					$template->show();
					
					// Everything has gone to plan, so do we set the cookie?
					if ($_POST['save_settings'] == 1)
					{
						setcookie('OD3_recommend_name', $_POST['sender_name'], time() + 60*60*24*365);
						setcookie('OD3_recommend_email', $_POST['sender_email'], time() + 60*60*24*365);
					}
				}
				else
				{
					// Template
					$template->show();
				}
			}
		}
		
		// Do we need to show the form?
		if (empty($hide_form) || $hide_form === false)
		{
			$template = $uim->fetch_template('files/recommend_friend');
			
			// Are we converting new lines to <br />?
			if (intval($file['convert_newlines']) === 1)
			{
				$file['description_small'] = nl2br($file['description_small']);
				$file['description_big'] = nl2br($file['description_big']);
			}
			
			$filesize = $fldm->format_size($file['size']);
	    	$file['size'] = $filesize['size'];
	    	
	    	$file['date'] = format_date($file['date']);
			
			$template->assign_var('filesize_format', $filesize['unit']);
			
			if (isset($error))
			{
				$template->assign_var('has_error', true);
				$template->assign_var('message', $error);
			}
			
			if (!isset($_POST['sender_name']) && !empty($_COOKIE['OD3_recommend_name']))
			{
				$_POST['sender_name'] = $_COOKIE['OD3_recommend_name'];
			}
			
			if (!isset($_POST['sender_email']) && !empty($_COOKIE['OD3_recommend_email']))
			{
				$_POST['sender_email'] = $_COOKIE['OD3_recommend_email'];
			}
			
			$template->assign_var('data', $_POST);
			
			$template->assign_var('file', $file);
			
			$template->show();
		}
	}
}
else
{
	$template = $uim->fetch_template('files/recommend_friend_disabled');
	$template->show();
}

$template = $uim->fetch_template('global/end');
$template->show();

if (isset($success))
{
	$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'recommend_friend'), 'details.php?file='.$_REQUEST['file']);
}
else
{
	$uim->generate(TITLE_PREFIX.$lm->language('frontend', 'recommend_friend'), false);
}
?>