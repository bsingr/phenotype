-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 17. November 2008 um 14:32
-- Server Version: 5.0.51
-- PHP-Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `phenotype`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `action`
--

DROP TABLE IF EXISTS `action`;
CREATE TABLE IF NOT EXISTS `action` (
  `act_id` int(11) NOT NULL auto_increment,
  `act_status` tinyint(4) NOT NULL default '0',
  `act_bez` varchar(50) collate latin1_general_ci NOT NULL default '',
  `act_description` text collate latin1_general_ci NOT NULL,
  `act_nextrun` int(11) NOT NULL default '0',
  `act_laststart` int(11) NOT NULL default '0',
  `act_lastrun` int(11) NOT NULL default '0',
  `act_runstatus` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`act_id`),
  KEY `act_nextrun` (`act_nextrun`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `action`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `component`
--

DROP TABLE IF EXISTS `component`;
CREATE TABLE IF NOT EXISTS `component` (
  `com_id` int(11) NOT NULL auto_increment,
  `com_bez` varchar(150) collate latin1_general_ci NOT NULL default '',
  `com_description` text collate latin1_general_ci NOT NULL,
  `com_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`com_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `component`
--

INSERT INTO `component` (`com_id`, `com_bez`, `com_description`, `com_rubrik`) VALUES
(1002, 'HTML', '## Component 1002 - HTML', 'System'),
(1003, 'Include (Function)', '## Component 1003 - Include', 'System'),
(1001, 'Richtext', '## Component 1001 - Richtext\n\n		This basic component fits many needs of simple web pages. It offers headlines, formatted text, images and links.\n		', 'Text');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `componentgroup`
--

DROP TABLE IF EXISTS `componentgroup`;
CREATE TABLE IF NOT EXISTS `componentgroup` (
  `cog_id` int(11) NOT NULL auto_increment,
  `cog_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `cog_description` text collate latin1_general_ci NOT NULL,
  `cog_pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `componentgroup`
--

INSERT INTO `componentgroup` (`cog_id`, `cog_bez`, `cog_description`, `cog_pos`) VALUES
(1, 'Default', '## Default', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `component_componentgroup`
--

DROP TABLE IF EXISTS `component_componentgroup`;
CREATE TABLE IF NOT EXISTS `component_componentgroup` (
  `cog_id` int(11) NOT NULL default '0',
  `com_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cog_id`,`com_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `component_componentgroup`
--

INSERT INTO `component_componentgroup` (`cog_id`, `com_id`) VALUES
(1, 1001),
(1, 1002),
(1, 1003);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `component_template`
--

DROP TABLE IF EXISTS `component_template`;
CREATE TABLE IF NOT EXISTS `component_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `com_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`com_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `component_template`
--

INSERT INTO `component_template` (`tpl_id`, `com_id`, `tpl_bez`) VALUES
(1, 1001, 'TPL_DEFAULT');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content`
--

DROP TABLE IF EXISTS `content`;
CREATE TABLE IF NOT EXISTS `content` (
  `con_id` int(11) NOT NULL auto_increment,
  `con_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `con_description` text collate latin1_general_ci NOT NULL,
  `con_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  `con_pos` int(1) NOT NULL default '0',
  `con_props` text collate latin1_general_ci NOT NULL,
  `con_anlegen` tinyint(4) NOT NULL default '1',
  `con_bearbeiten` tinyint(4) NOT NULL default '1',
  `con_loeschen` tinyint(4) NOT NULL default '1',
  `con_exportieren` tinyint(4) NOT NULL default '0',
  `con_importieren` tinyint(4) NOT NULL default '0',
  `con_statistik` int(11) NOT NULL default '0',
  PRIMARY KEY  (`con_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=1 AUTO_INCREMENT=1;

--
-- Daten für Tabelle `content`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_data`
--

DROP TABLE IF EXISTS `content_data`;
CREATE TABLE `content_data` (
  `dat_id` int(11) NOT NULL auto_increment,
  `dat_uid` varchar(32) collate latin1_general_ci NOT NULL default '0',
  `dat_status` tinyint(4) NOT NULL default '0',
  `dat_cache1` int(11) NOT NULL default '0',
  `dat_cache2` int(11) NOT NULL default '0',
  `dat_cache3` int(11) NOT NULL default '0',
  `dat_cache4` int(11) NOT NULL default '0',
  `dat_cache5` int(11) NOT NULL default '0',
  `dat_cache6` int(11) NOT NULL default '0',
  `dat_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `con_id` int(11) NOT NULL default '0',
  `dat_props` longtext collate latin1_general_ci NOT NULL,
  `usr_id_creator` int(11) NOT NULL default '0',
  `dat_creationdate` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `dat_date` int(11) NOT NULL default '0',
  `dat_pos` int(11) NOT NULL default '0',
  `dat_key1` varchar(100) collate latin1_general_ci default NULL,
  `dat_key2` varchar(100) collate latin1_general_ci default NULL,
  `dat_key3` varchar(100) collate latin1_general_ci default NULL,
  `dat_key4` varchar(100) collate latin1_general_ci default NULL,
  `dat_key5` varchar(100) collate latin1_general_ci default NULL,
  `dat_key6` varchar(100) collate latin1_general_ci default NULL,
  `dat_ikey1` int(11) NOT NULL default '0',
  `dat_ikey2` int(11) NOT NULL default '0',
  `dat_ikey3` int(11) NOT NULL default '0',
  `dat_ikey4` int(11) NOT NULL default '0',
  `dat_ikey5` int(11) NOT NULL default '0',
  `dat_ikey6` int(11) NOT NULL default '0',
  `dat_fullsearch` text collate latin1_general_ci NOT NULL,
  `med_id_thumb` int(11) NOT NULL default '0',
  `dat_permalink` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink1` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink2` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink3` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink4` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink5` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink6` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink7` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink8` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink9` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_altered` tinyint(4) NOT NULL,
  PRIMARY KEY  (`dat_id`),
  KEY `con_id` (`con_id`),
  KEY `dat_ikey1` (`dat_ikey1`),
  KEY `dat_ikey2` (`dat_ikey2`),
  KEY `dat_ikey3` (`dat_ikey3`),
  KEY `dat_ikey4` (`dat_ikey4`),
  KEY `dat_ikey5` (`dat_ikey5`),
  KEY `default_select` (`con_id`,`dat_status`),
  KEY `dat_ikey6` (`dat_ikey6`),
  FULLTEXT KEY `dat_fullsearch` (`dat_fullsearch`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `content_data`
--


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `content_data_editbuffer`
-- 

DROP TABLE IF EXISTS `content_data_editbuffer`;
CREATE TABLE `content_data_editbuffer` (
  `dat_id` int(11) NOT NULL auto_increment,
  `dat_uid` varchar(32) collate latin1_general_ci NOT NULL default '0',
  `dat_status` tinyint(4) NOT NULL default '0',
  `dat_cache1` int(11) NOT NULL default '0',
  `dat_cache2` int(11) NOT NULL default '0',
  `dat_cache3` int(11) NOT NULL default '0',
  `dat_cache4` int(11) NOT NULL default '0',
  `dat_cache5` int(11) NOT NULL default '0',
  `dat_cache6` int(11) NOT NULL default '0',
  `dat_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `con_id` int(11) NOT NULL default '0',
  `dat_props` longtext collate latin1_general_ci NOT NULL,
  `usr_id_creator` int(11) NOT NULL default '0',
  `dat_creationdate` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `dat_date` int(11) NOT NULL default '0',
  `dat_pos` int(11) NOT NULL default '0',
  `dat_key1` varchar(100) collate latin1_general_ci default NULL,
  `dat_key2` varchar(100) collate latin1_general_ci default NULL,
  `dat_key3` varchar(100) collate latin1_general_ci default NULL,
  `dat_key4` varchar(100) collate latin1_general_ci default NULL,
  `dat_key5` varchar(100) collate latin1_general_ci default NULL,
  `dat_key6` varchar(100) collate latin1_general_ci default NULL,
  `dat_ikey1` int(11) NOT NULL default '0',
  `dat_ikey2` int(11) NOT NULL default '0',
  `dat_ikey3` int(11) NOT NULL default '0',
  `dat_ikey4` int(11) NOT NULL default '0',
  `dat_ikey5` int(11) NOT NULL default '0',
  `dat_ikey6` int(11) NOT NULL default '0',
  `dat_fullsearch` text collate latin1_general_ci NOT NULL,
  `med_id_thumb` int(11) NOT NULL default '0',
  `dat_permalink` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink1` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink2` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink3` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink4` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink5` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink6` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink7` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink8` varchar(255) collate latin1_general_ci NOT NULL,
  `dat_permalink9` varchar(255) collate latin1_general_ci NOT NULL,
  KEY `con_id` (`con_id`),
  KEY `dat_ikey1` (`dat_ikey1`),
  KEY `dat_ikey2` (`dat_ikey2`),
  KEY `dat_ikey3` (`dat_ikey3`),
  KEY `dat_ikey4` (`dat_ikey4`),
  KEY `dat_ikey5` (`dat_ikey5`),
  KEY `default_select` (`con_id`,`dat_status`),
  KEY `dat_ikey6` (`dat_ikey6`),
  KEY `dat_id` (`dat_id`),
  FULLTEXT KEY `dat_fullsearch` (`dat_fullsearch`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_statistics`
--

DROP TABLE IF EXISTS `content_statistics`;
CREATE TABLE IF NOT EXISTS `content_statistics` (
  `dat_id` int(11) NOT NULL default '0',
  `sta_datum` int(11) NOT NULL default '0',
  `sta_contentview` int(11) NOT NULL default '0',
  KEY `sta_day` (`sta_datum`),
  KEY `exd_id` (`dat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `content_statistics`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_template`
--

DROP TABLE IF EXISTS `content_template`;
CREATE TABLE IF NOT EXISTS `content_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `con_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`con_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `content_template`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dataobject`
--

DROP TABLE IF EXISTS `dataobject`;
CREATE TABLE IF NOT EXISTS `dataobject` (
  `dao_id` int(11) NOT NULL auto_increment,
  `dao_bez` varchar(255) collate latin1_general_ci NOT NULL,
  `dao_params` varchar(255) collate latin1_general_ci NOT NULL,
  `dao_props` longtext collate latin1_general_ci NOT NULL,
  `dao_date` int(11) NOT NULL,
  `dao_ttl` int(11) NOT NULL,
  `dao_type` tinyint(4) NOT NULL,
  `dao_clearonedit` tinyint(4) NOT NULL,
  PRIMARY KEY  (`dao_id`),
  KEY `dao_bez` (`dao_bez`),
  KEY `dao_ttl` (`dao_ttl`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `dataobject`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `extra`
--

DROP TABLE IF EXISTS `extra`;
CREATE TABLE IF NOT EXISTS `extra` (
  `ext_id` int(11) NOT NULL auto_increment,
  `ext_bez` varchar(50) collate latin1_general_ci NOT NULL default '',
  `ext_description` text collate latin1_general_ci NOT NULL,
  `ext_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  `ext_props` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ext_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `extra`
--

INSERT INTO `extra` (`ext_id`, `ext_bez`, `ext_description`, `ext_rubrik`, `ext_props`) VALUES
(1002, 'Console', '', 'Development', 'a:1:{s:5:"color";s:1:"1";}'),
(1001, 'Pagewizard', '', 'Development', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `extra_template`
--

DROP TABLE IF EXISTS `extra_template`;
CREATE TABLE IF NOT EXISTS `extra_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `ext_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`ext_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `extra_template`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `include`
--

DROP TABLE IF EXISTS `include`;
CREATE TABLE IF NOT EXISTS `include` (
  `inc_id` int(11) NOT NULL auto_increment,
  `inc_bez` varchar(50) collate latin1_general_ci NOT NULL default '',
  `inc_description` text collate latin1_general_ci NOT NULL,
  `inc_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  `inc_usage_layout` tinyint(4) NOT NULL default '0',
  `inc_usage_includecomponent` tinyint(4) NOT NULL default '0',
  `inc_usage_page` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`inc_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1;

--
-- Daten für Tabelle `include`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `include_template`
--

DROP TABLE IF EXISTS `include_template`;
CREATE TABLE IF NOT EXISTS `include_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `inc_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`inc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `include_template`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layout`
--

DROP TABLE IF EXISTS `layout`;
CREATE TABLE IF NOT EXISTS `layout` (
  `lay_id` int(11) NOT NULL auto_increment,
  `lay_bez` varchar(100) collate latin1_general_ci NOT NULL default '0',
  `lay_description` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`lay_id`),
  UNIQUE KEY `tpl_id` (`lay_id`),
  KEY `tpl_id_2` (`lay_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `layout`
--

INSERT INTO `layout` (`lay_id`, `lay_bez`, `lay_description`) VALUES
(1, 'Standard', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layout_block`
--

DROP TABLE IF EXISTS `layout_block`;
CREATE TABLE IF NOT EXISTS `layout_block` (
  `lay_id` int(11) NOT NULL default '0',
  `lay_blocknr` int(11) NOT NULL default '0',
  `lay_blockbez` varchar(250) collate latin1_general_ci NOT NULL default '',
  `cog_id` int(11) NOT NULL default '0',
  `lay_context` int(11) NOT NULL default '0',
  PRIMARY KEY  (`lay_id`,`lay_blocknr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `layout_block`
--

INSERT INTO `layout_block` (`lay_id`, `lay_blocknr`, `lay_blockbez`, `cog_id`, `lay_context`) VALUES
(1, 1, 'Content', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layout_include`
--

DROP TABLE IF EXISTS `layout_include`;
CREATE TABLE IF NOT EXISTS `layout_include` (
  `lay_id` int(11) NOT NULL default '0',
  `inc_id` int(11) NOT NULL default '0',
  `lay_includenr` int(11) NOT NULL default '0',
  `lay_includecache` tinyint(4) NOT NULL default '1',
  KEY `tpl_id` (`lay_id`),
  KEY `inc_id` (`inc_id`),
  KEY `inc_nr` (`lay_includenr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `layout_include`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layout_pagegroup`
--

DROP TABLE IF EXISTS `layout_pagegroup`;
CREATE TABLE IF NOT EXISTS `layout_pagegroup` (
  `lay_id` int(11) NOT NULL default '0',
  `grp_id` int(11) NOT NULL default '0',
  KEY `lay_id` (`lay_id`,`grp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `layout_pagegroup`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `med_id` int(11) NOT NULL auto_increment,
  `grp_id` int(11) NOT NULL default '2',
  `med_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `med_versioncount` smallint(6) NOT NULL default '0',
  `med_physical_folder` varchar(50) collate latin1_general_ci NOT NULL default '',
  `med_bez_original` varchar(100) collate latin1_general_ci NOT NULL default '',
  `med_type` tinyint(20) NOT NULL default '0',
  `med_subtype` varchar(10) collate latin1_general_ci NOT NULL default '',
  `med_mimetype` varchar(64) collate latin1_general_ci NOT NULL default '',
  `med_x` int(11) NOT NULL default '0',
  `med_y` int(11) NOT NULL default '0',
  `med_thumb` tinyint(4) NOT NULL default '0',
  `med_alt` varchar(100) collate latin1_general_ci NOT NULL default '',
  `med_keywords` varchar(250) collate latin1_general_ci NOT NULL default '',
  `med_comment` text collate latin1_general_ci NOT NULL,
  `med_logical_folder1` varchar(200) collate latin1_general_ci NOT NULL default '',
  `med_logical_folder2` varchar(200) collate latin1_general_ci NOT NULL default '',
  `med_logical_folder3` varchar(200) collate latin1_general_ci NOT NULL default '',
  `usr_id_creator` int(11) NOT NULL default '0',
  `med_creationdate` int(11) NOT NULL default '0',
  `med_date` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`med_id`),
  KEY `grp_id` (`grp_id`),
  KEY `med_logical_folder1` (`med_logical_folder1`),
  KEY `med_logical_folder2` (`med_logical_folder2`),
  KEY `med_logical_folder3` (`med_logical_folder3`),
  KEY `med_type` (`med_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1;

--
-- Daten für Tabelle `media`
--

INSERT INTO `media` (`med_id`, `grp_id`, `med_bez`, `med_versioncount`, `med_physical_folder`, `med_bez_original`, `med_type`, `med_subtype`, `med_mimetype`, `med_x`, `med_y`, `med_thumb`, `med_alt`, `med_keywords`, `med_comment`, `med_logical_folder1`, `med_logical_folder2`, `med_logical_folder3`, `usr_id_creator`, `med_creationdate`, `med_date`, `usr_id`) VALUES
(1, 1, 'Nils Hagemann', 0, 'I0610420', 'nils.jpg', 1, 'jpg', '', 60, 75, 1, '', '', '', '_system', '', '', 0, 0, 1093458207, 1),
(2, 1, 'Peter Sellinger', 0, 'I0610420', 'peter.jpg', 1, 'jpg', '', 60, 71, 1, '', '', '', '_system', '', '', 0, 0, 1093458201, 1),
(3, 1, 'Paul Sellinger', 0, 'I0610420', 'paul.jpg', 1, 'jpg', '', 60, 77, 1, '', '', '', '_system', '', '', 0, 0, 1093458195, 1),
(4, 1, 'bild.jpg', 0, 'I0610420', 'bild.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1),
(5, 1, 'event.jpg', 0, 'I0610420', 'event.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1),
(6, 1, 'job.jpg', 0, 'I0610420', 'job.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1),
(7, 1, 'konfiguration.jpg', 0, 'I0610420', 'konfiguration.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1),
(8, 1, 'news.jpg', 0, 'I0610420', 'news.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1),
(9, 1, 'promofeld.gif', 0, 'I0610420', 'promofeld.gif', 1, 'gif', '', 60, 40, 0, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1),
(10, 1, 'shop.gif', 0, 'I0610420', 'shop.gif', 1, 'gif', '', 60, 40, 0, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1),
(11, 1, 'Markus Griesbach', 0, 'I0610420', 'maggus.jpg', 1, 'jpg', '', 60, 75, 1, '', '', '', '_system', '', '', 10, 1153063188, 1153063202, 10),
(13, 1, 'Michel', 0, 'I0610420', 'michel.jpg', 1, 'jpg', '', 60, 76, 1, '', '', '', '_system', '', '', 15, 1161453837, 1161453837, 15);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mediagroup`
--

DROP TABLE IF EXISTS `mediagroup`;
CREATE TABLE IF NOT EXISTS `mediagroup` (
  `grp_id` int(11) NOT NULL auto_increment,
  `grp_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `grp_description` text collate latin1_general_ci NOT NULL,
  `grp_type` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`grp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `mediagroup`
--

INSERT INTO `mediagroup` (`grp_id`, `grp_bez`, `grp_description`, `grp_type`) VALUES
(1, 'System', '', 1),
(2, 'Standard', '', 2),
(3, 'Tasks', '', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mediaversion`
--

DROP TABLE IF EXISTS `mediaversion`;
CREATE TABLE IF NOT EXISTS `mediaversion` (
  `ver_id` int(11) NOT NULL auto_increment,
  `med_id` int(11) NOT NULL default '0',
  `ver_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `ver_x` int(11) NOT NULL default '0',
  `ver_y` int(11) NOT NULL default '0',
  `ver_subtype` varchar(10) collate latin1_general_ci NOT NULL default '',
  `ver_mimetype` varchar(64) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`ver_id`),
  KEY `med_id` (`med_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `mediaversion`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page`
--

DROP TABLE IF EXISTS `page`;
CREATE TABLE IF NOT EXISTS `page` (
  `pag_id` int(11) NOT NULL auto_increment,
  `pag_uid` varchar(32) collate latin1_general_ci NOT NULL default '0',
  `pag_id_mimikry` int(11) NOT NULL default '0',
  `ver_id` int(11) NOT NULL default '0',
  `ver_nr` tinyint(4) NOT NULL default '0',
  `grp_id` smallint(6) NOT NULL default '0',
  `pag_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `pag_titel` varchar(250) collate latin1_general_ci NOT NULL default '',
  `pag_alttitel` varchar(100) collate latin1_general_ci NOT NULL default '',
  `pag_comment` text collate latin1_general_ci NOT NULL,
  `pag_status` tinyint(4) NOT NULL default '0',
  `pag_id_top` int(11) NOT NULL default '0',
  `pag_pos` tinyint(4) NOT NULL default '0',
  `pag_cache` int(11) NOT NULL default '0',
  `pag_printcache1` tinyint(4) NOT NULL default '0',
  `pag_printcache2` tinyint(4) NOT NULL default '0',
  `pag_printcache3` tinyint(4) NOT NULL default '0',
  `pag_printcache4` tinyint(4) NOT NULL default '0',
  `pag_printcache5` tinyint(4) NOT NULL default '0',
  `pag_printcache6` tinyint(4) NOT NULL default '0',
  `pag_xmlcache1` tinyint(4) NOT NULL default '0',
  `pag_xmlcache2` tinyint(4) NOT NULL default '0',
  `pag_xmlcache3` tinyint(4) NOT NULL default '0',
  `pag_xmlcache4` tinyint(4) NOT NULL default '0',
  `pag_xmlcache5` tinyint(4) NOT NULL default '0',
  `pag_xmlcache6` tinyint(4) NOT NULL default '0',
  `pag_quickfinder` varchar(100) collate latin1_general_ci NOT NULL default '',
  `pag_searchtext` text collate latin1_general_ci NOT NULL,
  `pag_date` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `usr_id_creator` int(11) NOT NULL default '0',
  `pag_creationdate` int(11) NOT NULL default '0',
  `pag_lastfetch` int(11) NOT NULL default '0',
  `pag_nextbuild1` int(11) NOT NULL default '0',
  `pag_nextbuild2` int(11) NOT NULL default '0',
  `pag_nextbuild3` int(11) NOT NULL default '0',
  `pag_nextbuild4` int(11) NOT NULL default '0',
  `pag_nextbuild5` int(11) NOT NULL default '0',
  `pag_nextbuild6` int(11) NOT NULL default '0',
  `pag_nextversionchange` int(11) NOT NULL default '0',
  `pag_lastbuild_time` varchar(20) collate latin1_general_ci NOT NULL default '',
  `pag_lastcache_time` varchar(20) collate latin1_general_ci NOT NULL default '',
  `pag_lastcachenr` tinyint(4) NOT NULL default '0',
  `pag_ver_nr_max` tinyint(4) NOT NULL default '0',
  `pag_url` varchar(200) collate latin1_general_ci NOT NULL default '',
  `pag_url1` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url2` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url3` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url4` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url5` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url6` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url7` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url8` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url9` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_props` text collate latin1_general_ci NOT NULL,
  `pag_props_all` text collate latin1_general_ci NOT NULL,
  `pag_props_locale` text collate latin1_general_ci NOT NULL,
  `pag_fullsearch` text collate latin1_general_ci NOT NULL,
  `pag_contenttype` smallint(6) NOT NULL default '1',
  `pag_multilanguage` tinyint(4) NOT NULL default '0',
  `pag_adminlock` tinyint(4) NOT NULL,
  `pag_redirect` varchar(250) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`pag_id`),
  UNIQUE KEY `ver_id` (`ver_id`),
  FULLTEXT KEY `pag_fullsearch` (`pag_fullsearch`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1;

--
-- Daten für Tabelle `page`
--

INSERT INTO `page` (`pag_id`, `pag_uid`, `pag_id_mimikry`, `ver_id`, `ver_nr`, `grp_id`, `pag_bez`, `pag_titel`, `pag_alttitel`, `pag_comment`, `pag_status`, `pag_id_top`, `pag_pos`, `pag_cache`, `pag_printcache1`, `pag_printcache2`, `pag_printcache3`, `pag_printcache4`, `pag_printcache5`, `pag_printcache6`, `pag_xmlcache1`, `pag_xmlcache2`, `pag_xmlcache3`, `pag_xmlcache4`, `pag_xmlcache5`, `pag_xmlcache6`, `pag_quickfinder`, `pag_searchtext`, `pag_date`, `usr_id`, `usr_id_creator`, `pag_creationdate`, `pag_lastfetch`, `pag_nextbuild1`, `pag_nextbuild2`, `pag_nextbuild3`, `pag_nextbuild4`, `pag_nextbuild5`, `pag_nextbuild6`, `pag_nextversionchange`, `pag_lastbuild_time`, `pag_lastcache_time`, `pag_lastcachenr`, `pag_ver_nr_max`, `pag_url`, `pag_url1`, `pag_url2`, `pag_url3`, `pag_url4`, `pag_url5`, `pag_url6`, `pag_url7`, `pag_url8`, `pag_url9`, `pag_props`, `pag_props_all`, `pag_props_locale`, `pag_fullsearch`, `pag_contenttype`, `pag_multilanguage`, `pag_adminlock`, `pag_redirect`) VALUES
(1, '41d1b7423d6618a0a7574487a3fbb89e', 1, 1, 1, 1, 'Startseite', 'Phenotype 2.7 Startseite', '', '', 1, 0, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1226676956, 13, 13, 1206374344, 1226928631, 1226962800, 1226927763, 1226927763, 1226927763, 1226927763, 1226927763, 0, '0.0607', '', 1, 0, 'Phenotype-2.7-Startseite', 'Phenotype-2.7-Startseite', 'Phenotype-2.7-Startseite', 'Phenotype-2.7-Startseite', 'Phenotype-2.7-Startseite', '', '', '', '', '', 'a:1:{s:8:"pag_url1";s:0:"";}', 'a:0:{}', 'a:0:{}', 'Phenotype 2.7 Startseite|Phenotype 2.7 Startseite|Phenotype 2.7 Startseite||||Phenotype 2.7|<p><strong>Welcome to Phenotype!</strong></p>\n<p>If you can read this text, Phenotype was installed succesfull with the PT_CORE package.</p>\n<p>If you''re new to Phenotype try to install PT_DEMO for a first impression on how Phenotype works.</p>\n<p>For more infos visit the <a href="http://www.phenotype.de">offical homepage</a>.</p>\n<p>(Sorry currently most infos in german only).</p>|', 1, 0, 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pagegroup`
--

DROP TABLE IF EXISTS `pagegroup`;
CREATE TABLE IF NOT EXISTS `pagegroup` (
  `grp_id` int(11) NOT NULL auto_increment,
  `grp_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `grp_description` text collate latin1_general_ci NOT NULL,
  `grp_statistic` tinyint(4) NOT NULL default '1',
  `grp_multilanguage` tinyint(4) NOT NULL default '0',
  `grp_smarturl_schema` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`grp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1;

--
-- Daten für Tabelle `pagegroup`
--

INSERT INTO `pagegroup` (`grp_id`, `grp_bez`, `grp_description`, `grp_statistic`, `grp_multilanguage`, `grp_smarturl_schema`) VALUES
(1, 'Structure', '', 1, 0, 0),
(2, 'Special Pages', '', 1, 0, 0),
(3, 'Dynamic Pages', '', 1, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pageversion`
--

DROP TABLE IF EXISTS `pageversion`;
CREATE TABLE IF NOT EXISTS `pageversion` (
  `ver_id` int(11) NOT NULL auto_increment,
  `ver_nr` tinyint(4) NOT NULL default '0',
  `pag_id` int(11) NOT NULL default '0',
  `lay_id` int(11) NOT NULL default '0',
  `ver_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `inc_id1` int(11) NOT NULL default '0',
  `inc_id2` int(11) NOT NULL default '0',
  `pag_exec_script` tinyint(4) NOT NULL default '0',
  `pag_fullsearch` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ver_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1;

--
-- Daten für Tabelle `pageversion`
--

INSERT INTO `pageversion` (`ver_id`, `ver_nr`, `pag_id`, `lay_id`, `ver_bez`, `inc_id1`, `inc_id2`, `pag_exec_script`, `pag_fullsearch`) VALUES
(1, 1, 1, 1, 'Version 1', 0, 0, 0, 'Phenotype 2.7 Startseite|Phenotype 2.7 Startseite|Phenotype 2.7 Startseite||||Phenotype 2.7|<p><strong>Welcome to Phenotype!</strong></p>\n<p>If you can read this text, Phenotype was installed succesfull with the PT_CORE package.</p>\n<p>If you''re new to Phenotype try to install PT_DEMO for a first impression on how Phenotype works.</p>\n<p>For more infos visit the <a href="http://www.phenotype.de">offical homepage</a>.</p>\n<p>(Sorry currently most infos in german only).</p>|');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pageversion_autoactivate`
--

DROP TABLE IF EXISTS `pageversion_autoactivate`;
CREATE TABLE IF NOT EXISTS `pageversion_autoactivate` (
  `auv_id` int(11) NOT NULL auto_increment,
  `pag_id` int(11) NOT NULL default '0',
  `ver_id` int(11) NOT NULL default '0',
  `ver_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`auv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `pageversion_autoactivate`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page_language`
--

DROP TABLE IF EXISTS `page_language`;
CREATE TABLE IF NOT EXISTS `page_language` (
  `pag_id` int(11) NOT NULL default '0',
  `lng_id` smallint(6) NOT NULL default '0',
  `pag_titel` varchar(250) collate latin1_general_ci NOT NULL default '',
  `pag_nextbuild1` int(11) NOT NULL default '0',
  `pag_nextbuild2` int(11) NOT NULL default '0',
  `pag_nextbuild3` int(11) NOT NULL default '0',
  `pag_nextbuild4` int(11) NOT NULL default '0',
  `pag_nextbuild5` int(11) NOT NULL default '0',
  `pag_nextbuild6` int(11) NOT NULL default '0',
  `pag_printcache1` tinyint(4) NOT NULL default '0',
  `pag_printcache2` tinyint(4) NOT NULL default '0',
  `pag_printcache3` tinyint(4) NOT NULL default '0',
  `pag_printcache4` tinyint(4) NOT NULL default '0',
  `pag_printcache5` tinyint(4) NOT NULL default '0',
  `pag_printcache6` tinyint(4) NOT NULL default '0',
  `pag_xmlcache1` tinyint(4) NOT NULL default '0',
  `pag_xmlcache2` tinyint(4) NOT NULL default '0',
  `pag_xmlcache3` tinyint(4) NOT NULL default '0',
  `pag_xmlcache4` tinyint(4) NOT NULL default '0',
  `pag_xmlcache5` tinyint(4) NOT NULL default '0',
  `pag_xmlcache6` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`pag_id`,`lng_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `page_language`
--

INSERT INTO `page_language` (`pag_id`, `lng_id`, `pag_titel`, `pag_nextbuild1`, `pag_nextbuild2`, `pag_nextbuild3`, `pag_nextbuild4`, `pag_nextbuild5`, `pag_nextbuild6`, `pag_printcache1`, `pag_printcache2`, `pag_printcache3`, `pag_printcache4`, `pag_printcache5`, `pag_printcache6`, `pag_xmlcache1`, `pag_xmlcache2`, `pag_xmlcache3`, `pag_xmlcache4`, `pag_xmlcache5`, `pag_xmlcache6`) VALUES
(1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page_statistics`
--

DROP TABLE IF EXISTS `page_statistics`;
CREATE TABLE IF NOT EXISTS `page_statistics` (
  `pag_id` int(11) NOT NULL default '0',
  `sta_datum` int(11) NOT NULL default '0',
  `sta_pageview` int(11) NOT NULL default '0',
  KEY `sta_day` (`sta_datum`),
  KEY `pag_id` (`pag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `page_statistics`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `rol_id` int(11) NOT NULL auto_increment,
  `rol_description` text collate latin1_general_ci NOT NULL,
  `rol_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `rol_rights` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`rol_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `role`
--

INSERT INTO `role` (`rol_id`, `rol_description`, `rol_bez`, `rol_rights`) VALUES
(1, '', 'Admin', 'a:20:{s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:10:"elm_extras";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}'),
(2, '', 'Editor', 'a:13:{s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:17:"access_mediagrp_2";i:1;s:10:"elm_extras";i:0;s:5:"sbj_1";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:8:"elm_page";i:1;}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sequence_data`
--

DROP TABLE IF EXISTS `sequence_data`;
CREATE TABLE IF NOT EXISTS `sequence_data` (
  `dat_id` int(11) NOT NULL auto_increment,
  `pag_id` int(11) NOT NULL default '0',
  `ver_id` int(11) NOT NULL default '0',
  `lng_id` tinyint(4) NOT NULL default '1',
  `dat_id_content` int(11) NOT NULL default '0',
  `dat_editbuffer` tinyint(4) NOT NULL default '0',
  `dat_visible` tinyint(4) NOT NULL default '1',
  `dat_blocknr` int(11) NOT NULL default '0',
  `dat_pos` int(11) NOT NULL default '0',
  `com_id` int(11) NOT NULL default '0',
  `dat_comdata` mediumtext collate latin1_general_ci NOT NULL,
  `dat_fullsearch` text collate latin1_general_ci NOT NULL,
  `usr_id` int(11) NOT NULL default '0',
  KEY `con_id` (`dat_id`,`lng_id`),
  KEY `page_select` (`pag_id`,`ver_id`,`lng_id`,`dat_visible`,`dat_blocknr`,`dat_editbuffer`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `sequence_data`
--

INSERT INTO `sequence_data` (`dat_id`, `pag_id`, `ver_id`, `lng_id`, `dat_id_content`, `dat_editbuffer`, `dat_visible`, `dat_blocknr`, `dat_pos`, `com_id`, `dat_comdata`, `dat_fullsearch`, `usr_id`) VALUES
(1, 1, 1, 1, 0, 0, 1, 1, 1, 1001, 'a:21:{s:8:"headline";s:13:"Phenotype 2.7";s:4:"text";s:388:"<p><strong>Welcome to Phenotype!</strong></p>\r\n<p>If you can read this text, Phenotype was installed succesfull with the PT_CORE package.</p>\r\n<p>If you''re new to Phenotype try to install PT_DEMO for a first impression on how Phenotype works.</p>\r\n<p>For more infos visit the <a href="http://www.phenotype.de">offical homepage</a>.</p>\r\n<p>(Sorry currently most infos in german only).</p>";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:7:"version";s:0:"";s:7:"img_alt";s:13:"Mein Alt-Text";s:9:"img_align";s:6:"rechts";s:6:"med_id";s:1:"0";s:8:"linktype";s:1:"0";s:8:"linktext";s:0:"";s:10:"linksource";s:0:"";s:5:"linkx";s:0:"";s:5:"linky";s:0:"";s:12:"imageimg_alt";s:13:"Mein Alt-Text";s:14:"imageimg_align";s:6:"rechts";s:11:"imageimg_id";s:1:"0";s:11:"imagemed_id";s:1:"0";}', 'Phenotype 2.7|<p><strong>Welcome to Phenotype!</strong></p>\n<p>If you can read this text, Phenotype was installed succesfull with the PT_CORE package.</p>\n<p>If you''re new to Phenotype try to install PT_DEMO for a first impression on how Phenotype works.</p>\n<p>For more infos visit the <a href="http://www.phenotype.de">offical homepage</a>.</p>\n<p>(Sorry currently most infos in german only).</p>', 0),
(1, 1, 1, 1, 0, 1, 1, 1, 1, 1001, 'a:21:{s:8:"headline";s:13:"Phenotype 2.7";s:4:"text";s:388:"<p><strong>Welcome to Phenotype!</strong></p>\r\n<p>If you can read this text, Phenotype was installed succesfull with the PT_CORE package.</p>\r\n<p>If you''re new to Phenotype try to install PT_DEMO for a first impression on how Phenotype works.</p>\r\n<p>For more infos visit the <a href="http://www.phenotype.de">offical homepage</a>.</p>\r\n<p>(Sorry currently most infos in german only).</p>";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:7:"version";s:0:"";s:7:"img_alt";s:13:"Mein Alt-Text";s:9:"img_align";s:6:"rechts";s:6:"med_id";s:1:"0";s:8:"linktype";s:1:"0";s:8:"linktext";s:0:"";s:10:"linksource";s:0:"";s:5:"linkx";s:0:"";s:5:"linky";s:0:"";s:12:"imageimg_alt";s:13:"Mein Alt-Text";s:14:"imageimg_align";s:6:"rechts";s:11:"imageimg_id";s:1:"0";s:11:"imagemed_id";s:1:"0";}', 'Phenotype 2.7|<p><strong>Welcome to Phenotype!</strong></p>\n<p>If you can read this text, Phenotype was installed succesfull with the PT_CORE package.</p>\n<p>If you''re new to Phenotype try to install PT_DEMO for a first impression on how Phenotype works.</p>\n<p>For more infos visit the <a href="http://www.phenotype.de">offical homepage</a>.</p>\n<p>(Sorry currently most infos in german only).</p>', 13);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `snapshot`
--

DROP TABLE IF EXISTS `snapshot`;
CREATE TABLE IF NOT EXISTS `snapshot` (
  `sna_id` int(11) NOT NULL auto_increment,
  `sna_type` varchar(2) collate latin1_general_ci NOT NULL,
  `key_id` int(11) NOT NULL,
  `sec_id` int(11) NOT NULL,
  `sna_date` int(11) NOT NULL,
  `usr_id` int(11) NOT NULL,
  `sna_zip` tinyint(4) NOT NULL,
  `sna_xml` longblob NOT NULL,
  `sna_sync` tinyint(4) NOT NULL,
  PRIMARY KEY  (`sna_id`),
  KEY `key_id` (`key_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `snapshot`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticket`
--

DROP TABLE IF EXISTS `ticket`;
CREATE TABLE IF NOT EXISTS `ticket` (
  `tik_id` int(11) NOT NULL auto_increment,
  `sbj_id` int(11) NOT NULL default '0',
  `dat_id_2ndorder` int(11) NOT NULL,
  `pag_id` int(11) NOT NULL default '0',
  `ver_id` int(11) NOT NULL default '0',
  `med_id` int(11) NOT NULL default '0',
  `dat_id_content` int(11) NOT NULL default '0',
  `tik_bez` varchar(150) collate latin1_general_ci NOT NULL default '',
  `usr_id_creator` int(11) NOT NULL default '0',
  `usr_id_owner` int(11) NOT NULL default '0',
  `tik_accepted` tinyint(4) NOT NULL default '0',
  `tik_status` tinyint(4) NOT NULL default '1',
  `tik_startdate` int(11) NOT NULL default '0',
  `tik_enddate` int(11) NOT NULL default '0',
  `tik_creationdate` int(11) NOT NULL default '0',
  `tik_closingdate` int(11) NOT NULL default '0',
  `tik_targetdate` int(11) NOT NULL default '0',
  `tik_sleepdate` int(11) NOT NULL default '0',
  `tik_percentage` tinyint(11) NOT NULL default '0',
  `tik_duration` int(11) NOT NULL default '0',
  `tik_prio` tinyint(4) NOT NULL default '0',
  `tik_complexity` tinyint(4) NOT NULL default '0',
  `tik_tendency` tinyint(4) NOT NULL default '0',
  `tik_eisenhower` char(1) collate latin1_general_ci NOT NULL default '',
  `tik_lastaction` int(11) NOT NULL default '0',
  `tik_fulltext` text collate latin1_general_ci NOT NULL,
  `tik_notice` text collate latin1_general_ci NOT NULL,
  `tik_props` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`tik_id`),
  KEY `prj_id` (`sbj_id`),
  FULLTEXT KEY `tik_fulltext` (`tik_fulltext`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ticket`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticketaction`
--

DROP TABLE IF EXISTS `ticketaction`;
CREATE TABLE IF NOT EXISTS `ticketaction` (
  `act_id` int(11) NOT NULL auto_increment,
  `tik_id` int(11) NOT NULL default '0',
  `act_type` smallint(6) NOT NULL default '0',
  `act_details` text collate latin1_general_ci NOT NULL,
  `act_date` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `act_comment` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`act_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ticketaction`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticketmarkup`
--

DROP TABLE IF EXISTS `ticketmarkup`;
CREATE TABLE IF NOT EXISTS `ticketmarkup` (
  `tik_id` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `tik_markup` tinyint(4) NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `ticketmarkup`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticketpin`
--

DROP TABLE IF EXISTS `ticketpin`;
CREATE TABLE IF NOT EXISTS `ticketpin` (
  `tik_id` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `tik_pin` tinyint(4) NOT NULL default '1',
  KEY `tik_id` (`tik_id`,`usr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `ticketpin`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticketrequest`
--

DROP TABLE IF EXISTS `ticketrequest`;
CREATE TABLE IF NOT EXISTS `ticketrequest` (
  `tik_id` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `tik_request` tinyint(4) NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `ticketrequest`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticketsubject`
--

DROP TABLE IF EXISTS `ticketsubject`;
CREATE TABLE IF NOT EXISTS `ticketsubject` (
  `sbj_id` int(11) NOT NULL auto_increment,
  `sbj_bez` varchar(150) collate latin1_general_ci NOT NULL default '',
  `sbj_description` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`sbj_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ticketsubject`
--

INSERT INTO `ticketsubject` (`sbj_id`, `sbj_bez`, `sbj_description`) VALUES
(1, 'Bugs', 'Bugtracker during development');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE IF NOT EXISTS `tokens` (
  `token` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `tokens`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `usr_id` int(11) NOT NULL auto_increment,
  `usr_status` int(11) NOT NULL default '0',
  `usr_login` varchar(50) collate latin1_general_ci NOT NULL default '',
  `usr_pass` varchar(50) collate latin1_general_ci NOT NULL default '',
  `usr_vorname` varchar(50) collate latin1_general_ci NOT NULL default '',
  `usr_nachname` varchar(50) collate latin1_general_ci NOT NULL default '',
  `usr_email` varchar(200) collate latin1_general_ci NOT NULL default '',
  `usr_createdate` int(11) NOT NULL default '0',
  `usr_lastlogin` int(11) NOT NULL default '0',
  `usr_rights` text collate latin1_general_ci NOT NULL,
  `usr_allrights` text collate latin1_general_ci NOT NULL,
  `usr_preferences` text collate latin1_general_ci NOT NULL,
  `usr_su` tinyint(4) NOT NULL default '0',
  `med_id_thumb` int(11) NOT NULL default '0',
  PRIMARY KEY  (`usr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`usr_id`, `usr_status`, `usr_login`, `usr_pass`, `usr_vorname`, `usr_nachname`, `usr_email`, `usr_createdate`, `usr_lastlogin`, `usr_rights`, `usr_allrights`, `usr_preferences`, `usr_su`, `med_id_thumb`) VALUES
(13, 1, 'starter', 'ph1c2fSo4Tg/2', 'Starter', '', '', 1128621734, 1226928639, 'a:4:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"rol_1";i:1;}', 'a:21:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:10:"elm_extras";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 0),
(1, 0, '', '', 'System', '', '', 1128535703, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0),
(2, 0, '', '', 'Importer', '', '', 1128535744, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0),
(3, 0, '', '', 'WWW', '', '', 1129560752, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_ticketsubject`
--

DROP TABLE IF EXISTS `user_ticketsubject`;
CREATE TABLE IF NOT EXISTS `user_ticketsubject` (
  `usr_id` int(11) NOT NULL default '0',
  `sbj_id` int(11) NOT NULL default '0',
  KEY `usr_id` (`usr_id`,`sbj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `user_ticketsubject`
--

