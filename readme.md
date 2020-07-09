# sMaSheD - Mininge Server Detector
## Introduction
Any organization should be aware of running mining software on its hardware in its network due to at least two reasons: a) the mining activity is often caused by malware, therefore, the mining activity is an indicator of a compromise; b) the energy (e.g., electricity, cooling, CPU and GPU power) spent on mining is paid by the hosting organization, but the recipient of the reward is a malicious actor. Universities or technological centers are typical examples of energy exploitation because they offer free computational resources (i.e., servers, network) to academics, researchers and students. Nevertheless, it is possible to start a mining operation in any organization. The malicious actor might exploit these assets resulting in an increased energy bill, depleted resources, endangered work processes, services and other users. Cryptocurrency mining is the only option how users may obtain freshly minted currency units. Moreover, mining is still the prevailing form of how to earn cryptorcurrencies with the existing equipment. 

We have created a publicly available web application that stores metadata about existing mining pools. Any user may query our system to check whether a given FQDN, IP address or port number is a part of known pool configuration.

### JANE Framework
This application is one of the modules of the JANE platform, which offers various mission-specific tools intended for digital forensics of computer networks. JANE follows microservice architecture and offers few containerized modules such as:

* [sMaSheD](https://github.com/kvetak/sMaSheD/) - tracks IP addresses and ports of well-known mining services. It also records the availability of mining service on;
* [Cryptoalarm](https://github.com/nesfit/jane-cryptoalarm/) - sends email/REST notifications triggered by the appearance of cryptocurrency address in new transactions;
* [DeMixer](https://github.com/nesfit/jane-DeMixer/) - DeMixer applies proof-of-concept heuristic (working on BestMixer.io cluster), which can correlate incoming and outgoing transactions going via mixing services;
* [Cryptoclients](https://github.com/nesfit/jane-cryptoclients/) - Blockbook web-application offers generic blockchain explorer supporting major cryptocurrencies (e.g., BTC, ETH, LTS, DASH, ZCASH);
* [Toreator](https://github.com/nesfit/toreator-ui) - stores metadata about Tor relays including IP addresses, capabilities and time when they were active;
* [MozArch](https://github.com/nesfit/mozarch/) - MozArch is web-application that periodically downloads, parses, decodes, and archives (in the MAFF) webpages appearing on the public Internet.

JANE and its modules are outcomes of the [TARZAN project](https://www.fit.vut.cz/research/project/1063/.en) supported by the [Ministry of the Interior of the Czech Republic](https://www.mvcr.cz). sMaSheD was developed in the frame of the [master thesis of Jakub Kelečéni](https://www.fit.vut.cz/study/thesis/21288/) supervised by [Vladimír Veselý](https://www.fit.vut.cz/person/veselyv/) in 2018.

### Goal
The primary motivation behind sMaSehD was active monitoring of cryptocurrency mining servers. Data retrieved during this long-term running monitoring should be collected in structural form to allow subsequent analysis. Mining server IP address and its availability for mining operation belong among the most important metadata stored in the system. In order to obtain such information, sMaSheD application periodically resolves FQDNs of pool servers and connects to their services using discovered IP address and knwon port number.

Network administrator and law enforcement agent (i.e., our main actors for mining detection use-case) shall have basic NetFlow records of investigated device/network segment. These records contain at least source/destination IP addresses, source/destination ports and a protocol identifier. The reasoning behind our second approach is following. If we know IP address of mining pool server, then we can reliably distinguish between mining and non-mining connections. Moreover, if we are aware of the port number employed by a pool operator, then we can tell what cryptocurrency is being mined through the connection.

### Technologies
sMaSheD periodically queries servers of known mining pools using GetBlockTemplate and Stratum 1.0 protocols. sMaSheD saves responses and reachability information in a relational database. This metadata about the mining server is then available via the web. 

sMaSheD is a web application written in PHP with the help of the Laravel framework:

* PHP 7.1.3
* Laravel 5.8
* MariaDB 10.5

## Installation guideline
sMaSheD web application source codes are available in the following [GitHub repository folder](https://github.com/kvetak/sMaSheD). Deployment of JANE sMaSheD module can be cloned via [Git repository](https://github.com/nesfit/jane-smashed).

### Prerequisites
All JANE modules run as containerized microservices. Therefore, the production environment is the same for all of them. JANE uses Docker for containerization. We expect that JANE containers can operate on any containerization solution compatible with Docker (such as Podman).

JANE was developed and tested on CentOS 7/8,  but it can be run on any operating system satisfying the following configuration. Here is a list of installation steps to successfully configure the hosting system:

1. enable routing for (virtual) network interface cards `/sbin/sysctl -w net.ipv4.ip_forward=1`

2. enable NAT on outside facing interfaces `firewall-cmd --zone=public --add-masquerade --permanent
install`

3. add Docker repository 
```
yum-config-manager \
    --add-repo \
    https://download.docker.com/linux/centos/docker-ce.repo
```

4. install Docker package and its prerequisites `yum install -y docker-ce`

5. run Docker as system service and enable it as one of the daemons 
```
systemctl start docker
systemctl enable docker
``` 

6. install docker-compose staging application 
```
sudo curl -L "https://github.com/docker/compose/releases/download/1.25.5/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
cp /usr/local/bin/docker-compose /sbin/docker-compose
```

7. install Docker add-on which allows to specify destinations for dynamically created volumes
```
curl -fsSL https://raw.githubusercontent.com/MatchbookLab/local-persist/master/scripts/install.sh | sudo bash
```

### Deployment
sMaSheD consists of three containers - Laravel 5.8 web application, nginx 1.19.1 HTTP server and MariaDB 10.5 database. In order to deploy sMaSheD on your server:

1. clone sMaSheD repository `git clone https://github.com/nesfit/jane-smashed`

2. copy container environmental variables file `cp .env.example .env`

3. specify in `.env` public port on which sMaSheD will be available and existing virtual network name nano .env
```
LOCAL_VOLUME_MOUNT_POINT=</path/to/volumes>
NETWORK=<docker_network>
HTTP_PORT=<public http port>

MARIADB_ROOT_PASSWORD=<db_rootpass>
MARIADB_DATABASE=<db_name>
MARIADB_USER=<db_user>
MARIADB_PASSWORD=<db_pass>

```

6. pull containers from [Docker hub repository](https://hub.docker.com/repository/docker/nesatfit/smashed) `docker-compose pull`

7. optionally build web application locally `docker-compose build`

8. run containers `docker-compose up -d`

## User manual
