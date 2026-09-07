#!/usr/bin/env bash

set -euo pipefail

IMAGE_REGISTRY="${IMAGE_REGISTRY:-532768535361.dkr.ecr.us-east-1.amazonaws.com}"
IMAGE_NAME="${IMAGE_NAME:-ocha-api-site-test}"
IMAGE_TAG="${IMAGE_TAG:-test}"
COMPOSE="docker compose -f tests/docker-compose.yml"
SKIP_CLEANUP=false
CLEANUP_ONLY=false

show_help() {
  cat << EOF
Usage: $0 [OPTIONS]

Run the OCHA API test suite in Docker.

OPTIONS:
    -h, --help           Show this help message
    -c, --cleanup-only   Cleanup containers and images, then exit
    -k, --skip-cleanup   Skip container/image cleanup (useful for debugging)

EXAMPLES:
    $0
    $0 --skip-cleanup
    $0 --cleanup-only

EOF
}

while [[ $# -gt 0 ]]; do
  case $1 in
    -h|--help)
      show_help
      exit 0
      ;;
    -c|--cleanup-only)
      CLEANUP_ONLY=true
      shift
      ;;
    -k|--skip-cleanup)
      SKIP_CLEANUP=true
      shift
      ;;
    *)
      echo "Unknown option: $1"
      show_help
      exit 1
      ;;
  esac
done

function cleanup() {
  echo "Removing test containers"
  $COMPOSE down -v || true
  echo "Removing test images"
  docker rmi "$IMAGE_REGISTRY/$IMAGE_NAME:$IMAGE_TAG" || true
}

if [ "$CLEANUP_ONLY" = true ]; then
  cleanup
  exit 0
fi

# Halt the whole script on Ctrl+C or SIGTERM.
# Run cleanup on interrupt if not skipped, then exit with 130 (SIGINT convention).
exit_on_signal() {
  echo ""
  echo "Interrupted. Stopping test script."
  [ "$SKIP_CLEANUP" = false ] && cleanup
  exit 130
}
trap exit_on_signal INT TERM

# Remove previous containers (unless skipped).
if [ "$SKIP_CLEANUP" = false ]; then
  cleanup
  trap cleanup EXIT
else
  echo "Skipping container/image cleanup (--skip-cleanup flag used)"
fi

# Build local image.
echo "Build local image."
make IMAGE_REGISTRY="$IMAGE_REGISTRY" IMAGE_NAME="$IMAGE_NAME" IMAGE_TAG="$IMAGE_TAG"

# Create the site and mysql containers.
echo "Create the site and mysql containers."
IMAGE_REGISTRY="$IMAGE_REGISTRY" IMAGE_NAME="$IMAGE_NAME" IMAGE_TAG="$IMAGE_TAG" $COMPOSE up -d

# Dump some information about the created containers.
echo "Dump some information about the created containers."
$COMPOSE ps -a

# Wait a bit for mysql to be ready.
echo "Wait a bit for mysql to be ready."
sleep 5

# Validate PHP files.
echo "Validate PHP files."
$COMPOSE exec -w /srv/www -T site sh -c \
  'test ! -d ./src || find -L ./src -name "*.php" -print0 | xargs -0 -n 1 -P 4 php -l'

# Install the test environment.
echo "Install the test environment."
$COMPOSE exec -w /srv/www -T site ./bin/console doctrine:database:drop --force
$COMPOSE exec -w /srv/www -T site ./bin/console doctrine:database:create --if-not-exists -n
$COMPOSE exec -w /srv/www -T site ./bin/console doctrine:schema:create -n
$COMPOSE exec -w /srv/www -T site ./bin/console netbrothers:version --drop-version -n
$COMPOSE exec -w /srv/www -T site ./bin/console netbrothers:version -n
$COMPOSE exec -w /srv/www -T site ./bin/console hautelook:fixtures:load -n
$COMPOSE exec -w /srv/www -T site ./bin/console cache:clear

# Lint Symfony config.
echo "Lint Symfony config."
$COMPOSE exec -w /srv/www -T site ./bin/console lint:yaml config --parse-tags
$COMPOSE exec -w /srv/www -T site ./bin/console lint:twig templates --env=test
$COMPOSE exec -w /srv/www -T site ./bin/console lint:xliff translations
$COMPOSE exec -w /srv/www -T site ./bin/console lint:container
$COMPOSE exec -w /srv/www -T site ./bin/console doctrine:schema:validate --skip-sync -vvv --no-interaction

# Run unit tests.
echo "Run unit tests."
$COMPOSE exec -T site mkdir -p /srv/www/html/build/logs
$COMPOSE exec -T site chmod -R 777 /srv/www/html/build/logs
$COMPOSE exec -T site mkdir -p /srv/www/coverage
$COMPOSE exec -T site chmod -R 777 /srv/www/coverage
$COMPOSE exec -u appuser -T -w /srv/www -e XDEBUG_MODE=coverage site \
  ./bin/phpunit --coverage-clover /srv/www/html/build/logs/clover.xml \
  --cache-result-file /tmp/phpunit-result-cache
