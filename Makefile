.PHONY: build
build:
	docker compose build

.PHONY: up
up:
	docker compose up -d

.PHONY: init
init:
	cp .env.example .env
	@make build
	@make up
	docker compose exec app composer install
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate:fresh --seed

.PHONY: down
down:
	docker compose down

.PHONY: refresh
refresh: down up

.PHONY: app
app:
	docker compose exec app bash

.PHONY: test-user-store-ok
test-user-store-ok:
	docker compose exec app php artisan test ./tests/Feature/UserStoreTest.php --filter 管理者がユーザー情報を正常に登録できる

.PHONY: test-user-me
test-user-me:
	docker compose exec app php artisan test ./tests/Feature/UserShowMeTest.php

.PHONY: test-user-store-ng
test-user-store-ng:
	docker compose exec app php artisan test ./tests/Feature/UserStoreTest.php --filter 入力値が不正な場合はバリデーションエラーレスポンスを返す


.PHONY: migrate
migrate:
	docker compose exec app php artisan migrate

.PHONY: db-fresh
db-fresh:
	docker compose exec app php artisan migrate:fresh --seed

.PHONY: tinker
tinker:
	docker compose exec app php artisan tinker

.PHONY: clean
clean:
	docker compose down --rmi all --volumes --remove-orphans
