# Game Server in PHP

This game server supports two Slime Volleyball Multiplayer game clients, running as Flash games hosted on any site domain.

It runs on PHP/MySQL because many free hosts support it. However, it supports only two clients per server, without spectators, which is somewhat wasteful, so the next step would be to improve this server to support multiple pairs of clients.

`crossdomain.xml` allows Flash clients hosted on other sites to connect, and it also allows master-servers to verify that the server has been properly installed at a given URL.

This project has been superseded by [gameserv_go](https://github.com/vwww/gameserv_go), which has been superseded by [gameserv_playerio](https://github.com/vwww/gameserv_playerio).
