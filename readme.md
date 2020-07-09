# sMaSheD - Mining Server Detector
## Introduction
Both mining server’s hostname and port number are publicly available (except mining malware cases) on pool’s webpage because they are necessary for successful setup of the mining process. Without this vital information, the miner would not be able to configure mining software properly. 

Based on these premises, we have decided to manually collect all mining software configurations announced by the biggest mining pools for several important cryptocurrencies. We gathered all these data in a database, which is accessible through a web application called sMaSheD (Mining Server Detector of cryptocurrency pools). Any user may query our system to check whether a given FQDN, IP address or port number is a part of known pool configuration. Moreover, this system monitors availability of mining service on known pools.

![sMaSheD demo](https://github.com/kvetak/sMaSheD/raw/master/docs/smashed.gif)

Any organization should be aware of running mining software on its hardware in its network due to at least two reasons: a) the mining activity is often caused by malware, therefore, the mining activity is an indicator of a compromise; b) the energy (e.g., electricity, cooling, CPU and GPU power) spent on mining is paid by the hosting organization, but the recipient of the reward is a malicious actor. Universities or technological centers are typical examples of energy exploitation because they offer free computational resources (i.e., servers, network) to academics, researchers and students. Nevertheless, it is possible to start a mining operation in any organization. The malicious actor might exploit these assets resulting in an increased energy bill, depleted resources, endangered work processes, services and other users. Cryptocurrency mining is the only option how users may obtain freshly minted currency units. Moreover, mining is still the prevailing form of how to earn cryptorcurrencies with the existing equipment. 

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

