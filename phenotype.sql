-- phpMyAdmin SQL Dump
-- version 2.9.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 22. März 2008 um 18:53
-- Server Version: 5.0.33
-- PHP-Version: 5.2.1
-- 
-- Datenbank: `phenotype`
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1602 ;

-- 
-- Daten für Tabelle `component`
-- 

INSERT INTO `component` VALUES (1002, 'HTML', '## Baustein 1002 - HTML', 'System');
INSERT INTO `component` VALUES (1003, 'Include', '## Baustein 1003 - Include', 'System');
INSERT INTO `component` VALUES (1001, 'Richtextabsatz', '## Baustein 1001 - Richtextabsatz\n\nMit diesem Bausteinen können bereits die meisten Anforderungen einer einfachen Website abegedeckt werden. Ein Absatz besteht aus Überschrift, Text, Bild und Link.\n\n', 'Textbausteine');
INSERT INTO `component` VALUES (1101, 'Überschrift', '## Baustein 1101 - Überschrift', 'Textbausteine');
INSERT INTO `component` VALUES (1102, 'Bild', '## Baustein 1102 - Bild', 'Media');
INSERT INTO `component` VALUES (1103, 'Aufzählung', '## Baustein 1103 - Aufzählung', 'Sonstige');
INSERT INTO `component` VALUES (1104, 'Trennlinie', '## Baustein 1104 - Trennlinie', 'Sonstige');
INSERT INTO `component` VALUES (1105, 'Tabelle', '', 'Demonstration');
INSERT INTO `component` VALUES (1601, 'Galerie', '', 'Demonstration');

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
INSERT INTO `component_componentgroup` VALUES (1, 1101);
INSERT INTO `component_componentgroup` VALUES (1, 1102);
INSERT INTO `component_componentgroup` VALUES (1, 1103);
INSERT INTO `component_componentgroup` VALUES (1, 1104);
INSERT INTO `component_componentgroup` VALUES (1, 1105);
INSERT INTO `component_componentgroup` VALUES (1, 1601);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `component_template`
-- 

CREATE TABLE `component_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `com_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=7 ;

-- 
-- Daten für Tabelle `component_template`
-- 

INSERT INTO `component_template` VALUES (4, 1001, 'TPL_TOPIMAGE');
INSERT INTO `component_template` VALUES (3, 1001, 'TPL_DEFAULT');
INSERT INTO `component_template` VALUES (5, 1101, 'TPL_1');
INSERT INTO `component_template` VALUES (6, 1102, 'TPL_1');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=1 AUTO_INCREMENT=1602 ;

-- 
-- Daten für Tabelle `content`
-- 

