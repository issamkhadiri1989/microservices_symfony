start:
	docker compose up -d --no-recreate --remove-orphans

build:
	docker compose build --force-rm

ps:
	docker compose ps

stop:
	docker compose stop

down: stop
	docker compose down

enter:
	docker compose exec server bash

setup: down build
	docker compose up -d --build --force-recreate