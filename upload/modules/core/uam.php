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

// User Authentication Module
class uam
{	
	// Holds any auth errors
	var $auth_error;
	
	// Stores user permissions
	var $permissions;
	
	// Creates a salt to add randomness to a password
	function generate_salt()
	{
		$salt = substr(md5(microtime()), 0, 5);
		
		return $salt;
	}
	
	// Encrypts the password using the salt
	function encrypt_password($password, $salt)
	{
		$encrypted = md5(md5($password).$salt);
		
		return $encrypted;
	}
	
	function user_login($id, $username, $group_id)
	{
		// Store session data
		$_SESSION['id'] = $id;
		$_SESSION['username'] = $username;
		$_SESSION['group_id'] = $group_id;
		
		// Store a hash for validation
		$_SESSION['hash'] = md5($id.$username.$group_id);
	}
	
	function user_logout()
	{
		session_destroy();
		setcookie('OD3_AutoLogin', ''); // Goodbye, Mr Cookie
		
		// Let's be sure
		$_SESSION = array();
	}
	
	function user_authed()
	{#return true;
		// Check data is all present and correct
		return (isset($_SESSION['id']) && isset($_SESSION['username']) &&
			(md5($_SESSION['id'].$_SESSION['username'].$_SESSION['group_id']) == $_SESSION['hash'])) ? true : false;
	}
	
