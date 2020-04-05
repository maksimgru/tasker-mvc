# Install
* Download (or Clone) app in your root directory.

* Rename `./public/.htaccess.dist` to `./public/.htaccess` in your root directory.

* If your web-path not equal `/`, than edit `./public/.htaccess` file for correct work of rewrite rules. Edit line `RewriteBase /`.

* Rename `app/config/config.php.dist` to `app/config/config.php`.
* Set up your own database credentials.
* Setup web base path (constant `BASE`) which will be equal with rewrite base rule (`RewriteBase /`) in your `./public/.htaccess` file.

* Load DB dump file `db_dump.sql`

* Run `composer install` in your root directory.
* Run `composer dump-autoload  --optimize`
