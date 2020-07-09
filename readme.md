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
### Protocols
A mining pool and its members are using dedicated protocols to coordinate distribution of mining process. There are three general mining protocols supported by a majority of
cryptocurrencies: 

* GetWork was the first mining protocol ever. Comparing to its descendants, GetWork is a simple request-response scheme protocol - server assigns work package and miner
blindly conducts mining task. Due to its simplicity, GetWork allows double-spent transactions in the case of corrupt pool operator. GetWork messages with JSON syntax are carried inside HTTP7. GetWork supports a limited number of protocol extensions using additional HTTP header lines.

* GetBlockTemplate is standardized mining protocol developed by Bitcoin community but also adopted by other cryptocurrencies. GetBlockTemplate was codified in [BIP 8](https://github.com/bitcoin/bips/blob/master/bip-0022.mediawiki). GetBlockTemplate is more decentralized by offloading block creation process onto miners instead of pools. GetBlockTemplate increases potential work package size and reduces mining protocol overhead to support performance delivered by ASIC miners. Moreover, [BIP 23](https://github.com/bitcoin/bips/blob/master/bip-0022.mediawiki) standardizes ways enhancing GetBlockTemplate without any major protocol redesign or non-conformant HTTP header hacks.

* Stratum protocol was [prototyped by M. Palatinus](https://slushpool.com/help/manual/stratum-protocol), inventor of pooled mining andoperator of the oldest Slush pool. Stratum development was motivated by a need to remedy design flaws of previous two protocols: a) by removing HTTP as a carrier, which reduces unnecessary protocol overhead; b) by removing long polling feature that posed scalability issue for load balancing of traffic between miner and server; and c) by adding the extranonce field that allows miner to generate more hashes locally without bothering a mining server for a new batch of work. Stratum is a JSON-RPC 2.0 compatible protocol that operates directly above TCP. All of these protocols leverage TCP as the transport layer protocol. Comparing to official cryptocurrency peer-to-peer clients, mining protocols do not use any "well-known" port number. It depends solely on the preference of mining pool administrator on which ports pool servers accept connections. Hence, port numbers 80, 443, and 25 are often used as a best practice to bypass firewalls between the mining device and its mining server.

The usual message exchange involves several steps. With the initial message, the miner connects to the mining server and provides authentication credentials. Authentication is
necessary because based on credentials, mining pool correlates submitted shares with miner’s account and credit earnings. Two types of authentication are common:

* registration-oriented - Before establishing the mining operation, the user owning mining rig needs to sign up to the pool and create an account. A part of account administration involves workers (i.e., separate mining devices) setup. Authentication credentials inside mining protocol include username and password.

* registration-less - Some pools tender their services without any dedicated account registration. In that case, the miner usually provides just cryptocurrency address to inform pool where to send payments. This identifier substitutes username and is enough for authentication. Regardless of authentication type, the username may contain optional suffixes such as
worker identifier (in order to distinguish different workers of the same user) or e-mail address (where the user is notified about any problems occurred during mining).

The next step in mining protocol communication is a recurrent assignment of work packages provided by the server. Each work package contains data, target, and nonce (other fields depend on cryptocurrency). A miner tries to find a hash (from combined data and nonce) that meets difficulty. Different cryptocurrencies are using distinct hashing algorithms - e.g., SHA-256d for Bitcoin, Scrypt for Litecoin, X11 for Dash. Miner either submits correct solution or restarts mining with different inputs upon receiving a new work package. Miner periodically announces its state to the server.

Below figure illustrates Stratum exchange from mining device to its server (with the red color) and vice versa (the blue color). We can observe the typical confluence of messages.

![Exchange](https://github.com/kvetak/sMaSheD/raw/master/docs/exchange.png)

A connection to the pool is initiated with the first message (marked as #1), where we can see authentication details. The server confirms it with message denoted as #2. The
server sends a work package (#3) that needs to be computed. Upon proper initialization of mining software, the miner asks for a new work package (#4), which the mining server
gladly provides (#5). The miner successfully finds the hash and submits (#6) the complete solution back to the server. The server decides whether the miner’s result is valid or not (in the case of #7, it is valid) and sends a new work package (#8). The miner starts a new task and meantime periodically updates server about its local computational speed (message
marked as #9) so that server can dynamically adjust the size of subsequent work packages. If we focus on the forensic analysis of metadata related to mining protocol, then we can
extract metadata described in the table below.

| Metadata | Description |
| --- | --- |
| IP address, port number | By inspecting IP addresses, we can geolocate both miner and mining server. Together with port numbers, we can account network traffic with NetFlow. Once we have NetFlow records available, we can answer questions such as for how long is mining operation active, how many mining devices are involved, etc. |
| Pool information | GetWork or GetBlockTemplate protocol extensions may uncover other useful intel such as alternative mining servers including their IP addresses, fully-qualified domain names, and port numbers. |
| Miner’s username | Based on authentication type, username field may contain either nickname or account name of pool user or its cryptocurrency address. This information may be crucial for successful correlation of real-world person and its electronic identity. |
| Miner’s password | Authentication message of any mining protocol includes a password. However, it is seldom used for authorization or any purpose by a pool. The default value of password field for the most of mining software is ‘x’. |
| Miner’s email | Some pools offer email notifications about the progress of mining operation. In case of any problem such as the miner outage, too many rejected shares or disconnection from the pool, the user is warned by email. The email address may be optionally part of mining protocol message filed, which may help to reveal user’s identity. |

### User-stories


## Programmer's documentation
The programmer's documentation for sMaSheD is autogenerated with the help of phpDox. This documentation is available statically in `docs` [folder](https://github.com/kvetak/sMaSheD/tree/master/docs). Moreover, for your convenience, it is also [available online](https://jane.nesad.fit.vutbr.cz/docs/demixer/index.xhtml) through JANE's [landing page](https://github.com/nesfit/jane-splashscreen/).

### Entity Relationship Diagram
ER corresponding to database scheme:

![ER diagram](https://github.com/kvetak/sMaSheD/raw/master/docs/erdiag.png)