	// Takes the user input and goes through the auth checks
	function user_check($username, $password)
	{
		global $dbim, $lm;
		
		// First, check username, and retrieve salt
		$result = $dbim->query('SELECT salt 
								FROM '.DB_PREFIX.'users 
								WHERE (username = "'.$username.'")');
		
		if (!$user = $dbim->fetch_array($result))
		{
			$this->auth_error = $lm->language('frontend', 'invalid_password');
			
			return false;
		}
		
		// Now, check the password using the salt we just got
		$password = md5(md5($password).$user['salt']);
		
		$result = $dbim->query('SELECT id, group_id, username 
								FROM '.DB_PREFIX.'users 
								WHERE (username = "'.$username.'") 
									AND (password = "'.$password.'")');
		
		if (!$user = $dbim->fetch_array($result))
		{
			$this->auth_error = $lm->language('frontend', 'invalid_password');
			
			return false;
		}

		return $user;
	}
	
	// Same as above but for registration
	function user_register($data_array)
	{
		global $dbim, $lm;
		
		// 1. Make sure all required fields have been given
		if (empty($data_array['username']) || empty($data_array['password']) ||
			empty($data_array['confirm']) || empty($data_array['email']))
			{
				$this->auth_error = $lm->language('frontend', 'required_fields');	
				
				return;	
			}
			
		// 2. Make sure the username isn't taken
		if ($this->check_exists('username', $data_array['username']))
		{
			$this->auth_error = $lm->language('frontend', 'username_taken');
			
			return;
		}
		
		// 3. Make sure the passwords are identical
		if ($data_array['password'] != $data_array['confirm'])
		{
			$this->auth_error = $lm->language('frontend', 'passwords_match');
			
			return;
		}
		
		// 4. Have they selected a group?
		if ($data_array['group'] == '--Select Group--')
		{
			$this->auth_error = $lm->language('frontend', 'please_select_group');
			
			return;
		}
		
		// Everything is ok, encrypt password
		$salt = $this->generate_salt();
		$pass = $this->encrypt_password($data_array['password'], $salt);
		
		$dbim->query('INSERT INTO '.DB_PREFIX.'users
						SET group_id = "'.$data_array['group'].'", 
							username = "'.$data_array['username'].'", 
							password = "'.$pass.'", 
							salt = "'.$salt.'", 
							email = "'.$data_array['email'].'",
							firstname = "'.$data_array['firstname'].'", 
							lastname = "'.$data_array['lastname'].'", 
							location = "'.$data_array['location'].'", 
							signature = "'.$data_array['signature'].'"');
		
		return true;
	}
	
	// Same as above but for editing
	function user_update($user_id, $data_array)
	{
		global $dbim, $lm;
		
		// 1. Make sure all required fields have been given
		if (empty($data_array['email']))
			{
				$this->auth_error = $lm->language('frontend', 'required_fields');	
				
				return;	
			}
			
		// 2. Make sure the username isn't taken
		if ($this->check_exists('username', $data_array['username']))
		{
			$this->auth_error = $lm->language('frontend', 'username_taken');
			
			return;
		}
		
		// 3. Make sure the passwords are identical
		if ($data_array['password'] != $data_array['confirm'])
		{
			$this->auth_error = $lm->language('frontend', 'passwords_match');
			
			return;
		}
		
		// 4. Have they selected a group?
		if ($data_array['group'] == '--Select Group--')
		{
			$this->auth_error = $lm->language('frontend', 'please_select_group');
			
			return;
		}
		
		// 5. Do they want to change their password?
		if (isset($data_array['password']) && !empty($data_array['password']))
		{
			$salt = $this->generate_salt();
			$pass = $this->encrypt_password($data_array['password'], $salt);
			
			$pass_update = ', password="'.$pass.'", salt="'.$salt.'"';
		}
		
		$query = 'UPDATE '.DB_PREFIX.'users 
					SET email = "'.$data_array['email'].'", 
						group_id = "'.$data_array['group'].'", 
						firstname = "'.$data_array['firstname'].'", 
						lastname = "'.$data_array['lastname'].'",
						location = "'.$data_array['location'].'", 
						signature = "'.$data_array['signature'].'"';
				
		if (isset($pass_update))
		{
			$query .= $pass_update;
		}
		
		$query .= ' WHERE (id = "'.$user_id.'")';

		$dbim->query($query);
		
		return true;
	}
		
	// Check's a field to see if it exists
	function check_exists($field, $value)
	{
		global $dbim;
		
		// Validation - Check $field not already in use	
		$result = $dbim->query('SELECT '.$field.'
								FROM '.DB_PREFIX.'users
								WHERE ('.$field.' = "'.$value.'")');
								
		if ($dbim->num_rows($result) > 0)
		{
			// Oh God, no, it exists
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// Get permissions & their default values
	function default_permissions()
	{
		global $dbim;
		
		// Get all the permission names
		$result = $dbim->query('SELECT permission_id, name, setting 
								FROM '.DB_PREFIX.'permissions');
		
		while ($permission = $dbim->fetch_array($result))
		{
			$permissions["$permission[name]"] = $permission['setting'];
		}

		return $permissions;
	}	
	
	// Get group specific permissions
	function group_permissions ($group_id = false)
	{
		global $dbim;
		
		// If no group id is given, use the user's one
		if (!$group_id)
		{
			$group_id = $_SESSION['group_id'];
		}
				
		$result = $dbim->query('SELECT p.name, up.setting 
								FROM '.DB_PREFIX.'permissions p, '.DB_PREFIX.'userpermissions up 
								WHERE (up.type = "user_group")
									AND (up.type_value = "'.$group_id.'")
										AND (p.permission_id=up.permission_id)');	
			
		while ($permission = $dbim->fetch_array($result))
		{
			$permissions["$permission[name]"] = $permission['setting'];
		}	
		
		return $permissions;
	}
	
	// Overrides existing permissions
	function add_permissions($existing, $new)
	{
		foreach ($new as $name => $setting)
		{
			$existing["$name"] = $setting;
		}
		
		return $existing;
	}
	
	// Get all permissions
	function all_permissions($user_id = false)
	{	
		// Initialise array
		$permissions = array();
		$permissions = $this->default_permissions();
		$permissions = $this->add_permissions($permissions, $this->group_permissions());
		
		// Store the permissions for later use
		$this->permissions = $permissions;
	}
	
	// Return the value for a specific permission
	function permitted($permission)
	{#return true;
		if (array_key_exists($permission, $this->permissions))
		{
			return $this->permissions["$permission"];
		}
		else
		{
			return false;
		}
	}
	
	function list_permissions()
	{
		global $dbim;
		
		// Get permission list from database
		$result = $dbim->query('SELECT permission_id, name 
								FROM '.DB_PREFIX.'permissions');
		
		while ($row = $dbim->fetch_array($result))
		{
			$name = $row['name'];
			$id = $row['permission_id'];
			$permissions[$name] = $id;
		}
		
		return $permissions;
	}
}
?>