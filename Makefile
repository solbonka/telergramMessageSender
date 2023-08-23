up:
	docker-compose up -d
down:
	docker-compose down
in:
	docker-compose exec php-fpm bash
work:
	php bin/console messenger:consume async -vvv
build:
	docker-compose build
ps:
	docker-compose ps -a
hook:
	docker-compose run ngrok
start:
	/bin/bash ./start.sh
