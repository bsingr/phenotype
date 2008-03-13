-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 21. Oktober 2006 um 21:08
-- Server Version: 5.0.22
-- PHP-Version: 5.1.4
-- 
-- Datenbank: `phenotype-svn`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `action`
-- 

CREATE TABLE `action` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `action`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `component`
-- 

CREATE TABLE `component` (
  `com_id` int(11) NOT NULL auto_increment,
  `com_bez` varchar(150) collate latin1_general_ci NOT NULL default '',
  `com_description` text collate latin1_general_ci NOT NULL,
  `com_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`com_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `component`
-- 

INSERT INTO `component` VALUES (1002, 'HTML', '## Baustein 1002 - HTML', 'System');
INSERT INTO `component` VALUES (1003, 'Include', '## Baustein 1003 - Include', 'System');
INSERT INTO `component` VALUES (1001, 'Richtextabsatz', '## Baustein 1001 - Richtextabsatz\n\nMit diesem Bausteinen können bereits die meisten Anforderungen einer einfachen Website abegedeckt werden. Ein Absatz besteht aus Überschrift, Text, Bild und Link.\n\n', 'Textbausteine');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `component_componentgroup`
-- 

CREATE TABLE `component_componentgroup` (
  `cog_id` int(11) NOT NULL default '0',
  `com_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cog_id`,`com_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `component_componentgroup`
-- 

INSERT INTO `component_componentgroup` VALUES (1, 1001);
INSERT INTO `component_componentgroup` VALUES (1, 1002);
INSERT INTO `component_componentgroup` VALUES (1, 1003);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `component_template`
-- 

CREATE TABLE `component_template` (
  `tpl_id` int(11) NOT NULL,
  `com_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`, `com_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `component_template`
-- 

INSERT INTO `component_template` VALUES (1, 1001, 'TPL_DEFAULT');
INSERT INTO `component_template` VALUES (2, 1001, 'TPL_TOPIMAGE');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `componentgroup`
-- 

CREATE TABLE `componentgroup` (
  `cog_id` int(11) NOT NULL auto_increment,
  `cog_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `cog_description` text collate latin1_general_ci NOT NULL,
  `cog_pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `componentgroup`
-- 

INSERT INTO `componentgroup` VALUES (1, 'Default', '## Default-Bausteingruppe', 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `content`
-- 

CREATE TABLE `content` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=1;

-- 
-- Daten für Tabelle `content`
-- 

INSERT INTO `content` VALUES (1001, 'Expandierende Liste', 'Listenobjekt für selbstexpandierende Auswahlisten in Formularen', 'System', 0, '', 1, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `content_data`
-- 

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
  `dat_ikey1` int(11) NOT NULL default '0',
  `dat_ikey2` int(11) NOT NULL default '0',
  `dat_ikey3` int(11) NOT NULL default '0',
  `dat_ikey4` int(11) NOT NULL default '0',
  `dat_ikey5` int(11) NOT NULL default '0',
  `dat_fullsearch` text collate latin1_general_ci NOT NULL,
  `med_id_thumb` int(11) NOT NULL default '0',
  PRIMARY KEY  (`dat_id`),
  KEY `con_id` (`con_id`),
  KEY `dat_ikey1` (`dat_ikey1`),
  KEY `dat_ikey2` (`dat_ikey2`),
  KEY `dat_ikey3` (`dat_ikey3`),
  KEY `dat_ikey4` (`dat_ikey4`),
  KEY `dat_ikey5` (`dat_ikey5`),
  KEY `default_select` (`con_id`,`dat_status`),
  FULLTEXT KEY `dat_fullsearch` (`dat_fullsearch`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `content_data`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `content_statistics`
-- 

CREATE TABLE `content_statistics` (
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

CREATE TABLE `content_template` (
  `tpl_id` int(11) NOT NULL,
  `con_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`, `con_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `content_template`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `dataobject`
-- 

CREATE TABLE `dataobject` (
  `dao_id` int(11) NOT NULL auto_increment,
  `dao_bez` varchar(255) collate latin1_general_ci NOT NULL,
  `dao_props` longtext collate latin1_general_ci NOT NULL,
  `dao_date` int(11) NOT NULL,
  `dao_ttl` int(11) NOT NULL,
  `dao_lastbuild_time` varchar(20) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`dao_id`),
  UNIQUE KEY `dao_bez` (`dao_bez`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `dataobject`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `dataobject2`
-- 

CREATE TABLE `dataobject2` (
  `dao_id` int(11) NOT NULL auto_increment,
  `dao_type` varchar(255) collate latin1_general_ci NOT NULL,
  `dao_dat_type` varchar(255) collate latin1_general_ci NOT NULL,
  `dao_dat_id` int(11) NOT NULL default '0',
  `dao_props` text collate latin1_general_ci NOT NULL,
  `dao_build_ts` int(11) NOT NULL default '0',
  `dao_expire_ts` int(11) NOT NULL default '0',
  PRIMARY KEY  (`dao_id`),
  KEY `type` (`dao_type`),
  KEY `dat_type` (`dao_dat_type`),
  KEY `dat_id` (`dao_dat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
-- 
-- Daten für Tabelle `dataobject2`
-- 



-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `extra`
-- 

CREATE TABLE `extra` (
  `ext_id` int(11) NOT NULL auto_increment,
  `ext_bez` varchar(50) collate latin1_general_ci NOT NULL default '',
  `ext_description` text collate latin1_general_ci NOT NULL,
  `ext_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  `ext_props` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ext_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `extra`
-- 

INSERT INTO `extra` VALUES (1001, 'Pagewizard', '', 'Development', '');
INSERT INTO `extra` VALUES (1002, 'Konsole', '', 'Development', 'a:1:{s:5:"color";s:1:"1";}');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `extra_template`
-- 

CREATE TABLE `extra_template` (
  `tpl_id` int(11) NOT NULL,
  `ext_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`, `ext_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `extra_template`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `include`
-- 

CREATE TABLE `include` (
  `inc_id` int(11) NOT NULL auto_increment,
  `inc_bez` varchar(50) collate latin1_general_ci NOT NULL default '',
  `inc_description` text collate latin1_general_ci NOT NULL,
  `inc_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  `inc_usage_layout` tinyint(4) NOT NULL default '0',
  `inc_usage_includecomponent` tinyint(4) NOT NULL default '0',
  `inc_usage_page` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`inc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `include`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `include_template`
-- 

CREATE TABLE `include_template` (
  `tpl_id` int(11) NOT NULL,
  `inc_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`, `inc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `include_template`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `layout`
-- 

CREATE TABLE `layout` (
  `lay_id` int(11) NOT NULL auto_increment,
  `lay_bez` varchar(100) collate latin1_general_ci NOT NULL default '0',
  `lay_description` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`lay_id`),
  UNIQUE KEY `tpl_id` (`lay_id`),
  KEY `tpl_id_2` (`lay_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `layout`
-- 

INSERT INTO `layout` VALUES (1, 'Standard', '');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `layout_block`
-- 

CREATE TABLE `layout_block` (
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

INSERT INTO `layout_block` VALUES (1, 1, 'Content', 1, 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `layout_include`
-- 

CREATE TABLE `layout_include` (
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

CREATE TABLE `layout_pagegroup` (
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

CREATE TABLE `media` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `media`
-- 

INSERT INTO `media` VALUES (1, 1, 'Nils Hagemann', 0, 'I0610420', 'nils.jpg', 1, 'jpg', '', 60, 75, 1, '', '', '', '_system', '', '', 0, 0, 1093458207, 1);
INSERT INTO `media` VALUES (2, 1, 'Peter Sellinger', 0, 'I0610420', 'peter.jpg', 1, 'jpg', '', 60, 71, 1, '', '', '', '_system', '', '', 0, 0, 1093458201, 1);
INSERT INTO `media` VALUES (3, 1, 'Paul Sellinger', 0, 'I0610420', 'paul.jpg', 1, 'jpg', '', 60, 77, 1, '', '', '', '_system', '', '', 0, 0, 1093458195, 1);
INSERT INTO `media` VALUES (4, 1, 'bild.jpg', 0, 'I0610420', 'bild.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1);
INSERT INTO `media` VALUES (5, 1, 'event.jpg', 0, 'I0610420', 'event.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1);
INSERT INTO `media` VALUES (6, 1, 'job.jpg', 0, 'I0610420', 'job.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1);
INSERT INTO `media` VALUES (7, 1, 'konfiguration.jpg', 0, 'I0610420', 'konfiguration.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1);
INSERT INTO `media` VALUES (8, 1, 'news.jpg', 0, 'I0610420', 'news.jpg', 1, 'jpg', '', 60, 40, 1, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1);
INSERT INTO `media` VALUES (9, 1, 'promofeld.gif', 0, 'I0610420', 'promofeld.gif', 1, 'gif', '', 60, 40, 0, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1);
INSERT INTO `media` VALUES (10, 1, 'shop.gif', 0, 'I0610420', 'shop.gif', 1, 'gif', '', 60, 40, 0, '', '', 'Thumbnails für Contentobjekte', '_system', '', '', 0, 0, 1098390582, 1);
INSERT INTO `media` VALUES (11, 1, 'Markus Griesbach', 0, 'I0610420', 'maggus.jpg', 1, 'jpg', '', 60, 75, 1, '', '', '', '_system', '', '', 10, 1153063188, 1153063202, 10);
INSERT INTO `media` VALUES (13, 1, 'Michel', 0, 'I0610420', 'michel.jpg', 1, 'jpg', '', 60, 76, 1, '', '', '', '_system', '', '', 15, 1161453837, 1161453837, 15);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `mediagroup`
-- 

CREATE TABLE `mediagroup` (
  `grp_id` int(11) NOT NULL auto_increment,
  `grp_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `grp_description` text collate latin1_general_ci NOT NULL,
  `grp_type` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`grp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `mediagroup`
-- 

INSERT INTO `mediagroup` VALUES (1, 'System', '', 1);
INSERT INTO `mediagroup` VALUES (2, 'Standard', '', 2);
INSERT INTO `mediagroup` VALUES (3, 'Aufgaben', '', 3);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `mediaversion`
-- 

CREATE TABLE `mediaversion` (
  `ver_id` int(11) NOT NULL auto_increment,
  `med_id` int(11) NOT NULL default '0',
  `ver_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `ver_x` int(11) NOT NULL default '0',
  `ver_y` int(11) NOT NULL default '0',
  `ver_subtype` varchar(10) collate latin1_general_ci NOT NULL default '',
  `ver_mimetype` varchar(64) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`ver_id`),
  KEY `med_id` (`med_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `mediaversion`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `page`
-- 

CREATE TABLE `page` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `page`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `page_language`
-- 

CREATE TABLE `page_language` (
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


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `page_statistics`
-- 

CREATE TABLE `page_statistics` (
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
-- Tabellenstruktur für Tabelle `pagegroup`
-- 

CREATE TABLE `pagegroup` (
  `grp_id` int(11) NOT NULL auto_increment,
  `grp_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `grp_description` text collate latin1_general_ci NOT NULL,
  `grp_statistic` tinyint(4) NOT NULL default '1',
  `grp_multilanguage` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`grp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `pagegroup`
-- 

INSERT INTO `pagegroup` VALUES (1, 'Struktur', '', 1, 0);
INSERT INTO `pagegroup` VALUES (2, 'Sonderseiten', '', 1, 0);
INSERT INTO `pagegroup` VALUES (3, 'Dynamisch', '', 1, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `pageversion`
-- 

CREATE TABLE `pageversion` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `pageversion`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `pageversion_autoactivate`
-- 

CREATE TABLE `pageversion_autoactivate` (
  `auv_id` int(11) NOT NULL auto_increment,
  `pag_id` int(11) NOT NULL default '0',
  `ver_id` int(11) NOT NULL default '0',
  `ver_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`auv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `pageversion_autoactivate`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `role`
-- 

CREATE TABLE `role` (
  `rol_id` int(11) NOT NULL auto_increment,
  `rol_description` text collate latin1_general_ci NOT NULL,
  `rol_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `rol_rights` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`rol_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `role`
-- 

INSERT INTO `role` VALUES (1, '', 'Admin', 'a:20:{s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:10:"elm_extras";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}');
INSERT INTO `role` VALUES (2, '', 'Redakteur', 'a:13:{s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:17:"access_mediagrp_2";i:1;s:10:"elm_extras";i:0;s:5:"sbj_1";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:8:"elm_page";i:1;}');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `sequence_data`
-- 

CREATE TABLE `sequence_data` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `sequence_data`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `snapshot`
-- 

CREATE TABLE `snapshot` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `snapshot`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ticket`
-- 

CREATE TABLE `ticket` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `ticket`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ticketaction`
-- 

CREATE TABLE `ticketaction` (
  `act_id` int(11) NOT NULL auto_increment,
  `tik_id` int(11) NOT NULL default '0',
  `act_type` smallint(6) NOT NULL default '0',
  `act_details` text collate latin1_general_ci NOT NULL,
  `act_date` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `act_comment` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`act_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `ticketaction`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ticketmarkup`
-- 

CREATE TABLE `ticketmarkup` (
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

CREATE TABLE `ticketpin` (
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

CREATE TABLE `ticketrequest` (
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

CREATE TABLE `ticketsubject` (
  `sbj_id` int(11) NOT NULL auto_increment,
  `sbj_bez` varchar(150) collate latin1_general_ci NOT NULL default '',
  `sbj_description` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`sbj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `ticketsubject`
-- 

INSERT INTO `ticketsubject` VALUES (1, 'Bugs', 'Bugtracker während der Entwicklung');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `user`
-- 

CREATE TABLE `user` (
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- 
-- Daten für Tabelle `user`
-- 

INSERT INTO `user` VALUES (13, 1, 'starter', 'ph1c2fSo4Tg/2', 'Starter', '', '', 1128621734, 1161455573, 'a:4:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"rol_1";i:1;}', 'a:21:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:10:"elm_extras";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 0);
INSERT INTO `user` VALUES (1, 0, '', '', 'System', '', '', 1128535703, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0);
INSERT INTO `user` VALUES (2, 0, '', '', 'Importer', '', '', 1128535744, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0);
INSERT INTO `user` VALUES (3, 0, '', '', 'WWW', '', '', 1129560752, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0);
INSERT INTO `user` VALUES (10, 1, 'Nils', '', 'Nils', 'Hagemann', '', 1093456701, 0, 'a:4:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"rol_1";i:1;}', 'a:21:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:10:"elm_extras";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 1);
INSERT INTO `user` VALUES (11, 1, 'Paul', '', 'Paul', 'Sellinger', '', 1098392296, 0, 'a:4:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"rol_1";i:1;}', 'a:21:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:10:"elm_extras";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 3);
INSERT INTO `user` VALUES (12, 1, 'Peter', '', 'Peter', 'Sellinger', '', 1098392353, 0, 'a:4:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"rol_1";i:1;}', 'a:21:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:10:"elm_extras";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 2);
INSERT INTO `user` VALUES (14, 1, 'Markus', '', 'Markus', 'Griesbach', '', 1153063063, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 1, 11);
INSERT INTO `user` VALUES (15, 1, 'michel', '', 'Michael', 'Krämer', '', 1161453219, 1161453705, 'a:4:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"rol_1";i:1;}', 'a:21:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:10:"elm_extras";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 13);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `user_ticketsubject`
-- 

CREATE TABLE `user_ticketsubject` (
  `usr_id` int(11) NOT NULL default '0',
  `sbj_id` int(11) NOT NULL default '0',
  KEY `usr_id` (`usr_id`,`sbj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `user_ticketsubject`
-- 

