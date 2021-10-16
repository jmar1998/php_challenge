# PREPARE DOCKERS
docker-compose up -d
docker exec -it php-challenge-src php ./project/vendor/bin/phpunit "project/src/tests" --testdox --verbose --bootstrap "project/init.php"