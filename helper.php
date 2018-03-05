<?php
/**
 * helper.php
 * Copyright (C) 2011-2012 www.comunidadjoomla.org. All rights reserved.
 * GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');

abstract class modTtm_diff_tool_otfHelper
{
	public static function get_Diff_Info(&$params)
	{
		require_once(JPATH_ROOT . '/' . 'modules' . '/' . 'mod_ttm_diff_tool_otf' . '/' . 'libraries' . '/' . 'mod_ttm_diff_tool_otf_functions.php');
		$diff_info = array();
		$diff_info = get_diff_from_selected_packages($params);

		return $diff_info;
	}
}
