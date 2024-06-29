#!/bin/bash
set -e

if [ -f .env ]; then
  export $(cat .env | sed 's/#.*//g' | xargs)
fi

# Run Doctrine migrations
echo "Running Doctrine migrations..."
docker-compose exec app php bin/console doctrine:migrations:migrate --no-interaction

# Check if seeders exist and run them
if [ -f src/DataFixtures/AppFixtures.php ]; then
  echo "Running Doctrine seeders..."
  docker-compose exec app php bin/console doctrine:fixtures:load --no-interaction
else
  echo "No seeders found."
fi

echo "Migrations and seeders completed successfully."
