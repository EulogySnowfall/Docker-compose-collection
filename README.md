# Docker-Compose collection

Execute and play docker-compose files list.


***Important***

The Vscode docker-compose plugin (up/down) don't take in charge custom environnement variables.

If you do you can aspect to have this kind of errors:

- WARNING: The TAG variable is not set. Defaulting to a blank string.
- WARNING: The CONFIGPATH variable is not set. Defaulting to a blank string.

So if you use it, always execute the first up run in commande line

Always execute this at the root of the docker-compose

Make sure to put your environnement variables in a .env in the same directory of your docker-compose file.

Valide the config variables

```bash
docker-compose config 
```

If all look good then...

First run to take in charge customs variables. 

```bash
docker-compose up -d
```

After you can restart from the Windows Docker Desktop or on windows boot, ***ever from vscode plugin... til is fix***.  

**

Enjoy!

Eulogy

- Tested on a Windows Docker installation under WSL2 with Ubuntu 20.04. May works without any problem under Linux environnement.  


## Communs issues and Errors

### Can't access files for a Web server with bind volumes

You have 2 things to fix here (if you work with wsl under Windows drives mount as me)

- Need to create the /etc/wsl.conf with the following stuff to get unix right on files & Folders

```bash
[automount]
enabled = true
options = "metadata,umask=22,fmask=11"
mountFsTab = false
```

Then restart your computer to restart WSL and Docker Desktop at the same time


- Give good right to your folder (of all include linux host)

If your are under Ubuntu base, normaly it's will be www-data www-data so in your data directory volume of your webapp do:
```
chown -R www-data.www-data *
```
or do the owner you need.
You may need to create the user with the same UID on the host.

### Can't activate SSL

Some files need to be on 400 or 600 right. Activate your volume as :ro can help you, otherwise apply the good chown on the files


### Can create Databases on de remote container on the apps

Even if MariaDB offer you by default to create user with password with remote right connexion of your apps, often the right is not deploy as that suppose to be.
If you have this problem, you need to connect the db container in cli (You can use the UI of Docker desktop) then:

```sql
mysql -u root -pYouPasswordToConnectRoot
GRANT ALL PRIVILEGES ON database_name.* TO 'username'@'%';
FLUSH PRIVILEGES;
```

Of Course you changes the values with yours :p
After you will be able to create your Apps Databases

### Volume limitation

The docker volume look to bee limited to 250Go, maybe is the wsl limite attribut.

### Change Volume docker-data

<https://www.guguweb.com/2019/02/07/how-to-move-docker-data-directory-to-another-location-on-ubuntu/>
<https://dev.to/kimcuonthenet/move-docker-desktop-data-distro-out-of-system-drive-4cg2>

## NextCloud


## Freepbx

- Work fine for the MACVLAN version...


## Plex

Private Media Server

## Docker repository

You own Private Docker repository

## Bitnami PHP-FPM base App

- This is simply the base Binami docker-compose for PHP-FPM with NGINX, MariaDB and PHPMyAdmin.

- With a Basic PHP Exemple. You just to put yours.


## Docker Basic

Some Basic exemple utils command for docker as exemple.

### Console access

docker exec -it [container-id] bash


### Backup Volume (exemple)

Base on a samba backup volume (with creation)

```bash
docker volume create --driver local --opt type=cifs --opt device=//192.168.0.10/Backup --opt o=addr=192.168.0.10,username=user,password=password,file_mode=0777,dir_mode=0777 --name backup-volume

docker run --rm --volumes-from apps -v $(pwd):/backup ubuntu tar -czvf /backup/backup.tar /dbdata

docker run -it --volumes-from apps -v backup-volume:/vlback --name back_test ubuntu bash
docker rm back_test
```

### Restore Volume (exemple)

```bash
docker run -it --volumes-from nextcloud_app_1 -v backup-volume:/backup_data --name backup_machine ubuntu bash
cd /backup/folder/
tar -xzvf file.tar.gz /directory_to_copy
docker rm backup_machine
```

### Copy files to containers

```bash
docker cp foo.txt mycontainer:/foo.txt
```

### backup BD (exemple)

```bash
sudo docker exec apps /usr/bin/mysqldump -u appsuser --password=userpassword apps > /mnt/data/apps.sql
docker exec site_db_1  /usr/bin/mysqldump -u root --password=rootpassword site > /mnt/g/site.sql
```
### Restore BD (exemple)

```bash 
cat /mnt/g/site.sql | docker exec -i site_db_1 /usr/bin/mysql -u root --password=rootpassword site
```

## Create macvlan network

```bash
docker network create -d macvlan --subnet=192.168.0.0/24 --gateway=192.168.0.1  -o parent=enp27s0 pub_vlan
```

### Networks macvlan on docker-compose

Put the container on a network who already existe (as above)

```yaml
# in container
  mac_address: ${MAC}

  networks:
  # Network name of the macvlan to use 
    pub_vlan:
      ipv4_address: ${PRIVATEIP}

networks:
  pub_vlan:
    # Tell the macvlan already created
    external: true

```

