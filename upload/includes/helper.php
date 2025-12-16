<?php
/**
 * PDO based compatibility layer for ext/mysql functions
 * Provides a minimal set of mysql_* functions implemented on top of PDO
 * so older code can continue working under PHP 7/8 where ext/mysql is removed.
 *
 * Added features:
 *  - Can connect to MySQL via PDO (default)
 *  - Supports SQLite: set server to 'sqlite' and then call mysql_select_db('/path/to/db.sqlite'),
 *    or pass 'sqlite:/full/path/to/db.sqlite' as the server in mysql_connect().
 *    This will create/open the SQLite DB and use PDO SQLite driver automatically.
 */

if (!defined('OD_PDO_MYSQL_HELPER'))
{
    define('OD_PDO_MYSQL_HELPER', 1);

    // Internal state
    $GLOBALS['__od_pdo_default'] = null; // default PDO instance
    $GLOBALS['__od_mysql_last_stmt'] = null;
    $GLOBALS['__od_mysql_last_error'] = '';
    $GLOBALS['__od_mysql_driver'] = 'mysql'; // 'mysql' or 'sqlite'
    $GLOBALS['__od_sqlite_path'] = null;

    function _od_get_pdo($link = null)
    {
        if ($link instanceof PDO) {
            return $link;
        }
        if (is_resource($link)) {
            // Not used, but kept for signature compatibility
            return $link;
        }
        return $GLOBALS['__od_pdo_default'];
    }

    if (!function_exists('mysql_connect'))
    {
        function mysql_connect($server = 'localhost', $username = null, $password = null, $new_link = false, $client_flags = 0)
        {
            $server = trim((string)$server);
            // Detect SQLite usage: 'sqlite:/path/to/db' or server == 'sqlite' (db path provided in mysql_select_db)
            if (stripos($server, 'sqlite:') === 0) {
                $path = substr($server, 7);
                $GLOBALS['__od_mysql_driver'] = 'sqlite';
                $GLOBALS['__od_sqlite_path'] = $path;
                try {
                    $pdo = new PDO('sqlite:'.$path, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    $GLOBALS['__od_pdo_default'] = $pdo;
                    return $pdo;
                } catch (PDOException $e) {
                    $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                    return false;
                }
            }

            // Also accept raw SQLite file paths, e.g. '/path/to/db.sqlite' or 'C:\path\db.sqlite' or files ending in .sqlite/.db
            if (preg_match('/\.sqlite$/i', $server) || preg_match('/\.db$/i', $server) || preg_match('/^[A-Z]:\\\\/i', $server) || strpos($server, '/') !== false) {
                // Treat this as a path to a SQLite DB
                $path = $server;
                $GLOBALS['__od_mysql_driver'] = 'sqlite';
                $GLOBALS['__od_sqlite_path'] = $path;
                try {
                    $pdo = new PDO('sqlite:'.$path, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    $GLOBALS['__od_pdo_default'] = $pdo;
                    return $pdo;
                } catch (PDOException $e) {
                    $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                    return false;
                }
            }

            if ($server === 'sqlite') {
                $GLOBALS['__od_mysql_driver'] = 'sqlite';
                // actual DB path expected in mysql_select_db()
                return true;
            }

            // Fallback to MySQL (PDO)
            try {
                // Parse host[:port] including IPv6 in [addr]:port form
                $host = $server;
                $port = null;
                if (preg_match('/^\[(.+)\](?::(\d+))?$/', $server, $m)) { // [IPv6]:port
                    $host = $m[1];
                    if (!empty($m[2])) $port = $m[2];
                } elseif (substr_count($server, ':') === 1) { // hostname:port
                    list($host, $port) = explode(':', $server, 2);
                }
                $dsn = 'mysql:host='.$host.(!empty($port) ? ';port='.$port : '').';charset=utf8mb4';
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                $GLOBALS['__od_mysql_driver'] = 'mysql';
                $GLOBALS['__od_pdo_default'] = $pdo;
                return $pdo;
            } catch (PDOException $e) {
                $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                return false;
            }
        }
    }

    if (!function_exists('mysql_pconnect'))
    {
        function mysql_pconnect($server = 'localhost', $username = null, $password = null)
        {
            $server = trim((string)$server);
            if (stripos($server, 'sqlite:') === 0) {
                $path = substr($server, 7);
                $GLOBALS['__od_mysql_driver'] = 'sqlite';
                $GLOBALS['__od_sqlite_path'] = $path;
                try {
                    $pdo = new PDO('sqlite:'.$path, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_PERSISTENT => true,
                    ]);
                    $GLOBALS['__od_pdo_default'] = $pdo;
                    return $pdo;
                } catch (PDOException $e) {
                    $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                    return false;
                }
            }

            // Also accept raw SQLite file paths, e.g. '/path/to/db.sqlite' or 'C:\path\db.sqlite' or files ending in .sqlite/.db
            if (preg_match('/\.sqlite$/i', $server) || preg_match('/\.db$/i', $server) || preg_match('/^[A-Z]:\\\\/i', $server) || strpos($server, '/') !== false) {
                // Treat this as a path to a SQLite DB
                $path = $server;
                $GLOBALS['__od_mysql_driver'] = 'sqlite';
                $GLOBALS['__od_sqlite_path'] = $path;
                try {
                    $pdo = new PDO('sqlite:'.$path, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_PERSISTENT => true,
                    ]);
                    $GLOBALS['__od_pdo_default'] = $pdo;
                    return $pdo;
                } catch (PDOException $e) {
                    $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                    return false;
                }
            }

            if ($server === 'sqlite') {
                $GLOBALS['__od_mysql_driver'] = 'sqlite';
                return true;
            }

            try {
                // Parse host[:port] including IPv6 in [addr]:port form
                $host = $server;
                $port = null;
                if (preg_match('/^\[(.+)\](?::(\d+))?$/', $server, $m)) { // [IPv6]:port
                    $host = $m[1];
                    if (!empty($m[2])) $port = $m[2];
                } elseif (substr_count($server, ':') === 1) { // hostname:port
                    list($host, $port) = explode(':', $server, 2);
                }
                $dsn = 'mysql:host='.$host.(!empty($port) ? ';port='.$port : '').';charset=utf8mb4';
                $pdo = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
                $GLOBALS['__od_mysql_driver'] = 'mysql';
                $GLOBALS['__od_pdo_default'] = $pdo;
                return $pdo;
            } catch (PDOException $e) {
                $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                return false;
            }
        }
    }

    if (!function_exists('mysql_select_db'))
    {
        function mysql_select_db($dbname, $link = null)
        {
            $pdo = _od_get_pdo($link);

            // If driver is sqlite and no PDO yet, create SQLite PDO using $dbname as path
            if (isset($GLOBALS['__od_mysql_driver']) && $GLOBALS['__od_mysql_driver'] === 'sqlite') {
                // If a path was provided earlier via mysql_connect('sqlite:/path'), prefer it
                $path = $GLOBALS['__od_sqlite_path'];
                if (empty($path)) {
                    // Use $dbname as the path
                    $path = $dbname;
                }
                try {
                    $pdo = new PDO('sqlite:'.$path, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]);
                    $GLOBALS['__od_pdo_default'] = $pdo;
                    $GLOBALS['__od_sqlite_path'] = $path;
                    return true;
                } catch (PDOException $e) {
                    $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                    return false;
                }
            }

            if (!$pdo) {
                $GLOBALS['__od_mysql_last_error'] = 'No active DB connection';
                return false;
            }
            try {
                $pdo->exec('USE `'.str_replace('`','',$dbname).'`');
                return true;
            } catch (PDOException $e) {
                $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                return false;
            }
        }
    }

    if (!function_exists('mysql_query'))
    {
        function mysql_query($sql, $link = null)
        {
            $pdo = _od_get_pdo($link);
            if (!$pdo) {
                $GLOBALS['__od_mysql_last_error'] = 'No active DB connection';
                return false;
            }
            try {
                $stmt = $pdo->query($sql);
                $GLOBALS['__od_mysql_last_stmt'] = $stmt;
                return $stmt;
            } catch (PDOException $e) {
                $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                return false;
            }
        }
    }

    if (!function_exists('mysql_fetch_assoc'))
    {
        function mysql_fetch_assoc($result)
        {
            if ($result instanceof PDOStatement) {
                return $result->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }
    }

    if (!function_exists('mysql_fetch_array'))
    {
        function mysql_fetch_array($result)
        {
            if ($result instanceof PDOStatement) {
                return $result->fetch(PDO::FETCH_BOTH);
            }
            return false;
        }
    }

    if (!function_exists('mysql_num_rows'))
    {
        function mysql_num_rows($result)
        {
            if ($result instanceof PDOStatement) {
                $count = $result->rowCount();
                // rowCount() may be 0 for SELECT until rows buffered; try safe fallback
                if ($count === 0) {
                    $all = $result->fetchAll(PDO::FETCH_NUM);
                    $count = is_array($all) ? count($all) : 0;
                    // Not ideal for large sets, but keeps compatibility
                }
                return $count;
            }
            return 0;
        }
    }

    if (!function_exists('mysql_affected_rows'))
    {
        function mysql_affected_rows($link = null)
        {
            $stmt = $GLOBALS['__od_mysql_last_stmt'];
            if ($stmt instanceof PDOStatement) {
                return $stmt->rowCount();
            }
            return 0;
        }
    }

    if (!function_exists('mysql_insert_id'))
    {
        function mysql_insert_id($link = null)
        {
            $pdo = _od_get_pdo($link);
            if (!$pdo) return 0;
            try {
                return $pdo->lastInsertId();
            } catch (PDOException $e) {
                $GLOBALS['__od_mysql_last_error'] = $e->getMessage();
                return 0;
            }
        }
    }

    if (!function_exists('mysql_real_escape_string'))
    {
        function mysql_real_escape_string($string, $link = null)
        {
            $pdo = _od_get_pdo($link);
            if ($pdo) {
                $quoted = $pdo->quote((string)$string);
                if ($quoted === false) return addslashes((string)$string);
                // PDO::quote returns the value enclosed in quotes
                if (strlen($quoted) >= 2 && $quoted[0] === "'" && substr($quoted, -1) === "'") {
                    return substr($quoted, 1, -1);
                }
                return $quoted;
            }
            return addslashes((string)$string);
        }
    }

    if (!function_exists('mysql_escape_string'))
    {
        function mysql_escape_string($string)
        {
            return addslashes((string)$string);
        }
    }

    if (!function_exists('mysql_error'))
    {
        function mysql_error()
        {
            return $GLOBALS['__od_mysql_last_error'];
        }
    }

    if (!function_exists('mysql_close'))
    {
        function mysql_close($link = null)
        {
            if ($link instanceof PDO) {
                // let GC handle it
                return true;
            }
            if (isset($GLOBALS['__od_pdo_default'])) {
                $GLOBALS['__od_pdo_default'] = null;
                return true;
            }
            return false;
        }
    }

    // Informational functions used by the environment survey page
    if (!function_exists('mysql_get_client_info'))
    {
        function mysql_get_client_info()
        {
            $pdo = _od_get_pdo();
            if ($pdo) {
                try {
                    if (isset($GLOBALS['__od_mysql_driver']) && $GLOBALS['__od_mysql_driver'] === 'sqlite') {
                        $ver = $pdo->query('select sqlite_version()')->fetchColumn();
                        return 'sqlite/'.$ver;
                    }
                    return $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION);
                } catch (Exception $e) {}
            }
            return 'PDO';
        }
    }

    if (!function_exists('mysql_get_server_info'))
    {
        function mysql_get_server_info()
        {
            $pdo = _od_get_pdo();
            if ($pdo) {
                try {
                    if (isset($GLOBALS['__od_mysql_driver']) && $GLOBALS['__od_mysql_driver'] === 'sqlite') {
                        return $pdo->query('select sqlite_version()')->fetchColumn();
                    }
                    return $pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
                } catch (Exception $e) {}
            }
            return '';
        }
    }

    if (!function_exists('mysql_client_encoding'))
    {
        function mysql_client_encoding()
        {
            try {
                $pdo = _od_get_pdo();
                if ($pdo) {
                    if (isset($GLOBALS['__od_mysql_driver']) && $GLOBALS['__od_mysql_driver'] === 'sqlite') {
                        $stmt = $pdo->query("PRAGMA encoding;");
                        $enc = $stmt->fetchColumn();
                        return $enc ?: 'UTF-8';
                    }
                    $stmt = $pdo->query("SELECT @@character_set_client");
                    return $stmt->fetchColumn();
                }
            } catch (Exception $e) {}
            return '';
        }
    }

    if (!function_exists('mysql_get_proto_info'))
    {
        function mysql_get_proto_info()
        {
            return '';
        }
    }

}

?>
