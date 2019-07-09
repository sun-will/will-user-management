Installation

1) git clone https://github.com/sun-will/will-user-management.git
2) composer install
3) php bin/console doctrine:database:create
4) php bin/console doctrine:schema:update --force
5) php bin/console doctrine:fixture:load
6) php bin/console server:start *:8001
7) The project is now accessible through http://localhost:8001
