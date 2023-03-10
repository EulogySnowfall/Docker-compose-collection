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

```bash
certbot -d *.yourdomain.com --manual --preferred-challenges dns certonly
cd /etc/letsencrypt/live/
openssl pkcs12 -export -out certificate.pfx -inkey privkey.pem -in cert.pem -certfile chain.pem
password *****
docker cp ./certificate.pfx plex:/config

# in the docker 
chown plex.plex certificate.pfx
chmod 400 certificate.pfx
```

restart docker
