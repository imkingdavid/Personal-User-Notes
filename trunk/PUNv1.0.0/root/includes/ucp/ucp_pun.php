<?php
/**
*
* @package ucp
* @version $Id$
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* ucp_profile
* Changing profile settings
*
* @todo what about pertaining user_sig_options?
* @package ucp
*/
class ucp_pun
{
	var $u_action;

	function main($id, $mode)
	{
		global $config, $db, $user, $auth, $template, $phpbb_root_path, $phpEx;

		$user->add_lang('mods/personal_user_notes');

		$preview	= (!empty($_POST['preview'])) ? true : false;
		$submit		= (!empty($_POST['submit'])) ? true : false;
		$delete		= (!empty($_POST['delete'])) ? true : false;
		$id			= (!empty($_GET['id'])) ? (int) $_GET['id'] : 0;
		$error = $data = array();
		$s_hidden_fields = '';

		// if we are trying to view or post one note
		if($mode == 'view' || $mode == 'post')
		{
			// require an id
			if(!$id)
			{
				trigger_error('UCP_NO_ID');
			}
			// grab the data
			$sql = 'SELECT * FROM ' . PUN_TABLE . '
				WHERE user_id = ' . $user->data['user_id'] . '
					AND id = ' . $id; // $id was cast to integer when it was defined
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			
			$bbcode_options = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
				(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + 
				(($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
			// send the data to the template
			$template->assign_vars(array(
				'TITLE'			=> $row['title'],
				'CREATED_DATE'	=> $user->format_date($row['date']),
				'LAST_UPDATED'	=> $user->format_date($row['last_updated']),
				
				'TEXT'			=> generate_text_for_display($row['text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $bbcode_options),
				
				'AUTHOR'		=> get_username_string('full', $user->data['user_id'], $user->data['usename'], $user->data['user_colour']),
			));
			$db->sql_freeresult($result);
		}
		// catchall
		else
		{
			$sql = 'SELECT * FROM ' . PUN_TABLE . '
				WHERE pun_user_id = ' . $user->data['user_id'];
			$result = $db->sql_query($sql);
			while($row = $db->sql_fetchrow($result))
			{
				$bbcode_options = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
					(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + 
					(($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);

				$template->assign_block_vars('notes', array(
					'TITLE'			=> $row['title'],
					'CREATED_DATE'	=> $user->format_date($row['date']),
					'LAST_UPDATED'	=> $user->format_date($row['last_updated']),
					
					'AUTHOR'		=> get_username_string('full', $user->data['user_id'], $user->data['usename'], $user->data['user_colour']),
				));
			}
			$db->sql_freeresult($result);
		}
		$template->assign_vars(array(
			'L_TITLE'	=> $user->lang['UCP_PUN_' . strtoupper($mode)],

			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			'S_UCP_ACTION'		=> $this->u_action)
		);

		// Set desired template
		$this->tpl_name = 'ucp_pun_' . $mode;
		$this->page_title = 'UCP_PUN_NOTES_TITLE';
	}
}

?>