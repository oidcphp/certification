#!/usr/bin/make -f

.PHONY: all check test server

# ---------------------------------------------------------------------

all: test

check:
	php vendor/bin/phpcs

# Dusk needs the app to be served on APP_URL, see `make server`
test: check
	php vendor/bin/phpunit -c phpunit.dusk.xml

server:
	php artisan serve
