<?php
// -------------------------------------------------------
// Phenotype Content Application Framework
// -------------------------------------------------------
// Copyright (c) 2003-2006 Nils Hagemann, Paul Sellinger,
// Peter Sellinger.
// -------------------------------------------------------
// Thanks for your support: Markus Griesbach, Michael
// Krämer, Annemarie Komor, Jochen Rieger, Alexander
// Wehrum, Martin Ochs.
// -------------------------------------------------------
// Kontakt:
// www.phenotype.de - offical product homepage
// www.phenotype-cms.de - documentation & support
// www.sellinger-server.de - inventors of phenotype
// -------------------------------------------------------
// Version ##!PT_VERSION!## vom ##!BUILD_DATE!##
// -------------------------------------------------------

/**
 * @package phenotype
 * @subpackage system
 *
 */

class PhenotypeLocaleManagerStandard
{
	// additional locales (not en)
	public $_locales = Array("de","xx");

	public function __construct()
	{
		$_token = Array();



		// Admin

		$_token["Admin"][]="* all pages *";
		$_token["Admin"][]="Add new action";
		$_token["Admin"][]="Add new mediagroup";
		$_token["Admin"][]="Add new pagegroup";
		$_token["Admin"][]="Add new role";
		$_token["Admin"][]="Admin rights";
		$_token["Admin"][]="Assign Image";
		$_token["Admin"][]="Build Content Index";
		$_token["Admin"][]="Cleanup Mediabase";
		$_token["Admin"][]="Clear page cache";
		$_token["Admin"][]="Clear page cache?";
		$_token["Admin"][]="Create";
		$_token["Admin"][]="Create new task realm";
		$_token["Admin"][]="Delete Action";
		$_token["Admin"][]="Delete realm";
		$_token["Admin"][]="Delete records";
		$_token["Admin"][]="Delete records irrevocably";
		$_token["Admin"][]="Edit Pagegroup";
		$_token["Admin"][]="Edit mediagroup";
		$_token["Admin"][]="Edit realm";
		$_token["Admin"][]="Editor / Content";
		$_token["Admin"][]="Editor / Mediabase";
		$_token["Admin"][]="Editor / Pages - CANNOT insert/remove/change components";
		$_token["Admin"][]="Editor / Pages - Create and configure pages";
		$_token["Admin"][]="Editor / Pages - Stats";
		$_token["Admin"][]="Elementary rights";
		$_token["Admin"][]="Execute";
		$_token["Admin"][]="Following content object records are deleted:";
		$_token["Admin"][]="Following media objects will be deleted:";
		$_token["Admin"][]="Following pages will be rendered upon next page impression:";
		$_token["Admin"][]="Full path (no language tokens)";
		$_token["Admin"][]="Full path (possibly language tokens)";
		$_token["Admin"][]="Multi language";
		$_token["Admin"][]="New Page Group";
		$_token["Admin"][]="New Role";
		$_token["Admin"][]="New action";
		$_token["Admin"][]="New layout";
		$_token["Admin"][]="New mediagroup";
		$_token["Admin"][]="Next run: immediately";
		$_token["Admin"][]="Overview Actions";
		$_token["Admin"][]="Overview Pagegroups";
		$_token["Admin"][]="Overview Roles";
		$_token["Admin"][]="Overview mediagroups";
		$_token["Admin"][]="Overview task realms";
		$_token["Admin"][]="Page titles (no language tokens)";
		$_token["Admin"][]="Page titles (possibly language tokens)";
		$_token["Admin"][]="Really delete this action?";
		$_token["Admin"][]="Really delete this folders with all images and documents irrevocably?";
		$_token["Admin"][]="Really delete this mediagroup?";
		$_token["Admin"][]="Really delete this pagegroup?";
		$_token["Admin"][]="Really delete this role?";
		$_token["Admin"][]="Really delete this task realm?";
		$_token["Admin"][]="Really reset this action?";
		$_token["Admin"][]="Rebuild index?";
		$_token["Admin"][]="Remove Image";
		$_token["Admin"][]="Reset";
		$_token["Admin"][]="State";
		$_token["Admin"][]="Stats";
		$_token["Admin"][]="Status: offline";
		$_token["Admin"][]="Status: online";
		$_token["Admin"][]="Sub path (no language tokens)";
		$_token["Admin"][]="Sub path (possibly language tokens)";
		$_token["Admin"][]="Task realm";
		$_token["Admin"][]="The index of following content object records is regenerated:";
		$_token["Admin"][]="This Layout is not used in any page.";
		$_token["Admin"][]="Versioning";
		$_token["Admin"][]="View Action";
		$_token["Admin"][]="View Pagegroup";
		$_token["Admin"][]="View mediagroup";
		$_token["Admin"][]="archiving";
		$_token["Admin"][]="count page impressions";
		$_token["Admin"][]="index.php (no language tokens)";
		$_token["Admin"][]="index.php (possibly language tokens)";
		$_token["Admin"][]="manual";
		$_token["Admin"][]="none";
		$_token["Admin"][]="smartURL-Schema";
		$_token["Admin"][]="users";
		$_token["Admin"][]="yes";


		// Admin_Users

		$_token["Admin_Users"][]="Action";
		$_token["Admin_Users"][]="Admin";
		$_token["Admin_Users"][]="Config";
		$_token["Admin_Users"][]="Create new user";
		$_token["Admin_Users"][]="Email";
		$_token["Admin_Users"][]="ID";
		$_token["Admin_Users"][]="Last login on";
		$_token["Admin_Users"][]="Lastname";
		$_token["Admin_Users"][]="Name";
		$_token["Admin_Users"][]="Never logged in.";
		$_token["Admin_Users"][]="Password (for change enter 2 times)";
		$_token["Admin_Users"][]="Photo";
		$_token["Admin_Users"][]="Preferences";
		$_token["Admin_Users"][]="Really delete this user?";
		$_token["Admin_Users"][]="Rights";
		$_token["Admin_Users"][]="Roles";
		$_token["Admin_Users"][]="State";
		$_token["Admin_Users"][]="Superuser";
		$_token["Admin_Users"][]="Surname";
		$_token["Admin_Users"][]="Ticket-Preferences";
		$_token["Admin_Users"][]="User";
		$_token["Admin_Users"][]="Username";
		$_token["Admin_Users"][]="contentobjects";
		$_token["Admin_Users"][]="created at";
		$_token["Admin_Users"][]="edit user";
		$_token["Admin_Users"][]="extras";
		$_token["Admin_Users"][]="headline_login";
		$_token["Admin_Users"][]="headline_name";
		$_token["Admin_Users"][]="headline_users";
		$_token["Admin_Users"][]="mediagroups";
		$_token["Admin_Users"][]="msg_password_change_failure";
		$_token["Admin_Users"][]="msg_password_change_success";
		$_token["Admin_Users"][]="pagegroups ";
		$_token["Admin_Users"][]="task subjects";
		$_token["Admin_Users"][]="value_allpages";
		$_token["Admin_Users"][]="value_newuser_lastname";
		$_token["Admin_Users"][]="value_newuser_surname";
		$_token["Admin_Users"][]="view user";


		// Config

		$_token["Config"][]="Add Template";
		$_token["Config"][]="Add inlcude";
		$_token["Config"][]="Add new component group";
		$_token["Config"][]="Application";
		$_token["Config"][]="Backend classes";
		$_token["Config"][]="Category";
		$_token["Config"][]="Cleanup";
		$_token["Config"][]="Component group";
		$_token["Config"][]="Componentgroup";
		$_token["Config"][]="Componentgroups";
		$_token["Config"][]="Components";
		$_token["Config"][]="Configuration";
		$_token["Config"][]="Configure Extras";
		$_token["Config"][]="Configure Layout";
		$_token["Config"][]="Configure componentgroups";
		$_token["Config"][]="Configure components";
		$_token["Config"][]="Configure content object classes";
		$_token["Config"][]="Configure content objects classes";
		$_token["Config"][]="Configure includes";
		$_token["Config"][]="Content object class";
		$_token["Config"][]="Create";
		$_token["Config"][]="Create new Extra";
		$_token["Config"][]="Create new component";
		$_token["Config"][]="Create new content object class";
		$_token["Config"][]="Create new include";
		$_token["Config"][]="Create new layout";
		$_token["Config"][]="Data";
		$_token["Config"][]="Default view";
		$_token["Config"][]="Delete Template";
		$_token["Config"][]="Delete component";
		$_token["Config"][]="Delete component group";
		$_token["Config"][]="Delete template";
		$_token["Config"][]="Edit component";
		$_token["Config"][]="Edit content object";
		$_token["Config"][]="Edit page scripts";
		$_token["Config"][]="Export Package";
		$_token["Config"][]="Export package";
		$_token["Config"][]="File gets deleted";
		$_token["Config"][]="Files within webroot";
		$_token["Config"][]="Folder gets deleted";
		$_token["Config"][]="Folders";
		$_token["Config"][]="Include component";
		$_token["Config"][]="Install";
		$_token["Config"][]="Install Package";
		$_token["Config"][]="Journal";
		$_token["Config"][]="Language maps";
		$_token["Config"][]="Layouts";
		$_token["Config"][]="Media objects";
		$_token["Config"][]="Mediabase";
		$_token["Config"][]="Meta";
		$_token["Config"][]="Method";
		$_token["Config"][]="New category";
		$_token["Config"][]="New component";
		$_token["Config"][]="New componentgroup";
		$_token["Config"][]="New content object class";
		$_token["Config"][]="New extra";
		$_token["Config"][]="New include";
		$_token["Config"][]="New layout";
		$_token["Config"][]="Package";
		$_token["Config"][]="Packages";
		$_token["Config"][]="Page";
		$_token["Config"][]="Page scripts";
		$_token["Config"][]="Pagescripts";
		$_token["Config"][]="Placeholder";
		$_token["Config"][]="Print view";
		$_token["Config"][]="Really delete this Extra?";
		$_token["Config"][]="Really delete this component group?";
		$_token["Config"][]="Really delete this component?";
		$_token["Config"][]="Really delete this content object?";
		$_token["Config"][]="Really delete this include?";
		$_token["Config"][]="Really delete this layout?";
		$_token["Config"][]="Really install this package?";
		$_token["Config"][]="Records";
		$_token["Config"][]="Remove Template";
		$_token["Config"][]="Remove action %1";
		$_token["Config"][]="Remove component %1";
		$_token["Config"][]="Remove component group %1";
		$_token["Config"][]="Remove contentobject %1";
		$_token["Config"][]="Remove extra %1";
		$_token["Config"][]="Remove include";
		$_token["Config"][]="Remove include %1";
		$_token["Config"][]="Remove layout %1";
		$_token["Config"][]="Remove placeholder";
		$_token["Config"][]="Remove template";
		$_token["Config"][]="Script";
		$_token["Config"][]="Select Package";
		$_token["Config"][]="Select/Deselect all";
		$_token["Config"][]="Smarty-Access";
		$_token["Config"][]="Storage folder";
		$_token["Config"][]="Structure";
		$_token["Config"][]="Structures";
		$_token["Config"][]="Templates";
		$_token["Config"][]="This Layout is not used in any page.";
		$_token["Config"][]="This layout is used in %1 pages";
		$_token["Config"][]="Tools";
		$_token["Config"][]="Usage";
		$_token["Config"][]="User & Roles";
		$_token["Config"][]="Utilization";
		$_token["Config"][]="View component";
		$_token["Config"][]="append";
		$_token["Config"][]="can be selected with the include component";
		$_token["Config"][]="can be used in a page";
		$_token["Config"][]="can be used in layouts";
		$_token["Config"][]="clean _host.config.inc.php";
		$_token["Config"][]="clean mediabase totally";
		$_token["Config"][]="delete all actions";
		$_token["Config"][]="delete all cache files";
		$_token["Config"][]="delete all component groups";
		$_token["Config"][]="delete all components";
		$_token["Config"][]="delete all content object classes";
		$_token["Config"][]="delete all extras";
		$_token["Config"][]="delete all files in storage folder";
		$_token["Config"][]="delete all includes";
		$_token["Config"][]="delete all layouts";
		$_token["Config"][]="delete all pages";
		$_token["Config"][]="delete all roles";
		$_token["Config"][]="delete all ticket subjects";
		$_token["Config"][]="delete all tickets";
		$_token["Config"][]="delete all users (except the one currently logged in)";
		$_token["Config"][]="delete alle content object records";
		$_token["Config"][]="delete alle page groups";
		$_token["Config"][]="delete application specific backend classes";
		$_token["Config"][]="delete content cache files";
		$_token["Config"][]="delete dataobjects";
		$_token["Config"][]="delete snapshots";
		$_token["Config"][]="install data";
		$_token["Config"][]="media objects";
		$_token["Config"][]="msg_install_structure_files";
		$_token["Config"][]="msg_use_ajax_installer";
		$_token["Config"][]="never";
		$_token["Config"][]="overwrite";
		$_token["Config"][]="remove temporary files and reset directory structure";
		$_token["Config"][]="remove unknown files in webroot folder";
		$_token["Config"][]="reset _application.inc.php and preferences.xml";
		$_token["Config"][]="same like page";
		$_token["Config"][]="selective";
		$_token["Config"][]="use AJAX exporter";


		// Editor

		$_token["Editor"][]="Action";
		$_token["Editor"][]="Add Link";
		$_token["Editor"][]="Add first page in group";
		$_token["Editor"][]="Add new record";
		$_token["Editor"][]="Alignment";
		$_token["Editor"][]="All";
		$_token["Editor"][]="Alternate";
		$_token["Editor"][]="Assign Document";
		$_token["Editor"][]="Assign Document/Image";
		$_token["Editor"][]="Assign Image";
		$_token["Editor"][]="Change Document";
		$_token["Editor"][]="Change Document/Image";
		$_token["Editor"][]="Change Image";
		$_token["Editor"][]="Content";
		$_token["Editor"][]="Copy page";
		$_token["Editor"][]="Copy record";
		$_token["Editor"][]="Create new task";
		$_token["Editor"][]="Current";
		$_token["Editor"][]="Date";
		$_token["Editor"][]="Delete record";
		$_token["Editor"][]="Display debug skin";
		$_token["Editor"][]="Document No.";
		$_token["Editor"][]="Document assigned";
		$_token["Editor"][]="Document/Image assigned";
		$_token["Editor"][]="Edit page";
		$_token["Editor"][]="Edit record";
		$_token["Editor"][]="Fulltext";
		$_token["Editor"][]="Help";
		$_token["Editor"][]="ID";
		$_token["Editor"][]="Insert";
		$_token["Editor"][]="Insert component";
		$_token["Editor"][]="Link selection";
		$_token["Editor"][]="Link text";
		$_token["Editor"][]="Link type";
		$_token["Editor"][]="Linkname";
		$_token["Editor"][]="Media";
		$_token["Editor"][]="Mediabase";
		$_token["Editor"][]="Name";
		$_token["Editor"][]="New version";
		$_token["Editor"][]="Next";
		$_token["Editor"][]="Page";
		$_token["Editor"][]="Pages";
		$_token["Editor"][]="Preview";
		$_token["Editor"][]="Put into / Take out of lightbox";
		$_token["Editor"][]="Reallocate page";
		$_token["Editor"][]="Really delete record?";
		$_token["Editor"][]="Really delete this record?";
		$_token["Editor"][]="Remove Document";
		$_token["Editor"][]="Remove Document/Image";
		$_token["Editor"][]="Remove Image";
		$_token["Editor"][]="Reset Link";
		$_token["Editor"][]="Rows";
		$_token["Editor"][]="Search Content";
		$_token["Editor"][]="Search Pages";
		$_token["Editor"][]="Select Link";
		$_token["Editor"][]="Select image";
		$_token["Editor"][]="Select link";
		$_token["Editor"][]="Send";
		$_token["Editor"][]="Source";
		$_token["Editor"][]="State";
		$_token["Editor"][]="Status offline";
		$_token["Editor"][]="Status: offline";
		$_token["Editor"][]="Status: online";
		$_token["Editor"][]="Thumb";
		$_token["Editor"][]="Type";
		$_token["Editor"][]="URL";
		$_token["Editor"][]="User";
		$_token["Editor"][]="Version";
		$_token["Editor"][]="Versions";
		$_token["Editor"][]="Where to locate the new page within the page tree?";
		$_token["Editor"][]="back";
		$_token["Editor"][]="for";
		$_token["Editor"][]="for ID";
		$_token["Editor"][]="fulltext";
		$_token["Editor"][]="in titles";
		$_token["Editor"][]="msg_align_center";
		$_token["Editor"][]="msg_align_left";
		$_token["Editor"][]="msg_align_right";
		$_token["Editor"][]="msg_creation_date_anonymous";
		$_token["Editor"][]="msg_creation_date_by_user";
		$_token["Editor"][]="msg_last_change_anonymous";
		$_token["Editor"][]="msg_last_change_by_user";
		$_token["Editor"][]="msg_recordchange_0";
		$_token["Editor"][]="msg_recordchange_1";
		$_token["Editor"][]="msg_recordchange_n";
		$_token["Editor"][]="msg_selected_image_not_found";
		$_token["Editor"][]="new window";
		$_token["Editor"][]="next";
		$_token["Editor"][]="prev";
		$_token["Editor"][]="same window";
		$_token["Editor"][]="select";
		$_token["Editor"][]="send";
		$_token["Editor"][]="tasks";


		// Editor_Content

		$_token["Editor_Content"][]="Really delete this record?";
		$_token["Editor_Content"][]="Record copied.";
		$_token["Editor_Content"][]="Record deleted.";
		$_token["Editor_Content"][]="Record not found.";


		// Editor_Media

		$_token["Editor_Media"][]="Action";
		$_token["Editor_Media"][]="All Files";
		$_token["Editor_Media"][]="Alternate";
		$_token["Editor_Media"][]="Create new version";
		$_token["Editor_Media"][]="Create new versions";
		$_token["Editor_Media"][]="Document upload succesful";
		$_token["Editor_Media"][]="Drag & Drop - Upload";
		$_token["Editor_Media"][]="Files";
		$_token["Editor_Media"][]="First version added";
		$_token["Editor_Media"][]="Folder";
		$_token["Editor_Media"][]="Group/Folder";
		$_token["Editor_Media"][]="Image (%1x%2)";
		$_token["Editor_Media"][]="Image / Document";
		$_token["Editor_Media"][]="Import";
		$_token["Editor_Media"][]="Import files";
		$_token["Editor_Media"][]="Lightbox";
		$_token["Editor_Media"][]="Media selection";
		$_token["Editor_Media"][]="Mediagroup";
		$_token["Editor_Media"][]="Mimetype";
		$_token["Editor_Media"][]="New Files";
		$_token["Editor_Media"][]="New version";
		$_token["Editor_Media"][]="Nothing to import.";
		$_token["Editor_Media"][]="Object %1 edited.";
		$_token["Editor_Media"][]="Object deleted.";
		$_token["Editor_Media"][]="Original";
		$_token["Editor_Media"][]="Overwrite version";
		$_token["Editor_Media"][]="Really delete this document?";
		$_token["Editor_Media"][]="Really delete this image?";
		$_token["Editor_Media"][]="Really delete this version?";
		$_token["Editor_Media"][]="Search Media";
		$_token["Editor_Media"][]="Select image";
		$_token["Editor_Media"][]="Shared properties";
		$_token["Editor_Media"][]="Sharpen";
		$_token["Editor_Media"][]="Size";
		$_token["Editor_Media"][]="Upload";
		$_token["Editor_Media"][]="Upload failed!";
		$_token["Editor_Media"][]="Upload files";
		$_token["Editor_Media"][]="Upload mediaobject";
		$_token["Editor_Media"][]="Version deleted.";
		$_token["Editor_Media"][]="add objects to lightbox";
		$_token["Editor_Media"][]="fixed ratio";
		$_token["Editor_Media"][]="fixed selection";
		$_token["Editor_Media"][]="fixed size";
		$_token["Editor_Media"][]="free";
		$_token["Editor_Media"][]="handle images like documents";
		$_token["Editor_Media"][]="illegal versionname";
		$_token["Editor_Media"][]="light";
		$_token["Editor_Media"][]="msg_error_imageversionupload";
		$_token["Editor_Media"][]="msg_error_overwrite_original";
		$_token["Editor_Media"][]="msg_error_readimage";
		$_token["Editor_Media"][]="msg_error_unknown_mimetype";
		$_token["Editor_Media"][]="msg_error_versionnamechange";
		$_token["Editor_Media"][]="msg_object_not_saved";
		$_token["Editor_Media"][]="none";
		$_token["Editor_Media"][]="normal";
		$_token["Editor_Media"][]="objects/page";
		$_token["Editor_Media"][]="strong";
		$_token["Editor_Media"][]="target frame";
		$_token["Editor_Media"][]="treat images like documents";
		$_token["Editor_Media"][]="very strong";


		// Editor_Pages

		$_token["Editor_Pages"][]="Access time";
		$_token["Editor_Pages"][]="Activate";
		$_token["Editor_Pages"][]="Add New Version";
		$_token["Editor_Pages"][]="Add Version Change";
		$_token["Editor_Pages"][]="Add new page";
		$_token["Editor_Pages"][]="After current page, same level";
		$_token["Editor_Pages"][]="Alternate Title";
		$_token["Editor_Pages"][]="Automatic version change";
		$_token["Editor_Pages"][]="Average value";
		$_token["Editor_Pages"][]="Before current page, same level";
		$_token["Editor_Pages"][]="Build %1 sec (Cache %2)";
		$_token["Editor_Pages"][]="Cache";
		$_token["Editor_Pages"][]="Cache %1 sec";
		$_token["Editor_Pages"][]="Cache-State";
		$_token["Editor_Pages"][]="Chart";
		$_token["Editor_Pages"][]="Comment";
		$_token["Editor_Pages"][]="Date";
		$_token["Editor_Pages"][]="Day view";
		$_token["Editor_Pages"][]="Delete Component";
		$_token["Editor_Pages"][]="Delete component";
		$_token["Editor_Pages"][]="HTTP-Header";
		$_token["Editor_Pages"][]="How to name the new page?";
		$_token["Editor_Pages"][]="Includes";
		$_token["Editor_Pages"][]="Includes%n(Pre/Post)";
		$_token["Editor_Pages"][]="Invisible";
		$_token["Editor_Pages"][]="Last Page Impression";
		$_token["Editor_Pages"][]="List of automatic version changes";
		$_token["Editor_Pages"][]="Listing";
		$_token["Editor_Pages"][]="Meta";
		$_token["Editor_Pages"][]="Mimikry";
		$_token["Editor_Pages"][]="Monitor";
		$_token["Editor_Pages"][]="Month view";
		$_token["Editor_Pages"][]="Navigation Behaviour";
		$_token["Editor_Pages"][]="New Page";
		$_token["Editor_Pages"][]="New version - Copy of";
		$_token["Editor_Pages"][]="No Include";
		$_token["Editor_Pages"][]="No Template";
		$_token["Editor_Pages"][]="No.";
		$_token["Editor_Pages"][]="PIs";
		$_token["Editor_Pages"][]="Page is regenerated on next call.";
		$_token["Editor_Pages"][]="Page name";
		$_token["Editor_Pages"][]="Page valid until";
		$_token["Editor_Pages"][]="Pagscript";
		$_token["Editor_Pages"][]="Really delete this page?";
		$_token["Editor_Pages"][]="Script";
		$_token["Editor_Pages"][]="Search";
		$_token["Editor_Pages"][]="Search terms";
		$_token["Editor_Pages"][]="Select page";
		$_token["Editor_Pages"][]="Standard";
		$_token["Editor_Pages"][]="Stats";
		$_token["Editor_Pages"][]="Stats: (Day/Month/Total)";
		$_token["Editor_Pages"][]="Switching time";
		$_token["Editor_Pages"][]="This page does not contain a script.";
		$_token["Editor_Pages"][]="Time";
		$_token["Editor_Pages"][]="Title%n(multilanguage)";
		$_token["Editor_Pages"][]="Today";
		$_token["Editor_Pages"][]="Trend";
		$_token["Editor_Pages"][]="UID";
		$_token["Editor_Pages"][]="URLs";
		$_token["Editor_Pages"][]="URLs%n(multilanguage)";
		$_token["Editor_Pages"][]="Under current page, lower level";
		$_token["Editor_Pages"][]="Vars";
		$_token["Editor_Pages"][]="Version name";
		$_token["Editor_Pages"][]="Versions";
		$_token["Editor_Pages"][]="Where to locate the new page within the page tree?";
		$_token["Editor_Pages"][]="Which template should be used?";
		$_token["Editor_Pages"][]="copy page";
		$_token["Editor_Pages"][]="marks the average value";
		$_token["Editor_Pages"][]="move page";
		$_token["Editor_Pages"][]="msg_editquickfinder1";
		$_token["Editor_Pages"][]="msg_editquickfinder2";
		$_token["Editor_Pages"][]="msg_firstedit_other_language";
		$_token["Editor_Pages"][]="msg_move_component_downward";
		$_token["Editor_Pages"][]="msg_move_component_upward";
		$_token["Editor_Pages"][]="msg_pagechange_0";
		$_token["Editor_Pages"][]="msg_pagechange_1";
		$_token["Editor_Pages"][]="msg_pagechange_n";
		$_token["Editor_Pages"][]="msg_pagescript";
		$_token["Editor_Pages"][]="never before";
		$_token["Editor_Pages"][]="smartURL (optional)";
		$_token["Editor_Pages"][]="visible";


		// Info

		$_token["Info"][]="headline_copyright";
		$_token["Info"][]="headline_info";
		$_token["Info"][]="headline_systemreqs";
		$_token["Info"][]="headline_tools";
		$_token["Info"][]="headline_version";
		$_token["Info"][]="msg_copyright";
		$_token["Info"][]="msg_radinks";
		$_token["Info"][]="msg_version";


		// Phenotype

		$_token["Phenotype"][]="Action";
		$_token["Phenotype"][]="Actions";
		$_token["Phenotype"][]="Admin";
		$_token["Phenotype"][]="Alert";
		$_token["Phenotype"][]="Analyze";
		$_token["Phenotype"][]="April";
		$_token["Phenotype"][]="Attention";
		$_token["Phenotype"][]="August";
		$_token["Phenotype"][]="Cache";
		$_token["Phenotype"][]="Changes saved";
		$_token["Phenotype"][]="Changes saved.";
		$_token["Phenotype"][]="Comment";
		$_token["Phenotype"][]="Config";
		$_token["Phenotype"][]="Content";
		$_token["Phenotype"][]="Content objects";
		$_token["Phenotype"][]="Contentobjects";
		$_token["Phenotype"][]="Copy of";
		$_token["Phenotype"][]="Date";
		$_token["Phenotype"][]="Day";
		$_token["Phenotype"][]="December";
		$_token["Phenotype"][]="Delete";
		$_token["Phenotype"][]="Description";
		$_token["Phenotype"][]="Document";
		$_token["Phenotype"][]="Documents";
		$_token["Phenotype"][]="Edit";
		$_token["Phenotype"][]="Editor";
		$_token["Phenotype"][]="Error";
		$_token["Phenotype"][]="Error during rollback.";
		$_token["Phenotype"][]="Extras";
		$_token["Phenotype"][]="February";
		$_token["Phenotype"][]="Feedback";
		$_token["Phenotype"][]="Folder";
		$_token["Phenotype"][]="Group";
		$_token["Phenotype"][]="Help";
		$_token["Phenotype"][]="ID";
		$_token["Phenotype"][]="Image";
		$_token["Phenotype"][]="Images";
		$_token["Phenotype"][]="Includes";
		$_token["Phenotype"][]="Info";
		$_token["Phenotype"][]="Install snapshot";
		$_token["Phenotype"][]="January";
		$_token["Phenotype"][]="July";
		$_token["Phenotype"][]="June";
		$_token["Phenotype"][]="Keywords";
		$_token["Phenotype"][]="Layout";
		$_token["Phenotype"][]="Login";
		$_token["Phenotype"][]="March";
		$_token["Phenotype"][]="May";
		$_token["Phenotype"][]="Media";
		$_token["Phenotype"][]="Mediagroup";
		$_token["Phenotype"][]="Mediagroups";
		$_token["Phenotype"][]="Month";
		$_token["Phenotype"][]="Name";
		$_token["Phenotype"][]="No.";
		$_token["Phenotype"][]="Notice";
		$_token["Phenotype"][]="November";
		$_token["Phenotype"][]="October";
		$_token["Phenotype"][]="Overview";
		$_token["Phenotype"][]="Pagegroup";
		$_token["Phenotype"][]="Pagegroups";
		$_token["Phenotype"][]="Pages";
		$_token["Phenotype"][]="Password";
		$_token["Phenotype"][]="Please remember to delete the user starter.";
		$_token["Phenotype"][]="Properties";
		$_token["Phenotype"][]="Rights";
		$_token["Phenotype"][]="Role";
		$_token["Phenotype"][]="Roles";
		$_token["Phenotype"][]="Rollback";
		$_token["Phenotype"][]="Save";
		$_token["Phenotype"][]="Search";
		$_token["Phenotype"][]="September";
		$_token["Phenotype"][]="Start";
		$_token["Phenotype"][]="State";
		$_token["Phenotype"][]="Status";
		$_token["Phenotype"][]="Task subjects";
		$_token["Phenotype"][]="Tasks";
		$_token["Phenotype"][]="Title";
		$_token["Phenotype"][]="User";
		$_token["Phenotype"][]="Username";
		$_token["Phenotype"][]="Users";
		$_token["Phenotype"][]="Year";
		$_token["Phenotype"][]="all";
		$_token["Phenotype"][]="before";
		$_token["Phenotype"][]="create new task";
		$_token["Phenotype"][]="day(s)";
		$_token["Phenotype"][]="day_short_friday";
		$_token["Phenotype"][]="day_short_monday";
		$_token["Phenotype"][]="day_short_saturday";
		$_token["Phenotype"][]="day_short_sunday";
		$_token["Phenotype"][]="day_short_thursday";
		$_token["Phenotype"][]="day_short_tuesday";
		$_token["Phenotype"][]="day_short_wednesday";
		$_token["Phenotype"][]="edit";
		$_token["Phenotype"][]="for";
		$_token["Phenotype"][]="hours";
		$_token["Phenotype"][]="install snapshot";
		$_token["Phenotype"][]="last month";
		$_token["Phenotype"][]="minutes";
		$_token["Phenotype"][]="msg_login_error";
		$_token["Phenotype"][]="msg_no_access";
		$_token["Phenotype"][]="msg_session_timeout";
		$_token["Phenotype"][]="msg_snapshot_contentobject";
		$_token["Phenotype"][]="msg_snapshot_mediaobject";
		$_token["Phenotype"][]="next month";
		$_token["Phenotype"][]="offline";
		$_token["Phenotype"][]="online";
		$_token["Phenotype"][]="pagegroups";
		$_token["Phenotype"][]="snapshot installed";


		// Ticket

		$_token["Ticket"][]="%bbTitle%bs for task:";
		$_token["Ticket"][]="+ Preferential";
		$_token["Ticket"][]="++ Highest Priority";
		$_token["Ticket"][]="- Subordinate";
		$_token["Ticket"][]="1 hour";
		$_token["Ticket"][]="1,5 hours";
		$_token["Ticket"][]="10 hours";
		$_token["Ticket"][]="10 minutes";
		$_token["Ticket"][]="15 minutes";
		$_token["Ticket"][]="2 hours";
		$_token["Ticket"][]="20 minutes";
		$_token["Ticket"][]="3 hours";
		$_token["Ticket"][]="30 minutes";
		$_token["Ticket"][]="4 hours";
		$_token["Ticket"][]="45 minutes";
		$_token["Ticket"][]="5 hours";
		$_token["Ticket"][]="5 minutes";
		$_token["Ticket"][]="6 hours";
		$_token["Ticket"][]="7 hours";
		$_token["Ticket"][]="8 hours";
		$_token["Ticket"][]="9 hours";
		$_token["Ticket"][]="Add ticket to watch list.";
		$_token["Ticket"][]="All realms";
		$_token["Ticket"][]="Attachment";
		$_token["Ticket"][]="Back to overview";
		$_token["Ticket"][]="Capability Planning";
		$_token["Ticket"][]="Classification";
		$_token["Ticket"][]="Close";
		$_token["Ticket"][]="Comment/Documentation";
		$_token["Ticket"][]="Complexity";
		$_token["Ticket"][]="Create new ticket";
		$_token["Ticket"][]="Deferral";
		$_token["Ticket"][]="Duration";
		$_token["Ticket"][]="Execute";
		$_token["Ticket"][]="Express Ticket";
		$_token["Ticket"][]="Fulltext";
		$_token["Ticket"][]="Hint for %1";
		$_token["Ticket"][]="Information";
		$_token["Ticket"][]="Limit";
		$_token["Ticket"][]="Log";
		$_token["Ticket"][]="Manage new task";
		$_token["Ticket"][]="Meta";
		$_token["Ticket"][]="New Task";
		$_token["Ticket"][]="Notice marker deleted.";
		$_token["Ticket"][]="Notices";
		$_token["Ticket"][]="Notices edited.";
		$_token["Ticket"][]="Pending question";
		$_token["Ticket"][]="Period";
		$_token["Ticket"][]="Planning";
		$_token["Ticket"][]="Please choose a title for this ticket";
		$_token["Ticket"][]="Please chosse a limit for this ticket";
		$_token["Ticket"][]="Postpone";
		$_token["Ticket"][]="Priority";
		$_token["Ticket"][]="Process task";
		$_token["Ticket"][]="Process task.";
		$_token["Ticket"][]="Processor";
		$_token["Ticket"][]="Processors";
		$_token["Ticket"][]="Processors/Creator";
		$_token["Ticket"][]="Progress";
		$_token["Ticket"][]="Question";
		$_token["Ticket"][]="Question for %1";
		$_token["Ticket"][]="Question marker deleted.";
		$_token["Ticket"][]="Really delete this ticket?";
		$_token["Ticket"][]="Realm";
		$_token["Ticket"][]="Remove Marker";
		$_token["Ticket"][]="Remove ticket from watch list.";
		$_token["Ticket"][]="Search tasks";
		$_token["Ticket"][]="Search tasks %1";
		$_token["Ticket"][]="Searchresult";
		$_token["Ticket"][]="Send";
		$_token["Ticket"][]="Standard Ticket";
		$_token["Ticket"][]="Target";
		$_token["Ticket"][]="Target Date";
		$_token["Ticket"][]="Tasks Overview";
		$_token["Ticket"][]="Ticked reopened";
		$_token["Ticket"][]="Ticket Notice";
		$_token["Ticket"][]="Ticket accepted.";
		$_token["Ticket"][]="Ticket closed.";
		$_token["Ticket"][]="Ticket deferral cleared.";
		$_token["Ticket"][]="Ticket deferred until %1.";
		$_token["Ticket"][]="Ticket delegated to %1";
		$_token["Ticket"][]="Ticket log";
		$_token["Ticket"][]="Ticket opened.";
		$_token["Ticket"][]="Ticket postponed until %1.";
		$_token["Ticket"][]="Ticket processed for %1 minutes.";
		$_token["Ticket"][]="Ticket processed for %1 minutes. %2% completed.";
		$_token["Ticket"][]="Ticket processed. %1% completed.";
		$_token["Ticket"][]="Ticket rejected.";
		$_token["Ticket"][]="Ticket viewed.";
		$_token["Ticket"][]="Time scheduling";
		$_token["Ticket"][]="Trend";
		$_token["Ticket"][]="View ticket log";
		$_token["Ticket"][]="all";
		$_token["Ticket"][]="close ticket";
		$_token["Ticket"][]="closed";
		$_token["Ticket"][]="day";
		$_token["Ticket"][]="end of week";
		$_token["Ticket"][]="few days";
		$_token["Ticket"][]="fulltext search for";
		$_token["Ticket"][]="give back ticket";
		$_token["Ticket"][]="hour";
		$_token["Ticket"][]="in 6 month";
		$_token["Ticket"][]="in four weeks";
		$_token["Ticket"][]="in one year";
		$_token["Ticket"][]="in three month";
		$_token["Ticket"][]="in two month";
		$_token["Ticket"][]="in two weeks";
		$_token["Ticket"][]="marked";
		$_token["Ticket"][]="mine";
		$_token["Ticket"][]="month";
		$_token["Ticket"][]="msg_calenderweek";
		$_token["Ticket"][]="msg_filter_closed_tickets_1";
		$_token["Ticket"][]="msg_filter_closed_tickets_2";
		$_token["Ticket"][]="msg_filter_closed_tickets_3";
		$_token["Ticket"][]="msg_selectusers_comment";
		$_token["Ticket"][]="msg_selectusers_question";
		$_token["Ticket"][]="msg_ticket_created";
		$_token["Ticket"][]="msg_ticket_duration_hours";
		$_token["Ticket"][]="msg_ticket_duration_minutes";
		$_token["Ticket"][]="msg_ticket_progress";
		$_token["Ticket"][]="my tasks";
		$_token["Ticket"][]="n/a";
		$_token["Ticket"][]="negative";
		$_token["Ticket"][]="next monday";
		$_token["Ticket"][]="next week";
		$_token["Ticket"][]="no estimation";
		$_token["Ticket"][]="not specified";
		$_token["Ticket"][]="notices";
		$_token["Ticket"][]="o Standard";
		$_token["Ticket"][]="order ABCD";
		$_token["Ticket"][]="order assigned to";
		$_token["Ticket"][]="order date";
		$_token["Ticket"][]="order importance";
		$_token["Ticket"][]="order last change";
		$_token["Ticket"][]="order priority";
		$_token["Ticket"][]="order reported by";
		$_token["Ticket"][]="order title";
		$_token["Ticket"][]="permanent task";
		$_token["Ticket"][]="positive";
		$_token["Ticket"][]="questions";
		$_token["Ticket"][]="reactivate ticket";
		$_token["Ticket"][]="search for";
		$_token["Ticket"][]="search for id";
		$_token["Ticket"][]="searchresult";
		$_token["Ticket"][]="set target";
		$_token["Ticket"][]="take over ticket";
		$_token["Ticket"][]="task overview";
		$_token["Ticket"][]="tasks";
		$_token["Ticket"][]="title";
		$_token["Ticket"][]="today";
		$_token["Ticket"][]="tomorrow";
		$_token["Ticket"][]="week";


		$this->_token = $_token;

	}





