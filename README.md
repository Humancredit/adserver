#adserver
===

reference implementation

---

##requirements

- PHP 5.4
- [composer](https://getcomposer.org/download/)

##installation

- clone git

> git clone git@github.com:Humancredit/adserver.git

- create /app/config/parameters.yml (copy /app/config/parameters.yml.dist) and adjust settings

- run in projects root directory

> composer install
> app/console doctrine:database:create
> app/console doctrine:schema:update --force
> app/console assets:install web --symlink
> app/console cache:clear
> app/console server:run

- then open

> http://localhost:8000
