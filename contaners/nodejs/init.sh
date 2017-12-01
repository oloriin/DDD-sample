#!/bin/bash
if [ ! -d "/var/www/ddd-sample/node_modules" ]; then
    cd /var/www/ddd-sample && npm install
fi