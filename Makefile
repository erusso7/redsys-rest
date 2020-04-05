.PHONY: help install test check-lint fix-lint pre-commit-hook
help:
	@echo 'These are the available commands:'
	@echo '-- help			To print this help menu.'
	@echo '-- install		To all the dependencies.'
	@echo '-- test			To run all the tests locally.'
	@echo '-- check-lint		To perform and analysis to the code style.'
	@echo '-- fix-lint		To fix some issues found in the code style.'
	@echo '-- pre-commit-hook	To execute manually the pre-commit hook.'

install:
	@composer install

test:
	@php ./vendor/bin/phpunit

check-lint:
	@php ./vendor/bin/phpcs
	@php ./vendor/bin/phpstan analyse -c ./phpstan.neon

fix-lint:
	@php ./vendor/bin/phpcbf

pre-commit-hook: check-lint test
