#!/bin/make

include .env.docker
export

COMPOSE_PROJECT = $(shell basename $(CURDIR) | tr '[:upper:]' '[:lower:]' | sed 's/ /_/g')

ps:
	@docker ps -a --filter "label=com.docker.compose.project=${COMPOSE_PROJECT}";
	@exit 0;

logs\:%:
	@CONTAINER=$$(docker ps -q -a --filter "label=com.docker.compose.project=${COMPOSE_PROJECT}" --filter "label=com.docker.compose.service=$*"); \
	CONTAINER_EXISTS=$$(docker ps -q -a --filter "label=com.docker.compose.project=${COMPOSE_PROJECT}" --filter "label=com.docker.compose.service=$*" | wc -l); \
	if [ $$CONTAINER_EXISTS -eq 1 ]; then \
		tput setaf 3; /bin/echo "Logs $*"; tput sgr0; \
		docker logs $$CONTAINER -f; \
	else \
		tput setaf 1; /bin/echo "Service $* not exists"; tput sgr0; \
	fi;

	@exit 0;

login\:%:
	@CONTAINER=$$(docker ps -q -a --filter "label=com.docker.compose.project=${COMPOSE_PROJECT}" --filter "label=com.docker.compose.service=$*"); \
	CONTAINER_EXISTS=$$(docker ps -q -a --filter "label=com.docker.compose.project=${COMPOSE_PROJECT}" --filter "label=com.docker.compose.service=$*" | wc -l); \
	if [ $$CONTAINER_EXISTS -eq 1 ]; then \
		tput setaf 3; /bin/echo "Entering into $*"; tput sgr0; \
		docker exec -i $$CONTAINER sh -c 'if command -v bash &> /dev/null; then bash; else sh; fi'; \
	else \
		tput setaf 1; /bin/echo "Service $* not exists"; tput sgr0; \
	fi;

	@exit 0;

bash\:%:
	@tput setaf 3; /bin/echo "Starting $* container"; tput sgr0;
	@docker compose -p ${COMPOSE_PROJECT} --env-file ./.env.docker -f ./docker/${TEMPLATE}/docker-compose.dev.yml run --rm --build --tty $* sh -c 'if command -v bash &> /dev/null; then bash; else sh; fi';
	@exit 0;

clean:
	@CONTAINERS=$$(docker ps -q -a --filter "label=com.docker.compose.project=${COMPOSE_PROJECT}" | wc -l); \
	if [ $$CONTAINERS -ge 1 ]; then \
		tput setaf 1; tput bold; tput smul; \
			/bin/echo "Removing all containers:"; \
		tput sgr0; \
		tput setaf 1; \
			docker ps -q -a --filter "label=com.docker.compose.project=${COMPOSE_PROJECT}" | while read -r CONTAINER_ID; do \
				CONTAINER_NAME=$$(docker inspect --format '{{.Name}}' $$CONTAINER_ID | tr -d '/'); \
				/bin/echo -n "$$CONTAINER_NAME - ";\
				docker rm -f -v $$CONTAINER_ID; \
			done; \
		tput sgr0; \
	fi;
	@exit 0;

ifdef TEMPLATE
ifdef ENV
start:
	@tput setaf 3; tput bold; tput smul; /bin/echo "Building ${ENV} environment:"; tput sgr0;
	@docker compose -p ${COMPOSE_PROJECT} --env-file ./.env.docker -f ./docker/${TEMPLATE}/docker-compose.${ENV}.yml build;
	@/bin/echo "";
	@${MAKE} -s clean;
	@/bin/echo "";
	@tput setaf 2; tput bold; tput smul; /bin/echo "Starting ${ENV} environment:"; tput sgr0;
	@docker compose -p ${COMPOSE_PROJECT} --env-file ./.env.docker -f ./docker/${TEMPLATE}/docker-compose.${ENV}.yml up -d --force-recreate --remove-orphans;
	@exit 0;
else
start:
	@/bin/echo "Error: ENV variable is not defined. Please set the ENV variable before running the 'start' target.";
	@exit 1;
endif
else
start:
	@/bin/echo "Error: TEMPLATE variable is not defined. Please set the TEMPLATE variable before running the 'start' target.";
	@exit 1;
endif
