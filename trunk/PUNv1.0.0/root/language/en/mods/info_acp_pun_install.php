<?php
/**
*
*===================================================================
*
*  Personal User Notes -- Install Language File
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
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'PUN'						=> 'Personal User Notes',
	//! Install
	'INSTALL_PUN'					=> 'Install Personal User Notes',
	'INSTALL_PUN_CONFIRM'			=> 'Are you ready to install Personal User Notes?',

	//! Uninstall
	'UNINSTALL_PUN'				=> 'Uninstall Personal User Notes',
	'UNINSTALL_PUN_CONFIRM'		=> 'Are you ready to uninstall Personal User Notes?  All settings and data saved by this mod will be removed! You will have to manually remove any files and undo any file edits, which can be found in the MOD\'s install.xml file.',
	
	//! Update
	'UPDATE_PUN'				=> 'Update Personal User Notes',
	'UPDATE_PUN_CONFIRM'		=> 'Are you ready to update Personal User Notes?',
));