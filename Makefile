# Variables
CODE_COVERAGE_DIR=docs/coverage

help:
	@echo "Please run 'make <target>' where <target> is one of:"
	@echo "  lint      Checks syntax of files and configuration"
	@echo "  test      Runs the testsuite for both unit and functional tests"
	@echo "  coverage  Generates Code Coverage report"

lint:
	@echo
	@echo "    -=[ container ]=-"
	@echo
	@php bin/console lint:container -vv -n
	@echo
	@echo "    -=[ twig ]=-"
	@echo
	@php bin/console lint:twig -vvv -n templates/
	@echo
	@echo "    -=[ xliff ]=-"
	@echo
	@php bin/console lint:xliff -vvv -n translations/
	@echo
	@echo "    -=[ yaml ]=-"
	@echo
	@php bin/console lint:yaml -vvv -n config/

test:
	@echo "@todo"

coverage:
	@echo "@todo"

# --- Hidden ---

selfsigned:
	# Need to use a conf file with some defaults
	openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout config/docker/nginx/commercekitty.local.key -out config/docker/nginx/commercekitty.local.crt