INSERT INTO `content` VALUES (1001, 'Expandierende Liste', 'Listenobjekt für selbstexpandierende Auswahlisten in Formularen', 'System', 0, '', 1, 1, 1, 0, 0, 0);
INSERT INTO `content` VALUES (1102, 'Kontakteintrag', '', 'Demonstration', 0, '', 0, 1, 1, 0, 0, 0);
INSERT INTO `content` VALUES (1601, 'Galerie', '', 'Demonstration', 0, '', 1, 1, 1, 0, 0, 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- Daten für Tabelle `content_data`
-- 

INSERT INTO `content_data` VALUES (1, '02b1099124656f7ccc47afc1c03944aa', 0, 0, 0, 0, 0, 0, 0, 'Demonstration Gallery', 1601, 'a:46:{s:3:"bez";s:21:"Demonstration Gallery";s:6:"anzahl";i:8;s:8:"maximage";i:8;s:7:"precalc";a:8:{i:1;a:6:{i:0;s:7:"Blume 1";i:1;s:36:"Dieses Bild stammt von photocase.com";i:2;s:23:"2/I0611440/000012,1.jpg";i:3;s:21:"2/I0611440/000012.jpg";i:4;s:3:"340";i:5;s:3:"255";}i:2;a:6:{i:0;s:7:"Blume 2";i:1;s:36:"Dieses Bild stammt von photocase.com";i:2;s:23:"2/I0611440/000020,8.jpg";i:3;s:21:"2/I0611440/000020.jpg";i:4;s:3:"340";i:5;s:3:"227";}i:3;a:6:{i:0;s:7:"Blume 3";i:1;s:36:"Dieses Bild stammt von photocase.com";i:2;s:23:"2/I0611440/000014,2.jpg";i:3;s:21:"2/I0611440/000014.jpg";i:4;s:3:"340";i:5;s:3:"255";}i:4;a:6:{i:0;s:7:"Blume 4";i:1;s:36:"Dieses Bild stammt von photocase.com";i:2;s:23:"2/I0611440/000015,3.jpg";i:3;s:21:"2/I0611440/000015.jpg";i:4;s:3:"340";i:5;s:3:"255";}i:5;a:6:{i:0;s:7:"Blume 5";i:1;s:36:"Dieses Bild stammt von photocase.com";i:2;s:23:"2/I0611440/000016,4.jpg";i:3;s:21:"2/I0611440/000016.jpg";i:4;s:3:"340";i:5;s:3:"255";}i:6;a:6:{i:0;s:7:"Blume 6";i:1;s:36:"Dieses Bild stammt von photocase.com";i:2;s:23:"2/I0611440/000017,5.jpg";i:3;s:21:"2/I0611440/000017.jpg";i:4;s:3:"340";i:5;s:3:"226";}i:7;a:6:{i:0;s:7:"Blume 7";i:1;s:36:"Dieses Bild stammt von photocase.com";i:2;s:23:"2/I0611440/000018,6.jpg";i:3;s:21:"2/I0611440/000018.jpg";i:4;s:3:"340";i:5;s:3:"255";}i:8;a:6:{i:0;s:7:"Blume 8";i:1;s:36:"Dieses Bild stammt von photocase.com";i:2;s:23:"2/I0611440/000019,7.jpg";i:3;s:21:"2/I0611440/000019.jpg";i:4;s:3:"340";i:5;s:3:"255";}}s:13:"image1_img_id";i:12;s:5:"view1";i:1;s:13:"image2_img_id";i:20;s:5:"view2";i:1;s:13:"image3_img_id";i:14;s:5:"view3";i:1;s:13:"image4_img_id";i:15;s:5:"view4";i:1;s:13:"image5_img_id";i:16;s:5:"view5";i:1;s:13:"image6_img_id";i:17;s:5:"view6";i:1;s:13:"image7_img_id";i:18;s:5:"view7";i:1;s:13:"image8_img_id";i:19;s:5:"view8";i:1;s:6:"titel1";s:7:"Blume 1";s:5:"text1";s:36:"Dieses Bild stammt von photocase.com";s:6:"titel2";s:7:"Blume 2";s:5:"text2";s:36:"Dieses Bild stammt von photocase.com";s:6:"titel3";s:7:"Blume 3";s:5:"text3";s:36:"Dieses Bild stammt von photocase.com";s:6:"titel4";s:7:"Blume 4";s:5:"text4";s:36:"Dieses Bild stammt von photocase.com";s:6:"titel5";s:7:"Blume 5";s:5:"text5";s:36:"Dieses Bild stammt von photocase.com";s:6:"titel6";s:7:"Blume 6";s:5:"text6";s:36:"Dieses Bild stammt von photocase.com";s:6:"titel7";s:7:"Blume 7";s:5:"text7";s:36:"Dieses Bild stammt von photocase.com";s:6:"titel8";s:7:"Blume 8";s:5:"text8";s:36:"Dieses Bild stammt von photocase.com";s:6:"titel9";s:0:"";s:5:"text9";s:0:"";s:13:"image9_img_id";s:1:"0";s:5:"view9";i:0;s:7:"titel10";s:0:"";s:6:"text10";s:0:"";s:14:"image10_img_id";s:1:"0";s:6:"view10";i:0;s:12:"ddimageorder";a:8:{i:0;s:1:"1";i:1;s:1:"2";i:2;s:1:"3";i:3;s:1:"4";i:4;s:1:"5";i:5;s:1:"6";i:6;s:1:"7";i:7;s:1:"8";}s:4:"desc";s:83:"Ausgew&auml;hlte Blumenbilder von <a href="http://photocase.com">photocase.com</a>.";}', 10, 1153067075, 13, 1191355965, 0, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, 0, 0, 'Demonstration Gallery | Demonstration Gallery | 8 | 8 | Array | 12 | 1 | 20 | 1 | 14 | 1 | 15 | 1 | 16 | 1 | 17 | 1 | 18 | 1 | 19 | 1 | Blume 1 | Dieses Bild stammt von photocase.com | Blume 2 | Dieses Bild stammt von photocase.com | Blume 3 | Dieses Bild stammt von photocase.com | Blume 4 | Dieses Bild stammt von photocase.com | Blume 5 | Dieses Bild stammt von photocase.com | Blume 6 | Dieses Bild stammt von photocase.com | Blume 7 | Dieses Bild stammt von photocase.com | Blume 8 | Dieses Bild stammt von photocase.com |  |  | 0 | 0 |  |  | 0 | 0 | Array | Ausgew&auml;hlte Blumenbilder von photocase.com.', 12);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `content_data1`
-- 

CREATE TABLE `content_data1` (
  `dat_id` int(11) default NULL,
  `title` varchar(50) collate latin1_general_ci default NULL,
  `count` int(11) default NULL,
  `image_id` int(11) default NULL,
  `author` varchar(12) collate latin1_general_ci default NULL,
  `desc` text collate latin1_general_ci,
  KEY `dat_id` (`dat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `content_data1`
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
  `tpl_id` int(11) NOT NULL auto_increment,
  `con_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

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
  `dao_props` text collate latin1_general_ci NOT NULL,
  `dao_date` int(11) NOT NULL,
  `dao_ttl` int(11) NOT NULL,
  `dao_lastbuild_time` varchar(20) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`dao_id`),
  UNIQUE KEY `dao_bez` (`dao_bez`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `dataobject`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1003 ;

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
  `tpl_id` int(11) NOT NULL auto_increment,
  `ext_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `extra_template`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `gallery`
-- 

CREATE TABLE `gallery` (
  `dat_id` int(11) default NULL,
  `title` varchar(50) collate latin1_general_ci default NULL,
  `count` int(11) default NULL,
  `image_id` int(11) default NULL,
  `author` varchar(12) collate latin1_general_ci default NULL,
  `desc` text collate latin1_general_ci,
  KEY `dat_id` (`dat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `gallery`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1107 ;

-- 
-- Daten für Tabelle `include`
-- 

INSERT INTO `include` VALUES (1101, 'Seitennavigation', '', 'Navigation', 1, 0, 0);
INSERT INTO `include` VALUES (1102, 'Seitenheader', '', 'Navigation', 1, 0, 0);
INSERT INTO `include` VALUES (1103, 'Breadcrumb', '', 'Navigation', 1, 0, 0);
INSERT INTO `include` VALUES (1104, 'Formular', '', 'Kontakt', 0, 1, 0);
INSERT INTO `include` VALUES (1106, 'Galeriedetailanzeige', '', 'Galerie', 0, 1, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `include_template`
-- 

CREATE TABLE `include_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `inc_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=4 ;

-- 
-- Daten für Tabelle `include_template`
-- 

INSERT INTO `include_template` VALUES (1, 1104, 'TPL_ERROR');
INSERT INTO `include_template` VALUES (2, 1104, 'TPL_FORM');
INSERT INTO `include_template` VALUES (3, 1104, 'TPL_THANKYOU');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=2 ;

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

INSERT INTO `layout_block` VALUES (1, 1, 'Bausteine', 1, 1);

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

INSERT INTO `layout_include` VALUES (1, 1102, 1, 1);
INSERT INTO `layout_include` VALUES (1, 1101, 2, 1);
INSERT INTO `layout_include` VALUES (1, 1103, 3, 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=21 ;

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
INSERT INTO `media` VALUES (12, 2, 'rose.jpg', 1, 'I0611440', 'rose.jpg', 1, 'jpg', '', 340, 255, 1, '', '', '', 'Galerie / Upload 1', '', '', 10, 1153067221, 1153067221, 10);
INSERT INTO `media` VALUES (14, 2, 'rose3.jpg', 1, 'I0611440', 'rose3.jpg', 1, 'jpg', '', 340, 255, 1, '', '', '', 'Galerie / Upload 1', '', '', 10, 1153067221, 1153067221, 10);
INSERT INTO `media` VALUES (15, 2, 'rose4.jpg', 1, 'I0611440', 'rose4.jpg', 1, 'jpg', '', 340, 255, 1, '', '', '', 'Galerie / Upload 1', '', '', 10, 1153067221, 1153067221, 10);
INSERT INTO `media` VALUES (16, 2, 'rose5.jpg', 1, 'I0611440', 'rose5.jpg', 1, 'jpg', '', 340, 255, 1, '', '', '', 'Galerie / Upload 1', '', '', 10, 1153067221, 1153067221, 10);
INSERT INTO `media` VALUES (17, 2, 'rose6.jpg', 1, 'I0611440', 'rose6.jpg', 1, 'jpg', '', 340, 226, 1, '', '', '', 'Galerie / Upload 1', '', '', 10, 1153067222, 1153067222, 10);
INSERT INTO `media` VALUES (18, 2, 'rose7.jpg', 1, 'I0611440', 'rose7.jpg', 1, 'jpg', '', 340, 255, 1, '', '', '', 'Galerie / Upload 1', '', '', 10, 1153067222, 1153067222, 10);
INSERT INTO `media` VALUES (19, 2, 'rose9.jpg', 1, 'I0611440', 'rose9.jpg', 1, 'jpg', '', 340, 255, 1, '', '', '', 'Galerie / Upload 1', '', '', 10, 1153067222, 1153067222, 10);
INSERT INTO `media` VALUES (20, 2, 'rose2.jpg', 1, 'I0611440', '000013.jpg', 1, 'jpg', '', 340, 227, 1, '', '', '', 'Galerie / Upload 1', '', '', 13, 1162717013, 1162717111, 13);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=4 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

-- 
-- Daten für Tabelle `mediaversion`
-- 

INSERT INTO `mediaversion` VALUES (1, 12, 'thumb', 100, 75, 'jpg', '');
INSERT INTO `mediaversion` VALUES (2, 14, 'thumb', 100, 75, 'jpg', '');
INSERT INTO `mediaversion` VALUES (3, 15, 'thumb', 100, 75, 'jpg', '');
INSERT INTO `mediaversion` VALUES (4, 16, 'thumb', 100, 75, 'jpg', '');
INSERT INTO `mediaversion` VALUES (5, 17, 'thumb', 100, 66, 'jpg', '');
INSERT INTO `mediaversion` VALUES (6, 18, 'thumb', 100, 75, 'jpg', '');
INSERT INTO `mediaversion` VALUES (7, 19, 'thumb', 100, 75, 'jpg', '');
INSERT INTO `mediaversion` VALUES (8, 20, 'thumb', 100, 67, 'jpg', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=45 ;

-- 
-- Daten für Tabelle `page`
-- 

INSERT INTO `page` VALUES (1, 'd3d760aea54607fe836ce16207354cac', 1, 1, 1, 1, 'Startseite', 'Startseite', '', '', 1, 0, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1162664748, 10, 10, 1153067027, 1205655743, 1205708400, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '1.0981', '', 1, 0, 'Startseite.html', 'a:0:{}', 'a:0:{}', 'Startseite|Startseite|Startseite|||||Ausführliche Dokumentation|Ausf&uuml;hrliche Info zu Phenotype finden Sie unter<a href="http://www.phenotype-cms.de"> www.phenotype-cms.de</a>.|Herzlich Willkommen|auf der Phenotype Demonstrations Website||Phenotype arbeitet seiten- und inhaltorientiert. <br />\n<br />\nDieser Text wurde direkt an dieser Seite mit dem Richtextabsatz-Baustein platziert.<br />\n<br />\nGleich folgen noch weitere per &quot;Baustein&quot; zugeordnete Inhalte: <br />\n<ul>\n    <li>eine Tabelle</li>\n    <li>ein Galerie-Include und<br />\n    </li>\n    <li>ein weiterer Richtextabsatz - diesmal mit anderem Style </li>\n</ul>||', 1, 0, 0, '');
INSERT INTO `page` VALUES (2, 'd9e30a77b4cd6e62afb22fe2511282de', 2, 2, 1, 1, 'Rubrik 1', 'Rubrik 1', '', '', 1, 1, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1162653654, 10, 10, 1153068248, 1202994194, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.0468', '', 1, 0, '', 'a:1:{s:8:"category";s:1:"2";}', 'a:1:{s:8:"category";s:1:"2";}', 'Rubrik 1|Rubrik 1|Rubrik 1|||||Mehrfachverwendung|Hier wird die gleiche Galerie, die auch auf der Startseite eingebunden ist, noch einmal verlinkt:|Rubrik 1||', 1, 0, 0, '');
INSERT INTO `page` VALUES (3, '1f1484506e3b33635c11f70dfc245837', 3, 3, 1, 1, 'Rubrik 2', 'Rubrik 2', '', '', 1, 1, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1162665672, 10, 10, 1153068248, 1202994183, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.5290', '', 1, 0, '', 'a:1:{s:8:"category";s:1:"3";}', 'a:1:{s:8:"category";s:1:"3";}', 'Rubrik 2|Rubrik 2|Rubrik 2||||\nDieser Tag ist natürlich nicht valide. (HTML-Baustein)\n\n|Richtextabsatz|Sie k&ouml;nnen nat&uuml;rlich f&uuml;r Ihre Anwendung komplett andere Bausteine entwicklen.||Demonstration aller Bausteine|||Richtextabsatz|Diese Seite listet alle Bausteine dieser Demonstration.|Punkt 1|Punkt 2|Punkt 3|||', 1, 0, 0, '');
INSERT INTO `page` VALUES (4, 'f0b1ad6861aa4ab3e4bc8b9a63e5a9b4', 4, 4, 1, 1, 'Rubrik 3', 'Rubrik 3', '', '', 1, 1, 3, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1162654585, 10, 10, 1153068248, 1202994186, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.0969', '', 1, 0, '', 'a:1:{s:8:"category";s:1:"4";}', 'a:1:{s:8:"category";s:1:"4";}', 'Rubrik 3|Rubrik 3|Rubrik 3||||Rubrik 3||', 1, 0, 0, '');
INSERT INTO `page` VALUES (5, 'a85dc8483c471c281c570eee39be3fde', 5, 5, 1, 1, 'Rubrik 4', 'Rubrik 4', '', '', 1, 1, 4, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1162654576, 10, 10, 1153068248, 1202994188, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.0601', '', 1, 0, '', 'a:1:{s:8:"category";s:1:"5";}', 'a:1:{s:8:"category";s:1:"5";}', 'Rubrik 4|Rubrik 4|Rubrik 4||||Rubrik 4||', 1, 0, 0, '');
INSERT INTO `page` VALUES (6, 'ef224ea0142f38e59a9c5cff2c0d9442', 6, 6, 1, 1, 'Kontakt', 'Kontakt', '', '', 1, 1, 5, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1162665725, 10, 10, 1153068248, 1202994190, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.2800', '', 1, 0, '', 'a:1:{s:8:"category";s:1:"6";}', 'a:1:{s:8:"category";s:1:"6";}', 'Kontakt|Kontakt|Kontakt|||||', 1, 0, 0, '');
INSERT INTO `page` VALUES (7, '22f87e22fe19f16166609e7112c01438', 7, 7, 1, 1, 'Seite 1', 'Seite 1', '', '', 1, 2, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 1202994195, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.0411', '', 1, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (8, '7259e606d7a5a2259b0a60d5a35fa7ec', 8, 8, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 7, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 1202994197, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.0482', '', 1, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (9, 'd886621e0e8297bea1ba3f72291265c6', 9, 9, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 7, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 1202994198, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.0433', '', 1, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (10, '7931897d37b7e1552e09837b25d43c2a', 10, 10, 1, 1, 'Seite 2', 'Seite 2', '', '', 1, 2, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (11, 'f974726adda1466889d36aaa12bd7abc', 11, 11, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 10, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (12, '7bc0f914215428001f745fb303196b78', 12, 12, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 10, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (13, '08fdf64b8d5b3779376ae18372429f38', 13, 13, 1, 1, 'Seite 3', 'Seite 3', '', '', 1, 2, 3, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (14, 'a84b2e0181b3cb5c8e81e6d47f0ea6ec', 14, 14, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 13, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (15, 'b1c2a2a6efa7499130f0e71ca1a6846c', 15, 15, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 13, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068316, 10, 10, 1153068316, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"2";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (16, 'e96a615a00e2a9f41cbd8d5602ffbc88', 16, 16, 1, 1, 'Seite 1', 'Seite 1', '', '', 1, 3, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (17, '67993d75e4025b0229ae8ca584c32a4c', 17, 17, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 16, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (18, 'c0bb3d7f02cc89698579cbe40a25ff39', 18, 18, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 16, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (19, 'c8e168a507d4e5bc61683c3c34a30682', 19, 19, 1, 1, 'Seite 2', 'Seite 2', '', '', 1, 3, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (20, '96dcad69e191439b4d7936839ec099bf', 20, 20, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 19, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (21, '4fc063a168f205c87788a8079e3c15f6', 21, 21, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 19, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (22, 'bc688b528b432d953814e03816bf883c', 22, 22, 1, 1, 'Seite 3', 'Seite 3', '', '', 1, 3, 3, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (23, '2620078eaf603295ae893f6eadfb4649', 23, 23, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 22, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (24, '5a8932a45ddbb61acc7fb01a9f9e1d74', 24, 24, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 22, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068370, 10, 10, 1153068370, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"3";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (25, 'cfdbf385193054df969995646bc1067d', 25, 25, 1, 1, 'Seite 1', 'Seite 1', '', '', 1, 4, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (26, '2d51a73d33af5178315fb11b446d72e0', 26, 26, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 25, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (27, '807a8263d17e7532a340f59582d18ca7', 27, 27, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 25, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (28, 'c08d44559235c6917e35271e01c10a4c', 28, 28, 1, 1, 'Seite 2', 'Seite 2', '', '', 1, 4, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (29, 'b62c6b038fe86ba50b70edd8dfda3807', 29, 29, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 28, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (30, 'b4d8348f8262a01968db717e3f45774e', 30, 30, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 28, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (31, 'eac70145fbf04bbe3fb3a16ee64b71b3', 31, 31, 1, 1, 'Seite 3', 'Seite 3', '', '', 1, 4, 3, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (32, 'f35587152b5d45a79399db1a3d4b2e8c', 32, 32, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 31, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (33, '7fa1facf3f8c1b8e087c8c7901f42c24', 33, 33, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 31, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068378, 10, 10, 1153068378, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"4";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (34, '8727ca834614b5edcb466f3f95ebf806', 34, 34, 1, 1, 'Seite 1', 'Seite 1', '', '', 1, 5, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (35, '5e84e12e8ddc3df662235febd804596e', 35, 35, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 34, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (36, '9c920af9207498d8d4121cf393922da9', 36, 36, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 34, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (37, '8f4c147ea87e916b0cd571c6eb2ce437', 37, 37, 1, 1, 'Seite 2', 'Seite 2', '', '', 1, 5, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (38, 'ffbe64eb6cb41c25bd254e087d477e88', 38, 38, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 37, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (39, 'fa43ca13d375d54aa34de7dfd6838747', 39, 39, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 37, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (40, '2c43cb4adeae5374df4b57e9c82d9efa', 40, 40, 1, 1, 'Seite 3', 'Seite 3', '', '', 1, 5, 3, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (41, '5ee21c64019a69198f2eee79a811ec92', 41, 41, 1, 1, 'Unterseite 1', 'Unterseite 1', '', '', 1, 40, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (42, 'a0f10fa79d27f561035057cc36cf2913', 42, 42, 1, 1, 'Unterseite 2', 'Unterseite 2', '', '', 1, 40, 2, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1153068388, 10, 10, 1153068388, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, '', 'a:1:{s:8:"category";s:1:"5";}', '', '', 1, 0, 0, '');
INSERT INTO `page` VALUES (43, '81609c16bb5632e80fd7f369d9611597', 1, 43, 1, 2, 'Impressum', 'Impressum', '', '', 1, 0, 1, 86400, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1162718858, 13, 10, 1162651603, 1202994205, 1203030000, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '0.0434', '', 1, 0, 'Impressum.html', 'a:0:{}', 'a:0:{}', 'Impressum|Impressum|Impressum||||Impressum|Zu jeder Website geh&ouml;rt ein Impressum. <br />\n<br />\nDieses hier dient nur dazu zu demonstrieren, wie Phenotype beliebige URIs mit smartUrl aufl&ouml;sen kann, in diesem Fall die URL &quot;Impressum.html&quot; - wenn ihre .htaccess-Datei richtig konfiguriert ist ...<br />\n<br />\nAu&szlig;erdem sehen Sie, dass das Men&uuml; links den gleichen Baum zeigt, wie beim Aufruf der Startseite. Diese liegt&nbsp; am Navigations-Mimikry. Diese Seite ist so konfiguriert, dass sie sich, wie die Startseite verh&auml;lt. Das Navigationsinclude muss das nat&uuml;rlich ber&uuml;cksichtigen.|', 1, 0, 0, '');
INSERT INTO `page` VALUES (44, 'f6cf1a80368a0ab5d453be709af320c2', 44, 44, 1, 3, 'Galerie', 'Galerie', '', '', 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1162718627, 13, 10, 1162651643, 0, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 1191355964, 0, '', '', 0, 0, 'Galerie.html', 'a:0:{}', 'a:0:{}', 'Galerie|Galerie|Galerie|||||', 1, 0, 0, '');

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

INSERT INTO `page_language` VALUES (1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (2, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (3, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (4, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (5, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (6, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (7, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (8, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (9, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (10, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (11, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (12, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (13, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (14, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (15, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (16, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (17, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (18, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (19, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (20, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (21, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (22, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (23, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (24, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (25, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (26, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (27, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (28, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (29, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (30, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (31, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (32, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (33, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (34, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (35, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (36, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (37, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (38, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (39, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (40, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (41, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (42, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (43, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
INSERT INTO `page_language` VALUES (44, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

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

INSERT INTO `page_statistics` VALUES (1, 20071019, 4);
INSERT INTO `page_statistics` VALUES (1, 20080214, 3);
INSERT INTO `page_statistics` VALUES (3, 20080214, 1);
INSERT INTO `page_statistics` VALUES (4, 20080214, 1);
INSERT INTO `page_statistics` VALUES (5, 20080214, 1);
INSERT INTO `page_statistics` VALUES (6, 20080214, 1);
INSERT INTO `page_statistics` VALUES (2, 20080214, 1);
INSERT INTO `page_statistics` VALUES (7, 20080214, 1);
INSERT INTO `page_statistics` VALUES (8, 20080214, 1);
INSERT INTO `page_statistics` VALUES (9, 20080214, 1);
INSERT INTO `page_statistics` VALUES (43, 20080214, 1);
INSERT INTO `page_statistics` VALUES (1, 20080215, 1);
INSERT INTO `page_statistics` VALUES (1, 20080228, 1);
INSERT INTO `page_statistics` VALUES (1, 20080301, 1);
INSERT INTO `page_statistics` VALUES (1, 20080316, 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=4 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=45 ;

-- 
-- Daten für Tabelle `pageversion`
-- 

INSERT INTO `pageversion` VALUES (1, 1, 1, 1, 'Version 1', 0, 0, 0, 'Startseite|Startseite|Startseite|||||Ausführliche Dokumentation|Ausf&uuml;hrliche Info zu Phenotype finden Sie unter<a href="http://www.phenotype-cms.de"> www.phenotype-cms.de</a>.|Herzlich Willkommen|auf der Phenotype Demonstrations Website||Phenotype arbeitet seiten- und inhaltorientiert. <br />\n<br />\nDieser Text wurde direkt an dieser Seite mit dem Richtextabsatz-Baustein platziert.<br />\n<br />\nGleich folgen noch weitere per &quot;Baustein&quot; zugeordnete Inhalte: <br />\n<ul>\n    <li>eine Tabelle</li>\n    <li>ein Galerie-Include und<br />\n    </li>\n    <li>ein weiterer Richtextabsatz - diesmal mit anderem Style </li>\n</ul>||');
INSERT INTO `pageversion` VALUES (2, 1, 2, 1, 'Version 1', 0, 0, 0, 'Rubrik 1|Rubrik 1|Rubrik 1|||||Mehrfachverwendung|Hier wird die gleiche Galerie, die auch auf der Startseite eingebunden ist, noch einmal verlinkt:|Rubrik 1||');
INSERT INTO `pageversion` VALUES (3, 1, 3, 1, 'Version 1', 0, 0, 0, 'Rubrik 2|Rubrik 2|Rubrik 2||||\nDieser Tag ist natürlich nicht valide. (HTML-Baustein)\n\n|Richtextabsatz|Sie k&ouml;nnen nat&uuml;rlich f&uuml;r Ihre Anwendung komplett andere Bausteine entwicklen.||Demonstration aller Bausteine|||Richtextabsatz|Diese Seite listet alle Bausteine dieser Demonstration.|Punkt 1|Punkt 2|Punkt 3|||');
INSERT INTO `pageversion` VALUES (4, 1, 4, 1, 'Version 1', 0, 0, 0, 'Rubrik 3|Rubrik 3|Rubrik 3||||Rubrik 3||');
INSERT INTO `pageversion` VALUES (5, 1, 5, 1, 'Version 1', 0, 0, 0, 'Rubrik 4|Rubrik 4|Rubrik 4||||Rubrik 4||');
INSERT INTO `pageversion` VALUES (6, 1, 6, 1, 'Version 1', 0, 0, 0, 'Kontakt|Kontakt|Kontakt|||||');
INSERT INTO `pageversion` VALUES (7, 1, 7, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (8, 1, 8, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (9, 1, 9, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (10, 1, 10, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (11, 1, 11, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (12, 1, 12, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (13, 1, 13, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (14, 1, 14, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (15, 1, 15, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (16, 1, 16, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (17, 1, 17, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (18, 1, 18, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (19, 1, 19, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (20, 1, 20, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (21, 1, 21, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (22, 1, 22, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (23, 1, 23, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (24, 1, 24, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (25, 1, 25, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (26, 1, 26, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (27, 1, 27, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (28, 1, 28, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (29, 1, 29, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (30, 1, 30, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (31, 1, 31, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (32, 1, 32, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (33, 1, 33, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (34, 1, 34, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (35, 1, 35, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (36, 1, 36, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (37, 1, 37, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (38, 1, 38, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (39, 1, 39, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (40, 1, 40, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (41, 1, 41, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (42, 1, 42, 1, 'Version 1', 0, 0, 0, '');
INSERT INTO `pageversion` VALUES (43, 1, 43, 1, 'Version 1', 0, 0, 0, 'Impressum|Impressum|Impressum||||Impressum|Zu jeder Website geh&ouml;rt ein Impressum. <br />\n<br />\nDieses hier dient nur dazu zu demonstrieren, wie Phenotype beliebige URIs mit smartUrl aufl&ouml;sen kann, in diesem Fall die URL &quot;Impressum.html&quot; - wenn ihre .htaccess-Datei richtig konfiguriert ist ...<br />\n<br />\nAu&szlig;erdem sehen Sie, dass das Men&uuml; links den gleichen Baum zeigt, wie beim Aufruf der Startseite. Diese liegt&nbsp; am Navigations-Mimikry. Diese Seite ist so konfiguriert, dass sie sich, wie die Startseite verh&auml;lt. Das Navigationsinclude muss das nat&uuml;rlich ber&uuml;cksichtigen.|');
INSERT INTO `pageversion` VALUES (44, 1, 44, 1, 'Version 1', 0, 0, 0, 'Galerie|Galerie|Galerie|||||');

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=3 ;

-- 
-- Daten für Tabelle `role`
-- 

INSERT INTO `role` VALUES (1, '', 'Admin', 'a:22:{s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:11:"elm_content";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:8:"con_1601";i:1;s:8:"con_1102";i:1;s:13:"elm_redaktion";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:10:"elm_extras";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}');
INSERT INTO `role` VALUES (2, '', 'Redakteur', 'a:14:{s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:8:"elm_task";i:1;s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:17:"access_mediagrp_2";i:1;s:10:"elm_extras";i:0;s:5:"sbj_1";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:8:"elm_page";i:1;}');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=22 ;

-- 
-- Daten für Tabelle `sequence_data`
-- 

INSERT INTO `sequence_data` VALUES (1, 1, 1, 1, 0, 0, 1, 1, 1, 1101, 'a:2:{s:3:"bez";s:19:"Herzlich Willkommen";s:7:"subline";s:40:"auf der Phenotype Demonstrations Website";}', 'Herzlich Willkommen|auf der Phenotype Demonstrations Website', 0);
INSERT INTO `sequence_data` VALUES (2, 1, 1, 1, 0, 0, 1, 1, 2, 1001, 'a:9:{s:8:"headline";s:0:"";s:4:"text";s:404:"Phenotype arbeitet seiten- und inhaltorientiert. <br />\r\n<br />\r\nDieser Text wurde direkt an dieser Seite mit dem Richtextabsatz-Baustein platziert.<br />\r\n<br />\r\nGleich folgen noch weitere per &quot;Baustein&quot; zugeordnete Inhalte: <br />\r\n<ul>\r\n    <li>eine Tabelle</li>\r\n    <li>ein Galerie-Include und<br />\r\n    </li>\r\n    <li>ein weiterer Richtextabsatz - diesmal mit anderem Style </li>\r\n</ul>";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:5:"style";s:1:"0";}', '|Phenotype arbeitet seiten- und inhaltorientiert. <br />\n<br />\nDieser Text wurde direkt an dieser Seite mit dem Richtextabsatz-Baustein platziert.<br />\n<br />\nGleich folgen noch weitere per &quot;Baustein&quot; zugeordnete Inhalte: <br />\n<ul>\n    <li>eine Tabelle</li>\n    <li>ein Galerie-Include und<br />\n    </li>\n    <li>ein weiterer Richtextabsatz - diesmal mit anderem Style </li>\n</ul>', 0);
INSERT INTO `sequence_data` VALUES (3, 1, 1, 1, 0, 0, 1, 1, 3, 1105, 'a:18:{s:5:"style";i:1;s:5:"width";s:3:"450";s:8:"anzahl_x";i:2;s:8:"anzahl_y";i:5;s:10:"tcon_x1_y1";s:7:"Feature";s:10:"tcon_x1_y2";s:9:"Mediabase";s:10:"tcon_x1_y3";s:16:"Seitenverwaltung";s:10:"tcon_x2_y1";s:9:"Erklärung";s:10:"tcon_x2_y2";s:101:"Medienverwaltung mit freier Kategorisierung, browsergestützer Bildbearbeitung und Variantenmanagement";s:10:"tcon_x2_y3";s:93:"Bestückung von Seiten über frei programmierbare "Bausteine", z.B. Tabellen, Teaser, Vote usw.";s:10:"tcon_x3_y1";s:0:"";s:10:"tcon_x3_y2";s:0:"";s:10:"tcon_x3_y3";s:0:"";s:8:"headline";s:34:"Das ist eine beispielhafte Tabelle";s:10:"tcon_x1_y4";s:17:"Contentverwaltung";s:10:"tcon_x2_y4";s:122:"Verwaltung von mehrfach zu verwendenden Inhalten über Contentobjekte mit frei programmierbaren "intelligenten" Formularen.";s:10:"tcon_x1_y5";s:3:"...";s:10:"tcon_x2_y5";s:18:"und vieles mehr ;)";}', '', 0);
INSERT INTO `sequence_data` VALUES (4, 1, 1, 1, 0, 0, 1, 1, 4, 1601, 'a:2:{s:8:"variable";s:4:"Wert";s:6:"dat_id";s:1:"1";}', '', 0);
INSERT INTO `sequence_data` VALUES (5, 1, 1, 1, 0, 0, 1, 1, 5, 1001, 'a:9:{s:8:"headline";s:26:"Ausführliche Dokumentation";s:4:"text";s:116:"Ausf&uuml;hrliche Info zu Phenotype finden Sie unter<a href="http://www.phenotype-cms.de"> www.phenotype-cms.de</a>.";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:5:"style";s:1:"2";}', 'Ausführliche Dokumentation|Ausf&uuml;hrliche Info zu Phenotype finden Sie unter<a href="http://www.phenotype-cms.de"> www.phenotype-cms.de</a>.', 0);
INSERT INTO `sequence_data` VALUES (6, 2, 2, 1, 0, 0, 1, 1, 1, 1101, 'a:2:{s:3:"bez";s:8:"Rubrik 1";s:7:"subline";s:0:"";}', 'Rubrik 1|', 0);
INSERT INTO `sequence_data` VALUES (7, 2, 2, 1, 0, 0, 1, 1, 2, 1001, 'a:9:{s:8:"headline";s:18:"Mehrfachverwendung";s:4:"text";s:97:"Hier wird die gleiche Galerie, die auch auf der Startseite eingebunden ist, noch einmal verlinkt:";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:5:"style";s:1:"2";}', 'Mehrfachverwendung|Hier wird die gleiche Galerie, die auch auf der Startseite eingebunden ist, noch einmal verlinkt:', 0);
INSERT INTO `sequence_data` VALUES (8, 2, 2, 1, 0, 0, 1, 1, 3, 1601, 'a:2:{s:8:"variable";s:4:"Wert";s:6:"dat_id";s:1:"1";}', '', 0);
INSERT INTO `sequence_data` VALUES (9, 3, 3, 1, 0, 0, 1, 1, 1, 1101, 'a:2:{s:3:"bez";s:29:"Demonstration aller Bausteine";s:7:"subline";s:0:"";}', 'Demonstration aller Bausteine|', 0);
INSERT INTO `sequence_data` VALUES (10, 3, 3, 1, 0, 0, 1, 1, 2, 1001, 'a:9:{s:8:"headline";s:14:"Richtextabsatz";s:4:"text";s:55:"Diese Seite listet alle Bausteine dieser Demonstration.";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:6:"rechts";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:5:"style";s:1:"0";}', 'Richtextabsatz|Diese Seite listet alle Bausteine dieser Demonstration.', 0);
INSERT INTO `sequence_data` VALUES (11, 3, 3, 1, 0, 0, 1, 1, 3, 1001, 'a:9:{s:8:"headline";s:14:"Richtextabsatz";s:4:"text";s:92:"Sie k&ouml;nnen nat&uuml;rlich f&uuml;r Ihre Anwendung komplett andere Bausteine entwicklen.";s:6:"img_id";s:1:"6";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:5:"style";s:1:"2";}', 'Richtextabsatz|Sie k&ouml;nnen nat&uuml;rlich f&uuml;r Ihre Anwendung komplett andere Bausteine entwicklen.', 0);
INSERT INTO `sequence_data` VALUES (12, 3, 3, 1, 0, 0, 1, 1, 4, 1103, 'a:5:{s:6:"anzahl";i:3;s:5:"text1";s:7:"Punkt 1";s:5:"text2";s:7:"Punkt 2";s:5:"text3";s:7:"Punkt 3";s:8:"headline";s:10:"Aufzählung";}', 'Punkt 1|Punkt 2|Punkt 3|', 0);
INSERT INTO `sequence_data` VALUES (13, 3, 3, 1, 0, 0, 1, 1, 5, 1104, 'a:0:{}', '', 0);
INSERT INTO `sequence_data` VALUES (14, 3, 3, 1, 0, 0, 1, 1, 6, 1002, 'a:1:{s:4:"html";s:106:"<br/>\n<blink><marquee>Dieser Tag ist natürlich nicht valide. (HTML-Baustein)</marquee></blink>\n<br/>\n<br/>";}', '\nDieser Tag ist natürlich nicht valide. (HTML-Baustein)\n\n', 0);
INSERT INTO `sequence_data` VALUES (15, 3, 3, 1, 0, 0, 1, 1, 7, 1102, 'a:3:{s:6:"img_id";s:1:"5";s:3:"alt";s:16:"Einfach ein Bild";s:15:"bildausrichtung";s:6:"mittig";}', '', 0);
INSERT INTO `sequence_data` VALUES (16, 3, 3, 1, 0, 0, 1, 1, 8, 1601, 'a:2:{s:8:"variable";s:4:"Wert";s:6:"dat_id";s:1:"1";}', '', 0);
INSERT INTO `sequence_data` VALUES (17, 4, 4, 1, 0, 0, 1, 1, 1, 1101, 'a:2:{s:3:"bez";s:8:"Rubrik 3";s:7:"subline";s:0:"";}', 'Rubrik 3|', 0);
INSERT INTO `sequence_data` VALUES (18, 5, 5, 1, 0, 0, 1, 1, 1, 1101, 'a:2:{s:3:"bez";s:8:"Rubrik 4";s:7:"subline";s:0:"";}', 'Rubrik 4|', 0);
INSERT INTO `sequence_data` VALUES (19, 6, 6, 1, 0, 0, 1, 1, 1, 1003, 'a:2:{s:6:"inc_id";s:4:"1104";s:5:"cache";s:1:"0";}', '', 0);
INSERT INTO `sequence_data` VALUES (20, 43, 43, 1, 0, 0, 1, 1, 1, 1001, 'a:9:{s:8:"headline";s:9:"Impressum";s:4:"text";s:588:"Zu jeder Website geh&ouml;rt ein Impressum. <br />\r\n<br />\r\nDieses hier dient nur dazu zu demonstrieren, wie Phenotype beliebige URIs mit smartUrl aufl&ouml;sen kann, in diesem Fall die URL &quot;Impressum.html&quot; - wenn ihre .htaccess-Datei richtig konfiguriert ist ...<br />\r\n<br />\r\nAu&szlig;erdem sehen Sie, dass das Men&uuml; links den gleichen Baum zeigt, wie beim Aufruf der Startseite. Diese liegt&nbsp; am Navigations-Mimikry. Diese Seite ist so konfiguriert, dass sie sich, wie die Startseite verh&auml;lt. Das Navigationsinclude muss das nat&uuml;rlich ber&uuml;cksichtigen.";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:5:"style";s:1:"0";}', 'Impressum|Zu jeder Website geh&ouml;rt ein Impressum. <br />\n<br />\nDieses hier dient nur dazu zu demonstrieren, wie Phenotype beliebige URIs mit smartUrl aufl&ouml;sen kann, in diesem Fall die URL &quot;Impressum.html&quot; - wenn ihre .htaccess-Datei richtig konfiguriert ist ...<br />\n<br />\nAu&szlig;erdem sehen Sie, dass das Men&uuml; links den gleichen Baum zeigt, wie beim Aufruf der Startseite. Diese liegt&nbsp; am Navigations-Mimikry. Diese Seite ist so konfiguriert, dass sie sich, wie die Startseite verh&auml;lt. Das Navigationsinclude muss das nat&uuml;rlich ber&uuml;cksichtigen.', 0);
INSERT INTO `sequence_data` VALUES (21, 44, 44, 1, 0, 0, 1, 1, 1, 1003, 'a:2:{s:6:"inc_id";s:4:"1106";s:5:"cache";s:1:"0";}', '', 0);
INSERT INTO `sequence_data` VALUES (4, 1, 1, 1, 0, 1, 1, 1, 4, 1601, 'a:2:{s:8:"variable";s:4:"Wert";s:6:"dat_id";s:1:"1";}', '', 13);
INSERT INTO `sequence_data` VALUES (5, 1, 1, 1, 0, 1, 1, 1, 5, 1001, 'a:9:{s:8:"headline";s:26:"Ausführliche Dokumentation";s:4:"text";s:116:"Ausf&uuml;hrliche Info zu Phenotype finden Sie unter<a href="http://www.phenotype-cms.de"> www.phenotype-cms.de</a>.";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:5:"style";s:1:"2";}', 'Ausführliche Dokumentation|Ausf&uuml;hrliche Info zu Phenotype finden Sie unter<a href="http://www.phenotype-cms.de"> www.phenotype-cms.de</a>.', 13);
INSERT INTO `sequence_data` VALUES (3, 1, 1, 1, 0, 1, 1, 1, 3, 1105, 'a:18:{s:5:"style";i:1;s:5:"width";s:3:"450";s:8:"anzahl_x";i:2;s:8:"anzahl_y";i:5;s:10:"tcon_x1_y1";s:7:"Feature";s:10:"tcon_x1_y2";s:9:"Mediabase";s:10:"tcon_x1_y3";s:16:"Seitenverwaltung";s:10:"tcon_x2_y1";s:9:"Erklärung";s:10:"tcon_x2_y2";s:101:"Medienverwaltung mit freier Kategorisierung, browsergestützer Bildbearbeitung und Variantenmanagement";s:10:"tcon_x2_y3";s:93:"Bestückung von Seiten über frei programmierbare "Bausteine", z.B. Tabellen, Teaser, Vote usw.";s:10:"tcon_x3_y1";s:0:"";s:10:"tcon_x3_y2";s:0:"";s:10:"tcon_x3_y3";s:0:"";s:8:"headline";s:34:"Das ist eine beispielhafte Tabelle";s:10:"tcon_x1_y4";s:17:"Contentverwaltung";s:10:"tcon_x2_y4";s:122:"Verwaltung von mehrfach zu verwendenden Inhalten über Contentobjekte mit frei programmierbaren "intelligenten" Formularen.";s:10:"tcon_x1_y5";s:3:"...";s:10:"tcon_x2_y5";s:18:"und vieles mehr ;)";}', '', 13);
INSERT INTO `sequence_data` VALUES (1, 1, 1, 1, 0, 1, 1, 1, 1, 1101, 'a:2:{s:3:"bez";s:19:"Herzlich Willkommen";s:7:"subline";s:40:"auf der Phenotype Demonstrations Website";}', 'Herzlich Willkommen|auf der Phenotype Demonstrations Website', 13);
INSERT INTO `sequence_data` VALUES (2, 1, 1, 1, 0, 1, 1, 1, 2, 1001, 'a:9:{s:8:"headline";s:0:"";s:4:"text";s:404:"Phenotype arbeitet seiten- und inhaltorientiert. <br />\r\n<br />\r\nDieser Text wurde direkt an dieser Seite mit dem Richtextabsatz-Baustein platziert.<br />\r\n<br />\r\nGleich folgen noch weitere per &quot;Baustein&quot; zugeordnete Inhalte: <br />\r\n<ul>\r\n    <li>eine Tabelle</li>\r\n    <li>ein Galerie-Include und<br />\r\n    </li>\r\n    <li>ein weiterer Richtextabsatz - diesmal mit anderem Style </li>\r\n</ul>";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";s:5:"style";s:1:"0";}', '|Phenotype arbeitet seiten- und inhaltorientiert. <br />\n<br />\nDieser Text wurde direkt an dieser Seite mit dem Richtextabsatz-Baustein platziert.<br />\n<br />\nGleich folgen noch weitere per &quot;Baustein&quot; zugeordnete Inhalte: <br />\n<ul>\n    <li>eine Tabelle</li>\n    <li>ein Galerie-Include und<br />\n    </li>\n    <li>ein weiterer Richtextabsatz - diesmal mit anderem Style </li>\n</ul>', 13);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=9 ;

-- 
-- Daten für Tabelle `snapshot`
-- 

INSERT INTO `snapshot` VALUES (1, 'CO', 1, 1601, 1191355605, 13, 0, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0a3c7068656e6f747970653e0a093c6d6574613e0a09093c707476657273696f6e3e23232150545f56455253494f4e2123233c2f707476657273696f6e3e0a09093c707473756276657273696f6e3e2323214255494c445f4e4f2123233c2f707473756276657273696f6e3e0a09093c636f6e5f69643e313630313c2f636f6e5f69643e0a09093c6461745f69643e313c2f6461745f69643e0a09093c6461745f69645f6c6f63616c3e313c2f6461745f69645f6c6f63616c3e0a09093c696d706f72746d6574686f643e6f76657277726974653c2f696d706f72746d6574686f643e0a09093c6275696c64696e6465783e313c2f6275696c64696e6465783e09090a093c2f6d6574613e0a093c636f6e74656e743e0a093c6461745f7569643e30326231303939313234363536663763636334376166633163303339343461613c2f6461745f7569643e0a3c6461745f62657a3e44656d6f6e7374726174696f6e2047616c6c6572793c2f6461745f62657a3e0a3c7573725f69645f63726561746f723e31303c2f7573725f69645f63726561746f723e0a3c6461745f6372656174696f6e646174653e313135333036373037353c2f6461745f6372656174696f6e646174653e0a3c7573725f69643e31333c2f7573725f69643e0a3c6461745f646174653e313139313335353630353c2f6461745f646174653e0a3c6461745f706f733e303c2f6461745f706f733e0a3c6461745f7374617475733e313c2f6461745f7374617475733e0a3c6d65645f69645f7468756d623e31323c2f6d65645f69645f7468756d623e0a3c6461745f66756c6c7365617263683e44656d6f6e7374726174696f6e2047616c6c657279207c2044656d6f6e7374726174696f6e2047616c6c657279207c2038207c2038207c204172726179207c203132207c2031207c203230207c2031207c203134207c2031207c203135207c2031207c203136207c2031207c203137207c2031207c203138207c2031207c203139207c2031207c20426c756d652031207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652032207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652033207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652034207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652035207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652036207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652037207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652038207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20207c20207c2030207c2030207c20207c20207c2030207c2030207c204172726179207c20417573676577262333383b61756d6c3b686c746520426c756d656e62696c64657220766f6e2070686f746f636173652e636f6d2e3c2f6461745f66756c6c7365617263683e0a0a09093c6461745f70726f70733e59546f304e6a7037637a6f7a4f694a695a586f694f334d364d6a4536496b526c62573975633352795958527062323467523246736247567965534937637a6f324f694a68626e7068614777694f326b364f44747a4f6a6736496d316865476c745957646c496a74704f6a6737637a6f334f694a77636d566a5957786a496a74684f6a673665326b364d5474684f6a593665326b364d44747a4f6a6336496b4a736457316c494445694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784d6977784c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445794c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a493759546f324f6e74704f6a4137637a6f334f694a43624856745a534179496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d6a41734f433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441794d433571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a4933496a743961546f7a4f3245364e6a703761546f774f334d364e7a6f69516d7831625755674d79493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445304c444975616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d545175616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49314e53493766576b364e4474684f6a593665326b364d44747a4f6a6336496b4a736457316c494451694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784e53777a4c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445314c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a553759546f324f6e74704f6a4137637a6f334f694a43624856745a534131496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d5459734e433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784e693571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743961546f324f3245364e6a703761546f774f334d364e7a6f69516d7831625755674e69493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445334c445575616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d546375616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49794e69493766576b364e7a74684f6a593665326b364d44747a4f6a6336496b4a736457316c494463694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784f4377324c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445344c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a673759546f324f6e74704f6a4137637a6f334f694a43624856745a534134496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d546b734e793571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784f533571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743966584d364d544d36496d6c745957646c4d56397062576466615751694f326b364d544937637a6f314f694a32615756334d53493761546f784f334d364d544d36496d6c745957646c4d6c397062576466615751694f326b364d6a4137637a6f314f694a32615756334d69493761546f784f334d364d544d36496d6c745957646c4d31397062576466615751694f326b364d545137637a6f314f694a32615756334d79493761546f784f334d364d544d36496d6c745957646c4e46397062576466615751694f326b364d545537637a6f314f694a32615756334e43493761546f784f334d364d544d36496d6c745957646c4e56397062576466615751694f326b364d545937637a6f314f694a32615756334e53493761546f784f334d364d544d36496d6c745957646c4e6c397062576466615751694f326b364d546337637a6f314f694a32615756334e69493761546f784f334d364d544d36496d6c745957646c4e31397062576466615751694f326b364d546737637a6f314f694a32615756334e79493761546f784f334d364d544d36496d6c745957646c4f46397062576466615751694f326b364d546b37637a6f314f694a32615756334f43493761546f784f334d364e6a6f6964476c305a577778496a747a4f6a6336496b4a736457316c494445694f334d364e546f6964475634644445694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624449694f334d364e7a6f69516d7831625755674d694937637a6f314f694a305a5868304d694937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734d794937637a6f334f694a43624856745a53417a496a747a4f6a5536496e526c6548517a496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577730496a747a4f6a6336496b4a736457316c494451694f334d364e546f6964475634644451694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624455694f334d364e7a6f69516d7831625755674e534937637a6f314f694a305a5868304e534937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734e694937637a6f334f694a43624856745a534132496a747a4f6a5536496e526c65485132496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577733496a747a4f6a6336496b4a736457316c494463694f334d364e546f6964475634644463694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624467694f334d364e7a6f69516d7831625755674f434937637a6f314f694a305a5868304f434937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734f534937637a6f774f6949694f334d364e546f696447563464446b694f334d364d446f69496a747a4f6a457a4f694a706257466e5a546c666157316e58326c6b496a747a4f6a4536496a41694f334d364e546f69646d6c6c647a6b694f326b364d44747a4f6a6336496e5270644756734d5441694f334d364d446f69496a747a4f6a5936496e526c654851784d434937637a6f774f6949694f334d364d545136496d6c745957646c4d5442666157316e58326c6b496a747a4f6a4536496a41694f334d364e6a6f69646d6c6c647a4577496a74704f6a4137637a6f784d6a6f695a4752706257466e5a5739795a475679496a74684f6a673665326b364d44747a4f6a4536496a45694f326b364d54747a4f6a4536496a49694f326b364d6a747a4f6a4536496a4d694f326b364d7a747a4f6a4536496a51694f326b364e44747a4f6a4536496a55694f326b364e54747a4f6a4536496a59694f326b364e6a747a4f6a4536496a63694f326b364e7a747a4f6a4536496a67694f33317a4f6a5136496d526c63324d694f334d364f444d36496b46316332646c64795a68645731734f32687364475567516d783162575675596d6c735a47567949485a76626941385953426f636d566d50534a6f644852774f693876634768766447396a59584e6c4c6d4e766253492b634768766447396a59584e6c4c6d4e766254777659543475496a74393c2f6461745f70726f70733e0a09093c73657175656e63655f646174613e0a09093c2f73657175656e63655f646174613e0a093c2f636f6e74656e743e0a3c2f7068656e6f747970653e, 0);
INSERT INTO `snapshot` VALUES (2, 'CO', 1, 1601, 1191355850, 13, 0, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0a3c7068656e6f747970653e0a093c6d6574613e0a09093c707476657273696f6e3e23232150545f56455253494f4e2123233c2f707476657273696f6e3e0a09093c707473756276657273696f6e3e2323214255494c445f4e4f2123233c2f707473756276657273696f6e3e0a09093c636f6e5f69643e313630313c2f636f6e5f69643e0a09093c6461745f69643e313c2f6461745f69643e0a09093c6461745f69645f6c6f63616c3e313c2f6461745f69645f6c6f63616c3e0a09093c696d706f72746d6574686f643e6f76657277726974653c2f696d706f72746d6574686f643e0a09093c6275696c64696e6465783e313c2f6275696c64696e6465783e09090a093c2f6d6574613e0a093c636f6e74656e743e0a093c6461745f7569643e30326231303939313234363536663763636334376166633163303339343461613c2f6461745f7569643e0a3c6461745f62657a3e44656d6f6e7374726174696f6e2047616c6c6572793c2f6461745f62657a3e0a3c7573725f69645f63726561746f723e31303c2f7573725f69645f63726561746f723e0a3c6461745f6372656174696f6e646174653e313135333036373037353c2f6461745f6372656174696f6e646174653e0a3c7573725f69643e31333c2f7573725f69643e0a3c6461745f646174653e313139313335353835303c2f6461745f646174653e0a3c6461745f706f733e303c2f6461745f706f733e0a3c6461745f7374617475733e313c2f6461745f7374617475733e0a3c6d65645f69645f7468756d623e31323c2f6d65645f69645f7468756d623e0a3c6461745f66756c6c7365617263683e44656d6f6e7374726174696f6e2047616c6c657279207c2044656d6f6e7374726174696f6e2047616c6c657279207c2038207c2038207c204172726179207c203132207c2031207c203230207c2031207c203134207c2031207c203135207c2031207c203136207c2031207c203137207c2031207c203138207c2031207c203139207c2031207c20426c756d652031207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652032207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652033207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652034207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652035207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652036207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652037207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652038207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20207c20207c2030207c2030207c20207c20207c2030207c2030207c204172726179207c20417573676577262333383b61756d6c3b686c746520426c756d656e62696c64657220766f6e2070686f746f636173652e636f6d2e3c2f6461745f66756c6c7365617263683e0a0a09093c6461745f70726f70733e59546f304e6a7037637a6f7a4f694a695a586f694f334d364d6a4536496b526c62573975633352795958527062323467523246736247567965534937637a6f324f694a68626e7068614777694f326b364f44747a4f6a6736496d316865476c745957646c496a74704f6a6737637a6f334f694a77636d566a5957786a496a74684f6a673665326b364d5474684f6a593665326b364d44747a4f6a6336496b4a736457316c494445694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784d6977784c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445794c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a493759546f324f6e74704f6a4137637a6f334f694a43624856745a534179496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d6a41734f433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441794d433571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a4933496a743961546f7a4f3245364e6a703761546f774f334d364e7a6f69516d7831625755674d79493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445304c444975616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d545175616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49314e53493766576b364e4474684f6a593665326b364d44747a4f6a6336496b4a736457316c494451694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784e53777a4c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445314c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a553759546f324f6e74704f6a4137637a6f334f694a43624856745a534131496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d5459734e433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784e693571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743961546f324f3245364e6a703761546f774f334d364e7a6f69516d7831625755674e69493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445334c445575616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d546375616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49794e69493766576b364e7a74684f6a593665326b364d44747a4f6a6336496b4a736457316c494463694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784f4377324c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445344c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a673759546f324f6e74704f6a4137637a6f334f694a43624856745a534134496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d546b734e793571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784f533571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743966584d364d544d36496d6c745957646c4d56397062576466615751694f326b364d544937637a6f314f694a32615756334d53493761546f784f334d364d544d36496d6c745957646c4d6c397062576466615751694f326b364d6a4137637a6f314f694a32615756334d69493761546f784f334d364d544d36496d6c745957646c4d31397062576466615751694f326b364d545137637a6f314f694a32615756334d79493761546f784f334d364d544d36496d6c745957646c4e46397062576466615751694f326b364d545537637a6f314f694a32615756334e43493761546f784f334d364d544d36496d6c745957646c4e56397062576466615751694f326b364d545937637a6f314f694a32615756334e53493761546f784f334d364d544d36496d6c745957646c4e6c397062576466615751694f326b364d546337637a6f314f694a32615756334e69493761546f784f334d364d544d36496d6c745957646c4e31397062576466615751694f326b364d546737637a6f314f694a32615756334e79493761546f784f334d364d544d36496d6c745957646c4f46397062576466615751694f326b364d546b37637a6f314f694a32615756334f43493761546f784f334d364e6a6f6964476c305a577778496a747a4f6a6336496b4a736457316c494445694f334d364e546f6964475634644445694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624449694f334d364e7a6f69516d7831625755674d694937637a6f314f694a305a5868304d694937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734d794937637a6f334f694a43624856745a53417a496a747a4f6a5536496e526c6548517a496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577730496a747a4f6a6336496b4a736457316c494451694f334d364e546f6964475634644451694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624455694f334d364e7a6f69516d7831625755674e534937637a6f314f694a305a5868304e534937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734e694937637a6f334f694a43624856745a534132496a747a4f6a5536496e526c65485132496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577733496a747a4f6a6336496b4a736457316c494463694f334d364e546f6964475634644463694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624467694f334d364e7a6f69516d7831625755674f434937637a6f314f694a305a5868304f434937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734f534937637a6f774f6949694f334d364e546f696447563464446b694f334d364d446f69496a747a4f6a457a4f694a706257466e5a546c666157316e58326c6b496a747a4f6a4536496a41694f334d364e546f69646d6c6c647a6b694f326b364d44747a4f6a6336496e5270644756734d5441694f334d364d446f69496a747a4f6a5936496e526c654851784d434937637a6f774f6949694f334d364d545136496d6c745957646c4d5442666157316e58326c6b496a747a4f6a4536496a41694f334d364e6a6f69646d6c6c647a4577496a74704f6a4137637a6f784d6a6f695a4752706257466e5a5739795a475679496a74684f6a673665326b364d44747a4f6a4536496a45694f326b364d54747a4f6a4536496a49694f326b364d6a747a4f6a4536496a4d694f326b364d7a747a4f6a4536496a51694f326b364e44747a4f6a4536496a55694f326b364e54747a4f6a4536496a59694f326b364e6a747a4f6a4536496a63694f326b364e7a747a4f6a4536496a67694f33317a4f6a5136496d526c63324d694f334d364f444d36496b46316332646c64795a68645731734f32687364475567516d783162575675596d6c735a47567949485a76626941385953426f636d566d50534a6f644852774f693876634768766447396a59584e6c4c6d4e766253492b634768766447396a59584e6c4c6d4e766254777659543475496a74393c2f6461745f70726f70733e0a09093c73657175656e63655f646174613e0a09093c2f73657175656e63655f646174613e0a093c2f636f6e74656e743e0a3c2f7068656e6f747970653e, 0);
INSERT INTO `snapshot` VALUES (3, 'CO', 1, 1601, 1191355880, 13, 0, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0a3c7068656e6f747970653e0a093c6d6574613e0a09093c707476657273696f6e3e23232150545f56455253494f4e2123233c2f707476657273696f6e3e0a09093c707473756276657273696f6e3e2323214255494c445f4e4f2123233c2f707473756276657273696f6e3e0a09093c636f6e5f69643e313630313c2f636f6e5f69643e0a09093c6461745f69643e313c2f6461745f69643e0a09093c6461745f69645f6c6f63616c3e313c2f6461745f69645f6c6f63616c3e0a09093c696d706f72746d6574686f643e6f76657277726974653c2f696d706f72746d6574686f643e0a09093c6275696c64696e6465783e313c2f6275696c64696e6465783e09090a093c2f6d6574613e0a093c636f6e74656e743e0a093c6461745f7569643e30326231303939313234363536663763636334376166633163303339343461613c2f6461745f7569643e0a3c6461745f62657a3e44656d6f6e7374726174696f6e2047616c6c6572793c2f6461745f62657a3e0a3c7573725f69645f63726561746f723e31303c2f7573725f69645f63726561746f723e0a3c6461745f6372656174696f6e646174653e313135333036373037353c2f6461745f6372656174696f6e646174653e0a3c7573725f69643e31333c2f7573725f69643e0a3c6461745f646174653e313139313335353838303c2f6461745f646174653e0a3c6461745f706f733e303c2f6461745f706f733e0a3c6461745f7374617475733e303c2f6461745f7374617475733e0a3c6d65645f69645f7468756d623e31323c2f6d65645f69645f7468756d623e0a3c6461745f66756c6c7365617263683e44656d6f6e7374726174696f6e2047616c6c657279207c2044656d6f6e7374726174696f6e2047616c6c657279207c2038207c2038207c204172726179207c203132207c2031207c203230207c2031207c203134207c2031207c203135207c2031207c203136207c2031207c203137207c2031207c203138207c2031207c203139207c2031207c20426c756d652031207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652032207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652033207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652034207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652035207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652036207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652037207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652038207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20207c20207c2030207c2030207c20207c20207c2030207c2030207c204172726179207c20417573676577262333383b61756d6c3b686c746520426c756d656e62696c64657220766f6e2070686f746f636173652e636f6d2e3c2f6461745f66756c6c7365617263683e0a0a09093c6461745f70726f70733e59546f304e6a7037637a6f7a4f694a695a586f694f334d364d6a4536496b526c62573975633352795958527062323467523246736247567965534937637a6f324f694a68626e7068614777694f326b364f44747a4f6a6736496d316865476c745957646c496a74704f6a6737637a6f334f694a77636d566a5957786a496a74684f6a673665326b364d5474684f6a593665326b364d44747a4f6a6336496b4a736457316c494445694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784d6977784c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445794c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a493759546f324f6e74704f6a4137637a6f334f694a43624856745a534179496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d6a41734f433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441794d433571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a4933496a743961546f7a4f3245364e6a703761546f774f334d364e7a6f69516d7831625755674d79493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445304c444975616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d545175616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49314e53493766576b364e4474684f6a593665326b364d44747a4f6a6336496b4a736457316c494451694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784e53777a4c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445314c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a553759546f324f6e74704f6a4137637a6f334f694a43624856745a534131496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d5459734e433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784e693571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743961546f324f3245364e6a703761546f774f334d364e7a6f69516d7831625755674e69493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445334c445575616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d546375616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49794e69493766576b364e7a74684f6a593665326b364d44747a4f6a6336496b4a736457316c494463694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784f4377324c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445344c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a673759546f324f6e74704f6a4137637a6f334f694a43624856745a534134496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d546b734e793571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784f533571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743966584d364d544d36496d6c745957646c4d56397062576466615751694f326b364d544937637a6f314f694a32615756334d53493761546f784f334d364d544d36496d6c745957646c4d6c397062576466615751694f326b364d6a4137637a6f314f694a32615756334d69493761546f784f334d364d544d36496d6c745957646c4d31397062576466615751694f326b364d545137637a6f314f694a32615756334d79493761546f784f334d364d544d36496d6c745957646c4e46397062576466615751694f326b364d545537637a6f314f694a32615756334e43493761546f784f334d364d544d36496d6c745957646c4e56397062576466615751694f326b364d545937637a6f314f694a32615756334e53493761546f784f334d364d544d36496d6c745957646c4e6c397062576466615751694f326b364d546337637a6f314f694a32615756334e69493761546f784f334d364d544d36496d6c745957646c4e31397062576466615751694f326b364d546737637a6f314f694a32615756334e79493761546f784f334d364d544d36496d6c745957646c4f46397062576466615751694f326b364d546b37637a6f314f694a32615756334f43493761546f784f334d364e6a6f6964476c305a577778496a747a4f6a6336496b4a736457316c494445694f334d364e546f6964475634644445694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624449694f334d364e7a6f69516d7831625755674d694937637a6f314f694a305a5868304d694937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734d794937637a6f334f694a43624856745a53417a496a747a4f6a5536496e526c6548517a496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577730496a747a4f6a6336496b4a736457316c494451694f334d364e546f6964475634644451694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624455694f334d364e7a6f69516d7831625755674e534937637a6f314f694a305a5868304e534937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734e694937637a6f334f694a43624856745a534132496a747a4f6a5536496e526c65485132496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577733496a747a4f6a6336496b4a736457316c494463694f334d364e546f6964475634644463694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624467694f334d364e7a6f69516d7831625755674f434937637a6f314f694a305a5868304f434937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734f534937637a6f774f6949694f334d364e546f696447563464446b694f334d364d446f69496a747a4f6a457a4f694a706257466e5a546c666157316e58326c6b496a747a4f6a4536496a41694f334d364e546f69646d6c6c647a6b694f326b364d44747a4f6a6336496e5270644756734d5441694f334d364d446f69496a747a4f6a5936496e526c654851784d434937637a6f774f6949694f334d364d545136496d6c745957646c4d5442666157316e58326c6b496a747a4f6a4536496a41694f334d364e6a6f69646d6c6c647a4577496a74704f6a4137637a6f784d6a6f695a4752706257466e5a5739795a475679496a74684f6a673665326b364d44747a4f6a4536496a45694f326b364d54747a4f6a4536496a49694f326b364d6a747a4f6a4536496a4d694f326b364d7a747a4f6a4536496a51694f326b364e44747a4f6a4536496a55694f326b364e54747a4f6a4536496a59694f326b364e6a747a4f6a4536496a63694f326b364e7a747a4f6a4536496a67694f33317a4f6a5136496d526c63324d694f334d364f444d36496b46316332646c64795a68645731734f32687364475567516d783162575675596d6c735a47567949485a76626941385953426f636d566d50534a6f644852774f693876634768766447396a59584e6c4c6d4e766253492b634768766447396a59584e6c4c6d4e766254777659543475496a74393c2f6461745f70726f70733e0a09093c73657175656e63655f646174613e0a09093c2f73657175656e63655f646174613e0a093c2f636f6e74656e743e0a3c2f7068656e6f747970653e, 0);
INSERT INTO `snapshot` VALUES (4, 'CO', 1, 1601, 1191355883, 13, 0, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0a3c7068656e6f747970653e0a093c6d6574613e0a09093c707476657273696f6e3e23232150545f56455253494f4e2123233c2f707476657273696f6e3e0a09093c707473756276657273696f6e3e2323214255494c445f4e4f2123233c2f707473756276657273696f6e3e0a09093c636f6e5f69643e313630313c2f636f6e5f69643e0a09093c6461745f69643e313c2f6461745f69643e0a09093c6461745f69645f6c6f63616c3e313c2f6461745f69645f6c6f63616c3e0a09093c696d706f72746d6574686f643e6f76657277726974653c2f696d706f72746d6574686f643e0a09093c6275696c64696e6465783e313c2f6275696c64696e6465783e09090a093c2f6d6574613e0a093c636f6e74656e743e0a093c6461745f7569643e30326231303939313234363536663763636334376166633163303339343461613c2f6461745f7569643e0a3c6461745f62657a3e44656d6f6e7374726174696f6e2047616c6c6572793c2f6461745f62657a3e0a3c7573725f69645f63726561746f723e31303c2f7573725f69645f63726561746f723e0a3c6461745f6372656174696f6e646174653e313135333036373037353c2f6461745f6372656174696f6e646174653e0a3c7573725f69643e31333c2f7573725f69643e0a3c6461745f646174653e313139313335353838333c2f6461745f646174653e0a3c6461745f706f733e303c2f6461745f706f733e0a3c6461745f7374617475733e303c2f6461745f7374617475733e0a3c6d65645f69645f7468756d623e31323c2f6d65645f69645f7468756d623e0a3c6461745f66756c6c7365617263683e44656d6f6e7374726174696f6e2047616c6c657279207c2044656d6f6e7374726174696f6e2047616c6c657279207c2038207c2038207c204172726179207c203132207c2031207c203230207c2031207c203134207c2031207c203135207c2031207c203136207c2031207c203137207c2031207c203138207c2031207c203139207c2031207c20426c756d652031207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652032207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652033207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652034207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652035207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652036207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652037207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652038207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20207c20207c2030207c2030207c20207c20207c2030207c2030207c204172726179207c20417573676577262333383b61756d6c3b686c746520426c756d656e62696c64657220766f6e2070686f746f636173652e636f6d2e3c2f6461745f66756c6c7365617263683e0a0a09093c6461745f70726f70733e59546f304e6a7037637a6f7a4f694a695a586f694f334d364d6a4536496b526c62573975633352795958527062323467523246736247567965534937637a6f324f694a68626e7068614777694f326b364f44747a4f6a6736496d316865476c745957646c496a74704f6a6737637a6f334f694a77636d566a5957786a496a74684f6a673665326b364d5474684f6a593665326b364d44747a4f6a6336496b4a736457316c494445694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784d6977784c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445794c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a493759546f324f6e74704f6a4137637a6f334f694a43624856745a534179496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d6a41734f433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441794d433571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a4933496a743961546f7a4f3245364e6a703761546f774f334d364e7a6f69516d7831625755674d79493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445304c444975616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d545175616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49314e53493766576b364e4474684f6a593665326b364d44747a4f6a6336496b4a736457316c494451694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784e53777a4c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445314c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a553759546f324f6e74704f6a4137637a6f334f694a43624856745a534131496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d5459734e433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784e693571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743961546f324f3245364e6a703761546f774f334d364e7a6f69516d7831625755674e69493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445334c445575616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d546375616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49794e69493766576b364e7a74684f6a593665326b364d44747a4f6a6336496b4a736457316c494463694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784f4377324c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445344c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a673759546f324f6e74704f6a4137637a6f334f694a43624856745a534134496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d546b734e793571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784f533571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743966584d364d544d36496d6c745957646c4d56397062576466615751694f326b364d544937637a6f314f694a32615756334d53493761546f784f334d364d544d36496d6c745957646c4d6c397062576466615751694f326b364d6a4137637a6f314f694a32615756334d69493761546f784f334d364d544d36496d6c745957646c4d31397062576466615751694f326b364d545137637a6f314f694a32615756334d79493761546f784f334d364d544d36496d6c745957646c4e46397062576466615751694f326b364d545537637a6f314f694a32615756334e43493761546f784f334d364d544d36496d6c745957646c4e56397062576466615751694f326b364d545937637a6f314f694a32615756334e53493761546f784f334d364d544d36496d6c745957646c4e6c397062576466615751694f326b364d546337637a6f314f694a32615756334e69493761546f784f334d364d544d36496d6c745957646c4e31397062576466615751694f326b364d546737637a6f314f694a32615756334e79493761546f784f334d364d544d36496d6c745957646c4f46397062576466615751694f326b364d546b37637a6f314f694a32615756334f43493761546f784f334d364e6a6f6964476c305a577778496a747a4f6a6336496b4a736457316c494445694f334d364e546f6964475634644445694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624449694f334d364e7a6f69516d7831625755674d694937637a6f314f694a305a5868304d694937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734d794937637a6f334f694a43624856745a53417a496a747a4f6a5536496e526c6548517a496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577730496a747a4f6a6336496b4a736457316c494451694f334d364e546f6964475634644451694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624455694f334d364e7a6f69516d7831625755674e534937637a6f314f694a305a5868304e534937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734e694937637a6f334f694a43624856745a534132496a747a4f6a5536496e526c65485132496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577733496a747a4f6a6336496b4a736457316c494463694f334d364e546f6964475634644463694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624467694f334d364e7a6f69516d7831625755674f434937637a6f314f694a305a5868304f434937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734f534937637a6f774f6949694f334d364e546f696447563464446b694f334d364d446f69496a747a4f6a457a4f694a706257466e5a546c666157316e58326c6b496a747a4f6a4536496a41694f334d364e546f69646d6c6c647a6b694f326b364d44747a4f6a6336496e5270644756734d5441694f334d364d446f69496a747a4f6a5936496e526c654851784d434937637a6f774f6949694f334d364d545136496d6c745957646c4d5442666157316e58326c6b496a747a4f6a4536496a41694f334d364e6a6f69646d6c6c647a4577496a74704f6a4137637a6f784d6a6f695a4752706257466e5a5739795a475679496a74684f6a673665326b364d44747a4f6a4536496a45694f326b364d54747a4f6a4536496a49694f326b364d6a747a4f6a4536496a4d694f326b364d7a747a4f6a4536496a51694f326b364e44747a4f6a4536496a55694f326b364e54747a4f6a4536496a59694f326b364e6a747a4f6a4536496a63694f326b364e7a747a4f6a4536496a67694f33317a4f6a5136496d526c63324d694f334d364f444d36496b46316332646c64795a68645731734f32687364475567516d783162575675596d6c735a47567949485a76626941385953426f636d566d50534a6f644852774f693876634768766447396a59584e6c4c6d4e766253492b634768766447396a59584e6c4c6d4e766254777659543475496a74393c2f6461745f70726f70733e0a09093c73657175656e63655f646174613e0a09093c2f73657175656e63655f646174613e0a093c2f636f6e74656e743e0a3c2f7068656e6f747970653e, 0);
INSERT INTO `snapshot` VALUES (5, 'CO', 1, 1601, 1191355892, 13, 0, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0a3c7068656e6f747970653e0a093c6d6574613e0a09093c707476657273696f6e3e23232150545f56455253494f4e2123233c2f707476657273696f6e3e0a09093c707473756276657273696f6e3e2323214255494c445f4e4f2123233c2f707473756276657273696f6e3e0a09093c636f6e5f69643e313630313c2f636f6e5f69643e0a09093c6461745f69643e313c2f6461745f69643e0a09093c6461745f69645f6c6f63616c3e313c2f6461745f69645f6c6f63616c3e0a09093c696d706f72746d6574686f643e6f76657277726974653c2f696d706f72746d6574686f643e0a09093c6275696c64696e6465783e313c2f6275696c64696e6465783e09090a093c2f6d6574613e0a093c636f6e74656e743e0a093c6461745f7569643e30326231303939313234363536663763636334376166633163303339343461613c2f6461745f7569643e0a3c6461745f62657a3e44656d6f6e7374726174696f6e2047616c6c6572793c2f6461745f62657a3e0a3c7573725f69645f63726561746f723e31303c2f7573725f69645f63726561746f723e0a3c6461745f6372656174696f6e646174653e313135333036373037353c2f6461745f6372656174696f6e646174653e0a3c7573725f69643e31333c2f7573725f69643e0a3c6461745f646174653e313139313335353839323c2f6461745f646174653e0a3c6461745f706f733e303c2f6461745f706f733e0a3c6461745f7374617475733e303c2f6461745f7374617475733e0a3c6d65645f69645f7468756d623e31323c2f6d65645f69645f7468756d623e0a3c6461745f66756c6c7365617263683e44656d6f6e7374726174696f6e2047616c6c657279207c2044656d6f6e7374726174696f6e2047616c6c657279207c2038207c2038207c204172726179207c203132207c2031207c203230207c2031207c203134207c2031207c203135207c2031207c203136207c2031207c203137207c2031207c203138207c2031207c203139207c2031207c20426c756d652031207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652032207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652033207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652034207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652035207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652036207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652037207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652038207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20207c20207c2030207c2030207c20207c20207c2030207c2030207c204172726179207c20417573676577262333383b61756d6c3b686c746520426c756d656e62696c64657220766f6e2070686f746f636173652e636f6d2e3c2f6461745f66756c6c7365617263683e0a0a09093c6461745f70726f70733e59546f304e6a7037637a6f7a4f694a695a586f694f334d364d6a4536496b526c62573975633352795958527062323467523246736247567965534937637a6f324f694a68626e7068614777694f326b364f44747a4f6a6736496d316865476c745957646c496a74704f6a6737637a6f334f694a77636d566a5957786a496a74684f6a673665326b364d5474684f6a593665326b364d44747a4f6a6336496b4a736457316c494445694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784d6977784c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445794c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a493759546f324f6e74704f6a4137637a6f334f694a43624856745a534179496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d6a41734f433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441794d433571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a4933496a743961546f7a4f3245364e6a703761546f774f334d364e7a6f69516d7831625755674d79493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445304c444975616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d545175616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49314e53493766576b364e4474684f6a593665326b364d44747a4f6a6336496b4a736457316c494451694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784e53777a4c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445314c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a553759546f324f6e74704f6a4137637a6f334f694a43624856745a534131496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d5459734e433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784e693571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743961546f324f3245364e6a703761546f774f334d364e7a6f69516d7831625755674e69493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445334c445575616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d546375616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49794e69493766576b364e7a74684f6a593665326b364d44747a4f6a6336496b4a736457316c494463694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784f4377324c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445344c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a673759546f324f6e74704f6a4137637a6f334f694a43624856745a534134496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d546b734e793571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784f533571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743966584d364d544d36496d6c745957646c4d56397062576466615751694f326b364d544937637a6f314f694a32615756334d53493761546f784f334d364d544d36496d6c745957646c4d6c397062576466615751694f326b364d6a4137637a6f314f694a32615756334d69493761546f784f334d364d544d36496d6c745957646c4d31397062576466615751694f326b364d545137637a6f314f694a32615756334d79493761546f784f334d364d544d36496d6c745957646c4e46397062576466615751694f326b364d545537637a6f314f694a32615756334e43493761546f784f334d364d544d36496d6c745957646c4e56397062576466615751694f326b364d545937637a6f314f694a32615756334e53493761546f784f334d364d544d36496d6c745957646c4e6c397062576466615751694f326b364d546337637a6f314f694a32615756334e69493761546f784f334d364d544d36496d6c745957646c4e31397062576466615751694f326b364d546737637a6f314f694a32615756334e79493761546f784f334d364d544d36496d6c745957646c4f46397062576466615751694f326b364d546b37637a6f314f694a32615756334f43493761546f784f334d364e6a6f6964476c305a577778496a747a4f6a6336496b4a736457316c494445694f334d364e546f6964475634644445694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624449694f334d364e7a6f69516d7831625755674d694937637a6f314f694a305a5868304d694937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734d794937637a6f334f694a43624856745a53417a496a747a4f6a5536496e526c6548517a496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577730496a747a4f6a6336496b4a736457316c494451694f334d364e546f6964475634644451694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624455694f334d364e7a6f69516d7831625755674e534937637a6f314f694a305a5868304e534937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734e694937637a6f334f694a43624856745a534132496a747a4f6a5536496e526c65485132496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577733496a747a4f6a6336496b4a736457316c494463694f334d364e546f6964475634644463694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624467694f334d364e7a6f69516d7831625755674f434937637a6f314f694a305a5868304f434937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734f534937637a6f774f6949694f334d364e546f696447563464446b694f334d364d446f69496a747a4f6a457a4f694a706257466e5a546c666157316e58326c6b496a747a4f6a4536496a41694f334d364e546f69646d6c6c647a6b694f326b364d44747a4f6a6336496e5270644756734d5441694f334d364d446f69496a747a4f6a5936496e526c654851784d434937637a6f774f6949694f334d364d545136496d6c745957646c4d5442666157316e58326c6b496a747a4f6a4536496a41694f334d364e6a6f69646d6c6c647a4577496a74704f6a4137637a6f784d6a6f695a4752706257466e5a5739795a475679496a74684f6a673665326b364d44747a4f6a4536496a45694f326b364d54747a4f6a4536496a49694f326b364d6a747a4f6a4536496a4d694f326b364d7a747a4f6a4536496a51694f326b364e44747a4f6a4536496a55694f326b364e54747a4f6a4536496a59694f326b364e6a747a4f6a4536496a63694f326b364e7a747a4f6a4536496a67694f33317a4f6a5136496d526c63324d694f334d364f444d36496b46316332646c64795a68645731734f32687364475567516d783162575675596d6c735a47567949485a76626941385953426f636d566d50534a6f644852774f693876634768766447396a59584e6c4c6d4e766253492b634768766447396a59584e6c4c6d4e766254777659543475496a74393c2f6461745f70726f70733e0a09093c73657175656e63655f646174613e0a09093c2f73657175656e63655f646174613e0a093c2f636f6e74656e743e0a3c2f7068656e6f747970653e, 0);
INSERT INTO `snapshot` VALUES (6, 'CO', 1, 1601, 1191355940, 13, 0, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0a3c7068656e6f747970653e0a093c6d6574613e0a09093c707476657273696f6e3e23232150545f56455253494f4e2123233c2f707476657273696f6e3e0a09093c707473756276657273696f6e3e2323214255494c445f4e4f2123233c2f707473756276657273696f6e3e0a09093c636f6e5f69643e313630313c2f636f6e5f69643e0a09093c6461745f69643e313c2f6461745f69643e0a09093c6461745f69645f6c6f63616c3e313c2f6461745f69645f6c6f63616c3e0a09093c696d706f72746d6574686f643e6f76657277726974653c2f696d706f72746d6574686f643e0a09093c6275696c64696e6465783e313c2f6275696c64696e6465783e09090a093c2f6d6574613e0a093c636f6e74656e743e0a093c6461745f7569643e30326231303939313234363536663763636334376166633163303339343461613c2f6461745f7569643e0a3c6461745f62657a3e44656d6f6e7374726174696f6e2047616c6c6572793c2f6461745f62657a3e0a3c7573725f69645f63726561746f723e31303c2f7573725f69645f63726561746f723e0a3c6461745f6372656174696f6e646174653e313135333036373037353c2f6461745f6372656174696f6e646174653e0a3c7573725f69643e31333c2f7573725f69643e0a3c6461745f646174653e313139313335353934303c2f6461745f646174653e0a3c6461745f706f733e303c2f6461745f706f733e0a3c6461745f7374617475733e303c2f6461745f7374617475733e0a3c6d65645f69645f7468756d623e31323c2f6d65645f69645f7468756d623e0a3c6461745f66756c6c7365617263683e44656d6f6e7374726174696f6e2047616c6c657279207c2044656d6f6e7374726174696f6e2047616c6c657279207c2038207c2038207c204172726179207c203132207c2031207c203230207c2031207c203134207c2031207c203135207c2031207c203136207c2031207c203137207c2031207c203138207c2031207c203139207c2031207c20426c756d652031207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652032207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652033207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652034207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652035207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652036207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652037207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652038207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20207c20207c2030207c2030207c20207c20207c2030207c2030207c204172726179207c20417573676577262333383b61756d6c3b686c746520426c756d656e62696c64657220766f6e2070686f746f636173652e636f6d2e3c2f6461745f66756c6c7365617263683e0a0a09093c6461745f70726f70733e59546f304e6a7037637a6f7a4f694a695a586f694f334d364d6a4536496b526c62573975633352795958527062323467523246736247567965534937637a6f324f694a68626e7068614777694f326b364f44747a4f6a6736496d316865476c745957646c496a74704f6a6737637a6f334f694a77636d566a5957786a496a74684f6a673665326b364d5474684f6a593665326b364d44747a4f6a6336496b4a736457316c494445694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784d6977784c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445794c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a493759546f324f6e74704f6a4137637a6f334f694a43624856745a534179496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d6a41734f433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441794d433571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a4933496a743961546f7a4f3245364e6a703761546f774f334d364e7a6f69516d7831625755674d79493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445304c444975616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d545175616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49314e53493766576b364e4474684f6a593665326b364d44747a4f6a6336496b4a736457316c494451694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784e53777a4c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445314c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a553759546f324f6e74704f6a4137637a6f334f694a43624856745a534131496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d5459734e433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784e693571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743961546f324f3245364e6a703761546f774f334d364e7a6f69516d7831625755674e69493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445334c445575616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d546375616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49794e69493766576b364e7a74684f6a593665326b364d44747a4f6a6336496b4a736457316c494463694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784f4377324c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445344c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a673759546f324f6e74704f6a4137637a6f334f694a43624856745a534134496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d546b734e793571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784f533571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743966584d364d544d36496d6c745957646c4d56397062576466615751694f326b364d544937637a6f314f694a32615756334d53493761546f784f334d364d544d36496d6c745957646c4d6c397062576466615751694f326b364d6a4137637a6f314f694a32615756334d69493761546f784f334d364d544d36496d6c745957646c4d31397062576466615751694f326b364d545137637a6f314f694a32615756334d79493761546f784f334d364d544d36496d6c745957646c4e46397062576466615751694f326b364d545537637a6f314f694a32615756334e43493761546f784f334d364d544d36496d6c745957646c4e56397062576466615751694f326b364d545937637a6f314f694a32615756334e53493761546f784f334d364d544d36496d6c745957646c4e6c397062576466615751694f326b364d546337637a6f314f694a32615756334e69493761546f784f334d364d544d36496d6c745957646c4e31397062576466615751694f326b364d546737637a6f314f694a32615756334e79493761546f784f334d364d544d36496d6c745957646c4f46397062576466615751694f326b364d546b37637a6f314f694a32615756334f43493761546f784f334d364e6a6f6964476c305a577778496a747a4f6a6336496b4a736457316c494445694f334d364e546f6964475634644445694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624449694f334d364e7a6f69516d7831625755674d694937637a6f314f694a305a5868304d694937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734d794937637a6f334f694a43624856745a53417a496a747a4f6a5536496e526c6548517a496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577730496a747a4f6a6336496b4a736457316c494451694f334d364e546f6964475634644451694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624455694f334d364e7a6f69516d7831625755674e534937637a6f314f694a305a5868304e534937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734e694937637a6f334f694a43624856745a534132496a747a4f6a5536496e526c65485132496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577733496a747a4f6a6336496b4a736457316c494463694f334d364e546f6964475634644463694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624467694f334d364e7a6f69516d7831625755674f434937637a6f314f694a305a5868304f434937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734f534937637a6f774f6949694f334d364e546f696447563464446b694f334d364d446f69496a747a4f6a457a4f694a706257466e5a546c666157316e58326c6b496a747a4f6a4536496a41694f334d364e546f69646d6c6c647a6b694f326b364d44747a4f6a6336496e5270644756734d5441694f334d364d446f69496a747a4f6a5936496e526c654851784d434937637a6f774f6949694f334d364d545136496d6c745957646c4d5442666157316e58326c6b496a747a4f6a4536496a41694f334d364e6a6f69646d6c6c647a4577496a74704f6a4137637a6f784d6a6f695a4752706257466e5a5739795a475679496a74684f6a673665326b364d44747a4f6a4536496a45694f326b364d54747a4f6a4536496a49694f326b364d6a747a4f6a4536496a4d694f326b364d7a747a4f6a4536496a51694f326b364e44747a4f6a4536496a55694f326b364e54747a4f6a4536496a59694f326b364e6a747a4f6a4536496a63694f326b364e7a747a4f6a4536496a67694f33317a4f6a5136496d526c63324d694f334d364f444d36496b46316332646c64795a68645731734f32687364475567516d783162575675596d6c735a47567949485a76626941385953426f636d566d50534a6f644852774f693876634768766447396a59584e6c4c6d4e766253492b634768766447396a59584e6c4c6d4e766254777659543475496a74393c2f6461745f70726f70733e0a09093c73657175656e63655f646174613e0a09093c2f73657175656e63655f646174613e0a093c2f636f6e74656e743e0a3c2f7068656e6f747970653e, 0);
INSERT INTO `snapshot` VALUES (7, 'CO', 1, 1601, 1191355952, 13, 0, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0a3c7068656e6f747970653e0a093c6d6574613e0a09093c707476657273696f6e3e23232150545f56455253494f4e2123233c2f707476657273696f6e3e0a09093c707473756276657273696f6e3e2323214255494c445f4e4f2123233c2f707473756276657273696f6e3e0a09093c636f6e5f69643e313630313c2f636f6e5f69643e0a09093c6461745f69643e313c2f6461745f69643e0a09093c6461745f69645f6c6f63616c3e313c2f6461745f69645f6c6f63616c3e0a09093c696d706f72746d6574686f643e6f76657277726974653c2f696d706f72746d6574686f643e0a09093c6275696c64696e6465783e313c2f6275696c64696e6465783e09090a093c2f6d6574613e0a093c636f6e74656e743e0a093c6461745f7569643e30326231303939313234363536663763636334376166633163303339343461613c2f6461745f7569643e0a3c6461745f62657a3e44656d6f6e7374726174696f6e2047616c6c6572793c2f6461745f62657a3e0a3c7573725f69645f63726561746f723e31303c2f7573725f69645f63726561746f723e0a3c6461745f6372656174696f6e646174653e313135333036373037353c2f6461745f6372656174696f6e646174653e0a3c7573725f69643e31333c2f7573725f69643e0a3c6461745f646174653e313139313335353935323c2f6461745f646174653e0a3c6461745f706f733e303c2f6461745f706f733e0a3c6461745f7374617475733e313c2f6461745f7374617475733e0a3c6d65645f69645f7468756d623e31323c2f6d65645f69645f7468756d623e0a3c6461745f66756c6c7365617263683e44656d6f6e7374726174696f6e2047616c6c657279207c2044656d6f6e7374726174696f6e2047616c6c657279207c2038207c2038207c204172726179207c203132207c2031207c203230207c2031207c203134207c2031207c203135207c2031207c203136207c2031207c203137207c2031207c203138207c2031207c203139207c2031207c20426c756d652031207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652032207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652033207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652034207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652035207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652036207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652037207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652038207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20207c20207c2030207c2030207c20207c20207c2030207c2030207c204172726179207c20417573676577262333383b61756d6c3b686c746520426c756d656e62696c64657220766f6e2070686f746f636173652e636f6d2e3c2f6461745f66756c6c7365617263683e0a0a09093c6461745f70726f70733e59546f304e6a7037637a6f7a4f694a695a586f694f334d364d6a4536496b526c62573975633352795958527062323467523246736247567965534937637a6f324f694a68626e7068614777694f326b364f44747a4f6a6736496d316865476c745957646c496a74704f6a6737637a6f334f694a77636d566a5957786a496a74684f6a673665326b364d5474684f6a593665326b364d44747a4f6a6336496b4a736457316c494445694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784d6977784c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445794c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a493759546f324f6e74704f6a4137637a6f334f694a43624856745a534179496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d6a41734f433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441794d433571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a4933496a743961546f7a4f3245364e6a703761546f774f334d364e7a6f69516d7831625755674d79493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445304c444975616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d545175616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49314e53493766576b364e4474684f6a593665326b364d44747a4f6a6336496b4a736457316c494451694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784e53777a4c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445314c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a553759546f324f6e74704f6a4137637a6f334f694a43624856745a534131496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d5459734e433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784e693571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743961546f324f3245364e6a703761546f774f334d364e7a6f69516d7831625755674e69493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445334c445575616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d546375616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49794e69493766576b364e7a74684f6a593665326b364d44747a4f6a6336496b4a736457316c494463694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784f4377324c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445344c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a673759546f324f6e74704f6a4137637a6f334f694a43624856745a534134496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d546b734e793571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784f533571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743966584d364d544d36496d6c745957646c4d56397062576466615751694f326b364d544937637a6f314f694a32615756334d53493761546f784f334d364d544d36496d6c745957646c4d6c397062576466615751694f326b364d6a4137637a6f314f694a32615756334d69493761546f784f334d364d544d36496d6c745957646c4d31397062576466615751694f326b364d545137637a6f314f694a32615756334d79493761546f784f334d364d544d36496d6c745957646c4e46397062576466615751694f326b364d545537637a6f314f694a32615756334e43493761546f784f334d364d544d36496d6c745957646c4e56397062576466615751694f326b364d545937637a6f314f694a32615756334e53493761546f784f334d364d544d36496d6c745957646c4e6c397062576466615751694f326b364d546337637a6f314f694a32615756334e69493761546f784f334d364d544d36496d6c745957646c4e31397062576466615751694f326b364d546737637a6f314f694a32615756334e79493761546f784f334d364d544d36496d6c745957646c4f46397062576466615751694f326b364d546b37637a6f314f694a32615756334f43493761546f784f334d364e6a6f6964476c305a577778496a747a4f6a6336496b4a736457316c494445694f334d364e546f6964475634644445694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624449694f334d364e7a6f69516d7831625755674d694937637a6f314f694a305a5868304d694937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734d794937637a6f334f694a43624856745a53417a496a747a4f6a5536496e526c6548517a496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577730496a747a4f6a6336496b4a736457316c494451694f334d364e546f6964475634644451694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624455694f334d364e7a6f69516d7831625755674e534937637a6f314f694a305a5868304e534937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734e694937637a6f334f694a43624856745a534132496a747a4f6a5536496e526c65485132496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577733496a747a4f6a6336496b4a736457316c494463694f334d364e546f6964475634644463694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624467694f334d364e7a6f69516d7831625755674f434937637a6f314f694a305a5868304f434937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734f534937637a6f774f6949694f334d364e546f696447563464446b694f334d364d446f69496a747a4f6a457a4f694a706257466e5a546c666157316e58326c6b496a747a4f6a4536496a41694f334d364e546f69646d6c6c647a6b694f326b364d44747a4f6a6336496e5270644756734d5441694f334d364d446f69496a747a4f6a5936496e526c654851784d434937637a6f774f6949694f334d364d545136496d6c745957646c4d5442666157316e58326c6b496a747a4f6a4536496a41694f334d364e6a6f69646d6c6c647a4577496a74704f6a4137637a6f784d6a6f695a4752706257466e5a5739795a475679496a74684f6a673665326b364d44747a4f6a4536496a45694f326b364d54747a4f6a4536496a49694f326b364d6a747a4f6a4536496a4d694f326b364d7a747a4f6a4536496a51694f326b364e44747a4f6a4536496a55694f326b364e54747a4f6a4536496a59694f326b364e6a747a4f6a4536496a63694f326b364e7a747a4f6a4536496a67694f33317a4f6a5136496d526c63324d694f334d364f444d36496b46316332646c64795a68645731734f32687364475567516d783162575675596d6c735a47567949485a76626941385953426f636d566d50534a6f644852774f693876634768766447396a59584e6c4c6d4e766253492b634768766447396a59584e6c4c6d4e766254777659543475496a74393c2f6461745f70726f70733e0a09093c73657175656e63655f646174613e0a09093c2f73657175656e63655f646174613e0a093c2f636f6e74656e743e0a3c2f7068656e6f747970653e, 0);
INSERT INTO `snapshot` VALUES (8, 'CO', 1, 1601, 1191355965, 13, 0, 0x3c3f786d6c2076657273696f6e3d22312e302220656e636f64696e673d2249534f2d383835392d3122203f3e0a3c7068656e6f747970653e0a093c6d6574613e0a09093c707476657273696f6e3e23232150545f56455253494f4e2123233c2f707476657273696f6e3e0a09093c707473756276657273696f6e3e2323214255494c445f4e4f2123233c2f707473756276657273696f6e3e0a09093c636f6e5f69643e313630313c2f636f6e5f69643e0a09093c6461745f69643e313c2f6461745f69643e0a09093c6461745f69645f6c6f63616c3e313c2f6461745f69645f6c6f63616c3e0a09093c696d706f72746d6574686f643e6f76657277726974653c2f696d706f72746d6574686f643e0a09093c6275696c64696e6465783e313c2f6275696c64696e6465783e09090a093c2f6d6574613e0a093c636f6e74656e743e0a093c6461745f7569643e30326231303939313234363536663763636334376166633163303339343461613c2f6461745f7569643e0a3c6461745f62657a3e44656d6f6e7374726174696f6e2047616c6c6572793c2f6461745f62657a3e0a3c7573725f69645f63726561746f723e31303c2f7573725f69645f63726561746f723e0a3c6461745f6372656174696f6e646174653e313135333036373037353c2f6461745f6372656174696f6e646174653e0a3c7573725f69643e31333c2f7573725f69643e0a3c6461745f646174653e313139313335353936353c2f6461745f646174653e0a3c6461745f706f733e303c2f6461745f706f733e0a3c6461745f7374617475733e303c2f6461745f7374617475733e0a3c6d65645f69645f7468756d623e31323c2f6d65645f69645f7468756d623e0a3c6461745f66756c6c7365617263683e44656d6f6e7374726174696f6e2047616c6c657279207c2044656d6f6e7374726174696f6e2047616c6c657279207c2038207c2038207c204172726179207c203132207c2031207c203230207c2031207c203134207c2031207c203135207c2031207c203136207c2031207c203137207c2031207c203138207c2031207c203139207c2031207c20426c756d652031207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652032207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652033207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652034207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652035207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652036207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652037207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20426c756d652038207c204469657365732042696c64207374616d6d7420766f6e2070686f746f636173652e636f6d207c20207c20207c2030207c2030207c20207c20207c2030207c2030207c204172726179207c20417573676577262333383b61756d6c3b686c746520426c756d656e62696c64657220766f6e2070686f746f636173652e636f6d2e3c2f6461745f66756c6c7365617263683e0a0a09093c6461745f70726f70733e59546f304e6a7037637a6f7a4f694a695a586f694f334d364d6a4536496b526c62573975633352795958527062323467523246736247567965534937637a6f324f694a68626e7068614777694f326b364f44747a4f6a6736496d316865476c745957646c496a74704f6a6737637a6f334f694a77636d566a5957786a496a74684f6a673665326b364d5474684f6a593665326b364d44747a4f6a6336496b4a736457316c494445694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784d6977784c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445794c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a493759546f324f6e74704f6a4137637a6f334f694a43624856745a534179496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d6a41734f433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441794d433571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a4933496a743961546f7a4f3245364e6a703761546f774f334d364e7a6f69516d7831625755674d79493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445304c444975616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d545175616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49314e53493766576b364e4474684f6a593665326b364d44747a4f6a6336496b4a736457316c494451694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784e53777a4c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445314c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a553759546f324f6e74704f6a4137637a6f334f694a43624856745a534131496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d5459734e433571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784e693571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743961546f324f3245364e6a703761546f774f334d364e7a6f69516d7831625755674e69493761546f784f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e766253493761546f794f334d364d6a4d36496a4976535441324d5445304e4441764d4441774d4445334c445575616e426e496a74704f6a4d37637a6f794d546f694d69394a4d4459784d5451304d4338774d4441774d546375616e426e496a74704f6a5137637a6f7a4f69497a4e4441694f326b364e54747a4f6a4d36496a49794e69493766576b364e7a74684f6a593665326b364d44747a4f6a6336496b4a736457316c494463694f326b364d54747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f326b364d6a747a4f6a497a4f6949794c306b774e6a45784e4451774c7a41774d4441784f4377324c6d70775a79493761546f7a4f334d364d6a4536496a4976535441324d5445304e4441764d4441774d4445344c6d70775a79493761546f304f334d364d7a6f694d7a5177496a74704f6a5537637a6f7a4f6949794e5455694f3331704f6a673759546f324f6e74704f6a4137637a6f334f694a43624856745a534134496a74704f6a4537637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a74704f6a4937637a6f794d7a6f694d69394a4d4459784d5451304d4338774d4441774d546b734e793571634763694f326b364d7a747a4f6a49784f6949794c306b774e6a45784e4451774c7a41774d4441784f533571634763694f326b364e44747a4f6a4d36496a4d304d43493761546f314f334d364d7a6f694d6a5531496a743966584d364d544d36496d6c745957646c4d56397062576466615751694f326b364d544937637a6f314f694a32615756334d53493761546f784f334d364d544d36496d6c745957646c4d6c397062576466615751694f326b364d6a4137637a6f314f694a32615756334d69493761546f784f334d364d544d36496d6c745957646c4d31397062576466615751694f326b364d545137637a6f314f694a32615756334d79493761546f784f334d364d544d36496d6c745957646c4e46397062576466615751694f326b364d545537637a6f314f694a32615756334e43493761546f784f334d364d544d36496d6c745957646c4e56397062576466615751694f326b364d545937637a6f314f694a32615756334e53493761546f784f334d364d544d36496d6c745957646c4e6c397062576466615751694f326b364d546337637a6f314f694a32615756334e69493761546f784f334d364d544d36496d6c745957646c4e31397062576466615751694f326b364d546737637a6f314f694a32615756334e79493761546f784f334d364d544d36496d6c745957646c4f46397062576466615751694f326b364d546b37637a6f314f694a32615756334f43493761546f784f334d364e6a6f6964476c305a577778496a747a4f6a6336496b4a736457316c494445694f334d364e546f6964475634644445694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624449694f334d364e7a6f69516d7831625755674d694937637a6f314f694a305a5868304d694937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734d794937637a6f334f694a43624856745a53417a496a747a4f6a5536496e526c6548517a496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577730496a747a4f6a6336496b4a736457316c494451694f334d364e546f6964475634644451694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624455694f334d364e7a6f69516d7831625755674e534937637a6f314f694a305a5868304e534937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734e694937637a6f334f694a43624856745a534132496a747a4f6a5536496e526c65485132496a747a4f6a4d324f694a456157567a5a584d67516d6c735a43427a6447467462585167646d39754948426f623352765932467a5a53356a623230694f334d364e6a6f6964476c305a577733496a747a4f6a6336496b4a736457316c494463694f334d364e546f6964475634644463694f334d364d7a5936496b52705a584e6c637942436157786b49484e30595731746443423262323467634768766447396a59584e6c4c6d4e7662534937637a6f324f694a306158526c624467694f334d364e7a6f69516d7831625755674f434937637a6f314f694a305a5868304f434937637a6f7a4e6a6f6952476c6c6332567a49454a7062475167633352686257313049485a76626942776147393062324e686332557559323974496a747a4f6a5936496e5270644756734f534937637a6f774f6949694f334d364e546f696447563464446b694f334d364d446f69496a747a4f6a457a4f694a706257466e5a546c666157316e58326c6b496a747a4f6a4536496a41694f334d364e546f69646d6c6c647a6b694f326b364d44747a4f6a6336496e5270644756734d5441694f334d364d446f69496a747a4f6a5936496e526c654851784d434937637a6f774f6949694f334d364d545136496d6c745957646c4d5442666157316e58326c6b496a747a4f6a4536496a41694f334d364e6a6f69646d6c6c647a4577496a74704f6a4137637a6f784d6a6f695a4752706257466e5a5739795a475679496a74684f6a673665326b364d44747a4f6a4536496a45694f326b364d54747a4f6a4536496a49694f326b364d6a747a4f6a4536496a4d694f326b364d7a747a4f6a4536496a51694f326b364e44747a4f6a4536496a55694f326b364e54747a4f6a4536496a59694f326b364e6a747a4f6a4536496a63694f326b364e7a747a4f6a4536496a67694f33317a4f6a5136496d526c63324d694f334d364f444d36496b46316332646c64795a68645731734f32687364475567516d783162575675596d6c735a47567949485a76626941385953426f636d566d50534a6f644852774f693876634768766447396a59584e6c4c6d4e766253492b634768766447396a59584e6c4c6d4e766254777659543475496a74393c2f6461745f70726f70733e0a09093c73657175656e63655f646174613e0a09093c2f73657175656e63655f646174613e0a093c2f636f6e74656e743e0a3c2f7068656e6f747970653e, 0);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=8 ;

-- 
-- Daten für Tabelle `ticket`
-- 

INSERT INTO `ticket` VALUES (1, 1, 0, 0, 0, 0, 0, '[1] Profil | Login', 1, 0, 0, 1, 1192808825, 1193785200, 1192808825, 0, 0, 0, 0, 0, 4, 0, 0, 'D', 1192808914, '[1] Profil | Login N.N. System  ', '', '');
INSERT INTO `ticket` VALUES (2, 1, 0, 0, 0, 0, 0, '[1] Profil | Login', 1, 17, 0, 1, 1192808942, 1193785200, 1192808942, 0, 0, 0, 0, 0, 1, 0, 0, 'A', 1192808942, '', '', '');
INSERT INTO `ticket` VALUES (3, 1, 0, 0, 0, 0, 0, '[1] Profil | Login', 1, 17, 0, 1, 1192808986, 1193785200, 1192808986, 0, 0, 0, 0, 0, 1, 0, 0, 'A', 1192808986, '', '', '');
INSERT INTO `ticket` VALUES (4, 1, 0, 0, 0, 0, 0, '[1] Profil | Login', 1, 1, 0, 1, 1192809037, 1193785200, 1192809037, 0, 0, 0, 0, 0, 1, 0, 0, 'A', 1192809037, '', '', '');
INSERT INTO `ticket` VALUES (5, 1, 0, 0, 0, 0, 0, 'arschloch', 10, 10, 0, 1, 1192809160, 1192831200, 1192809160, 0, 0, 0, 0, 0, 3, 0, 0, 'C', 1192809160, '', '', '');
INSERT INTO `ticket` VALUES (6, 10, 0, 0, 0, 0, 0, '[1] Profil | Login', 1, 1, 0, 1, 1192809235, 1193785200, 1192809235, 0, 0, 0, 0, 0, 1, 0, 0, 'A', 1192809235, '', '', '');
INSERT INTO `ticket` VALUES (7, 10, 0, 0, 0, 0, 0, '[1] Profil | Login', 1, 1, 0, 1, 1192809270, 1193785200, 1192809270, 0, 0, 0, 0, 0, 1, 0, 0, 'A', 1192809270, '', '', '');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=4 ;

-- 
-- Daten für Tabelle `ticketaction`
-- 

INSERT INTO `ticketaction` VALUES (1, 1, 9, 'a:14:{s:7:"subject";s:4:"Bugs";s:3:"bez";s:18:"[1] Profil | Login";s:4:"prio";s:1:"1";s:10:"percentage";s:1:"0";s:10:"complexity";s:1:"0";s:8:"tendency";s:1:"0";s:10:"eisenhower";s:1:"A";s:8:"duration";s:1:"0";s:7:"enddate";s:10:"1193785200";s:12:"usr_id_owner";s:2:"17";s:5:"color";s:6:"orange";s:6:"grafik";s:14:"t_a_orange.gif";s:11:"startgrafik";s:14:"t_a_orange.gif";s:11:"newpriority";s:1:"1";}', 1192808886, 10, '');
INSERT INTO `ticketaction` VALUES (2, 1, 2, 'a:15:{s:7:"subject";s:4:"Bugs";s:3:"bez";s:18:"[1] Profil | Login";s:4:"prio";s:1:"1";s:10:"percentage";s:1:"0";s:10:"complexity";s:1:"0";s:8:"tendency";s:1:"0";s:10:"eisenhower";s:1:"A";s:8:"duration";s:1:"0";s:7:"enddate";s:10:"1193785200";s:12:"usr_id_owner";s:2:"17";s:5:"color";s:6:"orange";s:6:"grafik";s:14:"t_a_orange.gif";s:11:"startgrafik";s:14:"t_a_orange.gif";s:11:"newpriority";s:1:"1";s:8:"newowner";s:4:"N.N.";}', 1192808886, 10, '');
INSERT INTO `ticketaction` VALUES (3, 1, 9, 'a:14:{s:7:"subject";s:4:"Bugs";s:3:"bez";s:18:"[1] Profil | Login";s:4:"prio";s:1:"4";s:10:"percentage";s:1:"0";s:10:"complexity";s:1:"0";s:8:"tendency";s:1:"0";s:10:"eisenhower";s:1:"D";s:8:"duration";s:1:"0";s:7:"enddate";s:10:"1193785200";s:12:"usr_id_owner";s:1:"0";s:5:"color";s:6:"orange";s:6:"grafik";s:14:"t_d_orange.gif";s:11:"startgrafik";s:14:"t_a_orange.gif";s:11:"newpriority";s:1:"4";}', 1192808914, 10, '');

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

INSERT INTO `ticketmarkup` VALUES (1, 1, 1);
INSERT INTO `ticketmarkup` VALUES (1, 17, 1);
INSERT INTO `ticketmarkup` VALUES (1, 0, 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=16 ;

-- 
-- Daten für Tabelle `user`
-- 

INSERT INTO `user` VALUES (13, 1, 'starter', 'ph1c2fSo4Tg/2', 'Starter', '', '', 1128621734, 1192806329, 'a:4:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"rol_1";i:1;}', 'a:23:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:1;s:10:"elm_extras";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:8:"con_1601";i:1;s:8:"con_1102";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 0);
INSERT INTO `user` VALUES (1, 0, '', '', 'System', '', '', 1128535703, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0);
INSERT INTO `user` VALUES (2, 0, '', '', 'Importer', '', '', 1128535744, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0);
INSERT INTO `user` VALUES (3, 0, '', '', 'WWW', '', '', 1129560752, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0);
INSERT INTO `user` VALUES (10, 1, 'Nils', 'phf9/.iOb8Mlw', 'Nils', 'Hagemann', '', 1093456701, 1192806368, 'a:6:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"sbj_1";i:1;s:8:"elm_task";i:1;s:5:"rol_1";i:1;}', 'a:25:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:1;s:10:"elm_extras";i:1;s:5:"sbj_1";i:1;s:8:"elm_task";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:8:"con_1601";i:1;s:8:"con_1102";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 1);
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

INSERT INTO `user_ticketsubject` VALUES (10, 1);
