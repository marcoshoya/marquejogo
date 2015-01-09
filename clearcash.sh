#!/bin/bash

sudo php app/console cache:clear --env=dev
sudo php app/console cache:clear --env=prod

sudo chmod 777 -R app/cache
sudo chmod 777 -R app/logs

echo "Cache cleared!" 
