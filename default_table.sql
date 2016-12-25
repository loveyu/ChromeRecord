CREATE DATABASE `chrome_record` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

CREATE TABLE `onBeforeRequest` (
  `_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `time` int(10) unsigned NOT NULL COMMENT '时间',
  `url` varchar(1023) NOT NULL COMMENT '访问地址',
  `requestId` int(10) unsigned NOT NULL COMMENT '页面请求ID',
  `type` varchar(63) NOT NULL COMMENT '类型',
  `detail` text NOT NULL COMMENT 'JSON详细对象',
  `ua` varchar(255) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB;

CREATE TABLE `onBeforeSendHeaders` (
  `_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `time` int(10) unsigned NOT NULL COMMENT '时间',
  `url` varchar(1023) NOT NULL COMMENT '访问地址',
  `requestId` int(10) unsigned NOT NULL COMMENT '页面请求ID',
  `type` varchar(63) NOT NULL COMMENT '类型',
  `detail` text NOT NULL COMMENT 'JSON详细对象',
  `ua` varchar(255) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB;

CREATE TABLE `onCompleted` (
  `_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `time` int(10) unsigned NOT NULL COMMENT '时间',
  `url` varchar(1023) NOT NULL COMMENT '访问地址',
  `requestId` int(10) unsigned NOT NULL COMMENT '页面请求ID',
  `type` varchar(63) NOT NULL COMMENT '类型',
  `detail` text NOT NULL COMMENT 'JSON详细对象',
  `ua` varchar(255) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB;

CREATE TABLE `onview` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '递增ID',
  `tab_id` int(11) DEFAULT NULL,
  `url` varchar(1023) NOT NULL COMMENT '网址',
  `title` varchar(1023) NOT NULL COMMENT '标题',
  `referrer` varchar(255) NOT NULL,
  `datetime` datetime(3) NOT NULL COMMENT '访问时间',
  `add_time` int(10) unsigned NOT NULL COMMENT '记录添加时间',
  `type` varchar(32) NOT NULL COMMENT '文档类型',
  `uid` char(36) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

