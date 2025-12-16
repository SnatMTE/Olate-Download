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

/*
* Explanation of the big query:
* 
* Type 1 = allow/deny by IP (e.g. 192.168.0.1)
* Type 2 = allow/deny by range (e.g. 192.168.0.1 - 192.168.0.100)
* Type 3 = allow/deny by dotted subnet mask (e.g. 192.168.0.0/255.255.255.0)
* Type 4 = allow/deny by CIDR netmask (e.g. 192.168.0.0/24)
*/

// Security module
class sm
{
	// To run at beginning of page
	function page_init()
	{
		global $site_config;
		
		$this->check_ip_access();
		
		// Check proxy?
		if (isset($_SERVER['X_FORWARDED_FOR']))
		{
			$this->check_ip_access($_SERVER['X_FORWARDED_FOR']);
		}
		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$this->check_ip_access($_SERVER['X_FORWARDED_FOR']);
		}
	}
	
	function check_ip_access($ip_address = '')
	{
		global $uim, $fcm, $lm, $site_config;
		
		// We need an IP address to check
		if ($ip_address == '')
		{
			$ip_address = $_SERVER['REMOTE_ADDR'];
		}
		
		// Does it conform to aaa.bbb.ccc.ddd?
		if ($this->validate_ip($ip_address, true))
		{
			// Check if listed then check if allowed access
			if ($this->count_entries($ip_address, 1) > 0)
			{
				if ($site_config['ip_restrict_mode'] == 0)
				{
					$permitted = false;
				}
				else
				{
					$permitted = true;
				}
			}
			else
			{
				if ($site_config['ip_restrict_mode'] == 0)
				{
					$permitted = true;
				}
				else
				{
					$permitted = false;
				}
			}
		}
		else
		{
			return false;
		}
		
		// Not allowed, so print access denied
		if (isset($permitted) && $permitted == false)
		{
			// Log it first
			$this->log_denial($ip_address);
			
			// Template
			$page = $uim->fetch_template('security/access_denied');
			$page->show();
			
			// End the page
			$end = $uim->fetch_template('global/end');
			$end->show();
			
			$uim->generate(TITLE_PREFIX.$lm->language('admin', 'permission_denied'), false);
			exit;
		}
	}
	
	function validate_ip($ip_address, $check_part_4 = false)
	{
		// Don't check empty addresses
		if ($ip_address == '')
		{
			return false;
		}
		elseif (preg_match('/^[0-9]{1,3}(?:\.[0-9]{1,3}){3}$/', $ip_address))
		{
			// Split at the dots
			$parts = explode('.', $ip_address);
			
			foreach ($parts as $part_num => $part)
			{
				// Check we're not using the .0 network address or .255 broadcast
				if ($check_part_4 && $part_num == 3)
				{
					if ($part == 0 || $part == 255)
					{
						return false;
					}
				}
				
				// Weirdness.  Shouldn't happen but covering for the eventuality
				if ($part < 0 || $part > 255)
				{
					return false;
				}
			}
			
			return true;
		}
		else
		{
			// IP not valid, so false
			return false;
		}
	}
	
	// List entries where IP is included
	function get_entries($ip_address, $filter_active = false)
	{
		global $dbim;
		
		// Have we run the query yet?
		if (!isset($this->_temp_result))
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'ip_restrict WHERE (
						(type = 1 AND INET_ATON(start) = INET_ATON("'.$ip_address.'")) OR
						(type = 2 AND 
							INET_ATON(start) <= INET_ATON("'.$ip_address.'") AND 
							INET_ATON(end) >= INET_ATON("'.$ip_address.'")) OR 
						(type = 3 AND (SIGN(INET_ATON(mask)) >= 0) AND 
							(floor(INET_ATON("'.$ip_address.'")/(POWER(2,32)-INET_ATON(mask))) = floor(INET_ATON(start)/(POWER(2,32)-INET_ATON(mask)))) ) OR 
						(type = 3 AND (SIGN(INET_ATON(mask)) = -1) AND 
							(floor(INET_ATON("'.$ip_address.'")/(POWER(2,32)-(POWER(2,3)+INET_ATON(mask)))) = floor(INET_ATON(start)/(POWER(2,32)-(POWER(2,32)+INET_ATON(mask))))) ) OR 
						(type = 4 AND 
							(floor(INET_ATON("'.$ip_address.'")/(POWER(2,(32-mask))))) = (floor(INET_ATON(start)/(POWER(2,(32-mask))))))
					)';
			#echo $sql;
			// Filtering by active status?
			if ($filter_active !== false && ($filter_active === 0 || $filter_active === 1))
			{
				$sql .= "\n AND active = $filter_active\n";
			}
			
			$sql .= 'ORDER BY active DESC';
			
			// Query
			$this->_temp_result = $dbim->query($sql);
		}
		
		$row = $dbim->fetch_array($this->_temp_result);
		
		// Finished getting stuff from database, so clean up
		if ($row === false)
		{
			unset($this->_temp_result);
		}
		
		return $row;
	}
	
	// Count entries where IP is included
	function count_entries($ip_address, $filter_active = false)
	{
		global $dbim;
		
		$sql = 'SELECT COUNT(*) as count FROM '.DB_PREFIX.'ip_restrict WHERE (
					(type = 1 AND INET_ATON(start) = INET_ATON("'.$ip_address.'")) OR
					(type = 2 AND 
						INET_ATON(start) <= INET_ATON("'.$ip_address.'") AND 
						INET_ATON(end) >= INET_ATON("'.$ip_address.'")) OR 
					(type = 3 AND (SIGN(INET_ATON(mask)) >= 0) AND 
						(floor(INET_ATON("'.$ip_address.'")/(POWER(2,32)-INET_ATON(mask))) = floor(INET_ATON(start)/(POWER(2,32)-INET_ATON(mask)))) ) OR 
					(type = 3 AND (SIGN(INET_ATON(mask)) = -1) AND 
						(floor(INET_ATON("'.$ip_address.'")/(POWER(2,32)-(POWER(2,3)+INET_ATON(mask)))) = floor(INET_ATON(start)/(POWER(2,32)-(POWER(2,32)+INET_ATON(mask))))) ) OR 
					(type = 4 AND 
						(floor(INET_ATON("'.$ip_address.'")/(POWER(2,(32-mask))))) = (floor(INET_ATON(start)/(POWER(2,(32-mask))))))
				)';
		
		// Filtering?
		if ($filter_active !== false && ($filter_active === 0 || $filter_active === 1))
		{
			$sql .= "\n AND active = $filter_active\n";
		}
		
		// Query
		$result = $dbim->query($sql);
		$row = $dbim->fetch_array($result);
		
		return $row['count'];
	}
	
	// Does the specified range contain specified IP address?
	function range_contains_ip($ip_address, $range_start, $range_end)
	{
		// Get unsigned integer value for various addresses
		$ip_long = sprintf("%u\n", ip2long($ip_address));
		$start_long = sprintf("%u\n", ip2long($range_start));
		$end_long = sprintf("%u\n", ip2long($range_end));
		
		// Check...
		if ($ip_long >= $start_long && $ip_long <= $end_long)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// Check whether specified network contains specified IP address
	function network_contains_ip($ip_address, $net_address, $netmask)
	{
		// Get unsigned integer version of IP and network addresses
		$ip_long = sprintf("%u\n", ip2long($ip_address));
		$net_long = sprintf("%u\n", ip2long($net_address));
		
		// Dotted netmask?
		if ($this->validate_ip($netmask))
		{
			// More integer getting
			$netmask_long = sprintf("%u\n", ip2long($netmask));
			
			if ($netmask_long >= 0)
			{
				// Check
				if (floor($ip_long/(pow(2, 32) - $netmask_long)) == floor($net_long/(pow(2, 32) - $netmask_long)))
				{
					return true;
				}
			}
			elseif ($netmask_long < 0)
			{
				// Other check
				if (floor($ip_long/(pow(2, 32) - (pow(2, 3) + $netmask_long))) == floor($net_long/(pow(2, 32) - (pow(2, 3) + $netmask_long))))
				{
					return true;
				}
			}
		}
		// CIDR?
		elseif ($netmask >= 1 && $netmask <= 32)
		{
			// Check
			if (floor($ip_long/pow(2,(32-$netmask))) == floor($net_long/pow(2,(32-$netmask))))
			{
				return true;
			}
		}
		
		// Catch-all: if we haven't returned true yet, then must it be false
		return false;
		
	}
	
	function get_entry_by_id($id)
	{
		global $dbim;
		
		// Just in case...
		$id = intval($id);
		
		// Query DB
		$sql = 'SELECT * FROM '.DB_PREFIX.'ip_restrict
				WHERE id = '.$id.'
				LIMIT 1';
		
		$result = $dbim->query($sql);
		
		if ($dbim->num_rows($result) != 0)
		{
			return $dbim->fetch_array($result);
		}
		else
		{
			return false;
		}
	}
	
	function log_denial($ip_address)
	{
		global $dbim;
		
		// Get the variables needed first
		$timestamp = time();
		$request_uri = $_SERVER['REQUEST_URI'];
		$referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : '';
		
		// Build SQL
		$sql = 'INSERT INTO '.DB_PREFIX.'ip_restrict_log
					(timestamp, ip_address, request_uri, referer)
				VALUES
					('.$timestamp.', "'.$ip_address.'", "'.$request_uri.'", "'.$referer.'")';
		
		return $dbim->query($sql);
	}
	
	function domain_can_leech($domain)
	{
		global $dbim, $site_config;
		
		// Are we looking for entries with action = 0 or action = 1?
		if($site_config['enable_leech_protection'])
		{
			$action = 0;
		}
		else
		{
			$action = 1;
		}
		
		$sql = 'SELECT COUNT(*) AS count 
				FROM '.DB_PREFIX.'leech_settings 
				WHERE ("'.$domain.'" LIKE (REPLACE(domain, "*", "%")))
						AND (action = '.$action.')';
		
		$result = $dbim->query($sql);
		$row = $dbim->fetch_array($result);
		
		// So we have the data, now decide whether to allow or deny
		if ($row['count'] > 0)
		{
			if($site_config['enable_leech_protection'])
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			if($site_config['enable_leech_protection'])
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
}

?>