	public function readTMX($file,$locale)
	{
		$_content =array();
		if (file_exists($file))
		{
			$xml = file_get_contents($file);
			$xml = str_replace("<tmx:","<",$xml);
			$xml = str_replace("/tmx:","/",$xml);
			$xml = str_replace("xml:lang","lang",$xml);

			//echo htmlentities($xml);
			$_xml = simplexml_load_string($xml);

			if (!$_xml)
			{
				throw new Exception ("Error parsing ".$file ."\n\nStopping tmx file generation. Please fix and rerun.");
			}


			foreach ($_xml->body->tu AS $_xml_tu)
			{
				//echo "tu";
				$key= (string)$_xml_tu["tuid"];
				$val ="";
				foreach ($_xml_tu->tuv AS $_xml_tuv)
				{
					//echo(string)$_xml_tuv["lang"];
					if ((string)$_xml_tuv["lang"]==$locale)
					{
						$val = (string)$_xml_tuv->seg;
						//echo "TEST".$val;
						break;
					}
				}
				$_content[$key]=$val;
			}
		}


		return $_content;
	}

	public function writeTMX($file,$_token,$_english,$locale = false, $_translation =array())
	{
		$xml ='<?xml version="1.0" encoding="UTF-8"?>
<tmx:tmx version="2.0" xmlns:tmx="http://www.lisa.org/tmx20">
  <tmx:header adminlang="en" creationtool="Phenotype" creationtoolversion="##!PT_VERSION!##" o-tmf="unknown" segtype="block" srclang="*all*"/>
  <tmx:body>';
		foreach ($_token AS $token)
		{
			$val = $_english[$token];
			$val = trim(codeX($val,true));
			$xml .='<tmx:tu tuid="'.codeX($token,true).'">
      <tmx:tuv xml:lang="en">
        <tmx:seg>'.$val.'</tmx:seg>
      </tmx:tuv>';
			if ($locale != false)
			{
				$val = $_translation[$token];
				$val = trim(codeX($val,true));
				$xml .='
      <tmx:tuv xml:lang="'.$locale.'">
        <tmx:seg>'.$val.'</tmx:seg>
      </tmx:tuv>';
			}
			$xml .= '</tmx:tu>
			';
		}
		$xml .='  </tmx:body>
</tmx:tmx>';

		//echo htmlentities($xml);
		file_put_contents($file,$xml);
	}

