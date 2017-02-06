Raspbian / GlusterFS image 

To build image:
docker build -t magentopi .

You need to setup/configure this image

To run image:

docker run -d --net=host --privileged --name gluster -v /data:/data choukalos/rpi-glusterfs
Note takes roughly ~600M of ram to run GlusterFS, performance on RPIs is pretty slow, < 1 M/s write, and read is roughly 6M/s .

First 2 containers need to probe each other - that way you'll see the hostname/etc rather then just ip addresses; then all other containers needed to be probed from one of the original 2 containers.

FIRSTCONTAINER:  docker exec -it gluster gluster peer probe SECONDCONTAINER
SECONDCONTAINER: docker exec -it gluster gluster peer probe FIRSTCONTAINER

Now create a gluster volume (replicated):
docker exec -it gluster bash
   gluster volume create vol replica 2 FIRSTCONTAINER:/data/vol SECONDCONTAINER:/data/vol
   gluster volume start vol


Other containers need to be probed from one of the first or second containers and can be added as full replicas:
docker exec -it gluster gluster peer probe NEWCONTAINER
docker exec -it gluster gluster volume add-brick vol replica #REPLICAS NEWCONTAINER:/data/vol

Note that when adding other containers you don't have to do a full replication, you can set the # of replicas lower and Gluster will distribute the data among the containers.

See Status:
docker exec -it gluster gluster info vol
docker exec -it gluster gluster peer status

The container exports volume /data , creating a Gluster volume in /data/vol 
   
Now if you want to use gluster from another raspberry pi running Raspbian you can do the following:

apt-get -y install glusterfs-client
mount -t glusterfs FIRSTCONTAINER:/vol /mnt/mygluster

Then touch, cp, move files into /mnt/mygluster
