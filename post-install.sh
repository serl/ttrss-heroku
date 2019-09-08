#!/bin/bash

echo "Injecting configuration file..."
cp ttrss-config.php tt-rss/config.php

echo "Fixing permissions..."
chmod -R -w tt-rss
chmod +w tt-rss/plugins.local
chmod -R 777 tt-rss/cache tt-rss/lock tt-rss/feed-icons

echo "Checking database..."
if ! psql "$DATABASE_URL" -c 'SELECT schema_version FROM ttrss_version' &>/dev/null; then
    echo "Initializing database..."
    psql "$DATABASE_URL" < tt-rss/schema/ttrss_schema_pgsql.sql >/dev/null
fi

php plugins-installer.php