	/**
	 * This function reads localisation tokens out of the given tmx-file
	 * and adds them to the dataobject which holds the translations
	 *
	 * @param unknown_type $myDAO
	 * @param unknown_type $file
	 * @param unknown_type $locale
	 */
	public function addTokens($myDAO,$file,$locale)
	{
		$xml = file_get_contents($file);
		$xml = str_replace("<tmx:","<",$xml);
		$xml = str_replace("/tmx:","/",$xml);
		$xml = str_replace("xml:lang","lang",$xml);
		$_xml = simplexml_load_string($xml);

		if (!$_xml)
		{
			echo "<br/>Attention! Problems parsing ".$file."<br/>";
		}
		foreach ($_xml->body->tu AS $_xml_tu)
		{
			$key= trim((string)$_xml_tu["tuid"]);
			$val="";
			$val_en="";
			foreach ($_xml_tu->tuv AS $_xml_tuv)
			{
				if ((string)$_xml_tuv["lang"]=="en")
				{
					$val_en = trim((string)$_xml_tuv->seg);
				}
				if ((string)$_xml_tuv["lang"]==$locale)
				{
					$val = trim((string)$_xml_tuv->seg);
					break;
				}
			}

			if ($val==""){
				if ($val_en !="" AND $myDAO->get($key)=="")
				{
					$myDAO->set($key,$val_en);
				}
			}
			else
			{
				$myDAO->set($key,$val);
			}
		}

	}

