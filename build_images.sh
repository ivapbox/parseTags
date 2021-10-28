#!/bin/bash

set -e
set -x

if [ -z $1 ];
then
    container="all"
else
    container=$1
fi

if ! [ $container == "php-fpm" -o $container == "nginx" -o $container == "postgres" -o $container == "all" ]
then
    echo "bad params"
    exit 1
fi

DOCKER_PROJECT_NAME=app # Specify an alternate project name

if [ $container == "all" -o $container == "php-fpm" ]
then
    docker build -t ${DOCKER_PROJECT_NAME}_php-fpm -f docker/php-fpm/Dockerfile .
fi

if [ $container == "all" -o $container == "nginx" ]
then
    docker build -t ${DOCKER_PROJECT_NAME}_nginx -f docker/nginx/Dockerfile .
fi

if [ $container == "all" -o $container == "postgres" ]
then
    docker build -t ${DOCKER_PROJECT_NAME}_postgres -f docker/postgres/Dockerfile .
fi
