SHELL := sh
.DEFAULT_GOAL := install

/usr/local/bin/lando:
	@echo "You need to install lando first!"
	@echo "See how-to: https://docs.lando.dev/basics/installation.html"
	@echo
	@exit 1

.PHONY: lando
lando: /usr/local/bin/lando

.PHONY: install
install: vendor

.PHONY: test
test: lando
	lando composer test

vendor: lando
	lando composer install
