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
}
?>