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
* @package module_install
*/
class ucp_pun_info
{
	function module()
	{
		return array(
			'filename'	=> 'ucp_pun',
			'title'		=> 'UCP_PUN',
			'version'	=> '1.0.0',
			'modes'		=> array(
				'viewall'		=> array('title' => 'UCP_PUN_VIEWALL', 'auth' => '', 'cat' => array('UCP_PUN')),
				'view'			=> array('title' => 'UCP_PUN_NOTE', 'auth' => '', 'cat' => array('UCP_PUN')),
				'post'			=> array('title' => 'UCP_PUN_POST', 'auth' => '', 'cat' => array('UCP_PUN')),
				'create'		=> array('title' => 'UCP_CREATE_NOTE', 'auth' => '', 'cat' => array('UCP_PUN')),
			),
		);
	}

	function install()
	{
	}

	function uninstall()
	{
	}
}