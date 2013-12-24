CREATE TABLE IF NOT EXISTS `california_nodes` (
  `node_id` int(11) unsigned NOT NULL,
  `lat` varchar(20) NOT NULL DEFAULT '',
  `lon` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `california_relation_members` (
  `relation_id` int(11) unsigned NOT NULL,
  `ref` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`relation_id`,`ref`,`type`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `california_relations` (
  `relation_id` int(11) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `shoreline` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `california_way_nodes` (
  `way_id` int(11) unsigned NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`way_id`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `california_ways` (
  `way_id` int(11) NOT NULL DEFAULT '0',
  `shoreline` decimal(10,5) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`way_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `florida_nodes` (
  `node_id` int(11) unsigned NOT NULL,
  `lat` varchar(20) NOT NULL DEFAULT '',
  `lon` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `florida_relation_members` (
  `relation_id` int(11) unsigned NOT NULL,
  `ref` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`relation_id`,`ref`,`type`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `florida_relations` (
  `relation_id` int(11) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `shoreline` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `florida_way_nodes` (
  `way_id` int(11) unsigned NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`way_id`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `florida_ways` (
  `way_id` int(11) NOT NULL DEFAULT '0',
  `shoreline` decimal(10,5) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`way_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `hawaii_nodes` (
  `node_id` int(11) unsigned NOT NULL,
  `lat` varchar(20) NOT NULL DEFAULT '',
  `lon` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `hawaii_relation_members` (
  `relation_id` int(11) unsigned NOT NULL,
  `ref` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`relation_id`,`ref`,`type`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `hawaii_relations` (
  `relation_id` int(11) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `shoreline` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `hawaii_way_nodes` (
  `way_id` int(11) unsigned NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`way_id`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `hawaii_ways` (
  `way_id` int(11) NOT NULL DEFAULT '0',
  `shoreline` decimal(10,5) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`way_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `minnesota_nodes` (
  `node_id` int(11) unsigned NOT NULL,
  `lat` varchar(20) NOT NULL DEFAULT '',
  `lon` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `minnesota_relation_members` (
  `relation_id` int(11) unsigned NOT NULL,
  `ref` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`relation_id`,`ref`,`type`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `minnesota_relations` (
  `relation_id` int(11) unsigned NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `shoreline` decimal(10,5) DEFAULT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `minnesota_way_nodes` (
  `way_id` int(11) unsigned NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`way_id`,`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `minnesota_ways` (
  `way_id` int(11) NOT NULL DEFAULT '0',
  `shoreline` decimal(10,5) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`way_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;