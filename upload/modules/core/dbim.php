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

// Database Interaction Module
class dbim
{
	// Declare the variables
	var $connection, $in_query, $name, $password, $persistant, $query_count, $result, $server, $username;
	
	// Connect to database
	function connect($username, $password, $server, $name, $persistant)
	{	
		// Populate class variables
		$this->username = $username;
		$this->password = $password;
		$this->server = $server;
		$this->name = $name;
		$this->persistant = $persistant;
		
		// Connect
		$this->connection = ($this->persistant) ? @mysql_pconnect($this->server, $this->username, $this->password, 1) : @mysql_connect($this->server, $this->username, $this->password, 1);
		
		// If everything was ok, select database
		if ($this->connection)
		{
			if (@mysql_select_db($this->name))
			{
				return $this->connection;
			}
		}
		
		// Error handling (If you have EHM debug >= 2 then you get a full var dump including DB access details)
		od_fatal('[DBIM] Connection Failed: '.mysql_error()); 
	}
	
	// Execute and return result of SQL query
	function query($query = false)
	{	
		if (isset($query))
		{
			// Make sure we're not already running a query 'cause it may break
			if (!$this->in_query)
			{
				$this->in_query = true;
				$this->result = @mysql_query($query, $this->connection);
				if (!$this->result)
				{
					// Error handling
					od_fatal('[DBIM] Query Failed: '.mysql_error().' Query: '.$query);
				}
				
				// Increment query counter
				$this->query_count++; 
				
				$this->in_query = false;				
				return $this->result;
			}
			else
			{
				// Error handling
				od_fatal('[DBIM] Already in query');
			} 
		}
		// Error handling
		od_fatal('[DBIM] No Query Specified'); 
	}

	// Return result row as an associative array
	function fetch_array($result)
	{
		return ($result) ? @mysql_fetch_assoc($result) : od_fatal('[DBIM] Query Failed: '.mysql_error()); 
	}
	
	// Return number of rows in result
	function num_rows($result)
	{	
		return ($result) ? @mysql_num_rows($result) : od_fatal('[DBIM] Query Failed: '.mysql_error()); 
	}
	
	// Return number of affected rows in previous operation
	function affected_rows()
	{
		return ($this->connection) ? @mysql_affected_rows($this->connection) : od_fatal('[DBIM] Query Failed: '.mysql_error()); 
	}
	
	// Return the ID generated from the previous INSERT operation
	function insert_id()
	{
		return ($this->connection) ? @mysql_insert_id($this->connection) : od_fatal('[DBIM] Query Failed: '.mysql_error()); 
	}
	
	// Execute a parameterized query using PDO prepared statements
	// $sql: SQL with ? placeholders
	// $params: array of parameter values
	// Returns: PDOStatement on success, or falls back to query() on failure
	function pquery($sql, $params = array())
	{
		// Try to get the PDO connection from the helper's polyfill
		if (function_exists('_od_get_pdo'))
		{
			$pdo = _od_get_pdo($this->connection);
			if ($pdo instanceof PDO)
			{
				try
				{
					$stmt = $pdo->prepare($sql);
					$stmt->execute($params);
					$this->query_count++;
					return $stmt;
				}
				catch (PDOException $e)
				{
					od_fatal('[DBIM] pquery Failed: '.$e->getMessage().' SQL: '.$sql);
				}
			}
		}
		
		// Fallback: if PDO is not available, escape params and use regular query()
		// This handles environments where native mysql_* functions are used directly
		$escaped_sql = $sql;
		if (!empty($params))
		{
			// Replace ? placeholders with escaped values
			foreach ($params as $param)
			{
				$escaped_param = (is_null($param)) ? 'NULL' : '"'.mysql_real_escape_string((string)$param, $this->connection).'"';
				$pos = strpos($escaped_sql, '?');
				if ($pos !== false)
				{
					$escaped_sql = substr_replace($escaped_sql, $escaped_param, $pos, 1);
				}
			}
		}
		return $this->query($escaped_sql);
	}
	
	// Fetch a row from a PDO statement (used with pquery results)
	function fetch_array_p($stmt)
	{
		if ($stmt instanceof PDOStatement)
		{
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		// Fallback for legacy mysql result resources
		return $this->fetch_array($stmt);
	}
	
	// Get row count from a PDO statement (used with pquery results)
	function num_rows_p($stmt)
	{
		if ($stmt instanceof PDOStatement)
		{
			return $stmt->rowCount();
		}
		// Fallback for legacy mysql result resources
		return $this->num_rows($stmt);
	}
}
?>