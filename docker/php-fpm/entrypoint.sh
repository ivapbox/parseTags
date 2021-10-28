#!/bin/sh

 set -e

 if [ ! -z "$DEBUG" ] && [ ! -f /usr/local/etc/php/conf.d/dev.ini ]; then
     docker-php-ext-enable xdebug
     cp -v /tmp/dev.ini /usr/local/etc/php/conf.d
 fi

 # first arg is `-f` or `--some-option`
 if [ "${1#-}" != "$1" ]; then
         set -- php-fpm "$@"
 fi

 exec "$@"
