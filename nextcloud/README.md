# Nextcloud docker

## Execute files scan

- app: is the container name

docker-compose exec -u www-data app php occ files:scan --all


## .env Variables file

```
NEXTCLOUD_DATA_DIR=/my/path/to/data
NEXTCLOUD_ADMIN_USER=Myadminuser
NEXTCLOUD_ADMIN_PASSWORD=MyAdminPassWord
NEXTCLOUD_TRUSTED_DOMAINS=nextcloud.exemple.com
MYSQL_PASSWORD=MyNextcloudDBPassWord
MYSQL_DB=nextcloud
MYSQL_USER=mynextclouduserdb
DATABASEPATH=/my/path/to/database
DATABASEBACKUPPATH=/my/path/to/database_backup
LOCALDATAPATH=/my/path/to/data

```

NEXTCLOUD_UPDATE=1 to force repo update
NEXTCLOUD_DATA_DIR is optional if you use the default one.

## Secret method

Instead of the .env, if you working with docker swarm or kubernetes, you can use secret. But not all environnement can be set. Check the accepted list in the docker repository

List of supported variables secret :


- Nextcloud

 - NEXTCLOUD_ADMIN_PASSWORD
 - NEXTCLOUD_ADMIN_USER
 - MYSQL_DB
 - MYSQL_PASSWORD
 - MYSQL_USER
 - POSTGRES_DB
 - POSTGRES_PASSWORD
 - POSTGRES_USER
 - REDIS_HOST_PASSWORD

- MariaDB

 - MYSQL_ROOT_PASSWORD
 - MYSQL_DATABASE
 - MYSQL_USER
 - MYSQL_PASSWORD


Only put the value in the text file.

Exemple
```text
MyS3cr3tP@ssW0rdV@lu3
```

Exemple
```yaml

environment:
    - NEXTCLOUD_UPDATE=0
    - NEXTCLOUD_DATA_DIR=${MYDATAPATH}
    - NEXTCLOUD_ADMIN_USER=/run/secrets/nextcloud_admin_user
    - NEXTCLOUD_ADMIN_PASSWORD=/run/secrets/nextcloud_admin_password
    - NEXTCLOUD_TRUSTED_DOMAINS=nextcloud.exemple.com
    - MYSQL_PASSWORD=/run/secrets/mariadb_password
    - MYSQL_DB=/run/secrets/mariadb_db
    - MYSQL_USER=/run/secrets/mariadb_user
    - MYSQL_HOST=db


# Create Secret (if not already)
secrets:
  nextcloud_admin_password:
    file: ./private/nextcloud_admin_password.txt # put admin password to this file
  nextcloud_admin_user:
    file: ./private/nextcloud_admin_user.txt # put admin username to this file
  mariadb_db:
    file: ./private/mariadb_db.txt # put mariadb db name to this file
  mariadb_password:
    file: ./private/mariadb_password.txt # put mariadb password to this file
  mariadb_user:
    file: ./private/mariadb_user.txt # put mariadb username to this file
  mariadb_root_password:
    file: ./private/mariadb_root_password.txt # put mariadb username to this file


```