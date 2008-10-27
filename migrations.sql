-- SQL MIGRATION SCRIPTS FOR PHENOTYPE DEVELOPMENT
--
--
-- with these ones you can migrate your DB in the case, one developer changed DB structure


-- tpl id changes, relevant after updating from r207 to 213
-- done by michel, 2007/10/30
ALTER TABLE `component_template` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `tpl_id` , `com_id` );
ALTER TABLE `component_template` CHANGE `tpl_id` `tpl_id` INT( 11 ) NOT NULL;


ALTER TABLE `include_template` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `tpl_id` , `inc_id` );
ALTER TABLE `include_template` CHANGE `tpl_id` `tpl_id` INT( 11 ) NOT NULL;


ALTER TABLE `content_template` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `tpl_id` , `con_id` );
ALTER TABLE `content_template` CHANGE `tpl_id` `tpl_id` INT( 11 ) NOT NULL;


ALTER TABLE `extra_template` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `tpl_id` , `ext_id` );
ALTER TABLE `extra_template` CHANGE `tpl_id` `tpl_id` INT( 11 ) NOT NULL;


ALTER TABLE `component_template` CHANGE `tpl_id` `tpl_id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `include_template` CHANGE `tpl_id` `tpl_id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `content_template` CHANGE `tpl_id` `tpl_id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `extra_template` CHANGE `tpl_id` `tpl_id` INT( 11 ) NOT NULL AUTO_INCREMENT;

ALTER TABLE `dataobject` ADD `dao_params` VARCHAR( 255 ) NOT NULL AFTER `dao_bez` ;
ALTER TABLE `dataobject` ADD `dao_type` TINYINT NOT NULL ;
ALTER TABLE `dataobject` DROP `dao_lastbuild_time`;
ALTER TABLE `dataobject` DROP INDEX `dao_bez`;  
ALTER TABLE `dataobject` ADD `dao_clearonedit` TINYINT NOT NULL ;

ALTER TABLE `page` ADD `pag_url1` VARCHAR( 255 ) NOT NULL AFTER `pag_url` ,
ADD `pag_url2` VARCHAR( 255 ) NOT NULL AFTER `pag_url1` ,
ADD `pag_url3` VARCHAR( 255 ) NOT NULL AFTER `pag_url2` ,
ADD `pag_url4` VARCHAR( 255 ) NOT NULL AFTER `pag_url3` ;

ALTER TABLE `pagegroup` ADD `grp_smarturl_schema` TINYINT NOT NULL DEFAULT '1';



ALTER TABLE `page` ADD `pag_props` TEXT NOT NULL AFTER `pag_url4` ;



--- content data key changes with version 2.6 (r271)
ALTER TABLE `content_data` ADD `dat_key6` VARCHAR( 100 ) DEFAULT NULL AFTER `dat_key5` ;
ALTER TABLE `content_data` ADD `dat_ikey6` INT DEFAULT '0' NOT NULL AFTER `dat_ikey5` ;
ALTER TABLE `content_data` ADD INDEX ( `dat_ikey6` ) ;


--- permalinks for content records with version 2.6 (r?)

ALTER TABLE `content_data` ADD `dat_permalink` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink1` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink2` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink3` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink4` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink5` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink6` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink7` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink8` VARCHAR( 255 ) NOT NULL ,
ADD `dat_permalink9` VARCHAR( 255 ) NOT NULL ;

--- only relevant during i8ln work

 CREATE TABLE `srv_sf`.`tokens` (
`token` VARCHAR( 255 ) NOT NULL ,
`section` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM 