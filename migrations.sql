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