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

reset_database:
	docker exec -it commercekitty_php_1 bin/console doctrine:database:drop -n -vvv --force
	@echo
	docker exec -it commercekitty_php_1 bin/console doctrine:database:create -n -vvv
	@echo
	docker exec -it commercekitty_php_1 bin/console doctrine:schema:update -n -vvv --dump-sql
	@echo
	docker exec -it commercekitty_php_1 bin/console doctrine:schema:update -n -vvv --force
	@echo
	docker exec -it commercekitty_php_1 bin/console doctrine:query:sql "CREATE EXTENSION IF NOT EXISTS \"uuid-ossp\";"
	@echo
	docker exec -it commercekitty_php_1 bin/console doctrine:fixtures:load -n -vvv
	@echo
	@echo


selfsigned:
	# Need to use a conf file with some defaults
	openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout config/docker/nginx/commercekitty.local.key -out config/docker/nginx/commercekitty.local.crt
