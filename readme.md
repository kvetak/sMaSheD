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
* PostgreSQL 12
