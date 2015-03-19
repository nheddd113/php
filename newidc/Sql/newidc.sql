/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : newidc

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-03-19 17:44:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `test_table`
-- ----------------------------
DROP TABLE IF EXISTS `test_table`;
CREATE TABLE `test_table` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `client_main` varchar(200) NOT NULL,
  `client_pay` varchar(200) NOT NULL,
  `client_center` varchar(200) NOT NULL,
  `client_reg` varchar(200) NOT NULL,
  `client_version` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_main` (`client_main`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of test_table
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_cupboard`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_cupboard`;
CREATE TABLE `uqee_cupboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cupname` varchar(50) NOT NULL DEFAULT '',
  `houid` int(11) NOT NULL DEFAULT '0' COMMENT 'æœºæˆ¿ID',
  `seatnum` int(11) NOT NULL DEFAULT '0' COMMENT 'æ€»æœ‰å¤šå°‘ä¸ªæœºä½',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `changetime` int(10) NOT NULL DEFAULT '0',
  `price` int(10) NOT NULL DEFAULT '0',
  `remark` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=87 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_cupboard
-- ----------------------------


-- ----------------------------
-- Table structure for `uqee_game`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_game`;
CREATE TABLE `uqee_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '',
  `alias` varchar(20) NOT NULL DEFAULT '',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `handler` varchar(20) NOT NULL DEFAULT '' COMMENT 'å¡«åŠ è€…',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_game
-- ----------------------------


-- ----------------------------
-- Table structure for `uqee_hostamount`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_hostamount`;
CREATE TABLE `uqee_hostamount` (
  `time` varchar(20) NOT NULL,
  `data` text NOT NULL,
  `updatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_hostamount
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_hostlist`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_hostlist`;
CREATE TABLE `uqee_hostlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sertag` varchar(20) NOT NULL DEFAULT '' COMMENT 'DELLæœåŠ¡ç¼–ç ',
  `houid` int(11) NOT NULL DEFAULT '0' COMMENT 'æœºæˆ¿ID',
  `cupid` int(11) NOT NULL DEFAULT '0' COMMENT 'æœºæŸœID',
  `seatid` int(11) NOT NULL DEFAULT '0' COMMENT 'æœºä½',
  `hostid` varchar(30) NOT NULL DEFAULT '' COMMENT 'èµ„äº§ç¼–ç ',
  `mainip` varchar(20) NOT NULL DEFAULT '',
  `subip` varchar(20) NOT NULL DEFAULT '',
  `innip` varchar(20) NOT NULL DEFAULT '',
  `cpu` varchar(20) NOT NULL DEFAULT '',
  `mem` varchar(20) NOT NULL DEFAULT '',
  `disk` varchar(20) NOT NULL DEFAULT '',
  `system` varchar(20) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0ä¸‹æž¶,1é—²ç½®,2ä¸Šæž¶',
  `hostype` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0æœªè¿è¥,1ç”³è¯·,2è¿è¥',
  `owner` varchar(20) NOT NULL DEFAULT '' COMMENT 'è¿è¥å•†ID',
  `uuid` varchar(128) NOT NULL,
  `ishost` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:æœåŠ¡å™¨,1:å®¿ä¸»æœº,2:è™šæ‹Ÿæœº,3:æ•°æ®ä¸­å¿ƒ',
  `parentid` int(11) NOT NULL DEFAULT '0' COMMENT 'å®¿ä¸»æœºID',
  `ismanager` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0éžæ‰˜ç®¡,1æ‰˜ç®¡',
  `gameid` int(11) NOT NULL DEFAULT '0',
  `pretime` varchar(30) NOT NULL DEFAULT '' COMMENT 'é¢„è®¡å¼€æœæ—¶é—´',
  `starttime` varchar(30) NOT NULL DEFAULT '',
  `changetime` int(10) NOT NULL DEFAULT '0',
  `remark` varchar(100) NOT NULL DEFAULT '',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `templateid` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hostid_idx` (`hostid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=777651 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_hostlist
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_hosttemplate`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_hosttemplate`;
CREATE TABLE `uqee_hosttemplate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `cpu` varchar(20) NOT NULL DEFAULT '' COMMENT 'æ¨¡æ¿CUP',
  `disk` int(5) NOT NULL,
  `mem` int(5) NOT NULL,
  `price` int(10) NOT NULL DEFAULT '18000',
  `one` int(5) NOT NULL DEFAULT '45',
  `two` int(5) NOT NULL DEFAULT '30',
  `three` int(5) NOT NULL DEFAULT '25',
  `other` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_hosttemplate
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_house`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_house`;
CREATE TABLE `uqee_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `houname` varchar(60) NOT NULL DEFAULT '',
  `company` varchar(60) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL DEFAULT '',
  `telphone` varchar(15) NOT NULL DEFAULT '',
  `linkqq` varchar(15) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `remark` varchar(100) NOT NULL DEFAULT '',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bandwidth` int(5) NOT NULL DEFAULT '0',
  `price` int(5) NOT NULL DEFAULT '0',
  `changetime` int(10) NOT NULL DEFAULT '0',
  `place` varchar(30) NOT NULL DEFAULT '' COMMENT '机房所在区域',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_house
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_iplist`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_iplist`;
CREATE TABLE `uqee_iplist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mainip` varchar(20) NOT NULL DEFAULT '' COMMENT 'ç”µä¿¡IP',
  `mainmask` varchar(20) NOT NULL DEFAULT '' COMMENT 'ç”µä¿¡æŽ©ç ',
  `maingw` varchar(20) NOT NULL DEFAULT '' COMMENT 'ç”µä¿¡ç½‘å…³',
  `subip` varchar(20) NOT NULL DEFAULT '' COMMENT 'åŒçº¿ç¬¬äºŒIP',
  `submask` varchar(20) NOT NULL DEFAULT '' COMMENT 'åŒçº¿ç¬¬äºŒIPæŽ©ç ',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:æœªä½¿ç”¨,1:å·²ä½¿ç”¨',
  `houid` int(11) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6485 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_iplist
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_log`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_log`;
CREATE TABLE `uqee_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hostid` varchar(30) NOT NULL DEFAULT '' COMMENT 'èµ„äº§ç¼–ç ',
  `chghostid` varchar(30) NOT NULL DEFAULT '' COMMENT 'äº¤æ¢çš„æœåŠ¡å™¨id',
  `hostdbid` int(11) NOT NULL COMMENT 'ä¸»æœºæ•°æ®ID',
  `serip` varchar(20) NOT NULL DEFAULT '' COMMENT 'æœåŠ¡å™¨IP',
  `content` varchar(500) NOT NULL DEFAULT '',
  `handler` varchar(20) NOT NULL DEFAULT '',
  `logtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12859 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_log
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_monitor`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_monitor`;
CREATE TABLE `uqee_monitor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '监控名称',
  `nagios` varchar(100) NOT NULL DEFAULT '',
  `cacti` varchar(100) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_monitor
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_notify`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_notify`;
CREATE TABLE `uqee_notify` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `state` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_notify
-- ----------------------------
INSERT INTO `uqee_notify` VALUES ('1', '1');

-- ----------------------------
-- Table structure for `uqee_ops`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_ops`;
CREATE TABLE `uqee_ops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seq` int(11) NOT NULL DEFAULT '0' COMMENT 'åŽå°ä¼ è¿‡æ¥çš„è¿è¥å•†ID',
  `name` varchar(30) NOT NULL DEFAULT '',
  `gameid` int(11) NOT NULL COMMENT 'æ¸¸æˆID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=210 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_ops
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_salt`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_salt`;
CREATE TABLE `uqee_salt` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `houseid` int(5) DEFAULT NULL,
  `host` varchar(128) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `houseid` (`houseid`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_salt
-- ----------------------------
INSERT INTO `uqee_salt` VALUES ('1', '1', '122.226.111.35', '9123');
INSERT INTO `uqee_salt` VALUES ('2', '5', '223.202.32.165', '8011');
INSERT INTO `uqee_salt` VALUES ('3', '14', '223.202.45.25', '8011');
INSERT INTO `uqee_salt` VALUES ('4', '28', '122.226.111.35', '9123');

-- ----------------------------
-- Table structure for `uqee_seat`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_seat`;
CREATE TABLE `uqee_seat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cupid` int(11) NOT NULL DEFAULT '0',
  `seatid` int(11) NOT NULL DEFAULT '0',
  `state` tinyint(4) DEFAULT '0' COMMENT '0:æœªä½¿ç”¨,1:å·²ä½¿ç”¨',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `changetime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2562 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_seat
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_session`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_session`;
CREATE TABLE `uqee_session` (
  `session_id` varchar(255) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` blob,
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_session
-- ----------------------------

-- ----------------------------
-- Table structure for `uqee_user`
-- ----------------------------
DROP TABLE IF EXISTS `uqee_user`;
CREATE TABLE `uqee_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `loginname` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `realname` varchar(30) NOT NULL DEFAULT '',
  `lastloginip` varchar(20) NOT NULL DEFAULT '',
  `lastlogintime` int(10) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL DEFAULT '1',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of uqee_user
-- ----------------------------

