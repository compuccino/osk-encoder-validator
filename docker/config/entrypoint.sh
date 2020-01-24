#!/bin/bash

# Change the videos to be writable
chmod 0777 /videos

source /etc/apache2/envvars
#tail -F /var/log/apache2/* &
exec apache2 -D FOREGROUND