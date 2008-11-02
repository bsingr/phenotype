-- phpMyAdmin SQL Dump
-- version 2.11.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 27. Oktober 2008 um 20:02
-- Server Version: 5.0.51
-- PHP-Version: 5.2.6

SET FOREIGN_KEY_CHECKS=0;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `action`
--

DROP TABLE IF EXISTS `action`;
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

DROP TABLE IF EXISTS `component`;
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

INSERT INTO `component` (`com_id`, `com_bez`, `com_description`, `com_rubrik`) VALUES
(1002, 'HTML', '## Baustein 1002 - HTML', 'System'),
(1003, 'Include', '## Baustein 1003 - Include', 'System'),
(1001, 'Richtextabsatz', '## Baustein 1001 - Richtextabsatz\n\nMit diesem Bausteinen können bereits die meisten Anforderungen einer einfachen Website abegedeckt werden. Ein Absatz besteht aus Überschrift, Text, Bild und Link.\n\n', 'Textbausteine');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `componentgroup`
--

DROP TABLE IF EXISTS `componentgroup`;
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

INSERT INTO `componentgroup` (`cog_id`, `cog_bez`, `cog_description`, `cog_pos`) VALUES
(1, 'Default', '## Default-Bausteingruppe', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `component_componentgroup`
--

DROP TABLE IF EXISTS `component_componentgroup`;
CREATE TABLE `component_componentgroup` (
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
CREATE TABLE `component_template` (
  `tpl_id` int(11) NOT NULL auto_increment,
  `com_id` int(11) NOT NULL default '0',
  `tpl_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`tpl_id`,`com_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `component_template`
--

INSERT INTO `component_template` (`tpl_id`, `com_id`, `tpl_bez`) VALUES
(1, 1001, 'TPL_DEFAULT'),
(2, 1001, 'TPL_TOPIMAGE');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content`
--

DROP TABLE IF EXISTS `content`;
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

INSERT INTO `content` (`con_id`, `con_bez`, `con_description`, `con_rubrik`, `con_pos`, `con_props`, `con_anlegen`, `con_bearbeiten`, `con_loeschen`, `con_exportieren`, `con_importieren`, `con_statistik`) VALUES
(1001, 'Expandierende Liste', 'Listenobjekt für selbstexpandierende Auswahlisten in Formularen', 'System', 0, '', 1, 1, 1, 0, 0, 0);

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `content_data`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `content_statistics`
--

DROP TABLE IF EXISTS `content_statistics`;
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

DROP TABLE IF EXISTS `content_template`;
CREATE TABLE `content_template` (
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
CREATE TABLE `dataobject` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=3 ;

--
-- Daten für Tabelle `dataobject`
--

INSERT INTO `dataobject` (`dao_id`, `dao_bez`, `dao_params`, `dao_props`, `dao_date`, `dao_ttl`, `dao_type`, `dao_clearonedit`) VALUES
(1, 'DebugLookUpTable', '', 'a:3:{s:8:"includes";a:0:{}s:10:"components";a:3:{i:1001;s:14:"Richtextabsatz";i:1002;s:4:"HTML";i:1003;s:7:"Include";}s:7:"content";a:1:{i:1001;s:19:"Expandierende Liste";}}', 1225133699, 1225133999, 1, 0),
(2, 'DebugInfo', '#uri#49060e834e978', 'a:1:{s:4:"html";s:27308:"<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\n<html xmlns="http://www.w3.org/1999/xhtml">\n<head>\n<meta http-equiv="Content-Type"\n	content="text/html; charset=iso-8859-1" />\n<title>Phenotype DebugInfo</title>\n<meta name="generator" content="Phenotype CMS" />\n<style type="text/css">\nbody {\n	background-color: #fff;\n	font-family: Verdana, Arial;\n	font-size: 12px;\n}\n\nem {\n	font-family: Verdana, Arial;\n	font-size: 12px;\n	font-style: normal;\n	font-variant: small-caps;\n	border-bottom: 1px solid #CFCFCF;\n	padding: 0px 1px 0px 1px;\n	line-height: 40px;\n	letter-spacing: 4px;\n}\n\n#main {\n	background: #F7F7F7 none repeat scroll 0%;\n	border-bottom: 1px solid #CFCFCF;\n	border-top: 1px solid #CFCFCF;\n	width: 780px;\n	padding: 10px;\n	margin-left: auto;\n	margin-right: auto;\n}\n\n#footer {\n	font-size: 10px;\n	width: 780px;\n	padding: 10px;\n	margin-left: auto;\n	margin-right: auto;\n	text-align: right;\n	height: 50px;\n}\n\n#header {\n	color: #000;\n}\n\n#message {\n	color: #f00;\n	font-weight: bold;\n	background-color: #fff;\n	padding: 7px; #\n	margin: 5px;\n	margin-top: 10px;\n	margin-bottom: 0px;\n}\n\n.request {\n	font-family: Courier;\n	list-style: none;\n	font-size: 11px;\n	background-color: #fff;\n	padding: 7px; #\n	margin: 5px;\n	margin-top: 0px;\n	margin-bottom: 20px;\n	overflow: auto;\n}\n\n.param_key {\n	display: block;\n	width: 125px;\n	float: left;\n}\n\n.param_value {\n	color: #cfcfcf;\n}\n\n.filename {\n	font-size: 9px;\n	color: #cfcfcf;\n	padding: 2px;\n	line-height: 18px;\n}\n\n.exec_context {\n	background-color: #cfcfcf;\n	font-size: 9px;\n	color: #fff;\n	padding: 2px 5px 5px 10px;\n	margin: 0px;\n	line-height: 18px;\n}\n\n.source {\n	font-family: Courier;\n	list-style: none;\n	font-size: 11px;\n	background-color: #fff;\n	padding: 7px; #\n	margin: 5px;\n	margin-top: 0px;\n	margin-bottom: 20px;\n	overflow: auto;\n}\n\n.current {\n	background-color: #CFCFCF;\n}\n\n.query {\n	background-color: #fff;\n	font-family: Courier;\n	color: #cfcfcf;\n	font-weight: normal;\n	font-size: 9px;\n	width: 780px;\n}\n\n.querynr {\n	color: #000;\n	width: 60px;\n}\n\n.querydetails {\n	width: 720px;\n	font-size: 9px;\n	font-family: Verdana, Arial;\n}\n\n.querydetails td,th {\n	border: 1px solid #cfcfcf;\n}\n</style>\n</head>\n<body>\n<div id="main">\n<div id="header"><strong>Phenotype DebugInfo</strong></div>\n	 <em>Request:</em><br />\n<div id="request">\n<ul class="request">\n	<li><span class="param_key">#PHPSESSID</span>: <span\n		class="param_value">2eb636e7cd98802c4d6217d8970dfeb7</span></li>\n			<li><span class="param_key">#id</span>: <span\n		class="param_value">1</span></li>\n		</ul>\n</div>\n		<div id="database"><em>SQL Queries</em><br />\n		<span class="exec_context">Setting MySQL operation mode.</span><span class="filename">[Database.class.phpin line 63]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0001:</td>\n		<td>SET @@session.sql_mode=''NO_FIELD_OPTIONS'';</td>\n	</tr>\n	<tr>\n		<td>0 record(s) in 0.0002		seconds</td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="filename">[Database.class.phpin line 72]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0002:</td>\n		<td>SET NAMES ''latin1''</td>\n	</tr>\n	<tr>\n		<td>0 record(s) in 0.0001		seconds</td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="filename">[Database.class.phpin line 75]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0003:</td>\n		<td>DELETE FROM dataobject WHERE dao_ttl &lt;1225133699 AND dao_ttl &lt;&gt;0</td>\n	</tr>\n	<tr>\n		<td>0 record(s) in 0.0002		seconds</td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Page: resolving by ID 1</span><span class="filename">[PhenotypePage.class.phpin line 293]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0004:</td>\n		<td>SELECT page.*,pagegroup.grp_id, pagegroup.grp_statistic, pagegroup.grp_multilanguage, pagegroup.grp_smarturl_schema FROM page, pagegroup WHERE pag_id = 1 AND page.grp_id=pagegroup.grp_id</td>\n	</tr>\n	<tr>\n		<td>1 record(s) in 0.0005		seconds</td>\n	</tr>\n		<tr>\n		<td><table class="querydetails"><tr><th>id</th><th>select_type</th><th>table</th><th>type</th><th>possible_keys</th><th>key</th><th>key_len</th><th>ref</th><th>rows</th><th>Extra</th></tr><tr><td>1</td><td>SIMPLE</td><td>page</td><td>system</td><td>PRIMARY</td><td></td><td></td><td></td><td>1</td><td></td></tr><tr><td>1</td><td>SIMPLE</td><td>pagegroup</td><td>const</td><td>PRIMARY</td><td>PRIMARY</td><td>4</td><td>const</td><td>1</td><td></td></tr></table></td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Page 1: initialization</span><span class="filename">[PhenotypePage.class.phpin line 371]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0005:</td>\n		<td>SELECT * FROM pageversion WHERE pag_id = 1 AND ver_id=1</td>\n	</tr>\n	<tr>\n		<td>1 record(s) in 0.0002		seconds</td>\n	</tr>\n		<tr>\n		<td><table class="querydetails"><tr><th>id</th><th>select_type</th><th>table</th><th>type</th><th>possible_keys</th><th>key</th><th>key_len</th><th>ref</th><th>rows</th><th>Extra</th></tr><tr><td>1</td><td>SIMPLE</td><td>pageversion</td><td>system</td><td>PRIMARY</td><td></td><td></td><td></td><td>1</td><td></td></tr></table></td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Page 1: gathering layout information</span><span class="filename">[PhenotypePage.class.phpin line 1323]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0006:</td>\n		<td>SELECT * FROM layout_block WHERE lay_id = 1 ORDER BY lay_blocknr</td>\n	</tr>\n	<tr>\n		<td>1 record(s) in 0.0003		seconds</td>\n	</tr>\n		<tr>\n		<td><table class="querydetails"><tr><th>id</th><th>select_type</th><th>table</th><th>type</th><th>possible_keys</th><th>key</th><th>key_len</th><th>ref</th><th>rows</th><th>Extra</th></tr><tr><td>1</td><td>SIMPLE</td><td>layout_block</td><td>system</td><td>PRIMARY</td><td></td><td></td><td></td><td>1</td><td></td></tr></table></td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Page 1: rendering components of $pt_block1</span><span class="filename">[PhenotypePage.class.phpin line 1341]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0007:</td>\n		<td>SELECT * FROM sequence_data WHERE pag_id = 1 AND ver_id = 1 AND dat_blocknr=1 AND dat_editbuffer=0 AND dat_visible = 1 AND lng_id=1 ORDER BY dat_pos</td>\n	</tr>\n	<tr>\n		<td>1 record(s) in 0.0003		seconds</td>\n	</tr>\n		<tr>\n		<td><table class="querydetails"><tr><th>id</th><th>select_type</th><th>table</th><th>type</th><th>possible_keys</th><th>key</th><th>key_len</th><th>ref</th><th>rows</th><th>Extra</th></tr><tr><td>1</td><td>SIMPLE</td><td>sequence_data</td><td>system</td><td>page_select</td><td></td><td></td><td></td><td>1</td><td></td></tr></table></td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Component 1001:</span><span class="filename">[PhenotypeComponent_1001.class.php(83) : eval()''d codein line 9]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0008:</td>\n		<td>SELECT * FROM component_template WHERE com_id = 1001</td>\n	</tr>\n	<tr>\n		<td>2 record(s) in 0.0003		seconds</td>\n	</tr>\n		<tr>\n		<td><table class="querydetails"><tr><th>id</th><th>select_type</th><th>table</th><th>type</th><th>possible_keys</th><th>key</th><th>key_len</th><th>ref</th><th>rows</th><th>Extra</th></tr><tr><td>1</td><td>SIMPLE</td><td>component_template</td><td>ALL</td><td></td><td></td><td></td><td></td><td>2</td><td>Using where</td></tr></table></td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Page 1: rendering includes</span><span class="filename">[PhenotypePage.class.phpin line 1361]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0009:</td>\n		<td>SELECT * FROM layout_include WHERE lay_id = 1 AND inc_id &lt;&gt; 0 ORDER BY lay_includenr</td>\n	</tr>\n	<tr>\n		<td>0 record(s) in 0.0004		seconds</td>\n	</tr>\n		<tr>\n		<td><table class="querydetails"><tr><th>id</th><th>select_type</th><th>table</th><th>type</th><th>possible_keys</th><th>key</th><th>key_len</th><th>ref</th><th>rows</th><th>Extra</th></tr><tr><td>1</td><td>SIMPLE</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Impossible WHERE noticed after reading const tables</td></tr></table></td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Component 1001:</span><span class="filename">[PhenotypePage.class.phpin line 752]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0010:</td>\n		<td>UPDATE `page` SET `pag_nextbuild1` = 1225133698, `pag_printcache1` = 0, `pag_xmlcache1` = 0 WHERE pag_id =1</td>\n	</tr>\n	<tr>\n		<td>1 record(s) in 0.0005		seconds</td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Page 1: storing page generation data</span><span class="filename">[PhenotypePage.class.phpin line 791]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0011:</td>\n		<td>UPDATE `page` SET `pag_lastfetch` = 1225133699, `pag_lastbuild_time` = ''0.0686'', `pag_lastcachenr` = 1 WHERE pag_id =1</td>\n	</tr>\n	<tr>\n		<td>1 record(s) in 0.0003		seconds</td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Page 1: updating view statistics</span><span class="filename">[PhenotypePage.class.phpin line 799]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0012:</td>\n		<td>UPDATE page_statistics SET sta_pageview = sta_pageview+1 WHERE pag_id = 1 AND sta_datum = 20081027</td>\n	</tr>\n	<tr>\n		<td>0 record(s) in 0.0002		seconds</td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="exec_context">Component 1001:</span><span class="filename">[PhenotypePage.class.phpin line 808]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0013:</td>\n		<td>INSERT INTO `page_statistics` (`pag_id`, `sta_datum`, `sta_pageview`) VALUES (1, 20081027, 1)</td>\n	</tr>\n	<tr>\n		<td>1 record(s) in 0.0003		seconds</td>\n	</tr>\n	</table>\n<br />\n<br />\n	<span class="filename">[PhenotypePage.class.phpin line 812]</span><br />\n<table class="query">\n	<tr>\n		<td rowspan="3" class="querynr" valign="top">#0014:</td>\n		<td>SELECT COUNT(*) AS C FROM page_statistics WHERE pag_id=1 AND sta_datum=20081027</td>\n	</tr>\n	<tr>\n		<td>1 record(s) in 0.0002		seconds</td>\n	</tr>\n		<tr>\n		<td><table class="querydetails"><tr><th>id</th><th>select_type</th><th>table</th><th>type</th><th>possible_keys</th><th>key</th><th>key_len</th><th>ref</th><th>rows</th><th>Extra</th></tr><tr><td>1</td><td>SIMPLE</td><td>page_statistics</td><td>system</td><td>sta_day,pag_id</td><td></td><td></td><td></td><td>1</td><td></td></tr></table></td>\n	</tr>\n	</table>\n<br />\n<br />\n	</div>\n	<div id="hints"><em>PHP Hints:</em><br />\n	 <span class="exec_context">Undefined index:  a</span><span\n	class="filename">[%%4B^4BF^4BF70037%%1001_0001_cm.tpl.php]</span>\n<ul class="source">\n	<li ><span>#0004:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;</span><span style="color: #0000BB">a&nbsp;name</span><span style="color: #007700">=</span><span style="color: #DD0000">"t&lt;?php&nbsp;echo&nbsp;$this-&gt;_tpl_vars[''id''];&nbsp;?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0005:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #DD0000">"&gt;&lt;/a&gt;<br /></span>\n</span>\n</code></li>\n		<li  class="current" ><span>#0006:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;</span><span style="color: #0000BB">h1</span><span style="color: #007700">&gt;&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''a''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0007:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''headline''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0008:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''aa''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n	</ul>\n	 <span class="exec_context">Undefined index:  aa</span><span\n	class="filename">[%%4B^4BF^4BF70037%%1001_0001_cm.tpl.php]</span>\n<ul class="source">\n	<li ><span>#0006:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;</span><span style="color: #0000BB">h1</span><span style="color: #007700">&gt;&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''a''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0007:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''headline''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li  class="current" ><span>#0008:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''aa''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0009:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;/</span><span style="color: #0000BB">h1</span><span style="color: #007700">&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0010:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;</span><span style="color: #0000BB">p</span><span style="color: #007700">&gt;&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''a''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n	</ul>\n	 <span class="exec_context">Undefined index:  a</span><span\n	class="filename">[%%4B^4BF^4BF70037%%1001_0001_cm.tpl.php]</span>\n<ul class="source">\n	<li ><span>#0008:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''aa''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0009:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;/</span><span style="color: #0000BB">h1</span><span style="color: #007700">&gt;<br /></span>\n</span>\n</code></li>\n		<li  class="current" ><span>#0010:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;</span><span style="color: #0000BB">p</span><span style="color: #007700">&gt;&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''a''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0011:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''image''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0012:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''aa''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n	</ul>\n	 <span class="exec_context">Undefined index:  image</span><span\n	class="filename">[%%4B^4BF^4BF70037%%1001_0001_cm.tpl.php]</span>\n<ul class="source">\n	<li ><span>#0009:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;/</span><span style="color: #0000BB">h1</span><span style="color: #007700">&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0010:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;</span><span style="color: #0000BB">p</span><span style="color: #007700">&gt;&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''a''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li  class="current" ><span>#0011:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''image''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0012:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''aa''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0013:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''text''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n	</ul>\n	 <span class="exec_context">Undefined index:  aa</span><span\n	class="filename">[%%4B^4BF^4BF70037%%1001_0001_cm.tpl.php]</span>\n<ul class="source">\n	<li ><span>#0010:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;</span><span style="color: #0000BB">p</span><span style="color: #007700">&gt;&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''a''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0011:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''image''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li  class="current" ><span>#0012:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''aa''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0013:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''text''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0014:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''link''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n	</ul>\n	 <span class="exec_context">Undefined index:  link</span><span\n	class="filename">[%%4B^4BF^4BF70037%%1001_0001_cm.tpl.php]</span>\n<ul class="source">\n	<li ><span>#0012:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''aa''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0013:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''text''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li  class="current" ><span>#0014:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;?</span><span style="color: #0000BB">php&nbsp;</span><span style="color: #007700">echo&nbsp;</span><span style="color: #0000BB">$this</span><span style="color: #007700">-&gt;</span><span style="color: #0000BB">_tpl_vars</span><span style="color: #007700">[</span><span style="color: #DD0000">''link''</span><span style="color: #007700">];&nbsp;</span><span style="color: #0000BB">?&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0015:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;/</span><span style="color: #0000BB">p</span><span style="color: #007700">&gt;<br /></span>\n</span>\n</code></li>\n		<li ><span>#0016:\n	</span><code><span style="color: #000000">\n<span style="color: #0000BB"></span><span style="color: #007700">&lt;/</span><span style="color: #0000BB">div</span><span style="color: #007700">&gt;<br /></span>\n</span>\n</code></li>\n	</ul>\n	</div>\n\n	<div id="database"><em>Quick Lookup</em><br />\n<span class="exec_context">components</span>\n<ul class="source">\n	<li><span>#1001: </span>Richtextabsatz</li>\n		<li><span>#1002: </span>HTML</li>\n		<li><span>#1003: </span>Include</li>\n	</ul>\n<span class="exec_context">content object classes</span>\n<ul class="source">\n	<li><span>#1001: </span>Expandierende Liste</li>\n	</ul>\n<span class="exec_context">includes</span>\n<ul class="source">\n</ul>\n</div>\n	</div>\n<div id="footer">27.10.2008 19:54</div>\n</div>\n</body>\n</html>\n	";}', 1225133699, 1225133759, 1, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `extra`
--

DROP TABLE IF EXISTS `extra`;
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

INSERT INTO `extra` (`ext_id`, `ext_bez`, `ext_description`, `ext_rubrik`, `ext_props`) VALUES
(1001, 'Pagewizard', '', 'Development', ''),
(1002, 'Konsole', '', 'Development', 'a:1:{s:5:"color";s:1:"1";}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `extra_template`
--

DROP TABLE IF EXISTS `extra_template`;
CREATE TABLE `extra_template` (
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


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `include_template`
--

DROP TABLE IF EXISTS `include_template`;
CREATE TABLE `include_template` (
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

INSERT INTO `layout` (`lay_id`, `lay_bez`, `lay_description`) VALUES
(1, 'Standard', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layout_block`
--

DROP TABLE IF EXISTS `layout_block`;
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

INSERT INTO `layout_block` (`lay_id`, `lay_blocknr`, `lay_blockbez`, `cog_id`, `lay_context`) VALUES
(1, 1, 'Content', 1, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `layout_include`
--

DROP TABLE IF EXISTS `layout_include`;
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

DROP TABLE IF EXISTS `layout_pagegroup`;
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

DROP TABLE IF EXISTS `media`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=14 ;

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

INSERT INTO `mediagroup` (`grp_id`, `grp_bez`, `grp_description`, `grp_type`) VALUES
(1, 'System', '', 1),
(2, 'Standard', '', 2),
(3, 'Aufgaben', '', 3);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `mediaversion`
--

DROP TABLE IF EXISTS `mediaversion`;
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `mediaversion`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page`
--

DROP TABLE IF EXISTS `page`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `page`
--

INSERT INTO `page` (`pag_id`, `pag_uid`, `pag_id_mimikry`, `ver_id`, `ver_nr`, `grp_id`, `pag_bez`, `pag_titel`, `pag_alttitel`, `pag_comment`, `pag_status`, `pag_id_top`, `pag_pos`, `pag_cache`, `pag_printcache1`, `pag_printcache2`, `pag_printcache3`, `pag_printcache4`, `pag_printcache5`, `pag_printcache6`, `pag_xmlcache1`, `pag_xmlcache2`, `pag_xmlcache3`, `pag_xmlcache4`, `pag_xmlcache5`, `pag_xmlcache6`, `pag_quickfinder`, `pag_searchtext`, `pag_date`, `usr_id`, `usr_id_creator`, `pag_creationdate`, `pag_lastfetch`, `pag_nextbuild1`, `pag_nextbuild2`, `pag_nextbuild3`, `pag_nextbuild4`, `pag_nextbuild5`, `pag_nextbuild6`, `pag_nextversionchange`, `pag_lastbuild_time`, `pag_lastcache_time`, `pag_lastcachenr`, `pag_ver_nr_max`, `pag_url`, `pag_url1`, `pag_url2`, `pag_url3`, `pag_url4`, `pag_url5`, `pag_url6`, `pag_url7`, `pag_url8`, `pag_url9`, `pag_props`, `pag_props_all`, `pag_props_locale`, `pag_fullsearch`, `pag_contenttype`, `pag_multilanguage`, `pag_adminlock`, `pag_redirect`) VALUES
(1, '41d1b7423d6618a0a7574487a3fbb89e', 1, 1, 1, 1, 'Startseite', 'Phenotype 2.6 Startseite', '', '', 1, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', 1206375124, 13, 13, 1206374344, 1225133699, 1225133698, 1225133513, 1225133513, 1225133513, 1225133513, 1225133513, 0, '0.0686', '', 1, 0, 'Phenotype-2.6-Startseite', 'Phenotype-2.6-Startseite', 'Phenotype-2.6-Startseite', 'Phenotype-2.6-Startseite', 'Phenotype-2.6-Startseite', '', '', '', '', '', 'a:1:{s:8:"pag_url1";s:0:"";}', 'a:0:{}', 'a:0:{}', 'Phenotype 2.6 Startseite|Phenotype 2.6 Startseite|Phenotype 2.6 Startseite||||Phenotype 2.6|<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\n<a href="_phenotype/admin/">Zum Redaktionssystem</a></p>\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href="http://www.phenotype.de">phenotype.de</a> und im <a href="http://phenotype.de/wiki/">Phenotype-Wiki</a></p>|', 1, 0, 0, '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pagegroup`
--

DROP TABLE IF EXISTS `pagegroup`;
CREATE TABLE `pagegroup` (
  `grp_id` int(11) NOT NULL auto_increment,
  `grp_bez` varchar(100) collate latin1_general_ci NOT NULL default '',
  `grp_description` text collate latin1_general_ci NOT NULL,
  `grp_statistic` tinyint(4) NOT NULL default '1',
  `grp_multilanguage` tinyint(4) NOT NULL default '0',
  `grp_smarturl_schema` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`grp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=4 ;

--
-- Daten für Tabelle `pagegroup`
--

INSERT INTO `pagegroup` (`grp_id`, `grp_bez`, `grp_description`, `grp_statistic`, `grp_multilanguage`, `grp_smarturl_schema`) VALUES
(1, 'Struktur', '', 1, 0, 0),
(2, 'Sonderseiten', '', 1, 0, 0),
(3, 'Dynamisch', '', 1, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pageversion`
--

DROP TABLE IF EXISTS `pageversion`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `pageversion`
--

INSERT INTO `pageversion` (`ver_id`, `ver_nr`, `pag_id`, `lay_id`, `ver_bez`, `inc_id1`, `inc_id2`, `pag_exec_script`, `pag_fullsearch`) VALUES
(1, 1, 1, 1, 'Version 1', 0, 0, 0, 'Phenotype 2.6 Startseite|Phenotype 2.6 Startseite|Phenotype 2.6 Startseite||||Phenotype 2.6|<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\n<a href="_phenotype/admin/">Zum Redaktionssystem</a></p>\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href="http://www.phenotype.de">phenotype.de</a> und im <a href="http://phenotype.de/wiki/">Phenotype-Wiki</a></p>|');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pageversion_autoactivate`
--

DROP TABLE IF EXISTS `pageversion_autoactivate`;
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
-- Tabellenstruktur für Tabelle `page_language`
--

DROP TABLE IF EXISTS `page_language`;
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

INSERT INTO `page_language` (`pag_id`, `lng_id`, `pag_titel`, `pag_nextbuild1`, `pag_nextbuild2`, `pag_nextbuild3`, `pag_nextbuild4`, `pag_nextbuild5`, `pag_nextbuild6`, `pag_printcache1`, `pag_printcache2`, `pag_printcache3`, `pag_printcache4`, `pag_printcache5`, `pag_printcache6`, `pag_xmlcache1`, `pag_xmlcache2`, `pag_xmlcache3`, `pag_xmlcache4`, `pag_xmlcache5`, `pag_xmlcache6`) VALUES
(1, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `page_statistics`
--

DROP TABLE IF EXISTS `page_statistics`;
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

INSERT INTO `page_statistics` (`pag_id`, `sta_datum`, `sta_pageview`) VALUES
(1, 20081027, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `role`
--

DROP TABLE IF EXISTS `role`;
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

INSERT INTO `role` (`rol_id`, `rol_description`, `rol_bez`, `rol_rights`) VALUES
(1, '', 'Admin', 'a:20:{s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:10:"elm_extras";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}'),
(2, '', 'Redakteur', 'a:13:{s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:17:"access_mediagrp_2";i:1;s:10:"elm_extras";i:0;s:5:"sbj_1";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:8:"elm_page";i:1;}');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sequence_data`
--

DROP TABLE IF EXISTS `sequence_data`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `sequence_data`
--

INSERT INTO `sequence_data` (`dat_id`, `pag_id`, `ver_id`, `lng_id`, `dat_id_content`, `dat_editbuffer`, `dat_visible`, `dat_blocknr`, `dat_pos`, `com_id`, `dat_comdata`, `dat_fullsearch`, `usr_id`) VALUES
(1, 1, 1, 1, 0, 0, 1, 1, 1, 1001, 'a:8:{s:8:"headline";s:13:"Phenotype 2.6";s:4:"text";s:409:"<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\r\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\r\n<a href="_phenotype/admin/">Zum Redaktionssystem</a></p>\r\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href="http://www.phenotype.de">phenotype.de</a> und im <a href="http://phenotype.de/wiki/">Phenotype-Wiki</a></p>";s:6:"img_id";s:1:"0";s:3:"alt";s:0:"";s:15:"bildausrichtung";s:5:"links";s:7:"linkbez";s:0:"";s:7:"linkurl";s:0:"";s:10:"linktarget";s:5:"_self";}', 'Phenotype 2.6|<p>Willkommen bei Phenotype - Ihr System wurde installiert.</p>\n<p>Loggen Sie sich im Redaktionssystem ein um Ihr System zu verwenden. Benutzername: starter / Passwort: deleteme<br />\n<a href="_phenotype/admin/">Zum Redaktionssystem</a></p>\n<p>Weitere Informationen &uuml;ber Phenotype auf <a href="http://www.phenotype.de">phenotype.de</a> und im <a href="http://phenotype.de/wiki/">Phenotype-Wiki</a></p>', 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `snapshot`
--

DROP TABLE IF EXISTS `snapshot`;
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `snapshot`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticket`
--

DROP TABLE IF EXISTS `ticket`;
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

--
-- Daten für Tabelle `ticket`
--


-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ticketaction`
--

DROP TABLE IF EXISTS `ticketaction`;
CREATE TABLE `ticketaction` (
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

DROP TABLE IF EXISTS `ticketpin`;
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

DROP TABLE IF EXISTS `ticketrequest`;
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

DROP TABLE IF EXISTS `ticketsubject`;
CREATE TABLE `ticketsubject` (
  `sbj_id` int(11) NOT NULL auto_increment,
  `sbj_bez` varchar(150) collate latin1_general_ci NOT NULL default '',
  `sbj_description` text collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`sbj_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `ticketsubject`
--

INSERT INTO `ticketsubject` (`sbj_id`, `sbj_bez`, `sbj_description`) VALUES
(1, 'Bugs', 'Bugtracker während der Entwicklung');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `tokens`
--

DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `token` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Daten für Tabelle `tokens`
--

INSERT INTO `tokens` (`token`, `section`) VALUES
('Folder gets deleted', 'Config'),
('File gets deleted', 'Config');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

DROP TABLE IF EXISTS `user`;
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0 AUTO_INCREMENT=14 ;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`usr_id`, `usr_status`, `usr_login`, `usr_pass`, `usr_vorname`, `usr_nachname`, `usr_email`, `usr_createdate`, `usr_lastlogin`, `usr_rights`, `usr_allrights`, `usr_preferences`, `usr_su`, `med_id_thumb`) VALUES
(13, 1, 'starter', 'ph1c2fSo4Tg/2', 'Starter', '', '', 1128621734, 1161455573, 'a:4:{s:13:"elm_redaktion";i:0;s:11:"elm_content";i:0;s:10:"elm_extras";i:0;s:5:"rol_1";i:1;}', 'a:21:{s:13:"elm_redaktion";i:1;s:11:"elm_content";i:0;s:10:"elm_extras";i:1;s:5:"rol_1";i:1;s:14:"elm_pageconfig";i:1;s:17:"elm_pagestatistic";i:1;s:13:"elm_mediabase";i:1;s:11:"elm_analyse";i:1;s:9:"elm_admin";i:1;s:17:"access_mediagrp_1";i:1;s:17:"access_mediagrp_2";i:1;s:17:"access_mediagrp_3";i:1;s:8:"ext_1001";i:1;s:8:"ext_1002";i:1;s:12:"access_grp_1";i:1;s:12:"pag_id_grp_1";s:1:"0";s:12:"access_grp_2";i:1;s:12:"pag_id_grp_2";s:1:"0";s:12:"access_grp_3";i:1;s:12:"pag_id_grp_3";s:1:"0";s:8:"elm_page";i:1;}', 'a:0:{}', 1, 0),
(1, 0, '', '', 'System', '', '', 1128535703, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0),
(2, 0, '', '', 'Importer', '', '', 1128535744, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0),
(3, 0, '', '', 'WWW', '', '', 1129560752, 0, 'a:0:{}', 'a:0:{}', 'a:0:{}', 0, 0);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_ticketsubject`
--

DROP TABLE IF EXISTS `user_ticketsubject`;
CREATE TABLE `user_ticketsubject` (
  `usr_id` int(11) NOT NULL default '0',
  `sbj_id` int(11) NOT NULL default '0',
  KEY `usr_id` (`usr_id`,`sbj_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `user_ticketsubject`
--


SET FOREIGN_KEY_CHECKS=1;
