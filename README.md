# Availability planner for groups

This is simple project with calendar function for various hobbyist groups find and plan their activity time.

More information will be provided later.

## Installation

```shell
git clone 
docker compose up -d
```

Now open: http://apfg.docker.localhost

Traefik dashboard can be accessed via: http://apfg.docker.localhost:8080

## Development

### Start Docker containers

```shell
docker compose up -d
```

### Install dependencies
```shell
docker compose exec php composer install
```

## Static code analysis:

- PHP CS Fixer:
    ```shell
    docker compose exec -e PHP_XDEBUG_MODE=off -e PHP_CS_FIXER_IGNORE_ENV=1 php vendor/bin/php-cs-fixer check -v --diff
    ```
- PHP Mess Detector
    ```shell
    docker compose exec -e PHP_XDEBUG_MODE=off php php -d error_reporting="E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED" -d display_errors=Off vendor/bin/phpmd bin,config,src,tests ansi phpmd.dist.xml
    ```
- PHPStan
    ```shell
    docker compose exec -e PHP_XDEBUG_MODE=off php vendor/bin/phpstan analyse
    ```

## Running tests

### PHPUnit

- With coverage
    ```shell
    docker compose exec -e PHP_XDEBUG_MODE=coverage -e XDEBUG_MODE=coverage php bin/phpunit
    ```
- Without coverage
    ```shell
    docker compose exec -e PHP_XDEBUG_MODE=off php bin/phpunit --no-coverage
    ```
