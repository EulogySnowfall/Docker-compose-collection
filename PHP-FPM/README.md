# Bitnami Docker LEMP Environnement 
## Date 13/10/2020
## Version 1.0


Basic working Docker environnement Bitnami.

Style:
 - Docker-composer

Base on:
 - https://www.howtoforge.com/tutorial/dockerizing-lemp-stack-with-docker-compose-on-ubuntu/
 
Services :
 - Nginx
 - PHP-FPM 7.4
 - MariaDB 
 - PHPMyAdmin

***Important***

This is only demo purpose password. CHANGE IT!!!!

# Environnement tested

Windows 10 Docker Desktop with WSL2 under Ubuntu 18.04/20.04


# Know issues

- Nginx won't bind on some port as 80. Probably conflit with local port bind. Need to be tested on other systems
- PHPMyAdmin issues with Privileges on DBs. But can use user creation on main system.

# Extra manipulation before run 

Don't forget to create db-data & logs folders with the files for logs: 
```
mkdir {logs,db-data}
touch logs/{error,access}.log

```
