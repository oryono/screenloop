#!/bin/bash
set -e

# Check if .env file exists and load environment variables
if [ -f .env ]; then
  export $(cat .env | sed 's/#.*//g' | xargs)
fi

# Function to run a command and check for errors
function run_command {
  echo "Running command: $1"
  eval "$1"
}

# Commands to execute inside the Docker container
run_command "docker-compose exec app php bin/console --env=test doctrine:database:create --if-not-exists"

run_command "docker-compose exec app php bin/console --env=test doctrine:migrations:migrate --no-interaction"

echo "Setup completed successfully."
