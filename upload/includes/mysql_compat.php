<?php
// Lightweight mysql_* compatibility layer backed by PDO
// Provides minimal functions used by legacy setup/import scripts

if (!function_exists('mysql_connect'))
{
    // Globals to hold PDO connection and last error
    $GLOBALS['__mysql_compat'] = array(
        'pdo' => null,
        'server' => null,
        'username' => null,
        'password' => null,
        'dbname' => null,
        'last_error' => null,
    );

    function mysql_connect($server = 'localhost', $username = null, $password = null, $new_link = false)
    {
        $GLOBALS['__mysql_compat']['server'] = $server;
        $GLOBALS['__mysql_compat']['username'] = $username;
        $GLOBALS['__mysql_compat']['password'] = $password;

        // Try to connect without a database first
        $dsn = 'mysql:host='.$server.';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $GLOBALS['__mysql_compat']['pdo'] = $pdo;
            return $pdo;
        } catch (Exception $e) {
            $GLOBALS['__mysql_compat']['last_error'] = $e->getMessage();
            return false;
        }
    }

    function mysql_select_db($dbname, $link_identifier = null)
    {
        $server = $GLOBALS['__mysql_compat']['server'] ?? 'localhost';
        $username = $GLOBALS['__mysql_compat']['username'] ?? null;
        $password = $GLOBALS['__mysql_compat']['password'] ?? null;

        $dsn = 'mysql:host='.$server.';dbname='.$dbname.';charset=utf8mb4';
        try {
            $pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $GLOBALS['__mysql_compat']['pdo'] = $pdo;
            $GLOBALS['__mysql_compat']['dbname'] = $dbname;
            return true;
        } catch (Exception $e) {
            $GLOBALS['__mysql_compat']['last_error'] = $e->getMessage();
            return false;
        }
    }

    function mysql_query($sql, $link = null)
    {
        $pdo = $GLOBALS['__mysql_compat']['pdo'] ?? null;
        if (!$pdo) {
            $GLOBALS['__mysql_compat']['last_error'] = 'No database connection';
            return false;
        }

        try {
            $stmt = $pdo->query($sql);
            return $stmt;
        } catch (Exception $e) {
            $GLOBALS['__mysql_compat']['last_error'] = $e->getMessage();
            return false;
        }
    }

    function mysql_fetch_array($result)
    {
        if ($result instanceof PDOStatement) {
            return $result->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    function mysql_fetch_assoc($result)
    {
        return mysql_fetch_array($result);
    }

    function mysql_num_rows($result)
    {
        if ($result instanceof PDOStatement) {
            try { return $result->rowCount(); } catch (Exception $e) { return 0; }
        }
        return 0;
    }

    function mysql_insert_id()
    {
        $pdo = $GLOBALS['__mysql_compat']['pdo'] ?? null;
        if ($pdo) return $pdo->lastInsertId();
        return 0;
    }

    function mysql_error()
    {
        return $GLOBALS['__mysql_compat']['last_error'];
    }

    function mysql_real_escape_string($str)
    {
        $pdo = $GLOBALS['__mysql_compat']['pdo'] ?? null;
        if ($pdo) {
            $q = $pdo->quote($str);
            // PDO::quote wraps with single quotes, strip them
            if (strlen($q) >= 2 && $q[0] == "'" && $q[strlen($q)-1] == "'") {
                $q = substr($q, 1, -1);
            }
            return $q;
        }
        return addslashes($str);
    }

    // Minimal metadata helpers used by environment checks
    function mysql_client_encoding()
    {
        return 'utf8mb4';
    }

    function mysql_get_client_info()
    {
        return phpversion('mysql') ?: 'native';
    }

    function mysql_get_proto_info()
    {
        return '';
    }

    function mysql_get_server_info()
    {
        $pdo = $GLOBALS['__mysql_compat']['pdo'] ?? null;
        if ($pdo) {
            try { return $pdo->getAttribute(PDO::ATTR_SERVER_VERSION); } catch (Exception $e) { }
        }
        return '';
    }
}

?>