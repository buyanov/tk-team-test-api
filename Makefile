#!/usr/bin/make
# Makefile readme (ru): <http://linux.yaroslavl.ru/docs/prog/gnu_make_3-79_russian_manual.html>
# Makefile readme (en): <https://www.gnu.org/software/make/manual/html_node/index.html#SEC_Contents>

dc_bin := $(shell command -v docker-compose 2> /dev/null)

SHELL = /bin/sh
RUN_APP_ARGS = --rm app

.PHONY : help build install up down shell test migrate cs init seed index import logs
.DEFAULT_GOAL : help

# This will output the help for each task. thanks to https://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
help: ## Show this help
	@printf "\033[33m%s:\033[0m\n" 'Available commands'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[32m%-14s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

build: ## Build docker images, required for current package environment
	$(dc_bin) build

install: ## Install all app dependencies
	$(dc_bin) run $(RUN_APP_ARGS) composer install --no-interaction --ansi --no-suggest --prefer-dist

up: ## Create and start containers
	$(dc_bin) up --detach

down: ## Stop and remove containers, networks, images, and volumes
	$(dc_bin) down -t 5

shell: ## Start shell into app container
	$(dc_bin) exec app ${SHELL}

test: ## Execute app tests
	$(dc_bin) exec app php artisan test

cs: ## Execute app codestyle checker
	$(dc_bin) exec app composer cs

clean: ## Execute app cache clear
	$(dc_bin) exec app php artisan optimize:clear

migrate: ## Execute app database migrate
	$(dc_bin) exec app php artisan migrate

seed: ## Run database seeders
	$(dc_bin) exec app php artisan db:seed

index: ## Create elastic index
	$(dc_bin) exec app php artisan elastic:migrate

import: ## Import models into Elasticsearch
	$(dc_bin) exec app php artisan scout:import "App\Task"

init: ## Execute app database migrate, seed, create admin, elastic create index
	$(dc_bin) exec app php artisan migrate
	$(dc_bin) exec app php artisan db:seed
	$(dc_bin) exec app php artisan elastic:migrate
	$(dc_bin) exec app php artisan scout:import "App\Task"

logs: ## Show docker logs
	$(dc_bin) logs --follow
