-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: w.rdc.sae.sina.com.cn:3307
-- Generation Time: Sep 21, 2013 at 09:51 PM
-- Server version: 5.5.23
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `app_qiniutimeline`
--

-- --------------------------------------------------------

--
-- Table structure for table `q_resources`
--

CREATE TABLE IF NOT EXISTS `q_resources` (
  `rid` tinyint(8) NOT NULL AUTO_INCREMENT,
  `bucket` varchar(60) NOT NULL,
  `rkey` varchar(100) NOT NULL,
  `upload_time` char(11) NOT NULL,
  `uid` tinyint(8) NOT NULL,
  `desc` text NOT NULL,
  `mime` varchar(30) NOT NULL,
  PRIMARY KEY (`rid`),
  UNIQUE KEY `qiniu_key` (`bucket`,`rkey`,`uid`),
  KEY `uid` (`uid`),
  KEY `key_index` (`bucket`,`rkey`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `q_user`
--

CREATE TABLE IF NOT EXISTS `q_user` (
  `uid` tinyint(8) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `pswd` varchar(32) NOT NULL COMMENT 'md5',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `name` (`user`),
  UNIQUE KEY `verifymatch` (`pswd`,`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

