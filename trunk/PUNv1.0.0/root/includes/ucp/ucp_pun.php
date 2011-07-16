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
		$id			= request_var('id', 0);
		$error = $data = array();
		$s_hidden_fields = '';
		
		$template->assign_var('MODE', $mode);

		// if we are trying to view or post one note
		if ($mode == 'view' || $mode == 'post')
		{
			// require an id
			if (!$id)
			{
				trigger_error('UCP_NO_ID');
			}
			// grab the data
			$sql = 'SELECT * FROM ' . PUN_TABLE . '
				WHERE user_id = ' . $user->data['user_id'] . '
					AND id = ' . $id; // $id was cast to integer when it was defined
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			
			$text = utf8_normalize_nfc($row['text']);
			$title = utf8_normalize_nfc($row['title']);
			$bbcode_options = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) +
				(($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + 
				(($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
			// send the data to the template
			$template->assign_vars(array(
				'TITLE'			=> $title,
				'DATE'			=> $user->format_date($row['date']),
				'LAST_UPDATED'	=> $user->format_date($row['last_updated']),
				'TEXT'			=> generate_text_for_display($text, $row['bbcode_uid'], $row['bbcode_bitfield'], $bbcode_options),
				'AUTHOR'		=> get_username_string('full', $user->data['user_id'], $user->data['username'], $user->data['user_colour']),
			));
			$db->sql_freeresult($result);
			
			if ($mode == 'post')
			{
				$forum_id = request_var('forum_id', 0);
				$return = '<a href="' . append_sid($phpbb_root_path . 'ucp.' . $phpEx, array('i' => 'pun', 'mode' => 'view', 'id' => $id)) . '">' . $user->lang('RETURN_TO_NOTE') . '</a>';
				if ($row['topic_id'])
				{
					$gototopic = '<a href="' . append_sid($phpbb_root_path . 'viewtopic.' . $phpEx, array('t' => $row['topic_id'])) . '">' . $user->lang('GO_TO_TOPIC') . '</a>';
					trigger_error($user->lang('ALREADY_POSTED_NOTE') . '<br />' . $gototopic . '<br />' . $return);
				}
				if ($submit)
				{
					$goback = '<a href="' . append_sid($phpbb_root_path . 'ucp.' . $phpEx, array('i' => 'pun', 'mode' => 'post', 'id' => $id)) . '"' . $user->lang('GO_BACK') . '</a>';
					if (!$forum_id)
					{
						trigger_error($user->lang('SPECIFY_FORUM_ID') . '<br />' . $goback . '<br />' . $return);
					}
					// post the new to a new topic in the specified forum.
					if(!function_exists('submit_post'))
					{
						include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
					}
					
					// Time to submit the post
					$poll = $uid = $bitfield = $options = '';
					$bbcode = $smilies = $urls = true;
					generate_text_for_storage($text, $uid, $bitfield, $options, $bbcode, $urls, $smilies);		
					
					$data = array( 
						'forum_id'      => $forum_id,
						'topic_id'		=> 0,
						'icon_id'       => false,
					
						'enable_bbcode'     => $bbcode,
						'enable_smilies'    => $smilies,
						'enable_urls'       => $urls,
						'enable_sig'        => true,
					
						'message'       => $text,
						'message_md5'   => md5($text),
									
						'bbcode_bitfield'   => $bitfield,
						'bbcode_uid'        => $uid,
					
						'post_edit_locked'  => 0,
						'topic_title'		=> $title,
						'notify_set'        => false,
						'notify'            => false,
						'post_time'         => 0,
						'forum_name'        => '',
						'enable_indexing'   => true,
					);
					$submit = submit_post('post', $title, '', POST_NORMAL, $poll, $data);
					if($submit)
					{
						//set note posted status to true; this is last so that in case of error, it doesn't screw it up
						$sql = 'UPDATE ' . PUN_TABLE . '
							SET posted = 1, topic_id = ' . $data['topic_id'] . '
							WHERE id = ' . $id;
						$result = $db->sql_query($sql);
						$db->sql_freeresult($result);
						
						trigger_error($user->lang('POSTING_SUCCESS') . '<br />' . $return);
					}
					else
					{
						trigger_error($user->lang('POSTING_FAILED') . '<br />' . $goback . '<br />' . $return);
					}
				}
				else
				{
					// Need to know what forums the user can post in.
					if (!function_exists('make_forum_select'))
					{
						include($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
					}
					$forum_options = make_forum_select(0, false, false, true, true);
					$forum_select = '<select name="forum_id"><option value="0"></option>' . $forum_options . '</select>';
					$template->assign_vars(array(
						'FORUM_SELECT'	=> $forum_select,
						'ID'			=> $id,
					));
				}
			}
		}
		// make a new note or change an existing one; same form, different code
		else if ($mode == 'create' || $mode == 'edit')
		{
			// display the form if it hasn't been submitted. Otherwise, do it the note.
			if ($mode == 'edit' && !$id)
			{
				
			}
			if ($submit)
			{
				// validate input
				$title = $db->sql_escape(request_var('title', ''));
				$date = date();
				$text = $db->sql_escape(request_var('text', ''));
				
				generate_text_for_storage($text);
			}
			else
			{
			
			}
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
			'U_POST_NEW_NOTE'	=> append_sid($phpbb_root_path . 'ucp.' . $phpEx, array('i' => 'pun', 'mode' => 'create')),
			'S_HIDDEN_FIELDS'	=> $s_hidden_fields,
			'S_UCP_ACTION'		=> $this->u_action)
		);

		// Set desired template
		$this->tpl_name = 'ucp_pun';
		$this->page_title = 'UCP_PUN_NOTES_TITLE';
	}
}

?>