# Ensure help is shown as the default and fallback target
.DEFAULT_GOAL := help

# Local Environment
build:
	docker-compose -f docker-compose.yml down --volumes --rmi all --remove-orphans
	docker-compose -f docker-compose.yml build

up:
	docker-compose -f docker-compose.yml up -d

down:
	docker-compose -f docker-compose.yml down --volumes --rmi all --remove-orphans

stop:
	docker-compose -f docker-compose.yml stop

shell:
	docker exec -it auth-service-app /bin/bash

# Help message
help:
	@echo "Available targets:"
	@echo "  build       Build the local environment"
	@echo "  up          Start the local environment"
	@echo "  stop       Stop the local environment"
	@echo "  down        Stop the local environment"
	@echo "  shell       Open a shell in the local environment"

# Fallback for invalid targets
%:
	@echo "Invalid target: $@"
	@echo "Showing help instead..."
	@make help
