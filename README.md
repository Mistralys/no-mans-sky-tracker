# No Man's Sky tracker

PHP-based tool for tracking solar systems and outposts in [No Man's Sky](https://www.nomanssky.com/).

Offers a user interface to manually add solar systems, planets and outpost 
to keep track of. Various views make it easy to find things again, with
filtering tools.

It is intended to make it easier to keep track of noteworthy things while
you explore. Add detailed comments on solar systems, planets, outposts and
more. Save the position of planetary POIs to have a way to get back to them. 

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

1) Go to "Solar Systems".
2) Add a solar system.

Keep the tool open in a browser tab while you play, and add the solar
systems and planets as you visit them.
