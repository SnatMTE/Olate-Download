<?php
// Database Interaction Module (PDO + SQLite)
// Modernized replacement for mysql_* based dbim to use PDO and SQLite for development

class DBResult
{
	public $rows = array();
	private $pointer = 0;

	public function __construct($rows = array())
	{
		$this->rows = $rows;
	}

	public function fetch()
	{
		if ($this->pointer < count($this->rows))
		{
			return $this->rows[$this->pointer++];
		}
		return false;
	}

	public function num_rows()
	{
		return count($this->rows);
	}
}

class dbim
{
	public $pdo = null;
	public $in_query = false;
	public $query_count = 0;
	public $lastStatement = null;

	// Connect to database - for development we default to SQLite
	public function connect($username = null, $password = null, $server = null, $name = null, $persistant = false)
	{
		try
		{
			// Determine SQLite DB file path inside upload/data/olate.sqlite
			$dbDir = realpath(__DIR__ . '/../../data');
			if ($dbDir === false)
			{
				$dbDir = __DIR__ . '/../../data';
				if (!is_dir($dbDir))
				{
					@mkdir($dbDir, 0755, true);
				}
			}

			$dbFile = $dbDir . '/olate.sqlite';
			$dsn = 'sqlite:' . $dbFile;

			$options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);
			$this->pdo = new PDO($dsn, null, null, $options);

			return $this->pdo;
		}
		catch (Exception $e)
		{
			trigger_error('[DBIM] Connection Failed: '. $e->getMessage(), E_USER_ERROR);
		}
	}

	// Execute and return result of SQL query
	public function query($query = false)
	{
		if (!isset($query))
		{
			trigger_error('[DBIM] No Query Specified', E_USER_ERROR);
		}

		if ($this->in_query)
		{
			trigger_error('[DBIM] Already in query', E_USER_ERROR);
		}

		$this->in_query = true;
		try
		{
			$stmt = $this->pdo->query($query);
			$this->lastStatement = $stmt;

			// For SELECT queries, fetch all rows into DBResult for compatibility
			$rows = array();
			if ($stmt !== false)
			{
				try { $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); } catch (Exception $e) { $rows = array(); }
			}

			$this->query_count++;
			$this->in_query = false;

			return new DBResult($rows);
		}
		catch (Exception $e)
		{
			$this->in_query = false;
			trigger_error('[DBIM] Query Failed: '. $e->getMessage() .' Query: '. $query, E_USER_ERROR);
		}
	}

	// Return result row as an associative array
	public function fetch_array($result)
	{
		if ($result instanceof DBResult)
		{
			return $result->fetch();
		}
		trigger_error('[DBIM] Query Failed: invalid result', E_USER_ERROR);
	}

	// Return number of rows in result
	public function num_rows($result)
	{
		if ($result instanceof DBResult)
		{
			return $result->num_rows();
		}
		trigger_error('[DBIM] Query Failed: invalid result', E_USER_ERROR);
	}

	// Return number of affected rows in previous operation
	public function affected_rows()
	{
		if ($this->lastStatement instanceof PDOStatement)
		{
			return $this->lastStatement->rowCount();
		}
		return 0;
	}

	// Return the ID generated from the previous INSERT operation
	public function insert_id()
	{
		try
		{
			return $this->pdo->lastInsertId();
		}
		catch (Exception $e)
		{
			trigger_error('[DBIM] insert_id Failed: '. $e->getMessage(), E_USER_ERROR);
		}
	}
}

?>