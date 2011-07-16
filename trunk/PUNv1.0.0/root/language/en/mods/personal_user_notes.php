<?php
/**
*
*===================================================================
*
*  Personal User Notes -- Language File
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
/**
* DO NOT CHANGE
*/
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
	'UCP_PUN_NOTES'			=> 'Notes',
	'UCP_PUN_NOTES_TITLE'	=> 'Your Personal User Notes',
	
	'UCP_PUN_VIEWALL'		=> 'View Notes',
	'UCP_PUN_VIEW'			=> 'View Note',
	'UCP_PUN_POST'			=> 'Post a Note',
	'UCP_PUN_NEW'			=> 'New Note',
	
	'UCP_NO_ID'				=> 'You must specify a valid note ID to view or post a note.',
	'ALREADY_POSTED_NOTE'	=> 'You cannot post the same note twice.',
	'SPECIFY_FORUM_ID'		=> 'You must choose a forum into which to post your note.',
	'POSTING_FAILED'		=> 'Something went wrong with posting your note. Please try again.',
	'RETURN_TO_NOTE'		=> 'Return to note',
	'GO_TO_TOPIC'			=> 'View topic',
	'GO_BACK'				=> 'Go back',
	
	'SELECT_FORUM_EXPLAIN'	=> 'Please select a forum below into which to post your note, and then click submit.',
	
	'NOTES'					=> 'Notes',
	'UPDATED'				=> 'Last Updated',
	'NO_NOTES'				=> 'You have no notes.',
	
	'LIST_NOTE'					=> '1 note',
	'LIST_NOTES'				=> '%d notes',
));