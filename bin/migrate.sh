#!/bin/bash
set -e

if [ -f .env ]; then
  export $(cat .env | sed 's/#.*//g' | xargs)
fi

# Run Doctrine migrations
echo "Running Doctrine migrations..."
docker-compose exec app php bin/console doctrine:migrations:migrate --no-interaction

echo "Migrations run successfully."
