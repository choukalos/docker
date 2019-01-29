# rpi-mqtt

Raspberry PI docker image with mosquitto mqtt broker based upon docker-moquitto

## How to run
```
docker run -tip 1883:1883 -p 9001:9001 choukalos/rpi-mqtt
```

Exposes port 1883 (MQTT) and 9001 (Websocket MQTT)

You can use volumes to make the changes persistent and change the configuration

/srv/mqtt/config
/srv/mqtt/data
/srv/mqtt/log

## How to build the image
```
make build
make push
```


