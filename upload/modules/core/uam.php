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
	// Kept for backward compatibility with legacy MD5 hashes
	function generate_salt()
	{
		$salt = substr(md5(microtime()), 0, 5);
		
		return $salt;
	}
	
	// Encrypts the password using the salt (legacy MD5)
	// Kept for backward compatibility with existing MD5 hashes
	function encrypt_password($password, $salt)
	{
		$encrypted = md5(md5($password).$salt);
		
		return $encrypted;
	}
	
	// New bcrypt-based password hashing
	function hash_password($password)
	{
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
	}
	
	// Verify password against a hash, with MD5 fallback for legacy users
	function verify_password($password, $hash, $legacy_salt = null)
	{
		// If the stored hash starts with $2y$, it's bcrypt — use password_verify
		if (strlen($hash) >= 60 && substr($hash, 0, 4) === '$2y$')
		{
			return password_verify($password, $hash);
		}
		
		// Legacy MD5 hash — verify with old method and rehash on success
		if ($legacy_salt !== null)
		{
			$legacy_hash = md5(md5($password).$legacy_salt);
			if (hash_equals($legacy_hash, $hash))
			{
				return true; // Caller should rehash using rehash_password()
			}
		}
		
		return false;
	}
	
	// Rehash a legacy password to bcrypt (call after successful legacy MD5 verification)
	function rehash_password($user_id, $password)
	{
		global $dbim;
		
		$new_hash = $this->hash_password($password);
		$dbim->pquery('UPDATE '.DB_PREFIX.'users 
						SET password = ?, salt = "" 
						WHERE (id = ?)',
						array($new_hash, $user_id));
	}
	
	function user_login($id, $username, $group_id)
	{
		// Store session data
		$_SESSION['id'] = $id;
		$_SESSION['username'] = $username;
		$_SESSION['group_id'] = $group_id;
		
		// Store a hash for validation (use HMAC instead of bare MD5)
		$session_key = defined('SECRET_KEY') ? SECRET_KEY : 'Od3DefaultKey_changeMe';
		$_SESSION['hash'] = hash_hmac('sha256', $id.'::'.$username.'::'.$group_id, $session_key);
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
		if (!isset($_SESSION['id']) || !isset($_SESSION['username']) || !isset($_SESSION['hash']))
		{
			return false;
		}
		// Verify session using HMAC instead of bare MD5
		$session_key = defined('SECRET_KEY') ? SECRET_KEY : 'Od3DefaultKey_changeMe';
		$expected = hash_hmac('sha256', $_SESSION['id'].'::'.$_SESSION['username'].'::'.$_SESSION['group_id'], $session_key);
		return hash_equals($expected, $_SESSION['hash']);
	}
	
	// Takes the user input and goes through the auth checks
	function user_check($username, $password)
	{
		global $dbim, $lm;
		
		// Fetch user by username using parameterized query
		$result = $dbim->pquery('SELECT id, group_id, username, password, salt 
								FROM '.DB_PREFIX.'users 
								WHERE (username = ?)',
								array($username));
		$user = $dbim->fetch_array_p($result);
		
		if (!$user)
		{
			$this->auth_error = $lm->language('frontend', 'invalid_password');
			
			return false;
		}
		
		// Verify password — supports both bcrypt and legacy MD5
		if (!$this->verify_password($password, $user['password'], $user['salt']))
		{
			$this->auth_error = $lm->language('frontend', 'invalid_password');
			return false;
		}
		
		// If the user authenticated with legacy MD5, rehash to bcrypt
		if (strlen($user['password']) < 60 || substr($user['password'], 0, 4) !== '$2y$')
		{
			$this->rehash_password($user['id'], $password);
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
		
		// Everything is ok, encrypt password using bcrypt
		$pass = $this->hash_password($data_array['password']);
		
		$dbim->pquery('INSERT INTO '.DB_PREFIX.'users
						SET group_id = ?, 
							username = ?, 
							password = ?, 
							salt = "", 
							email = ?,
							firstname = ?, 
							lastname = ?, 
							location = ?, 
							signature = ?',
						array(
							$data_array['group'],
							$data_array['username'],
							$pass,
							$data_array['email'],
							$data_array['firstname'],
							$data_array['lastname'],
							$data_array['location'],
							$data_array['signature']
						));
		
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
		$pass_update_sql = '';
		$pass_update_params = array();
		if (isset($data_array['password']) && !empty($data_array['password']))
		{
			$pass = $this->hash_password($data_array['password']);
			$pass_update_sql = ', password = ?, salt = ""';
			$pass_update_params = array($pass);
		}
		
		$params = array(
			$data_array['email'],
			$data_array['group'],
			$data_array['firstname'],
			$data_array['lastname'],
			$data_array['location'],
			$data_array['signature']
		);
		
		if (!empty($pass_update_params))
		{
			$params = array_merge($pass_update_params, $params);
		}
		$params[] = $user_id;
		
		$query = 'UPDATE '.DB_PREFIX.'users 
					SET email = ?, 
						group_id = ?, 
						firstname = ?, 
						lastname = ?,
						location = ?, 
						signature = ?';
		$query .= $pass_update_sql;
		$query .= ' WHERE (id = ?)';

		$dbim->pquery($query, $params);
		
		return true;
	}
		
	// Check's a field to see if it exists
	function check_exists($field, $value)
	{
		global $dbim;
		
		// Validate field name against whitelist to prevent SQL injection via column name
		$allowed_fields = array('username', 'email');
		if (!in_array($field, $allowed_fields))
		{
			return false;
		}
		
		// Use parameterized query
		$result = $dbim->pquery('SELECT '.$field.'
								FROM '.DB_PREFIX.'users
								WHERE ('.$field.' = ?)',
								array($value));
								
		if ($dbim->num_rows_p($result) > 0)
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
		$result = $dbim->pquery('SELECT permission_id, name, setting 
								FROM '.DB_PREFIX.'permissions', array());
		
		while ($permission = $dbim->fetch_array_p($result))
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
				
		$result = $dbim->pquery('SELECT p.name, up.setting 
								FROM '.DB_PREFIX.'permissions p, '.DB_PREFIX.'userpermissions up 
								WHERE (up.type = "user_group")
									AND (up.type_value = ?)
										AND (p.permission_id=up.permission_id)',
								array($group_id));
			
		while ($permission = $dbim->fetch_array_p($result))
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
		$result = $dbim->pquery('SELECT permission_id, name 
								FROM '.DB_PREFIX.'permissions', array());
		
		while ($row = $dbim->fetch_array_p($result))
		{
			$name = $row['name'];
			$id = $row['permission_id'];
			$permissions[$name] = $id;
		}
		
		return $permissions;
	}
}
?>