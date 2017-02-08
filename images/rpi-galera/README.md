DRAFT/Doesn't Work

# MariaDb Galera Cluster

This Docker container is based on alpine linux v3.5 for the raspberry pi, mariadb:10.1 official image and is designed to be
compatible with auto-scheduling systems, specifically Docker Swarm Mode (1.12+) and Kontena.  Borrowed heavily from Colin 
Mollenhour's Docker image.  However, it could also work with manual scheduling (`docker run`) by specifying the correct
environment variables or possibly other scheduling systems that use similar conventions.

It takes as a command one of the following:

 - "seed" - Used only to initialize a new cluster and after initialization and other nodes are joined
   the "seed" container should be stopped and replaced with a "node" container using the same volume.
 - "node" - Join an existing cluster. Takes as a second argument a comma-separated list of IPs or
   hostnames to resolve which are used to build the `--wsrep_cluster_address` option for joining a cluster.
 - "no-galera" - Start server with Galera disabled. Useful for maintenance tasks like performing mysql_upgrade
   and resetting root credentials.
 - "sleep" - Start the container but not the server. Runs "sleep infinity". Useful just to get volumes
   initialized or if you want to `docker exec` without the server running.

By using DNS resolution to discover other nodes they don't have to be specified explicitly. This should works
with any system with DNS-based service discovery such as Kontena, Docker Swarm Mode, Consul, etc.

### Example (Docker 1.12 Swarm Mode)

```bash
 $ docker service create --name galera-seed --replicas 1 [OPTIONS] [IMAGE] seed
 $ docker service create --name galera --replicas 2 [OPTIONS] [IMAGE] node tasks.galera-seed,tasks.galera
 $ docker service rm galera-seed
 $ docker service scale galera=3
```

### Environment Variables

 - `XTRABACKUP_PASSWORD` (required unless `XTRABACKUP_PASSWORD_FILE` is provided)
 - `SYSTEM_PASSWORD` (optional - defaults to hash of `XTRABACKUP_PASSWORD`)
 - `CLUSTER_NAME` (optional)
 - `NODE_ADDRESS` (optional - defaults to ethwe, then eth0)

Additional variables for "seed":

 - `MYSQL_ROOT_PASSWORD` (optional)
 - `MYSQL_DATABASE` (optional)
 - `MYSQL_USER` (optional)
 - `MYSQL_PASSWORD` (optional)

Additional variables for "node":

 - `GCOMM_MINIMUM` (optional - defaults to 2)

#### Providing secrets through files

It's also possible to configure the sensitive variables (especially passwords)
by providing files. This makes it easier, for example, to integrate with
[Docker Swarm's secret support](https://docs.docker.com/engine/swarm/secrets/)
added in Docker 1.13.0.

The path to the secret file must be provided in environment variables:
- `XTRABACKUP_PASSWORD_FILE` (required unless `XTRABACKUP_PASSWORD` provided)
- `SYSTEM_PASSWORD_FILE` (optional - defaults to hash of `XTRABACKUP_PASSWORD`)
- `MYSQL_ROOT_PASSWORD_FILE` (optional)
- `MYSQL_PASSWORD_FILE` (optional)

### Health Checks

By default there are two HTTP-based healthcheck servers running in the background.

* Port 8080 only reports healthy when ready to serve clients. Use this one for load balancer health checks
* Port 8081 reports healthy as long as the server is synced or donor/desynced state. This one is used to help
  other nodes determine cluster state before launching the server.

The default "HEALTHCHECK" command returns healthy status if `/var/lib/mysql/sst_in_progress` is present to avoid
a node being killed during an SST. Otherwise it uses the first health check (port 8080) to return healthy only if
it is fully ready to serve clients. How you want the healthcheck command to behave will vary on your uses for the
healthcheck so you may need to override it depending on the behavior you desire. Regardless, both healthcheck servers
will be started and will use negligible resources unless they are actually being pinged.

### More Info

 - Tries to handle as many recovery scenarios as possible including full cluster ungraceful shutdown by
   using --wsrep-recovery and inter-node communication to discover the optimal node for bootstrapping
   a new cluster when the old one cannot be recovered.
 - If you need to perform manual recovery of a previously healthy cluster you can use "node" mode
   but touch a file at `/var/lib/mysql/wsrep-new-cluster` to force a node to bootstrap a new cluster
   and bypass the automatic recovery steps.
 - XtraBackup is used for state transfer and MariaDb now supports `pc.recovery` so the primary component should
   automatically be recovered in the case of all nodes being gracefully shutdown.
 - A go server runs within the cluster exposing an http service for intelligent health checking.
    - Port 8080 is used by the Docker 1.12 HEALTHCHECK feature and also can be used by any other health checking
      node in the network such as HAProxy or Consul to determine readable/writeable nodes.
    - Port 8081 is used to detemine cluster status
 - If your container network uses something other than `ethwe*` or `eth0` then you need to specify `NODE_ADDRESS`
   as either the name of the interface to listen on or a grep pattern to match one of the container's IP addresses.
   E.g.: `NODE_ADDRESS='^10.0.1.*'`
 - When using DNS for node address discovery the container entrypoint script will wait indefinitely for
   `GCOMM_MINIMUM` IP addresses to resolve before trying to start `mysqld` in case some containers are starting
   slower than others to increase the chance of a healthy recovery. Scenarios where not enough IPs would resolve
   might include:
    - Some nodes may finish pulling container images from remote repositories sooner than others
    - Schedulers may not be launching nodes quickly enough
    - Service discovery systems may be slow to propagate updates via DNS
 - If the file `/usr/local/lib/startup.sh` exists it will be sourced in the start.sh script.
 - If you need to promote a running node to be a new "Primary Component" you can run the following command to do so:
       $ docker exec -i <container> mysql -p /primary-component.sql
 - You can monitor cluster state changes more clearly by setting `wsrep_notify_cmd` to `/usr/local/bin/notify.sh`
   which will output the updates to the Docker logs/console.

### Credit

 - Forked Originally from ["jakolehm/docker-galera-mariadb-10.0"](https://github.com/jakolehm/docker-galera-mariadb-10.0)
   - Forked from ["sttts/docker-galera-mariadb-10.0"](https://github.com/sttts/docker-galera-mariadb-10.0)
 - galera-healthcheck go binary from ["sttts/galera-healthcheck"](https://github.com/sttts/galera-healthcheck)
 - Forked from ["colinmollenhour/mariadb-galera-swarm"](https://github.com/colinmollenhour/mariadb-galera-swarm)

### Changes

 - Rebase to armhf/alpine linux v3.5 using mariadb 10.1 apk package
