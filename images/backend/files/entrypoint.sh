#! /bin/bash

set -e
set -x 

 # Set WORK_DIR to /var/www/html if not set
if [ -z "${WORK_DIR}" ]; then
  export WORK_DIR="/var/www/html"
fi

# if composer.json does exist
if [ -e $WORK_DIR/composer.json  ]; then
   cd $WORK_DIR
   cat ./composer.json
   composer install
fi 

exec apache2-foreground