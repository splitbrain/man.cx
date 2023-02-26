# man.cx

[man.cx](https://man.cx) is a web interface for man pages. It provides man pages from thousands of Debian packages for easy lookup without the need to install them.

This repo contains the PHP source code that powers the site.

## Update process

See `update.sh` for the involved steps. It's called from cron.

1. `bin/fetch.php` downloads Debian packages to `data/temp` and extracts their man pages to `data/man`
2. `bin/process.php` creates HTML from the man pages and stores them in `data/html`
3. `bin/sitemaps.php` creates XML sitemaps for SEO and stores them in `www/___/sitemaps/`