4. pull containers from [Docker hub repository](https://hub.docker.com/repository/docker/nesatfit/smashed) `docker-compose pull`

5. optionally build web application locally `docker-compose build`

6. run containers `docker-compose up -d`

## User manual
There are hundreds of coins (and tokens) available in cryptocurrency universe. In order to choose coins supported by sMaSheD, we did due diligence on "the most popular" cryptocurrencies taking into account public news, dedicated reports and consultations with our LEA partners. Bitcoin is dominating this ladder due to its importance. Regardless of the current set of cryptocurrencies, sMaSheD is designed to be a generic catalog of mining pools which should be easy to maintain and operate.

Our tool operates according to the diagram outlined in figure below. Pool information are stored into sMaSheD together with available FQDNs of pool servers. Server names are resolved onto a list of IPv4/IPv6 addresses, which are then verified as mining servers by employing mining protocol probes. Each operational step is described in more detail below. 

![smashed scheme](https://github.com/kvetak/sMaSheD/raw/master/docs/smashedscheme.png)

We investigated mining distribution among available pools for each chosen cryptocurrency. The majority of pools add their signature into the freshly mined block. This marking allows to account the success rate of each participating pool. Moreover, pools are announcing their overall hashrate performance publicly. By combining these data, we receive quite a reliable overview about more and less important pools for every cryptocurrency. Anyone can obtain these data from dedicated web-pages, e.g. [for Bitcoins](https://www.blockchain.com/en/pools?timespan=4days), [for Litecoin](https://www.litecoinpool.org/pools), and [for Ethereum](https://etherscan.io/stat/miner?range=7&blocktype=blocks).

Mining software configurations are collected by web scraping the content of pool web pages. This procedure is currently performed manually by sMaSheD administrators. However, we aim at the automation of this process in the near future. The following set of information is being collected for every pool:
* the name of the pool and its home URL;
* the list of pool servers identified by FQDN including ports associated with a mined cryptocurrencies;
* every mining server FQDN is resolved onto a list of IPv4/IPv6 addresses.

Nevertheless, some pools are private. The operator of such pool does not maintain any publicly available web page, which makes any web scraping of configuration impossible. Hence, sMaSheD catalog does not contain a complete list of pools for a given cryptocurrency. Fortunately, private pools constitute a fraction of overall network hashrate. Mining server FQDNs may include information about location, mined cryptocurrency (e.g., `eth-us2.dwarfpool.com`) or employed algorithm (e.g., `sha256.eu.nicehash.com`). However, a single visit of a pool’s web page does not take into account the changing nature of pool infrastructure (i.e., mining service availability on new/old servers).

Pool operators provide server FQDNs, which are resolved by miners onto various IP addresses based on miner’s geolocation. Based on deployment (see figure below), DNS may resolve a single FQDN onto many IP addresses (e.g., `stratum.slushpool.com`) in order to guarantee high-availability of a mining service. sMaSheD tries to keep the list of these IP addresses as up-to-date as possible. It is a necessity especially for pools leveraging cloud deployment because cloud providers often rotate available IP addresses among customers’ virtual machines. An IP address of mining server today can belong to a completely different machine tomorrow. Because of this changing nature and since a single FQDN may actually represent a set of load-balancing mining servers, sMaSheD periodically renews the list of IP addresses associated with each mining server within the system.

![Mining server deployment](https://github.com/kvetak/sMaSheD/raw/master/docs/deploy2.png)

In order to provide more reliable results if a given IP address belongs to a mining server or not, we developed probing. This probing repeats for all known pools (and their mining servers). During every periodic check of mining server, sMaSheD sends crafted mining protocol message and waits for the response. If counterparty reacts properly (with a message containing work package), then it confirms that this device is really a pool’s mining server.

Probing is supported for Stratum and GetBlockTemplate mining protocols. sMaSheD is probing single server for both of these protocols. GetWork is also implemented, but we were not able to test it since this protocol is deprecated and not employed by any pool within our system. There are three probing return codes:
* DOWN - Probing failed because connection had not been even established. This occurs when a port on the server is closed, or some middle-box is blocking the connection.
* LISTEN - The connection was accepted on a specified port, but the alleged server returns an empty response. This happens when a) server is using different mining protocol than the tested one; b) port is opened but bound to a different application.
* UP - Probing succeeded because mining server responded with mining protocol message containing valid content. Message validity depends on employed mining protocol and consists of multiple value presence tests (e.g., error, result and other JSON fields). This validator can be easily extended to support changes or even new mining protocols. 

Probing return code is usually accompanied with a verbose result (i.e., destination unreachable, unknown method, mining subscribe). sMaSheD records each probing attempt, which creates a history of service availability for a given mining server. These temporal data can later prove that IP address was used by a mining server (at least from the perspective of sMaSheD).

### Protocols
A mining pool and its members are using dedicated protocols to coordinate distribution of mining process. There are three general mining protocols supported by a majority of
cryptocurrencies: 

* GetWork was the first mining protocol ever. Comparing to its descendants, GetWork is a simple request-response scheme protocol - server assigns work package and miner
blindly conducts mining task. Due to its simplicity, GetWork allows double-spent transactions in the case of corrupt pool operator. GetWork messages with JSON syntax are carried inside HTTP7. GetWork supports a limited number of protocol extensions using additional HTTP header lines.

* GetBlockTemplate is standardized mining protocol developed by Bitcoin community but also adopted by other cryptocurrencies. GetBlockTemplate was codified in [BIP 8](https://github.com/bitcoin/bips/blob/master/bip-0022.mediawiki). GetBlockTemplate is more decentralized by offloading block creation process onto miners instead of pools. GetBlockTemplate increases potential work package size and reduces mining protocol overhead to support performance delivered by ASIC miners. Moreover, [BIP 23](https://github.com/bitcoin/bips/blob/master/bip-0022.mediawiki) standardizes ways enhancing GetBlockTemplate without any major protocol redesign or non-conformant HTTP header hacks.

* Stratum protocol was [prototyped by M. Palatinus](https://slushpool.com/help/manual/stratum-protocol), inventor of pooled mining and operator of the oldest Slush pool. Stratum development was motivated by a need to remedy design flaws of previous two protocols: a) by removing HTTP as a carrier, which reduces unnecessary protocol overhead; b) by removing long polling feature that posed scalability issue for load balancing of traffic between miner and server; and c) by adding the extranonce field that allows miner to generate more hashes locally without bothering a mining server for a new batch of work. Stratum is a JSON-RPC 2.0 compatible protocol that operates directly above TCP. All of these protocols leverage TCP as the transport layer protocol. Comparing to official cryptocurrency peer-to-peer clients, mining protocols do not use any "well-known" port number. It depends solely on the preference of mining pool administrator on which ports pool servers accept connections. Hence, port numbers 80, 443, and 25 are often used as a best practice to bypass firewalls between the mining device and its mining server.

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
sMaSheD is a combination of command-line script (which periodically probes mining server on a given IP address and port number) and web application that offers user control of the system.

There is no need for authentication of users accessing the system for read-only access to the mining server catalog. However, for create-update-delete operations over resources, the user must be logged in. Therefore, web application supports the basic AAA system employing username-password credentials.

The system consists of the following views:

* *Main* - allowing a generic search (by providing FQDN or IP address) of data contained in the catalog;
* *Dashboard* - consolidating resources statistics;
* *Login* - landing page for unauthorized access through which the user provides session-based credentials;
* *Pools* - management of existing pools (e.g., their names and URLs);
* *Cryptos* - management of cryptocurrencies mined by recognized pools (e.g., their name, abbreviation and project URL);
* *Servers* - management of pool servers (e.g., their fully-qualified domain names, IP addresses, open ports for a given cryptocurrency mining service);
* *Ports* - management of ports assigned to particular server and cryptocurrency;
* *Addresses* - management of all IP addresses resolved from FQDNs of servers;
* *Mining Properties* - management of all probes checking the availability of mining operation service on a given IPs (and belonging servers).

### Operation
The web application is designed to have multiple screens. The first screen is Main page, where anyone (even not a logged user) may search the catalog for FQDN or IP address. If input exists in the database, then information about that particular server is returned to the user.

![Main page](https://github.com/kvetak/sMaSheD/raw/master/docs/s1.png)

Dashboard show aggregated statistics about all resources within the system.

![Dashboard page](https://github.com/kvetak/sMaSheD/raw/master/docs/s2.png)

Following next are dedicated views for every resource within the system: pools, cryptos, servers, ports, and addresses. A single pool may operate multiple servers. A fully-qualified domain name recognizes the server, and numerous mining operations can run on different ports. There may be more than an IP address assigned to a server FQDN.

Each resource can be managed via control buttons:
* create button accompanying form for creating;
* pencil for updating (the dedicated view is displayed);
* trashbin for deleting (beware that no confirmation is needed);

A collection of resources is displayed in the data table that allows sorting and filtering according to resource properties. Detail of every resource can be shown in a separate view using a unique resource id. 

Moreover, the server resource view is equipped with a red button called "Refresh IPs" that will resolve and update IP addresses belonging to all FQDN servers in the system.

![Resource page](https://github.com/kvetak/sMaSheD/raw/master/docs/s3.png)

Full collection of any resource may be obtained via the teal "JSON" button (in JSON form), which allows further integration of catalog data with 3rd party applications.

![JSON export](https://github.com/kvetak/sMaSheD/raw/master/docs/s4.png)

The most exciting part from the forensic point of view is the Mining Properties page. It contains existing probes within the system for each IP-port pair for every server in the system. sMaSheD dispatches these probes every 3 hours to determine any changes in mining operation status. If it is necessary, probes can be dispatched ad hoc by clicking on red button "Dispatch probing".

![MiningProps page](https://github.com/kvetak/sMaSheD/raw/master/docs/s5.png)

Before every run, FQDNs are automatically resolved via DNS to find any new IP addresses belonging to servers. Return codes of every probe are chronologically stored in the database and available retrospectively.

![Probe results](https://github.com/kvetak/sMaSheD/raw/master/docs/s6.png)

### Evaluation
We need to be sure that our probing tool provides trustworthy results. In order to validate them, we compared the behavior of sMaSheD with official mining software. We decided to use [cgminer 3.7.2](https://github.com/ckolivas/cgminer) because it is well-established and supports all available mining protocols.

We tested both tools over the same set of mining servers and recorded communication into PCAP file. We compared connection success rate (based on textual console outputs) and messages exchanged between miner (either sMaSheD or cgminer) and mining server. We did not find any differences for detected mining servers when comparing sMaSheD and cgminer connection attempts. Both applications used the same set (1983 entries) of IP addresses and ports of alleged mining servers.
sMaSheD system does not send any authentication credentials towards a pool upon the check, the basic response for mining subscription message is enough to mark a device as mining server positively. This is illustrated in Wireshark message captures depicted in following figures:

* Message exchange between cgminer (red) and pool (blue) 
![cgminerpcap](https://github.com/kvetak/sMaSheD/raw/master/docs/wshark-cgminer.png)

* Message exchange between sMaSheD (red) and pool (blue) 
![smashedpcap](https://github.com/kvetak/sMaSheD/raw/master/docs/wshark-smashed.png)



## Programmer's documentation
The programmer's documentation for sMaSheD is autogenerated with the help of phpDox. This documentation is available statically in `docs` [folder](https://github.com/kvetak/sMaSheD/tree/master/docs). Moreover, for your convenience, it is also [available online](https://jane.nesad.fit.vutbr.cz/docs/demixer/index.xhtml) through JANE's [landing page](https://github.com/nesfit/jane-splashscreen/).

### Entity Relationship Diagram
ER corresponding to database scheme:

![ER diagram](https://github.com/kvetak/sMaSheD/raw/master/docs/erdiag.png)
