#!/bin/bash
if [ ! -d "/var/www/html/vendor" ]; then
    cd /var/www/html && php ./composer.phar install
fi