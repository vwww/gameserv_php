-- slime server

CREATE TABLE IF NOT EXISTS `clients` (
  `secret` char(40) NOT NULL,
  `playername` varchar(16) NOT NULL,
  `color` mediumint(8) unsigned NOT NULL,
  `last_receive` bigint(20) unsigned NOT NULL,
  `ping` smallint(5) unsigned NOT NULL,
  `score` tinyint(3) unsigned NOT NULL,
  `ballx` double NOT NULL,
  `bally` double NOT NULL,
  PRIMARY KEY (`secret`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `messages` (
  `target` char(40) NOT NULL,
  `type` varchar(8) NOT NULL,
  `payload` varchar(1000) NOT NULL
) ENGINE=MEMORY DEFAULT CHARSET=latin1;
