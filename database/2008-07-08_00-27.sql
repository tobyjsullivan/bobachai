-- phpMyAdmin SQL Dump
-- version 2.11.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 08, 2008 at 12:27 AM
-- Server version: 5.0.38
-- PHP Version: 5.2.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `bubbletea`
--

-- --------------------------------------------------------

--
-- Table structure for table `dictionary`
--

CREATE TABLE IF NOT EXISTS `dictionary` (
  `phrase_id` int(5) unsigned zerofill NOT NULL auto_increment,
  `phrase_tag` varchar(256) character set ascii collate ascii_bin NOT NULL,
  `en` longtext character set utf8 collate utf8_bin NOT NULL,
  `es` longtext character set utf8 collate utf8_bin NOT NULL,
  PRIMARY KEY  (`phrase_id`),
  UNIQUE KEY `key` (`phrase_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `dictionary`
--

INSERT INTO `dictionary` (`phrase_id`, `phrase_tag`, `en`, `es`) VALUES
(00001, 'boba_chai', 0x426f62612043686169, 0x62757262756a612074c3a9),
(00002, 'received_bubble_teas', 0x526563656976656420427562626c652054656173, 0x46656368612062757262756a612074c3a973),
(00003, 'sent_bubble_teas', 0x53656e7420427562626c652054656173, 0x456e766961646f2062757262756a612074c3a9),
(00004, 'manage_profile', 0x4d616e6167652050726f66696c65, 0x41646d696e6973747261722070657266696c),
(00005, 'send_bubble_tea', 0x53656e6420427562626c6520546561, 0x456e766961722062757262756a612074c3a9),
(00006, 'ingredient_shop', 0x496e6772656469656e742053686f70, 0x496e6772656469656e7465207469656e6461),
(00007, 'profile_settings', 0x50726f66696c652053657474696e6773, 0x436f6e6669677572616369c3b36e2064656c2070657266696c),
(00008, 'mix_bubble_tea', 0x4d6978206120627562626c65207465613a, 0x4d657a636c617220756e612062757262756a612064652074c3a93a),
(00009, 'make_type', 0x547970653a, 0x5469706f3a),
(00010, 'make_flavor', 0x466c61766f723a, 0x5361626f723a),
(00011, 'make_filling', 0x46696c6c696e673a, 0x52656c6c656e6f3a),
(00012, 'bank_balance', 0x436173683a, 0x456665637469766f3a),
(00013, 'type_milk_tea', 0x4d696c6b20546561, 0x4c656368652074c3a9),
(00014, 'type_green_tea', 0x477265656e20546561, 0x456c2074c3a9207665726465),
(00015, 'type_black_tea', 0x426c61636b20546561, 0x54c3a9206e6567726f),
(00016, 'flavor_taro', 0x5461726f, 0x5461726f),
(00017, 'flavor_strawberry', 0x53747261776265727279, 0x4672657361),
(00018, 'filling_black_pearls', 0x426c61636b20506561726c73, 0x4e6567726f207065726c6173),
(00019, 'filling_coconut_jellies', 0x436f636f6e7574204a656c6c696573, 0x436f636f206a616c656173);

-- --------------------------------------------------------

--
-- Table structure for table `tea_fillings`
--

CREATE TABLE IF NOT EXISTS `tea_fillings` (
  `filling_id` int(2) unsigned zerofill NOT NULL auto_increment,
  `filling_tag` varchar(256) character set ascii collate ascii_bin NOT NULL,
  `phrase_tag` varchar(256) character set ascii collate ascii_bin NOT NULL,
  PRIMARY KEY  (`filling_id`),
  UNIQUE KEY `filling_tag` (`filling_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tea_fillings`
--

INSERT INTO `tea_fillings` (`filling_id`, `filling_tag`, `phrase_tag`) VALUES
(01, 'black_pearls', 'filling_black_pearls'),
(02, 'coconut_jellies', 'filling_coconut_jellies');

-- --------------------------------------------------------

--
-- Table structure for table `tea_flavors`
--

CREATE TABLE IF NOT EXISTS `tea_flavors` (
  `flavor_id` int(3) unsigned zerofill NOT NULL auto_increment,
  `type_id` int(2) unsigned zerofill NOT NULL,
  `flavor_tag` varchar(256) character set ascii collate ascii_bin NOT NULL,
  `phrase_tag` varchar(256) character set ascii collate ascii_bin NOT NULL,
  `tea_color_code` varchar(6) character set ascii NOT NULL,
  PRIMARY KEY  (`flavor_id`),
  UNIQUE KEY `flavor_tag` (`flavor_tag`),
  KEY `type_id` (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tea_flavors`
--

INSERT INTO `tea_flavors` (`flavor_id`, `type_id`, `flavor_tag`, `phrase_tag`, `tea_color_code`) VALUES
(001, 01, 'taro', 'flavor_taro', '99CCFF'),
(002, 01, 'strawberry', 'flavor_strawberry', 'FFCCFF');

-- --------------------------------------------------------

--
-- Table structure for table `tea_types`
--

CREATE TABLE IF NOT EXISTS `tea_types` (
  `type_id` int(2) unsigned zerofill NOT NULL auto_increment,
  `type_tag` varchar(256) character set armscii8 collate armscii8_bin NOT NULL,
  `phrase_tag` varchar(256) character set ascii collate ascii_bin NOT NULL,
  PRIMARY KEY  (`type_id`),
  UNIQUE KEY `type_tag` (`type_tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tea_types`
--

INSERT INTO `tea_types` (`type_id`, `type_tag`, `phrase_tag`) VALUES
(01, 'milk_tea', 'type_milk_tea'),
(02, 'green_tea', 'type_green_tea'),
(03, 'black_tea', 'type_black_tea');
