# Olate Download

**Purpose**

Olate Download is a lightweight PHP/MySQL web application for hosting and distributing downloadable files. It provides an admin interface to manage categories, files, users and mirrors; a public-facing catalog with search and RSS support; and a modular structure that supports extensions and templates.

Although development appeared to have stopped for a period, the project was maintained in a working fork by Amazing World Music (www.amazingworldmusic.com). As that site moves to a new, custom backend the maintainer published the fixes and updates made to keep the codebase compatible with PHP 8.x.

There is unlikely to be active support or ongoing feature development for this project, but you are welcome to fork or contribute — if you get involved, SnatMTE will help connect contributors with anyone who wishes to take over maintenance.

**Key features**

- File catalog with categories and mirrors
- Admin modules for user, file and site management
- Template-based frontend (templates/olate)
- Integrated WYSIWYG editor (FCKeditor)
- Installer and upgrade scripts (setup/)

**Quick setup**

1. Copy the files to your web server.
2. Create a MySQL database and user.
3. Run the installer: visit `/setup/` in a browser and follow the prompts.
4. After install, remove the `/setup/` directory and secure `includes/config.php` (set proper permissions).

**Security & hardening (short notes)**

- Default debug should be disabled in production. Ensure `display_errors` is off and `error_log` points to a location outside the web root (e.g., `/var/log/olate/od_debug.log` or the system temp directory).
- Do not display raw database or environment errors to users. Check `od_fatal()`/error handlers to make sure user-facing messages are generic while detailed errors are only logged server-side.
- Ensure the `setup/` directory is removed after installation and that `includes/config.php` is not world-writable.
- Keep the application and dependencies up to date, and run with the minimum required permissions.

**Where to look in the codebase**

- Configuration: `includes/config.php`
- Bootstrap: `includes/init.php`
- Fatal/error handling: `includes/global.php` (see `od_fatal()`)
- Database module: `modules/core/dbim.php`
- Installer: `setup/`

