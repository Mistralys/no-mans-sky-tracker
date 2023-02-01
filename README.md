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
2) Import the file `/sql/new-install.sql` into a database.
3) Navigate to the `htdocs` folder.
4) Run `composer install` to install the dependencies.
5) Rename `config.dist.php` to `config.php`.
6) Edit the configuration settings as needed.
7) Point your browser to the `htdocs` folder.

## Quick start

1) Go to "Manage" > "Solar System Clusters".
2) Add a first cluster to work with.
3) Now you can start adding solar systems.

Keep the tool open in a browser tab while you play, and add the solar
systems and planets as you visit them.
