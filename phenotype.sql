-- MySQL dump 10.11
--
-- Host: localhost    Database: phenotype-svn
-- ------------------------------------------------------
-- Server version	5.0.51a

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `action`
--

DROP TABLE IF EXISTS `action`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `action`
--

LOCK TABLES `action` WRITE;
/*!40000 ALTER TABLE `action` DISABLE KEYS */;
/*!40000 ALTER TABLE `action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `component`
--

DROP TABLE IF EXISTS `component`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `component` (
  `com_id` int(11) NOT NULL auto_increment,
  `com_bez` varchar(150) collate latin1_general_ci NOT NULL default '',
  `com_description` text collate latin1_general_ci NOT NULL,
  `com_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`com_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1602 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `component`
--

LOCK TABLES `component` WRITE;
/*!40000 ALTER TABLE `component` DISABLE KEYS */;
INSERT INTO `component` VALUES (1003,'Include','## Baustein 1003 - Include','System'),(1002,'HTML','## Baustein 1002 - HTML','System'),(1001,'Richtextabsatz','## Baustein 1001 - Richtextabsatz\n\nMit diesem Bausteinen können bereits die meisten Anforderungen einer einfachen Website abegedeckt werden. Ein Absatz besteht aus Überschrift, Text, Bild und Link.\n\n','Textbausteine');
/*!40000 ALTER TABLE `component` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `component_componentgroup`
--

DROP TABLE IF EXISTS `component_componentgroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `component_componentgroup` (
  `cog_id` int(11) NOT NULL default '0',
  `com_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cog_id`,`com_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `component_componentgroup`
--

LOCK TABLES `component_componentgroup` WRITE;
/*!40000 ALTER TABLE `component_componentgroup` DISABLE KEYS */;
INSERT INTO `component_componentgroup` VALUES (1,1001),(1,1002),(1,1003);
/*!40000 ALTER TABLE `component_componentgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `component_template`
--

DROP TABLE IF EXISTS `component_template`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `component_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `com_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`com_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `component_template`
--

LOCK TABLES `component_template` WRITE;
/*!40000 ALTER TABLE `component_template` DISABLE KEYS */;
INSERT INTO `component_template` VALUES (1,1001,'TPL_DEFAULT'),(2,1001,'TPL_TOPIMAGE');
/*!40000 ALTER TABLE `component_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `componentgroup`
--

DROP TABLE IF EXISTS `componentgroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `componentgroup` (
  `cog_id` int(11) NOT NULL auto_increment,
  `cog_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `cog_description` text collate latin1_general_ci NOT NULL,
  `cog_pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `componentgroup`
--

LOCK TABLES `componentgroup` WRITE;
/*!40000 ALTER TABLE `componentgroup` DISABLE KEYS */;
INSERT INTO `componentgroup` VALUES (1,'Default','## Default-Bausteingruppe',0);
/*!40000 ALTER TABLE `componentgroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content`
--

DROP TABLE IF EXISTS `content`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
) ENGINE=MyISAM AUTO_INCREMENT=1602 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `content`
--

LOCK TABLES `content` WRITE;
/*!40000 ALTER TABLE `content` DISABLE KEYS */;
INSERT INTO `content` VALUES (1001,'Expandierende Liste','Listenobjekt für selbstexpandierende Auswahlisten in Formularen','System',0,'',1,1,1,0,0,0);
/*!40000 ALTER TABLE `content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_data`
--

DROP TABLE IF EXISTS `content_data`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `content_data`
--

LOCK TABLES `content_data` WRITE;
/*!40000 ALTER TABLE `content_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_statistics`
--

DROP TABLE IF EXISTS `content_statistics`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `content_statistics` (
  `dat_id` int(11) NOT NULL default '0',
  `sta_datum` int(11) NOT NULL default '0',
  `sta_contentview` int(11) NOT NULL default '0',
  KEY `sta_day` (`sta_datum`),
  KEY `exd_id` (`dat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `content_statistics`
--

LOCK TABLES `content_statistics` WRITE;
/*!40000 ALTER TABLE `content_statistics` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_statistics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `content_template`
--

DROP TABLE IF EXISTS `content_template`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `content_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `con_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`con_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `content_template`
--

LOCK TABLES `content_template` WRITE;
/*!40000 ALTER TABLE `content_template` DISABLE KEYS */;
/*!40000 ALTER TABLE `content_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dataobject`
--

DROP TABLE IF EXISTS `dataobject`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `dataobject` (
  `dao_id` int(11) NOT NULL auto_increment,
  `dao_bez` varchar(255) collate latin1_general_ci NOT NULL,
  `dao_params` varchar(255) collate latin1_general_ci NOT NULL,
  `dao_props` longtext collate latin1_general_ci NOT NULL,
  `dao_date` int(11) NOT NULL,
  `dao_ttl` int(11) NOT NULL,
  `dao_type` tinyint(4) NOT NULL,
  `dao_clearonedit` tinyint(4) NOT NULL,
  PRIMARY KEY  (`dao_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `dataobject`
--

LOCK TABLES `dataobject` WRITE;
/*!40000 ALTER TABLE `dataobject` DISABLE KEYS */;
/*!40000 ALTER TABLE `dataobject` ENABLE KEYS */;
UNLOCK TABLES;


