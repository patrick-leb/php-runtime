SHELL := /bin/bash
.PHONY: build-images

build-images:
	# Build base Docker image
	cd base ; docker build -t woogo/runtime/base .
	# Build PHP Docker images
	cd php-80 ; docker build -t woogo/runtime/php-80 .
	cd php-81 ; docker build -t woogo/runtime/php-81 .

publish-images: publish-php-80 publish-php-81

publish-dev-images: publish-dev-php-72 publish-dev-php-73 publish-dev-php-74 publish-dev-php-80 publish-dev-php-81

publish-php%: build-images
	PHP_VERSION=$$(echo $@ | cut -d'.' -f 1 | cut -d'-' -f 2,3); \
    docker image tag woogo/runtime/$$PHP_VERSION:latest woogostores/php-runtime:$$PHP_VERSION; \
    docker image push woogostores/php-runtime:$$PHP_VERSION
