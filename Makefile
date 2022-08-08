SHELL := /bin/bash
.PHONY: build-images publish-images

build-images:
	cd runtime && make build-images

publish-images:
	cd runtime && make publish-images

publish-dev-images:
	cd runtime && make publish-dev-images
