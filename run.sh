# PREPARE DOCKERS
docker-compose up -d
# PREPARE DATABASE
docker exec php-challenge-mysql /bin/sh -c 'mysql -u root --password="admin" --database="php-challenge" < ./project/database-init.sql'
clear
docker exec -it php-challenge-src php ./project/ASP-TEST.php $@