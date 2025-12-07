<?php
/* Simple SQLite installer for Olate Download 4.0
 * Creates `upload/data/olate.sqlite`, creates tables and seeds initial data.
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $site_name = isset($_POST['site_name']) ? trim($_POST['site_name']) : 'Olate Download';
    $admin_email = isset($_POST['admin_email']) ? trim($_POST['admin_email']) : '';

    $dataDir = __DIR__ . '/../../data';
    if (!is_dir($dataDir)) {
        if (!@mkdir($dataDir, 0755, true)) {
            $error = "Unable to create data directory ($dataDir).";
        }
    }

    $dbFile = $dataDir . '/olate.sqlite';

    try {
        $pdo = new PDO('sqlite:' . $dbFile);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create tables (SQLite-friendly definitions)
        $stmts = array();

        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_agreements (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, contents TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_categories (id INTEGER PRIMARY KEY AUTOINCREMENT, parent_id INTEGER NOT NULL DEFAULT 0, name TEXT NOT NULL, description TEXT NOT NULL, sort INTEGER NOT NULL DEFAULT 0, keywords TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_comments (id INTEGER PRIMARY KEY AUTOINCREMENT, file_id INTEGER NOT NULL DEFAULT 0, timestamp INTEGER NOT NULL DEFAULT 0, name TEXT NOT NULL, email TEXT NOT NULL, comment TEXT NOT NULL, status INTEGER NOT NULL DEFAULT 0);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_config (version TEXT NOT NULL DEFAULT '', site_name TEXT NOT NULL DEFAULT '', url TEXT NOT NULL DEFAULT '', flood_interval INTEGER NOT NULL DEFAULT 60, admin_email TEXT NOT NULL DEFAULT '', language TEXT NOT NULL DEFAULT '', template TEXT NOT NULL DEFAULT '', date_format TEXT NOT NULL DEFAULT '', filesize_format TEXT NOT NULL DEFAULT 'KB', page_amount INTEGER NOT NULL DEFAULT 10, latest_files INTEGER NOT NULL DEFAULT 5, enable_topfiles INTEGER NOT NULL DEFAULT 1, top_files INTEGER NOT NULL DEFAULT 5, enable_allfiles INTEGER NOT NULL DEFAULT 1, enable_comments INTEGER NOT NULL DEFAULT 0, approve_comments INTEGER NOT NULL DEFAULT 0, enable_search INTEGER NOT NULL DEFAULT 0, enable_ratings INTEGER NOT NULL DEFAULT 0, enable_stats INTEGER NOT NULL DEFAULT 0, enable_rss INTEGER NOT NULL DEFAULT 0, enable_count INTEGER NOT NULL DEFAULT 1, enable_useruploads INTEGER NOT NULL DEFAULT 0, enable_actual_upload INTEGER NOT NULL DEFAULT 0, enable_mirrors INTEGER NOT NULL DEFAULT 0, enable_leech_protection INTEGER NOT NULL DEFAULT 1, mirrors INTEGER NOT NULL DEFAULT 5, uploads_allowed_ext TEXT NOT NULL, userupload_always_approve INTEGER NOT NULL DEFAULT 0, filter_cats INTEGER NOT NULL DEFAULT 0, ip_restrict_mode INTEGER NOT NULL DEFAULT 0, enable_recommend_friend INTEGER NOT NULL DEFAULT 1, enable_recommend_confirm INTEGER NOT NULL DEFAULT 0, acp_check_extensions INTEGER NOT NULL DEFAULT 0, use_fckeditor INTEGER NOT NULL DEFAULT 0, allow_user_lang INTEGER NOT NULL DEFAULT 0);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_customfields (id INTEGER PRIMARY KEY AUTOINCREMENT, label TEXT NOT NULL, value TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_customfields_data (id INTEGER PRIMARY KEY AUTOINCREMENT, field_id INTEGER NOT NULL DEFAULT 0, file_id INTEGER NOT NULL DEFAULT 0, value TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_files (id INTEGER PRIMARY KEY AUTOINCREMENT, category_id INTEGER NOT NULL DEFAULT 0, name TEXT NOT NULL, description_small TEXT NOT NULL, description_big TEXT NOT NULL, downloads INTEGER NOT NULL DEFAULT 0, views INTEGER NOT NULL DEFAULT 0, size INTEGER NOT NULL DEFAULT 0, date INTEGER NOT NULL DEFAULT 0, agreement_id INTEGER NOT NULL DEFAULT 0, rating_votes INTEGER NOT NULL DEFAULT 0, rating_value TEXT NOT NULL DEFAULT '0', password TEXT NOT NULL DEFAULT '', status INTEGER NOT NULL DEFAULT 1, convert_newlines INTEGER NOT NULL DEFAULT 0, keywords TEXT NOT NULL, activate_at INTEGER NOT NULL DEFAULT 0);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_mirrors (id INTEGER PRIMARY KEY AUTOINCREMENT, file_id INTEGER NOT NULL DEFAULT 0, name TEXT NOT NULL, location TEXT NOT NULL, url TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_permissions (permission_id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, setting INTEGER NOT NULL DEFAULT 0);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_stats (id INTEGER PRIMARY KEY AUTOINCREMENT, file_id INTEGER NOT NULL DEFAULT 0, timestamp TEXT NOT NULL DEFAULT '', ip TEXT NOT NULL DEFAULT '', referrer TEXT NOT NULL, user_agent TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_usergroups (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_userpermissions (id INTEGER PRIMARY KEY AUTOINCREMENT, permission_id INTEGER NOT NULL DEFAULT 0, type TEXT NOT NULL DEFAULT 'user_group', type_value INTEGER NOT NULL DEFAULT 0, setting INTEGER NOT NULL DEFAULT 0);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_users (id INTEGER PRIMARY KEY AUTOINCREMENT, group_id INTEGER NOT NULL DEFAULT 0, username TEXT NOT NULL DEFAULT '', password TEXT NOT NULL DEFAULT '', salt TEXT NOT NULL DEFAULT '', email TEXT NOT NULL DEFAULT '', firstname TEXT NOT NULL DEFAULT '', lastname TEXT NOT NULL DEFAULT '', location TEXT NOT NULL DEFAULT '', signature TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_ip_restrict (id INTEGER PRIMARY KEY AUTOINCREMENT, type INTEGER NOT NULL DEFAULT 0, start TEXT NOT NULL DEFAULT '', end TEXT NOT NULL DEFAULT '', mask TEXT NOT NULL DEFAULT '', action INTEGER NOT NULL DEFAULT 0, active INTEGER NOT NULL DEFAULT 0);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_ip_restrict_log (id INTEGER PRIMARY KEY AUTOINCREMENT, timestamp INTEGER DEFAULT 0, ip_address TEXT NOT NULL DEFAULT '', request_uri TEXT NOT NULL, referer TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_leech_settings (id INTEGER PRIMARY KEY AUTOINCREMENT, domain TEXT NOT NULL, action INTEGER NOT NULL, PRIMARY KEY(id));";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_recommend_log (id INTEGER PRIMARY KEY AUTOINCREMENT, timestamp INTEGER NOT NULL, ip_address TEXT NOT NULL, file_id INTEGER NOT NULL, sender_name TEXT NOT NULL, sender_email TEXT NOT NULL, rcpt_name TEXT NOT NULL, rcpt_email TEXT NOT NULL, message TEXT NOT NULL, confirm_hash TEXT NOT NULL, confirmed INTEGER NOT NULL DEFAULT 0);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_recommend_blocklist (id INTEGER PRIMARY KEY AUTOINCREMENT, address TEXT NOT NULL);";
        $stmts[] = "CREATE TABLE IF NOT EXISTS downloads_languages (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, site_default INTEGER NOT NULL DEFAULT 0, filename TEXT NOT NULL, version_major INTEGER NOT NULL DEFAULT 0, version_minor INTEGER NOT NULL DEFAULT 0);";

        foreach ($stmts as $s) {
            $pdo->exec($s);
        }

        // Seed basic data (categories, sample file, permissions, groups, userpermissions, languages)
        $inserts = array();
        $inserts[] = "INSERT INTO downloads_categories (id, parent_id, name, description, sort) VALUES (1,0,'Test Parent 1','Test category 1',2);";
        $inserts[] = "INSERT INTO downloads_files (id, category_id, name, description_small, description_big, downloads, views, size, date, agreement_id, rating_votes, rating_value, password) VALUES (1,1,'Test File 1','This is a short description.','This is a longer description.',0,0,150,1093290536,0,0,'','');";
        $inserts[] = "INSERT INTO downloads_mirrors (id, file_id, name, location, url) VALUES (1,1,'Mirror 1','Mirror 1','http://www.example.com');";
        $inserts[] = "INSERT INTO downloads_permissions (permission_id, name, setting) VALUES (1,'acp_view',0);";
        $inserts[] = "INSERT INTO downloads_usergroups (id, name) VALUES (1,'Members'),(2,'Admins');";
        $inserts[] = "INSERT INTO downloads_userpermissions (id, permission_id, type, type_value, setting) VALUES (1,1,'user_group',2,1);";
        $inserts[] = "INSERT INTO downloads_languages (id, name, site_default, filename, version_major, version_minor) VALUES (1,'English (British)',1,'english.php',3,4);";

        foreach ($inserts as $i) {
            try { $pdo->exec($i); } catch (Exception $e) { /* ignore duplicate seed errors */ }
        }

        // Insert configuration row
        $cfg = $pdo->prepare('INSERT INTO downloads_config (version, site_name, url, admin_email, date_format) VALUES (:version, :site_name, :url, :admin_email, :date_format)');
        $cfg->execute(array(':version' => '4.0', ':site_name' => $site_name, ':url' => (isset($_SERVER['HTTP_HOST'])?('http://'.$_SERVER['HTTP_HOST']):''), ':admin_email' => $admin_email, ':date_format' => 'jS M Y'));

        // Write a minimal includes/config.php that keeps DB_PREFIX and basic db array
        $configPath = __DIR__ . '/../../includes/config.php';
        $configContents = "<?php\n// Generated by SQLite installer\nif (!defined('DB_PREFIX')) define('DB_PREFIX', 'downloads_');\n\n$config = array();\n$config['database'] = array(\n    'username' => '',\n    'password' => '',\n    'server'   => '',\n    'name'     => '',\n    'persistant'=> false,\n);\n?>";

        file_put_contents($configPath, $configContents);

        $success = true;
    }
    catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?><!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Olate Installer (SQLite)</title></head><body>
<h1>Olate Download - SQLite Installer</h1>
<?php if (!empty($error)): ?>
    <div style="color:red;"><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (!empty($success)): ?>
    <div style="color:green;"><strong>Success:</strong> Database created at <code><?php echo htmlspecialchars($dbFile); ?></code></div>
    <p>Config written to <code>upload/includes/config.php</code>. You can now remove the <code>setup</code> directory.</p>
<?php else: ?>
    <form method="post">
        <p><label>Site name: <input name="site_name" value="Olate Download"></label></p>
        <p><label>Admin email: <input name="admin_email" value=""></label></p>
        <p><button type="submit">Create SQLite DB</button></p>
    </form>
<?php endif; ?>
</body></html>
