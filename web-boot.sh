#!/bin/bash

# Download the icons from S3, so tt-rss is happy and shows them.
php s3_sync.php download

# Start the web server.
vendor/bin/heroku-php-apache2 tt-rss
