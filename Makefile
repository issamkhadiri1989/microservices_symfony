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
	docker compose up -d --remove-orphans api_nginx api_server
enter-api:
	docker compose exec api_server bash

# Elasticsearch
start-elasticsearch:
	docker compose up -d elasticsearch

# WEB
start-mysql:
	docker compose up -d --force-recreate --remove-orphans web_mysql web_myadmin
start-server:
	docker compose up -d --force-recreate --remove-orphans  web_nginx web_server
start-web: start-server start-mysql
enter-web:
	docker compose exec web_server bash

# USER
start-user-database:
	docker compose up -d --force-recreate --remove-orphans user_mysql user_myadmin
start-user-server:
	docker compose up -d --force-recreate --remove-orphans user_web_nginx user_server
start-user: start-user-database start-user-server
enter-user:
	docker compose exec user_server bash

indexation-web:
	docker compose exec web_server php bin/console fos:elastica:populate

start-storage:
	docker compose up -d minio


#### start the main client #### 
start-client:
	docker compose up -d client_proxy client client_database client_database_admin
enter:
	docker compose exec client bash
