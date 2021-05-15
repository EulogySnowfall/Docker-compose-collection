# Plex


## In root directory .env (for others variables)

```
TZ='PHP TIME ZONE- See PHP list for yours' Ex. 'America/Toronto'
PLEX_CLAIM=YourPlexAccount
ADVERTISE_IP=http://YOURHOSTIP:32400/
DATAPATH=YOURPATH
TRANSCODEPATH=YOURPATH
CONFIGPATH=YOURPATH
TAG=latest
```

## Add SSL

You can generate a .pfx SSL Certificat and add it in the /config root. after the first run. Then you can configure it in the Plex Network.

### Fix memory limite bug version 21


Add memory_limit in the commande line
```bash 
sudo -u www-data php -d memory_limit=1G occ user:resetpassword admin
```

### Desktop synch connexion problem

in the config.php add the following line below the 'overwrite.cli.url'

```php
  'overwriteprotocol' => 'https',
```