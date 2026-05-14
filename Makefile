.PHONY: setup dev test lint format check docker-up docker-down optimize clear

setup:
	composer setup

dev:
	composer dev

test:
	composer test

lint:
	composer lint

format:
	composer format

check:
	composer check

optimize:
	php artisan optimize

clear:
	php artisan optimize:clear

docker-up:
	docker compose up --build

docker-down:
	docker compose down