	/**
	 * This function prints a list with locale-commands for all tokens
	 *
	 * Very usefull, when you want to localize scripts / backend pages
	 *
	 */
	public function helpMe()
	{
		echo '<table>';
		$heading="";
		foreach ($this->_token AS $k => $section)
		{
			foreach ($section as  $v)
			{
				if ($k!=$heading)
				{
					$heading=$k;
					echo '<tr><td colspan="2"><strong>'.$heading.'<strong></td></tr>';
				}
				echo '<tr><td>'.htmlentities('<?php echo localeH("'.$v.'");?>').'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; locale("'.$v.'")'.'</td></tr>';
			}
		}
		echo '</table>';
	}

	/**
	 * sorts alle localization tokens for optimizing the LocaleManager class
	 *
	 * interal (system developer) usage only
	 *
	 */
	public function sortTokens()
	{
		$_tmx = $this->_token;

		ksort($_tmx);
		foreach ($_tmx AS $name => $_token)
		{
			echo '<br/><br/>// '.$name ."<br/><br/>";
			asort($_token);
			$_token=array_unique($_token);
			foreach ($_token as $k)
			{
				echo '$_token["'.$name.'"][]="'.$k.'";<br/>';
			}
		}
	}

	/**
	 * prints out all unknown (i.e. new) tokens, collected into database table tokens
	 * 
	 * internal (system developer) usage only
	 */
	public function displayNewTokens()
	{
		global $myDB;
		$sql = "SELECT * FROM tokens ORDER BY section, token";
		$rs = $myDB->query($sql);
		while ($row=mysql_fetch_array($rs))
		{
			echo '$_token["'.$row["section"].'"][]="'.$row["token"].'";<br/>';
		}
	}

