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
				'DATE'			=> $user->format_date($row['date']),
				'LAST_UPDATED'	=> $user->format_date($row['last_updated']),
				'TEXT'			=> generate_text_for_display($row['text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $bbcode_options),
				'AUTHOR'		=> get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']),
			));
			$db->sql_freeresult($result);
		}
		// catchall
		else
		{
			$sql = 'SELECT * FROM ' . PUN_TABLE . '
				WHERE user_id = ' . $user->data['user_id'];
			$result = $db->sql_query($sql);
			while($row = $db->sql_fetchrow($result))
			{
				$bbcode_options = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
					(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + 
					(($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
				$template->assign_block_vars('notes', array(
					'TITLE'			=> $row['title'],
					'DATE'			=> $user->format_date($row['date']),
					'LAST_UPDATED'	=> $user->format_date($row['last_updated']),
					'U_VIEW'		=> append_sid($phpbb_root_path . 'ucp.' . $phpEx, array('i' => 'pun', 'mode' => 'view', 'id' => $row['id'])),
					'AUTHOR'		=> get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']),
					'NOTE_ID'		=> $row['id'],
				));
			}
			$db->sql_freeresult($result);
			
			// pagination
			$start   = request_var('start', 0);
			$limit   = request_var('limit', 25);
			$sql = 'SELECT COUNT(id) AS count FROM ' . PUN_TABLE . '
				WHERE user_id = ' . $user->data['user_id'];
			$result = $db->sql_query($sql);
			$total_notes = $db->sql_fetchfield('count');
			$db->sql_freeresult($result);
			$pag_url = append_sid($phpbb_root_path . 'ucp.' . $phpEx, array('i' => 'pun'));
			// Assign the pagination variables to the template.
			$template->assign_vars(array(
				'PAGINATION'        => generate_pagination($pag_url, $total_notes, $limit, $start),
				'PAGE_NUMBER'       => on_page($total_notes, $limit, $start),
				'TOTAL_NOTES'       => ($total_notes == 1) ? $user->lang['LIST_NOTE'] : sprintf($user->lang['LIST_NOTES'], $total_notes),
			));
		}
		$template->assign_vars(array(
			'L_TITLE'	=> $user->lang['UCP_PUN_' . strtoupper($mode)],

			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			'S_UCP_ACTION'		=> $this->u_action)
		);

		// Set desired template
		$this->tpl_name = 'ucp_pun';
		$this->page_title = 'UCP_PUN_NOTES_TITLE';
	}
}

?>