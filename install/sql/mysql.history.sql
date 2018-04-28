
# Table structure for table `history`
#

CREATE TABLE `history` (
  `hid` mediumint(64) unsigned NOT NULL auto_increment,
  `hits` mediumint(64) unsigned NOT NULL default 0,
  `typal` enum('ipv4','ipv6','netbios','unknown') NOT NULL default 'unknown',
  `value` varchar(300) NOT NULL default 'localhost',
  `ipv4` varchar(15) NOT NULL default '127.0.0.1',
  `ipv6` varchar(64) NOT NULL default '::1',
  `created` int(13) unsigned NOT NULL default 0,
  `queried` int(13) unsigned NOT NULL default 0,
  PRIMARY KEY  (`hid`),
  KEY typalvalue (`typal`,`value`(32)),
  KEY ipv4ipv6created (`ipv4`,`ipv6`,`created`)
) ENGINE=INNODB;