--
-- Table structure for table `extra`
--

DROP TABLE IF EXISTS `extra`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `extra` (
  `ext_id` int(11) NOT NULL auto_increment,
  `ext_bez` varchar(50) collate latin1_general_ci NOT NULL default '',
  `ext_description` text collate latin1_general_ci NOT NULL,
  `ext_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  `ext_props` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ext_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1003 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `extra`
--

LOCK TABLES `extra` WRITE;
/*!40000 ALTER TABLE `extra` DISABLE KEYS */;
INSERT INTO `extra` VALUES (1002,'Konsole','','Development','a:1:{s:5:\"color\";s:1:\"1\";}'),(1001,'Pagewizard','','Development','');
/*!40000 ALTER TABLE `extra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `extra_template`
--

DROP TABLE IF EXISTS `extra_template`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `extra_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `ext_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`ext_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `extra_template`
--

LOCK TABLES `extra_template` WRITE;
/*!40000 ALTER TABLE `extra_template` DISABLE KEYS */;
/*!40000 ALTER TABLE `extra_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `include`
--

DROP TABLE IF EXISTS `include`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `include` (
  `inc_id` int(11) NOT NULL auto_increment,
  `inc_bez` varchar(50) collate latin1_general_ci NOT NULL default '',
  `inc_description` text collate latin1_general_ci NOT NULL,
  `inc_rubrik` varchar(50) collate latin1_general_ci NOT NULL default '',
  `inc_usage_layout` tinyint(4) NOT NULL default '0',
  `inc_usage_includecomponent` tinyint(4) NOT NULL default '0',
  `inc_usage_page` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`inc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1107 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `include`
--

LOCK TABLES `include` WRITE;
/*!40000 ALTER TABLE `include` DISABLE KEYS */;
/*!40000 ALTER TABLE `include` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `include_template`
--

DROP TABLE IF EXISTS `include_template`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `include_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `inc_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`inc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `include_template`
--

LOCK TABLES `include_template` WRITE;
/*!40000 ALTER TABLE `include_template` DISABLE KEYS */;
/*!40000 ALTER TABLE `include_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layout`
--

DROP TABLE IF EXISTS `layout`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `layout` (
  `lay_id` int(11) NOT NULL auto_increment,
  `lay_bez` varchar(100) collate latin1_general_ci NOT NULL default '0',
  `lay_description` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`lay_id`),
  UNIQUE KEY `tpl_id` (`lay_id`),
  KEY `tpl_id_2` (`lay_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `layout`
--

LOCK TABLES `layout` WRITE;
/*!40000 ALTER TABLE `layout` DISABLE KEYS */;
INSERT INTO `layout` VALUES (1,'Standard','');
/*!40000 ALTER TABLE `layout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layout_block`
--

DROP TABLE IF EXISTS `layout_block`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `layout_block` (
  `lay_id` int(11) NOT NULL default '0',
  `lay_blocknr` int(11) NOT NULL default '0',
  `lay_blockbez` varchar(250) collate latin1_general_ci NOT NULL default '',
  `cog_id` int(11) NOT NULL default '0',
  `lay_context` int(11) NOT NULL default '0',
  PRIMARY KEY  (`lay_id`,`lay_blocknr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `layout_block`
--

LOCK TABLES `layout_block` WRITE;
/*!40000 ALTER TABLE `layout_block` DISABLE KEYS */;
INSERT INTO `layout_block` VALUES (1,1,'Content',1,1);
/*!40000 ALTER TABLE `layout_block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layout_include`
--

DROP TABLE IF EXISTS `layout_include`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `layout_include` (
  `lay_id` int(11) NOT NULL default '0',
  `inc_id` int(11) NOT NULL default '0',
  `lay_includenr` int(11) NOT NULL default '0',
  `lay_includecache` tinyint(4) NOT NULL default '1',
  KEY `tpl_id` (`lay_id`),
  KEY `inc_id` (`inc_id`),
  KEY `inc_nr` (`lay_includenr`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `layout_include`
--

LOCK TABLES `layout_include` WRITE;
/*!40000 ALTER TABLE `layout_include` DISABLE KEYS */;
/*!40000 ALTER TABLE `layout_include` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `layout_pagegroup`
--

DROP TABLE IF EXISTS `layout_pagegroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `layout_pagegroup` (
  `lay_id` int(11) NOT NULL default '0',
  `grp_id` int(11) NOT NULL default '0',
  KEY `lay_id` (`lay_id`,`grp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `layout_pagegroup`
--

LOCK TABLES `layout_pagegroup` WRITE;
/*!40000 ALTER TABLE `layout_pagegroup` DISABLE KEYS */;
/*!40000 ALTER TABLE `layout_pagegroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,1,'Nils Hagemann',0,'I0610420','nils.jpg',1,'jpg','',60,75,1,'','','','_system','','',0,0,1093458207,1),(2,1,'Peter Sellinger',0,'I0610420','peter.jpg',1,'jpg','',60,71,1,'','','','_system','','',0,0,1093458201,1),(3,1,'Paul Sellinger',0,'I0610420','paul.jpg',1,'jpg','',60,77,1,'','','','_system','','',0,0,1093458195,1),(4,1,'bild.jpg',0,'I0610420','bild.jpg',1,'jpg','',60,40,1,'','','Thumbnails für Contentobjekte','_system','','',0,0,1098390582,1),(5,1,'event.jpg',0,'I0610420','event.jpg',1,'jpg','',60,40,1,'','','Thumbnails für Contentobjekte','_system','','',0,0,1098390582,1),(6,1,'job.jpg',0,'I0610420','job.jpg',1,'jpg','',60,40,1,'','','Thumbnails für Contentobjekte','_system','','',0,0,1098390582,1),(7,1,'konfiguration.jpg',0,'I0610420','konfiguration.jpg',1,'jpg','',60,40,1,'','','Thumbnails für Contentobjekte','_system','','',0,0,1098390582,1),(8,1,'news.jpg',0,'I0610420','news.jpg',1,'jpg','',60,40,1,'','','Thumbnails für Contentobjekte','_system','','',0,0,1098390582,1),(9,1,'promofeld.gif',0,'I0610420','promofeld.gif',1,'gif','',60,40,0,'','','Thumbnails für Contentobjekte','_system','','',0,0,1098390582,1),(10,1,'shop.gif',0,'I0610420','shop.gif',1,'gif','',60,40,0,'','','Thumbnails für Contentobjekte','_system','','',0,0,1098390582,1),(11,1,'Markus Griesbach',0,'I0610420','maggus.jpg',1,'jpg','',60,75,1,'','','','_system','','',10,1153063188,1153063202,10),(13,1,'Michel',0,'I0610420','michel.jpg',1,'jpg','',60,76,1,'','','','_system','','',15,1161453837,1161453837,15);
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mediagroup`
--

DROP TABLE IF EXISTS `mediagroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `mediagroup` (
  `grp_id` int(11) NOT NULL auto_increment,
  `grp_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `grp_description` text collate latin1_general_ci NOT NULL,
  `grp_type` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`grp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `mediagroup`
--

LOCK TABLES `mediagroup` WRITE;
/*!40000 ALTER TABLE `mediagroup` DISABLE KEYS */;
INSERT INTO `mediagroup` VALUES (1,'System','',1),(2,'Standard','',2),(3,'Aufgaben','',3);
/*!40000 ALTER TABLE `mediagroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mediaversion`
--

DROP TABLE IF EXISTS `mediaversion`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `mediaversion`
--

LOCK TABLES `mediaversion` WRITE;
/*!40000 ALTER TABLE `mediaversion` DISABLE KEYS */;
/*!40000 ALTER TABLE `mediaversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
  `pag_url1` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url2` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url3` varchar(255) collate latin1_general_ci NOT NULL,
  `pag_url4` varchar(255) collate latin1_general_ci NOT NULL,
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES (1,'41d1b7423d6618a0a7574487a3fbb89e',1,1,1,1,'Startseite','Phenotype 2.6 Startseite','','',1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,'','',1206375124,13,13,1206374344,1206375049,1206375241,1206375241,1206375241,1206375241,1206375241,1206375241,0,'0.0319','1',1,1,'Phenotype-2.6-Startseite','Phenotype-2.6-Startseite','Phenotype-2.6-Startseite','Phenotype-2.6-Startseite','Phenotype-2.6-Startseite','a:1:{s:8:\"pag_url1\";s:0:\"\";}','a:0:{}','a:0:{}','Phenotype 2.6 Startseite|Phenotype 2.6 Startseite|Phenotype 2.6 Startseite||||Phenotype 2.6|<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\r\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\r\n<a href=\"_phenotype/admin/\">Zum Redaktionssystem</a></p>\r\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href=\"http://www.phenotype.de\">phenotype.de</a> und im <a href=\"http://phenotype.de/wiki/\">Phenotype-Wiki</a></p>|',1,0,0,'');
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_language`
--

DROP TABLE IF EXISTS `page_language`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `page_language`
--

LOCK TABLES `page_language` WRITE;
/*!40000 ALTER TABLE `page_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_statistics`
--

DROP TABLE IF EXISTS `page_statistics`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `page_statistics` (
  `pag_id` int(11) NOT NULL default '0',
  `sta_datum` int(11) NOT NULL default '0',
  `sta_pageview` int(11) NOT NULL default '0',
  KEY `sta_day` (`sta_datum`),
  KEY `pag_id` (`pag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `page_statistics`
--

LOCK TABLES `page_statistics` WRITE;
/*!40000 ALTER TABLE `page_statistics` DISABLE KEYS */;
INSERT INTO `page_statistics` VALUES (1,20080324,4);
/*!40000 ALTER TABLE `page_statistics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagegroup`
--

DROP TABLE IF EXISTS `pagegroup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pagegroup` (
  `grp_id` int(11) NOT NULL auto_increment,
  `grp_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `grp_description` text collate latin1_general_ci NOT NULL,
  `grp_statistic` tinyint(4) NOT NULL default '1',
  `grp_multilanguage` tinyint(4) NOT NULL default '0',
  `grp_smarturl_schema` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`grp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pagegroup`
--

LOCK TABLES `pagegroup` WRITE;
/*!40000 ALTER TABLE `pagegroup` DISABLE KEYS */;
INSERT INTO `pagegroup` VALUES (1,'Struktur','',1,0,0),(2,'Sonderseiten','',1,0,0),(3,'Dynamisch','',1,0,0);
/*!40000 ALTER TABLE `pagegroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pageversion`
--

DROP TABLE IF EXISTS `pageversion`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pageversion`
--

LOCK TABLES `pageversion` WRITE;
/*!40000 ALTER TABLE `pageversion` DISABLE KEYS */;
INSERT INTO `pageversion` VALUES (1,1,1,1,'Version 1',0,0,0,'Phenotype 2.6 Startseite|Phenotype 2.6 Startseite|Phenotype 2.6 Startseite||||Phenotype 2.6|<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\r\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\r\n<a href=\"_phenotype/admin/\">Zum Redaktionssystem</a></p>\r\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href=\"http://www.phenotype.de\">phenotype.de</a> und im <a href=\"http://phenotype.de/wiki/\">Phenotype-Wiki</a></p>|');
/*!40000 ALTER TABLE `pageversion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pageversion_autoactivate`
--

DROP TABLE IF EXISTS `pageversion_autoactivate`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `pageversion_autoactivate` (
  `auv_id` int(11) NOT NULL auto_increment,
  `pag_id` int(11) NOT NULL default '0',
  `ver_id` int(11) NOT NULL default '0',
  `ver_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`auv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `pageversion_autoactivate`
--

LOCK TABLES `pageversion_autoactivate` WRITE;
/*!40000 ALTER TABLE `pageversion_autoactivate` DISABLE KEYS */;
/*!40000 ALTER TABLE `pageversion_autoactivate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `role` (
  `rol_id` int(11) NOT NULL auto_increment,
  `rol_description` text collate latin1_general_ci NOT NULL,
  `rol_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `rol_rights` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`rol_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'','Admin','a:20:{s:14:\"elm_pageconfig\";i:1;s:17:\"elm_pagestatistic\";i:1;s:13:\"elm_mediabase\";i:1;s:11:\"elm_analyse\";i:1;s:9:\"elm_admin\";i:1;s:13:\"elm_redaktion\";i:1;s:11:\"elm_content\";i:0;s:17:\"access_mediagrp_1\";i:1;s:17:\"access_mediagrp_2\";i:1;s:17:\"access_mediagrp_3\";i:1;s:8:\"ext_1001\";i:1;s:8:\"ext_1002\";i:1;s:10:\"elm_extras\";i:1;s:12:\"access_grp_1\";i:1;s:12:\"pag_id_grp_1\";s:1:\"0\";s:12:\"access_grp_2\";i:1;s:12:\"pag_id_grp_2\";s:1:\"0\";s:12:\"access_grp_3\";i:1;s:12:\"pag_id_grp_3\";s:1:\"0\";s:8:\"elm_page\";i:1;}'),(2,'','Redakteur','a:13:{s:14:\"elm_pageconfig\";i:1;s:17:\"elm_pagestatistic\";i:1;s:13:\"elm_mediabase\";i:1;s:13:\"elm_redaktion\";i:1;s:11:\"elm_content\";i:0;s:17:\"access_mediagrp_2\";i:1;s:10:\"elm_extras\";i:0;s:5:\"sbj_1\";i:1;s:12:\"access_grp_1\";i:1;s:12:\"pag_id_grp_1\";s:1:\"0\";s:12:\"access_grp_2\";i:1;s:12:\"pag_id_grp_2\";s:1:\"0\";s:8:\"elm_page\";i:1;}');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sequence_data`
--

DROP TABLE IF EXISTS `sequence_data`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `sequence_data`
--

LOCK TABLES `sequence_data` WRITE;
/*!40000 ALTER TABLE `sequence_data` DISABLE KEYS */;
INSERT INTO `sequence_data` VALUES (1,1,1,1,0,1,1,1,1,1001,'a:8:{s:8:\"headline\";s:13:\"Phenotype 2.6\";s:4:\"text\";s:409:\"<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\r\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\r\n<a href=\"_phenotype/admin/\">Zum Redaktionssystem</a></p>\r\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href=\"http://www.phenotype.de\">phenotype.de</a> und im <a href=\"http://phenotype.de/wiki/\">Phenotype-Wiki</a></p>\";s:6:\"img_id\";s:1:\"0\";s:3:\"alt\";s:0:\"\";s:15:\"bildausrichtung\";s:5:\"links\";s:7:\"linkbez\";s:0:\"\";s:7:\"linkurl\";s:0:\"\";s:10:\"linktarget\";s:5:\"_self\";}','Phenotype 2.6|<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\r\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\r\n<a href=\"_phenotype/admin/\">Zum Redaktionssystem</a></p>\r\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href=\"http://www.phenotype.de\">phenotype.de</a> und im <a href=\"http://phenotype.de/wiki/\">Phenotype-Wiki</a></p>',13),(1,1,1,1,0,0,1,1,1,1001,'a:8:{s:8:\"headline\";s:13:\"Phenotype 2.6\";s:4:\"text\";s:409:\"<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\r\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\r\n<a href=\"_phenotype/admin/\">Zum Redaktionssystem</a></p>\r\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href=\"http://www.phenotype.de\">phenotype.de</a> und im <a href=\"http://phenotype.de/wiki/\">Phenotype-Wiki</a></p>\";s:6:\"img_id\";s:1:\"0\";s:3:\"alt\";s:0:\"\";s:15:\"bildausrichtung\";s:5:\"links\";s:7:\"linkbez\";s:0:\"\";s:7:\"linkurl\";s:0:\"\";s:10:\"linktarget\";s:5:\"_self\";}','Phenotype 2.6|<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\r\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\r\n<a href=\"_phenotype/admin/\">Zum Redaktionssystem</a></p>\r\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href=\"http://www.phenotype.de\">phenotype.de</a> und im <a href=\"http://phenotype.de/wiki/\">Phenotype-Wiki</a></p>',0);
/*!40000 ALTER TABLE `sequence_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `snapshot`
--

DROP TABLE IF EXISTS `snapshot`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `snapshot`
--

LOCK TABLES `snapshot` WRITE;
/*!40000 ALTER TABLE `snapshot` DISABLE KEYS */;
/*!40000 ALTER TABLE `snapshot` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticket`
--

DROP TABLE IF EXISTS `ticket`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ticket`
--

LOCK TABLES `ticket` WRITE;
/*!40000 ALTER TABLE `ticket` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticketaction`
--

DROP TABLE IF EXISTS `ticketaction`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ticketaction`
--

LOCK TABLES `ticketaction` WRITE;
/*!40000 ALTER TABLE `ticketaction` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticketaction` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticketmarkup`
--

DROP TABLE IF EXISTS `ticketmarkup`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ticketmarkup` (
  `tik_id` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `tik_markup` tinyint(4) NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ticketmarkup`
--

LOCK TABLES `ticketmarkup` WRITE;
/*!40000 ALTER TABLE `ticketmarkup` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticketmarkup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticketpin`
--

DROP TABLE IF EXISTS `ticketpin`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ticketpin` (
  `tik_id` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `tik_pin` tinyint(4) NOT NULL default '1',
  KEY `tik_id` (`tik_id`,`usr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ticketpin`
--

LOCK TABLES `ticketpin` WRITE;
/*!40000 ALTER TABLE `ticketpin` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticketpin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticketrequest`
--

DROP TABLE IF EXISTS `ticketrequest`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ticketrequest` (
  `tik_id` int(11) NOT NULL default '0',
  `usr_id` int(11) NOT NULL default '0',
  `tik_request` tinyint(4) NOT NULL default '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ticketrequest`
--

LOCK TABLES `ticketrequest` WRITE;
/*!40000 ALTER TABLE `ticketrequest` DISABLE KEYS */;
/*!40000 ALTER TABLE `ticketrequest` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ticketsubject`
--

DROP TABLE IF EXISTS `ticketsubject`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `ticketsubject` (
  `sbj_id` int(11) NOT NULL auto_increment,
  `sbj_bez` varchar(150) collate latin1_general_ci NOT NULL default '',
  `sbj_description` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`sbj_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `ticketsubject`
--

LOCK TABLES `ticketsubject` WRITE;
/*!40000 ALTER TABLE `ticketsubject` DISABLE KEYS */;
INSERT INTO `ticketsubject` VALUES (1,'Bugs','Bugtracker während der Entwicklung');
/*!40000 ALTER TABLE `ticketsubject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (13,1,'starter','ph1c2fSo4Tg/2','Starter','','',1128621734,1161455573,'a:4:{s:13:\"elm_redaktion\";i:0;s:11:\"elm_content\";i:0;s:10:\"elm_extras\";i:0;s:5:\"rol_1\";i:1;}','a:21:{s:13:\"elm_redaktion\";i:1;s:11:\"elm_content\";i:0;s:10:\"elm_extras\";i:1;s:5:\"rol_1\";i:1;s:14:\"elm_pageconfig\";i:1;s:17:\"elm_pagestatistic\";i:1;s:13:\"elm_mediabase\";i:1;s:11:\"elm_analyse\";i:1;s:9:\"elm_admin\";i:1;s:17:\"access_mediagrp_1\";i:1;s:17:\"access_mediagrp_2\";i:1;s:17:\"access_mediagrp_3\";i:1;s:8:\"ext_1001\";i:1;s:8:\"ext_1002\";i:1;s:12:\"access_grp_1\";i:1;s:12:\"pag_id_grp_1\";s:1:\"0\";s:12:\"access_grp_2\";i:1;s:12:\"pag_id_grp_2\";s:1:\"0\";s:12:\"access_grp_3\";i:1;s:12:\"pag_id_grp_3\";s:1:\"0\";s:8:\"elm_page\";i:1;}','a:0:{}',1,0),(1,0,'','','System','','',1128535703,0,'a:0:{}','a:0:{}','a:0:{}',0,0),(2,0,'','','Importer','','',1128535744,0,'a:0:{}','a:0:{}','a:0:{}',0,0),(3,0,'','','WWW','','',1129560752,0,'a:0:{}','a:0:{}','a:0:{}',0,0);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_ticketsubject`
--

DROP TABLE IF EXISTS `user_ticketsubject`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user_ticketsubject` (
  `usr_id` int(11) NOT NULL default '0',
  `sbj_id` int(11) NOT NULL default '0',
  KEY `usr_id` (`usr_id`,`sbj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user_ticketsubject`
--

LOCK TABLES `user_ticketsubject` WRITE;
/*!40000 ALTER TABLE `user_ticketsubject` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_ticketsubject` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2008-03-24 16:26:40
