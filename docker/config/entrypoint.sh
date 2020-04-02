#!/bin/bash

# Change the videos to be writable
chmod 0777 /videos

# Start fpm pools
service php7.2-fpm restart

# Run nginx
nginx  -g 'daemon off;'