-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: w.rdc.sae.sina.com.cn:3307
-- Generation Time: Jan 17, 2013 at 09:35 AM
-- Server version: 5.5.23
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `app_tt4s`
--

-- --------------------------------------------------------

--
-- Table structure for table `activecode`
--

CREATE TABLE IF NOT EXISTS `activecode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL,
  `creator_uid` int(11) NOT NULL,
  `timeline` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE IF NOT EXISTS `attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=file;2=image;3=doc',
  `path` varchar(255) NOT NULL,
  `tid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `timeline` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fid` (`fid`),
  KEY `tid` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fid` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `timeline` datetime DEFAULT NULL,
  `uid` int(11) NOT NULL,
  `device` varchar(16) DEFAULT 'web',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `feed`
--

CREATE TABLE IF NOT EXISTS `feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` mediumtext NOT NULL,
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL DEFAULT '0',
  `reblog_id` int(11) NOT NULL DEFAULT '0' COMMENT '0=no_relog',
  `timeline` datetime DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=normal,1=notice,2=todo,3=user activity,4=cast',
  `device` varchar(16) DEFAULT 'web',
  `comment_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `keyvalue`
--

CREATE TABLE IF NOT EXISTS `keyvalue` (
  `key` varchar(64) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from_uid` int(11) NOT NULL COMMENT 'from_uid=0表示系统消息',
  `to_uid` int(11) NOT NULL,
  `from_delete` tinyint(1) NOT NULL DEFAULT '0',
  `to_delete` tinyint(1) NOT NULL DEFAULT '0',
  `timeline` datetime DEFAULT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notice`
--

CREATE TABLE IF NOT EXISTS `notice` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `to_uid` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=todo;2=feed',
  `content` varchar(255) NOT NULL,
  `data` varchar(255) DEFAULT NULL,
  `timeline` datetime DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE IF NOT EXISTS `todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `timeline` datetime DEFAULT NULL,
  `owner_uid` int(11) DEFAULT NULL,
  `comment_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `todo_history`
--

CREATE TABLE IF NOT EXISTS `todo_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=action;2=comment',
  `timeline` datetime DEFAULT NULL,
  `device` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `todo_user`
--

CREATE TABLE IF NOT EXISTS `todo_user` (
  `tid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `is_star` int(11) NOT NULL DEFAULT '0',
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `is_follow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否订阅',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=added,2=doing,3=done',
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `last_action_at` datetime DEFAULT NULL,
  UNIQUE KEY `tid` (`tid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `url`
--

CREATE TABLE IF NOT EXISTS `url` (
  `url` varchar(255) NOT NULL,
  `code` varchar(16) NOT NULL,
  KEY `code` (`code`),
  KEY `url` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `online`
--
CREATE TABLE IF NOT EXISTS `online` (
  `uid` int(11) NOT NULL,
  `last_active` datetime NOT NULL,
  `session` varchar(32) NOT NULL,
  `device` varchar(32) DEFAULT NULL,
  `place` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `last_active` (`last_active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `plugin`
--
CREATE TABLE  `plugin` (
`folder_name` VARCHAR( 32 ) NOT NULL ,
`on` TINYINT( 1 ) NOT NULL DEFAULT  '0',
PRIMARY KEY (  `folder_name` )
) ENGINE = MYISAM ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'uid',
  `name` varchar(32) NOT NULL,
  `pinyin` varchar(32) DEFAULT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(32) NOT NULL COMMENT 'md5后的值',
  `avatar_small` varchar(255) DEFAULT NULL,
  `avatar_normal` varchar(255) DEFAULT NULL,
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户组',
  `timeline` datetime DEFAULT NULL,
  `settings` mediumtext,
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `mobile` varchar(32) DEFAULT NULL,
  `tel` varchar(32) DEFAULT NULL,
  `eid` varchar(32) DEFAULT NULL COMMENT '员工号',
  `weibo` varchar(32) DEFAULT NULL,
  `desp` text,
  `groups` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`),
  KEY `is_closed` (`is_closed`),
  KEY `groups` (`groups`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `name`, `pinyin`, `email`, `password`, `avatar_small`, `avatar_normal`, `level`, `timeline`, `settings`, `is_closed`, `mobile`, `tel`, `eid`, `weibo`, `desp`) VALUES
(1, 'admin', 'admin', 'member@teamtoy.net', '{password}', '', '', 9, '2012-11-26 17:37:03', '', 0, '', '', '', '', '');
