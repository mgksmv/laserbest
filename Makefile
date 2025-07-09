COMPOSE_FILE = docker-compose.yml

CONFIRM = \
	read -p "⚠️ Вы уверены? [y/N] " ans; \
	if [ "$$ans" != "y" ] && [ "$$ans" != "Y" ]; then \
		echo "Команда прервана."; \
		exit 1; \
	fi


init:
	@file=$$(./docker/choose-env.sh) || exit 1; \
	if [ "$$file" = "docker-compose.yml" ]; then \
		init_target=app-init; \
	elif [ "$$file" = "docker-compose.ip.yml" ] || [ "$$file" = "docker-compose.prod.yml" ]; then \
		init_target=prod-app-init; \
	fi; \
	$(CONFIRM); \
	$(MAKE) COMPOSE_FILE="$$file" docker-down-clear app-clear docker-up-build "$$init_target"

up:
	@file=$$(./docker/choose-env.sh) || exit 1; \
	$(MAKE) COMPOSE_FILE="$$file" docker-up
down:
	@file=$$(./docker/choose-env.sh) || exit 1; \
	$(MAKE) COMPOSE_FILE="$$file" docker-down
down-clear:
	@file=$$(./docker/choose-env.sh) || exit 1; \
	$(CONFIRM); \
	$(MAKE) COMPOSE_FILE="$$file" docker-down-clear
restart:
	@file=$$(./docker/choose-env.sh) || exit 1; \
	$(MAKE) COMPOSE_FILE="$$file" docker-up
build:
	@file=$$(./docker/choose-env.sh) || exit 1; \
	if [ "$$file" = "docker-compose.yml" ]; then \
		init_target=app-init; \
	elif [ "$$file" = "docker-compose.ip.yml" ] || [ "$$file" = "docker-compose.prod.yml" ]; then \
		init_target=prod-app-init; \
	fi; \
	$(MAKE) COMPOSE_FILE="$$file" docker-down docker-up-build "$$init_target"

update-deps: app-composer-update app-npm-update restart

docker-up:
	docker compose -f $(COMPOSE_FILE) up -d

docker-up-build:
	docker compose -f $(COMPOSE_FILE) up -d --build --remove-orphans

docker-down:
	docker compose -f $(COMPOSE_FILE) down --remove-orphans

docker-down-clear:
	docker compose -f $(COMPOSE_FILE) down -v --remove-orphans

docker-pull:
	docker compose -f $(COMPOSE_FILE) pull

docker-build:
	docker compose -f $(COMPOSE_FILE) build --pull


app-init: app-composer-install app-wait-db app-migrate app-generate-key app-storage-link app-set-permissions app-npm-install app-node-ready
prod-app-init: app-composer-install app-wait-db app-migrate app-generate-key app-storage-link app-set-permissions app-npm-install app-npm-build

app-clear:
	docker run --rm -v ${PWD}:/app -w /app alpine sh -c \
		'rm -rf storage/framework/cache/data/* storage/framework/sessions/* storage/framework/testing/* storage/framework/views/* storage/logs/*'
	docker run --rm -v ${PWD}:/app -w /app alpine sh -c 'rm -rf .ready build'

app-set-permissions:
	docker compose run --rm app-php-fpm chmod 777 -R storage bootstrap/cache

app-node-ready:
	docker compose run --rm app-node touch .ready

app-composer-install:
	docker compose run --rm app-php-fpm composer install

app-composer-update:
	docker compose run --rm app-php-fpm composer update

app-npm-install:
	docker compose run --rm app-node npm install

app-npm-update:
	docker compose run --rm app-node npm update

app-npm-build:
	docker compose run --rm app-node npm run build

app-wait-db:
	docker compose run --rm app-php-fpm wait-for-it db:3306 -t 30

app-migrate:
	docker compose run --rm app-php-fpm php artisan migrate --force

app-generate-key:
	docker compose run --rm app-php-fpm php artisan key:generate

app-storage-link:
	docker compose run --rm app-php-fpm php artisan storage:link
