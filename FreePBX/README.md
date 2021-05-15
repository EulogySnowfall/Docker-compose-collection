# Freepbx

- docker-compose
  - Version 1.0 maclvan (for Linux base only). Nor working on Windows/wsl base.

## Know issues

### Bridge Connexion

- docker-compose-bridge
  - Version Alpha 0.2
  - SIP/AIX Phones don't connect in bridge mode. In investigation.


### SIP PORT configuration (RTP Range)

- The basic setep is between 10000-20000, but the docker do 18000-20000. So you need to change the default setup in :
  - Settings
    - Asterisk SIP Settings
      - General Settings
        - RTP Settings
          - RTP PORT Ranges (10000-20000) > (18000-20000)

### DHCP support under macvlan

The default docker configuration don't support DHCP yet. You need to compile other driver for this.


## Create User MYSQL (if DB_EMBEDDED=false)

```sql
CREATE USER 'freepbxuser'@'%' IDENTIFIED BY 'ChoosenPassWord';
grant all privileges on asterisk.* to 'freepbxuser'@'%' IDENTIFIED BY 'ChoosenPassWord';
flush privileges;
```

## List SQL User
```sql
use mysql;
select host, user, password from mysql.user;
```

## Env File

```
DB_NAME=dbname
DB_USER=dbuser
DB_PASS=dbpassword
MAC=02:42:c0:00:00:00
PRIVATEIP=192.168.10.2
ETH=eth0
NETWORK=192.168.10.0/24
GATEWAY=192.168.10.1
LOGPATH=/path/to/freepbx/log
```