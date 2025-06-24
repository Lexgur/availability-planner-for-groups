include .env

.PHONY: up down stop prune ps shell logs composer console php-cs-fixer phpmd phpstan phpunit

default: up

## help	:	Print commands help.
help : docker.mk
	@sed -n 's/^##//p' $<

## up	:	Start up containers.
up:
	@echo "Starting up containers for for $(PROJECT_NAME)..."
	docker compose pull
	docker compose up -d --remove-orphans

## down	:	Stop containers.
down: stop

## start	:	Start containers without updating.
start:
	@echo "Starting containers for $(PROJECT_NAME) from where you left off..."
	@docker compose start

## stop	:	Stop containers.
stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	@docker compose stop

## prune	:	Remove containers and their volumes.
##		You can optionally pass an argument with the service name to prune single container
##		prune mariadb	: Prune `mariadb` container and remove its volumes.
##		prune mariadb solr	: Prune `mariadb` and `solr` containers and remove their volumes.
prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	@docker compose down -v $(filter-out $@,$(MAKECMDGOALS))

## ps	:	List running containers.
ps:
	@docker ps --filter name='$(PROJECT_NAME)*'

## shell	:	Access `php` container via shell.
##		You can optionally pass an argument with a service name to open a shell on the specified container
shell:
	docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(PROJECT_NAME)_$(or $(filter-out $@,$(MAKECMDGOALS)), 'php')' --format "{{ .ID }}") bash

## logs	:	View containers logs.
##		You can optinally pass an argument with the service name to limit logs
##		logs php	: View `php` container logs.
##		logs nginx php	: View `nginx` and `php` containers logs.
logs:
	@docker compose logs -f $(filter-out $@,$(MAKECMDGOALS))

composer:
	@docker compose exec -e PHP_XDEBUG_MODE=off php composer $(filter-out $@,$(MAKECMDGOALS))

console:
	@docker compose exec php bin/console $(filter-out $@,$(MAKECMDGOALS))

php-cs-fixer:
	@docker compose exec -e PHP_XDEBUG_MODE=off -e PHP_CS_FIXER_IGNORE_ENV=1 php vendor/bin/php-cs-fixer check -v --diff

phpmd:
	@docker compose exec -e PHP_XDEBUG_MODE=off php php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED" -d display_errors=Off vendor/bin/phpmd bin,config,src,tests ansi phpmd.dist.xml

phpstan:
	@docker compose exec -e PHP_XDEBUG_MODE=off php vendor/bin/phpstan analyse

phpunit:
	@docker compose exec -e PHP_XDEBUG_MODE=coverage -e XDEBUG_MODE=coverage php bin/phpunit $(filter-out $@,$(MAKECMDGOALS))

# https://stackoverflow.com/a/6273809/1826109
%:
	@:
