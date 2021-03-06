COLOR_BLACK=\033[30m
COLOR_RED=\033[31m
COLOR_GREEN=\033[32m
COLOR_YELLOW=\033[33m
COLOR_BLUE=\033[34m
COLOR_MAGENTA=\033[35m
COLOR_CYAN=\033[36m
COLOR_WHITE=\033[37m
COLOR_RESET=\033[m

help:
	@echo "Commerce Kitty (https://commercekitty.com)"
	@echo ""
	@echo "$(COLOR_YELLOW)Usage:$(COLOR_RESET)"
	@echo "  make $(COLOR_GREEN)target$(COLOR_RESET)"
	@echo ""
	@echo "$(COLOR_YELLOW)Available targets:$(COLOR_RESET)"
	@echo "  $(COLOR_GREEN)init$(COLOR_RESET)              Installs deps, compiles assets, spins up docker, and configures database (destructive)"
	@echo "  $(COLOR_GREEN)install$(COLOR_RESET)           Install composer and node dependencies"
	@echo "  $(COLOR_GREEN)up$(COLOR_RESET)                Spins up containers"
	@echo "  $(COLOR_GREEN)stop$(COLOR_RESET)              Stops containers"
	@echo "  $(COLOR_GREEN)clean$(COLOR_RESET)             Clears cache and temp files"
	@echo "  $(COLOR_GREEN)worker$(COLOR_RESET)            Runs wokers in the foreground"
	@echo "  $(COLOR_GREEN)compile.dev$(COLOR_RESET)       Compile assets"
	@echo "  $(COLOR_GREEN)compile.prod$(COLOR_RESET)      Compile assets"
	@echo "  $(COLOR_GREEN)db.fixtures$(COLOR_RESET)       Loads DB Fixtures (destructive)"
	@echo "  $(COLOR_GREEN)db.migrate$(COLOR_RESET)        Runs database migrations"
	@echo "  $(COLOR_GREEN)docs.coverage$(COLOR_RESET)     Generates HTML Code Coverage report"
	@echo " $(COLOR_YELLOW)lint$(COLOR_RESET)              Runs all lint targets"
	@echo "  $(COLOR_GREEN)lint.container$(COLOR_RESET)    Check container"
	@echo "  $(COLOR_GREEN)lint.twig$(COLOR_RESET)         Check twig files"
	@echo "  $(COLOR_GREEN)lint.xliff$(COLOR_RESET)        Check translations"
	@echo "  $(COLOR_GREEN)lint.php$(COLOR_RESET)          Check php files"
	@echo " $(COLOR_YELLOW)tests$(COLOR_RESET)             Runs the testsuite for both unit and functional tests"
	@echo "  $(COLOR_GREEN)tests.unit$(COLOR_RESET)        Runs unit tests"
	@echo "  $(COLOR_GREEN)tests.functional$(COLOR_RESET)  Runs functional tests"

init: install compile.dev up db.init db.fixtures
	@echo "To access the app, please open your browser to ${COLOR_BLUE}https://127.0.0.1${COLOR_RESET}"
	@echo ""
	@echo "Default Login Credentials"
	@echo "      Email:    ${COLOR_BLUE}kitty@commercekitty.com${COLOR_RESET}"
	@echo "      Password: ${COLOR_BLUE}password123${COLOR_RESET}"
	@echo ""
	@echo "Run '${COLOR_GREEN}make stop${COLOR_RESET}' to spin everything down"
	@echo ""

compile.dev:
	@echo " -=[ ${COLOR_BLUE}Compiling Assets${COLOR_RESET} ]=-"
	@echo ""
	yarn encore dev
	@echo ""
	@echo " -=[ ${COLOR_GREEN}Complete${COLOR_RESET} ]=-"
	@echo ""

compile.prod:
	yarn encore production

clean:
	php bin/console cache:clear -vvv -n --no-warmup

install:
	@echo " -=[ ${COLOR_BLUE}Install deps using 'composer'${COLOR_RESET} ]=-"
	@echo ""
	composer install
	@echo ""
	@echo " -=[ ${COLOR_GREEN}Complete${COLOR_RESET} ]=-"
	@echo ""
	@echo ""
	@echo " -=[ ${COLOR_BLUE}Install deps using 'yarn'${COLOR_RESET} ]=-"
	@echo ""
	yarn install
	@echo ""
	@echo " -=[ ${COLOR_GREEN}Complete${COLOR_RESET} ]=-"
	@echo ""

up:
	@echo " -=[ ${COLOR_BLUE}Spinning up containers${COLOR_RESET} ]=-"
	@echo ""
	docker-compose up -d --remove-orphans
	@echo ""
	@echo " -=[ ${COLOR_GREEN}Complete${COLOR_RESET} ]=-"
	@echo ""

stop:
	docker-compose stop

lint: lint.container lint.twig lint.xliff lint.php

lint.container:
	php bin/console lint:container -vvv -n

lint.twig:
	php bin/console lint:twig -vvv -n templates/

lint.xliff:
	php bin/console lint:xliff -vvv -n translations/

lint.yaml:
	php bin/console lint:yaml -vvv -n config/

lint.php:
	find bin/ bundles/ components/ config/ migrations/ public/ src/ tests/ -name "*.php" -print0 | xargs -0 -n1 -P0 php -l

.PHONY: tests
tests: tests.unit tests.functional

tests.unit:
	find tests/unit -name "*Test.php" | php vendor/bin/fastest -vvv "vendor/bin/phpunit {};"

tests.functional:
	php bin/console doctrine:database:create -vvv -n --env=test
	php bin/console doctrine:schema:update -vvv -n --force --env=test
	find tests/functional -name "*Test.php" | php vendor/bin/fastest -vvv "vendor/bin/phpunit {};"
	php bin/console doctrine:database:drop -vvv -n --force --env=test

coverage:
	mkdir -vp docs/coverage var/log/build var/build
	find tests/unit/ -name "*Test.php" | php vendor/bin/fastest -vvv "vendor/bin/phpunit {} --coverage-php var/build/unit-{n}.cov;"
	php bin/console doctrine:database:create --env=test -vvv -n
	php bin/console doctrine:schema:update --env=test -vvv -n --force
	find tests/functional/ -name "*Test.php" | php vendor/bin/fastest -vvv "vendor/bin/phpunit {} --coverage-php var/build/functional-{n}.cov;"
	php bin/console doctrine:database:drop --env=test -vvv -n --force
	php vendor/bin/phpcov merge var/log/build/coverage --html docs/coverage
	rm -rf var/build/*.cov

worker:
	docker exec -it commercekitty_php_1 bin/console messenger:consume -n -vvv --limit=100 --time-limit=360 commands events

db.init:
	@echo " -=[ ${COLOR_BLUE}Initializing database${COLOR_RESET} ]=-"
	@echo ""
	docker exec -it commercekitty_php_1 bin/console doctrine:database:drop -n -vvv --force --if-exists
	docker exec -it commercekitty_php_1 bin/console doctrine:database:create -n -vvv --if-not-exists
	@# @todo Make this run migrations
	#docker exec -it commercekitty_php_1 bin/console doctrine:schema:update -n -vvv --dump-sql --force
	make db.schema.update
	@echo ""
	@echo " -=[ ${COLOR_GREEN}Complete${COLOR_RESET} ]=-"
	@echo ""

db.schema.update:
	docker exec -it commercekitty_php_1 bin/console doctrine:schema:update -n -vvv --dump-sql --force

db.fixtures:
	@echo " -=[ ${COLOR_BLUE}Loading data into database${COLOR_RESET} ]=-"
	@echo ""
	docker exec -it commercekitty_php_1 bin/console doctrine:fixtures:load -n -vvv
	@echo ""
	@echo " -=[ ${COLOR_GREEN}Complete${COLOR_RESET} ]=-"
	@echo ""

db.migrate:
	docker exec -it commercekitty_php_1 bin/console doctrine:migrations:migrate -n -vvv

# ---

selfsigned:
	# Need to use a conf file with some defaults
	openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout config/docker/nginx/commercekitty.local.key -out config/docker/nginx/commercekitty.local.crt
