#!/bin/bash

CRON_FILE="/tmp/cronjob_xkcd"
CRON_CMD="0 9 * * * php $(pwd)/cron.php"

crontab -l > "$CRON_FILE" 2>/dev/null
if ! grep -Fq "$CRON_CMD" "$CRON_FILE"; then
    echo "$CRON_CMD" >> "$CRON_FILE"
    crontab "$CRON_FILE"
    echo "CRON job added!"
else
    echo "CRON job already exists."
fi
