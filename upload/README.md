Olate Download - Development Notes

- This repository has been partially modernized for development use.
- The legacy `dbim` MySQL abstraction has been replaced with a PDO-based implementation that uses SQLite by default for development.

Requirements:
- PHP 7.4+ (recommended PHP 8.x)
- PDO extension and `pdo_sqlite` enabled

Quickstart (development):
1. From the project root start the PHP built-in server targeting the `upload` directory:

```powershell
php -S localhost:8000 -t upload\
```

2. Open `http://localhost:8000/` in your browser.

Notes:
- A SQLite database file will be created at `upload/data/olate.sqlite` when the app first connects.
- Many installer/upgrade scripts still reference legacy MySQL functions; for a smooth development experience run the application and use the web-based setup to create required tables, or import SQL into the SQLite DB as needed.
- For production, migrate to a proper RDBMS and configure a PDO DSN accordingly.

If you want, I can:
- Convert the installer scripts to use PDO/SQLite
- Migrate all modules/pages to prepared statements and strict typing
- Add automated tests and a sample SQLite schema
