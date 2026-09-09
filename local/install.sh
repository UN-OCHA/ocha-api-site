#!/usr/bin/env bash

set -e -u

# Usage.
usage() {
  echo "Usage: ./local/install.sh [OPTIONS]" >&2
  echo "-h                    : Display usage" >&2
  echo "-m                    : Create local image" >&2
  echo "-i                    : Install database (migrations + fixtures)" >&2
  echo "-d                    : Install dev dependencies" >&2
  echo "-u                    : Pull latest service and base images and recreate containers" >&2
  echo "-s                    : Stop the site containers" >&2
  echo "-x                    : Shutdown and remove the site containers" >&2
  echo "-v                    : Also remove the volumes when shutting down the containers" >&2
  exit 1
}

create_image="no"
install_site="no"
install_dev_dependencies="no"
update="no"
stop="no"
shutdown="no"
shutdown_options=""

# Parse options.
while getopts "hmidusxv" opt; do
  case $opt in
    h)
      usage
      ;;
    m)
      create_image="yes"
      ;;
    i)
      install_site="yes"
      ;;
    d)
      install_dev_dependencies="yes"
      ;;
    u)
      update="yes"
      ;;
    s)
      stop="yes"
      ;;
    x)
      shutdown="yes"
      ;;
    v)
      shutdown_options="$shutdown_options -v"
      ;;
    *)
      usage
      ;;
  esac
done

function docker_compose {
  docker compose -f local/docker-compose.yml "$@"
}

# Load the environment variables.
# They are only available in this script as we don't export them.
source local/.env

# Stop the containers.
if [ "$stop" = "yes" ]; then
  echo "Stop the containers."
  docker_compose stop || true
  exit 0
fi

# Stop and remove the containers.
if [ "$shutdown" = "yes" ]; then
  echo "Stop and remove the containers."
  docker_compose down $shutdown_options || true
  exit 0
fi

# Update the image.
if [ "$update" = "yes" ]; then
  echo "Pull service images."
  docker_compose pull --ignore-pull-failures
  echo "Pull base site image."
  docker pull "$(grep -E -o "FROM ([^ ]+)$" docker/Dockerfile | awk '{print $2}')"
  create_image="yes"
fi;

# Build local image.
if [ "$create_image" = "yes" ]; then
  echo "Build local image."
  make IMAGE_REGISTRY=$IMAGE_REGISTRY IMAGE_NAME=$IMAGE_NAME IMAGE_TAG=$IMAGE_TAG
fi;

# Create the site and mysql containers.
echo "Create the site and mysql containers."
docker_compose up -d --remove-orphans

# Wait a bit for mysql to be ready.
echo "Wait a bit for mysql to be ready."
sleep 10

# Dump some information about the created containers.
echo "Dump some information about the created containers."
docker_compose ps -a

# Ensure var directories are writable.
echo "Ensure the var directories are writable."
docker_compose exec site mkdir -p /srv/www/var/cache /srv/www/var/log /srv/www/var/sessions
docker_compose exec site chown -R appuser:appuser /srv/www/var

# Install the site database.
if [ "$install_site" = "yes" ]; then
  echo "Create the database if needed."
  docker_compose exec -w /srv/www -u appuser site ./bin/console doctrine:database:create --if-not-exists -n

  echo "Run doctrine migrations."
  docker_compose exec -w /srv/www -u appuser site ./bin/console doctrine:migrations:migrate -n

  echo "Initialize netbrothers version tables."
  docker_compose exec -w /srv/www -u appuser site ./bin/console netbrothers:version -n || true

  echo "Load fixtures."
  docker_compose exec -w /srv/www -u appuser site ./bin/console hautelook:fixtures:load -n

  echo "Clear the cache."
  docker_compose exec -w /srv/www -u appuser site ./bin/console cache:clear
fi

# Install the dev dependencies.
if [ "$install_dev_dependencies" = "yes" ]; then
  echo "Install the dev dependencies."
  docker_compose exec -w /srv/www site composer install

  echo "Clear the cache."
  docker_compose exec -w /srv/www -u appuser site ./bin/console cache:clear
fi
