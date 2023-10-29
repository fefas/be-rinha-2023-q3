MAKEFLAGS += --silent --always-make
.PHONY: *

build-target ?= prod
deploy-mode ?= prod

#
# deploy
build:
	make docker/compose/build

deploy:
	make docker/compose/up args='-V -d'

status:
	make docker/compose/ps

clean:
	make docker/compose/down

#
# Dev
%/sh:
	make docker/compose/run service=${*} entrypoint=sh

acceptance-tests: option = $(if ${tags},--tags=${tags})
acceptance-tests:
	make docker/compose/run service=acceptance-tests cmd=${option}

acceptance-tests/features/%:
	make docker/compose/run service=acceptance-tests cmd='features/${*}

dev/local-setup:
	make build
	make dev/install-local-vendors

dev/install-local-vendors:
	composer install --working-dir=./acceptance-tests --ignore-platform-reqs --no-scripts
	composer install --working-dir=./app --ignore-platform-reqs --no-scripts

#
# docker
docker/compose/config:
	make docker/compose cmd='config'

docker/compose/build:
	make docker/compose cmd='build --parallel'

docker/compose/up:
	make docker/compose cmd='up ${args}'

docker/compose/down:
	make docker/compose cmd='down -v --remove-orphans --timeout 1'

docker/compose/ps:
	make docker/compose cmd='ps'

docker/compose/run: options = $(if ${CI},-T)
docker/compose/run: options += $(if ${entrypoint},--entrypoint=${entrypoint})
docker/compose/run:
	make docker/compose cmd='run --rm ${options} ${service} ${cmd}'

docker/compose/files: dirs = ./ ./projects/core/
docker/compose/files: bind ?= $(if ${CI},n,y)
docker/compose/files:
	echo docker-compose.yaml $(if $(findstring dev,${deploy-mode}),docker-compose.dev.yaml)

docker/compose: project-name = -p fefas-be-rinha-2023
docker/compose: compose-files = $(addprefix -f,$(shell make docker/compose/files))
docker/compose:
	BUILD_TARGET=${build-target} docker compose ${project-name} ${compose-files} ${cmd}
