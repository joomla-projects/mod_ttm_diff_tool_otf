<?php
/**
 * mod_ttm_diff_tool.php
 * Copyright (C) 2011 www.comunidadjoomla.org. All rights reserved.
 * GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

$diff_info = modTtm_diff_tool_otfHelper::get_Diff_Info($params);

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_ttm_diff_tool_otf', $params->get('layout', 'default'));
