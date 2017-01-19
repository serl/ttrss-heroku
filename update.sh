#!/bin/bash

if [ "$1" == "daemon" ]; then
    (while true; do php s3_sync.php upload; sleep 600; done) &
    php tt-rss/update_daemon2.php
else
    php tt-rss/update.php --feeds
    php s3_sync.php upload
fi
