CREATE TABLE `onBeforeRequest` (
  `_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `time` int(10) unsigned NOT NULL COMMENT '时间',
  `url` varchar(1023) NOT NULL COMMENT '访问地址',
  `requestId` int(10) unsigned NOT NULL COMMENT '页面请求ID',
  `type` varchar(63) NOT NULL COMMENT '类型',
  `detail` text NOT NULL COMMENT 'JSON详细对象',
  `ua` varchar(255) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `onBeforeSendHeaders` (
  `_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `time` int(10) unsigned NOT NULL COMMENT '时间',
  `url` varchar(1023) NOT NULL COMMENT '访问地址',
  `requestId` int(10) unsigned NOT NULL COMMENT '页面请求ID',
  `type` varchar(63) NOT NULL COMMENT '类型',
  `detail` text NOT NULL COMMENT 'JSON详细对象',
  `ua` varchar(255) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `onCompleted` (
  `_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `time` int(10) unsigned NOT NULL COMMENT '时间',
  `url` varchar(1023) NOT NULL COMMENT '访问地址',
  `requestId` int(10) unsigned NOT NULL COMMENT '页面请求ID',
  `type` varchar(63) NOT NULL COMMENT '类型',
  `detail` text NOT NULL COMMENT 'JSON详细对象',
  `ua` varchar(255) NOT NULL,
  PRIMARY KEY (`_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
