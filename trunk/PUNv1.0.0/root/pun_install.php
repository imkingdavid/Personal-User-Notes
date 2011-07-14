<?php
/**
*
*===================================================================
*
*  Personal User Notes Install File
*-------------------------------------------------------------------
*	Script info:
* Version:		1.0.0
* Copyright:	(C) 2010 | David
* License:		http://opensource.org/licenses/gpl-2.0.php | GNU Public License v2
* Package:		phpBB3
*
*===================================================================
*
*/

/**
* @ignore
*/
define('UMIL_AUTO', true);
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if (!file_exists($phpbb_root_path . 'umil/umil_auto.' . $phpEx))
{
	trigger_error('Please download the latest UMIL (Unified MOD Install Library) from: <a href="http://www.phpbb.com/mods/umil/">phpBB.com/mods/umil</a>', E_USER_ERROR);
}

// The name of the mod to be displayed during installation.
$mod_name = 'PUN';

/*
* The name of the config variable which will hold the currently installed version
* You do not need to set this yourself, UMIL will handle setting and updating the version itself.
*/
$version_config_name = 'pun_version';

/*
* The language file which will be included when installing
* Language entries that should exist in the language file for UMIL (replace $mod_name with the mod's name you set to $mod_name above)
* $mod_name
* 'INSTALL_' . $mod_name
* 'INSTALL_' . $mod_name . '_CONFIRM'
* 'UPDATE_' . $mod_name
* 'UPDATE_' . $mod_name . '_CONFIRM'
* 'UNINSTALL_' . $mod_name
* 'UNINSTALL_' . $mod_name . '_CONFIRM'
*/
$language_file = 'mods/info_acp_pun_install';

/*
* Options to display to the user (this is purely optional, if you do not need the options you do not have to set up this variable at all)
* Uses the acp_board style of outputting information, with some extras (such as the 'default' and 'select_user' options)

$options = array(
	'test_username'	=> array('lang' => 'TEST_USERNAME', 'type' => 'text:40:255', 'explain' => true, 'default' => $user->data['username'], 'select_user' => true),
	'test_boolean'	=> array('lang' => 'TEST_BOOLEAN', 'type' => 'radio:yes_no', 'default' => true),
);
*/

/*
* Optionally we may specify our own logo image to show in the upper corner instead of the default logo.
* $phpbb_root_path will get prepended to the path specified
* Image height should be 50px to prevent cut-off or stretching.
*/
//$logo_img = 'styles/prosilver/imageset/site_logo.gif';

/*
* The array of versions and actions within each.
* You do not need to order it a specific way (it will be sorted automatically), however, you must enter every version, even if no actions are done for it.
*
* You must use correct version numbering.  Unless you know exactly what you can use, only use X.X.X (replacing X with an integer).
* The version numbering must otherwise be compatible with the version_compare function - http://php.net/manual/en/function.version-compare.php
*/

$versions = array(
	'1.0.0' => array(
		'module_add' => array(
			array('ucp', 0, 'UCP_CAT_PUN'),
			array('ucp', 'UCP_CAT_PUN', 'UCP_PUN'),
			array('ucp', 'UCP_PUN', array(
					'module_basename'		=> 'pun',
					'modes'					=> array('viewall', 'view', 'post'),
				),
			),
		),
		
		'permission_add' => array(
			array('u_use_pun', true),
			array('u_post_pun', true),
		),
		
		
		'table_add' => array(
			array('phpbb_pun', array(
					'COLUMNS'		=> array(
						'id'				=> array('UINT', NULL, 'auto_increment'),
						'user_id'			=> array('UINT', 0),
						'title'				=> array('VCHAR_UNI', ''), 
						'date'				=> array('TIMESTAMP', 0),
						'last_updated'		=> array('TIMESTAMP', 0),
						'bbcode_uid'		=> array('VCHAR_UNI', ''),
						'bbcode_bitfield'	=> array('VCHAR_UNI', ''),
						'enable_bbcode'		=> array('TINT:1', 1),
						'enable_smilies'	=> array('TINT:1', 1),
						'enable_magic_url'	=> array('TINT:1', 1),
					),
					'PRIMARY_KEY'	=> 'id',
				),
			),
		),
	),
);
include($phpbb_root_path . 'umil/umil_auto.' . $phpEx);