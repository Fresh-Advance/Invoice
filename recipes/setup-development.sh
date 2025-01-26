#!/bin/bash
# Flags possible:
# -e for shop edition. Possible values: CE/PE/EE

edition='CE'
while getopts e: flag; do
  case "${flag}" in
  e) edition=${OPTARG} ;;
  *) ;;
  esac
done

SCRIPT_PATH=$(dirname ${BASH_SOURCE[0]})

cd $SCRIPT_PATH/../../ || exit

# Prepare services configuration
make setup
make addbasicservices
make file=services/selenium-chrome.yml addservice

# Configure containers
perl -pi\
  -e 's#error_reporting = .*#error_reporting = E_ALL ^ E_WARNING ^ E_DEPRECATED#g;'\
  containers/php-fpm/custom.ini

perl -pi\
  -e 's#/var/www/#/var/www/source/#g;'\
  containers/httpd/project.conf

perl -pi\
  -e 's#PHP_VERSION=.*#PHP_VERSION=8.1#g;'\
  .env

mkdir source
docker compose up --build -d php

git clone https://github.com/Fresh-Advance/Invoice.git ./source -b b-7.0.x

$SCRIPT_PATH/parts/shared/require_twig_components.sh -e"CE" -b"b-7.0.x"
$SCRIPT_PATH/parts/shared/require.sh -n"oxid-esales/twig-theme" -v"dev-b-7.0.x"
$SCRIPT_PATH/parts/shared/require_demodata_package.sh -e"CE" -b"b-7.0.x"

docker compose exec php composer update --no-interaction

make up

$SCRIPT_PATH/parts/shared/setup_database.sh

docker compose exec -T php vendor/bin/oe-console oe:module:install ./

docker compose exec -T php vendor/bin/oe-console oe:module:activate fa_invoice
docker compose exec -T php vendor/bin/oe-console oe:theme:activate twig

$SCRIPT_PATH/parts/shared/create_admin.sh