	/**
	 * Rewrites all txm files based on the tokens array in this class. 
	 * Be cautious: 
	 * That means, any entry to a token in any tmx file not referenced in this class gets deleted!
	 * 
	 * 
	 * internal (system developer) usage only
	 */
	public function rebuildTMXFiles($insert="",$_englishinsert=array(),$_germaninsert=array())
	{
		foreach ($this->_token AS $name => $_token)
		{

			$file = SYSTEMPATH . "tmx/". $name ."_en.tmx";

			$_english = $this->readTMX($file,"en");
			if ($name==$insert)
			{
				$_english = array_merge($_english,$_englishinsert);
			}
			$this->writeTMX($file,$_token,$_english);
			foreach ($this->_locales AS $locale)
			{
				$file = SYSTEMPATH . "tmx/". $name ."_".$locale.".tmx";
				$_translation = $this->readTMX($file,$locale);
				if ($name==$insert AND $locale == "de")
				{
					$_translation = array_merge($_translation,$_germaninsert);
				}
				$this->writeTMX($file,$_token,$_english,$locale,$_translation);
			}
		}
	}

	/**
	 * This functions pastes token names into the english content (if empty)
	 * 
	 * Very usefull since most english translations for a token are identical to the token itself
	 * 
	 * internal (system developer) usage only
	 */
	public function lumberEnglishTMX()
	{
		foreach ($this->_token AS $name => $_token)
		{
			$file = SYSTEMPATH . "tmx/". $name ."_en.tmx";
			//echo $file ."<br/>";
			$_english = $this->readTMX($file,"en");
			foreach ($_english AS $k=>$v)
			{
				if ($v=="")
				{
					$_english[$k]=$k;
				}
			}
			$this->writeTMX($file,$_token,$_english);
		}
	}

}


