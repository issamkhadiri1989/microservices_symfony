build:
	docker compose build --force-rm

ps:
	docker compose ps

stop:
	docker compose stop

down: stop
	docker compose down

setup: down build
	docker compose up -d --build --force-recreate

recreate: down
	docker compose up -d --force-recreate --build

# API
start-api: start-elasticsearch
	docker compose up -d --force-recreate  --remove-orphans  --build api_nginx api_server
enter-api:
	docker compose exec api_server bash

# Elasticsearch
start-elasticsearch:
	docker compose up -d elasticsearch

# WEB
start-web:
	docker compose up -d --force-recreate --remove-orphans web_nginx web_server web_mysql web_myadmin
enter-web:
	docker compose exec web_server bash
indexation-web:
	docker compose exec web_server php bin/console fos:elastica:populate
