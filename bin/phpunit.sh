#!/bin/bash
set -e

if [ -f .env ]; then
  export $(cat .env | sed 's/#.*//g' | xargs)
fi

docker-compose exec app ./bin/phpunit
