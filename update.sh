#!/bin/bash

case "$1" in
    daemon)
        '(while true; do php s3_sync.php upload; sleep 600; done) &'
        php tt-rss/update_daemon2.php
        ;;
    icons)
        psql "$DATABASE_URL" -c 'UPDATE ttrss_feeds SET favicon_last_checked=NULL, last_updated=NULL, last_update_started=NULL;' 2>/dev/null
        "$0"
        echo "Now you should restart the web dyno to actually see the icons."
        echo "Do this by running 'heroku restart'."
        ;;
    *)
        php tt-rss/update.php --feeds
        php s3_sync.php upload
        psql "$DATABASE_URL" -c 'TRUNCATE ttrss_tags;' 2>/dev/null
        ;;
esac
