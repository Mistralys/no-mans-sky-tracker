# No Man's Sky tracker

PHP-based tool for tracking solar systems and outposts in No Man's Sky.

Offers a user interface to manually add solar systems, planets and outpost 
to keep track of. Various views make it easy to find things again, with
filtering tools.

## Requirements

- Local webserver
- PHP 7.4+
- [Composer](https://getcomposer.org/)
- MySQL or MariaDB database

## Installation

1) Clone locally into a webserver's webroot.
2) Run `composer install` to install the dependencies.
3) Import the file `sql/new-install.sql` into a database.
4) Rename `htdocs/config.dist.php` to `htdocs/config.php`.
5) Edit the configuration settings as needed.
6) Point your browser to the application's `htdocs` folder.
