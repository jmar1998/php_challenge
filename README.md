# PHP CHALLENGE APP
This is the php challenge app, to run any command you need to run one from the given commands available on Application and Testing section (This commands handles already the creation of containers)
## ***Bootstrap***
------------------
To run the application you must be on linux enviroment and run the script to prepare the app.
- sh prepare.sh
## ***Application***
------------------
Available Commands
- sh run.sh USER:CREATE
    - Command to create user
- sh run.sh USER:CREATE-PWD [ID]
    - Command to set a password on a existing user, it accepts the ID as the first argument
## ***Testing***
------------------
Available Commands
- sh run-tests.sh
## ***Docker***
------------------
Requirements : PHP, MySQL and PHPmyAdmin
PHP extensions used : pdo, pdo_mysql, mysqli
