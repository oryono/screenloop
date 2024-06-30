#!/bin/bash
set -e

if [ -f .env ]; then
  export $(cat .env | sed 's/#.*//g' | xargs)
fi

# Check if seeders exist and run them
if [ -f src/DataFixtures/AppFixtures.php ]; then
  echo "Running Doctrine seeders..."
  docker-compose exec app php bin/console doctrine:fixtures:load --no-interaction
else
  echo "No seeders found."
fi

echo "Seeders  completed successfully."