# PREPARE DOCKERS
docker-compose up -d
echo "WAITING FOR MYSQL CONNECTION...";
sleep 15;
# PREPARE DATABASE
docker exec php-challenge-mysql /bin/sh -c 'mysql -u root --password="admin" --database="php-challenge" < ./project/database-init.sql'