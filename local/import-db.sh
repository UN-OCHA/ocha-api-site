#!/usr/bin/env bash

set -e -u

# Usage.
usage() {
  echo "Usage: ./local/import-db.sh [<dump.sql|.sql.gz>]" >&2
  echo "       ./local/import-db.sh < dump.sql" >&2
  echo "" >&2
  echo "Replace the local database with a SQL dump. Run from the repository root." >&2
  echo "Accepts a file path, a .sql.gz path, or SQL on stdin." >&2
  exit 1
}

if [ "${1:-}" = "-h" ] || [ "${1:-}" = "--help" ]; then
  usage
fi

function docker_compose {
  docker compose -f local/docker-compose.yml "$@"
}

# Load the environment variables.
# They are only available in this script as we don't export them.
source local/.env

if [ $# -eq 0 ] && [ -t 0 ]; then
  usage
fi

echo "Drop the local database."
docker_compose exec -w /srv/www -u appuser site ./bin/console doctrine:database:drop --force

echo "Create the local database."
docker_compose exec -w /srv/www -u appuser site ./bin/console doctrine:database:create --if-not-exists -n

echo "Import the dump."
if [ $# -ge 1 ]; then
  if [ ! -f "$1" ]; then
    echo "File not found: $1" >&2
    exit 1
  fi
  case "$1" in
    *.gz)
      gunzip -c "$1" | docker_compose exec -T mysql mysql -u"$MYSQL_USER" -p"$MYSQL_PASS" "$MYSQL_DB"
      ;;
    *)
      docker_compose exec -T mysql mysql -u"$MYSQL_USER" -p"$MYSQL_PASS" "$MYSQL_DB" < "$1"
      ;;
  esac
else
  docker_compose exec -T mysql mysql -u"$MYSQL_USER" -p"$MYSQL_PASS" "$MYSQL_DB"
fi

echo "Clear the cache."
docker_compose exec -w /srv/www -u appuser site ./bin/console cache:clear

echo "Database import complete."
