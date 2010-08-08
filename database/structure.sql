SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `stops`;

CREATE TABLE `stops` (
  `id` int(11) NOT NULL auto_increment,
  `intersection` varchar(128) NOT NULL,
  `lat` decimal(20,12) NOT NULL,
  `long` decimal(20,12) NOT NULL,
  `route_id` int(11) NOT NULL,
  `stop_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `routes`;

CREATE TABLE `routes` (
  `id` int(11) NOT NULL auto_increment,
  `number` varchar(4) NOT NULL,
  `name` varchar(128) NOT NULL,
  `north` int(1) NOT NULL,
  `south` int(1) NOT NULL,
  `east` int(1) NOT NULL,
  `west` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=latin1;


SET FOREIGN_KEY_CHECKS = 1;
