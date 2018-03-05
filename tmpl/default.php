<?php
/**
 * default.php
 * Copyright (C) 2011-2013 www.comunidadjoomla.org. All rights reserved.
 * GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('stylesheet', 'mod_ttm_diff_tool_otf/template.css', array(), true);
?>
<div class="mod-ttm_diff_tool_otf<?php echo $moduleclass_sfx ?>">
	<?php
	//Uncomment to sort module language keys.
	//$ini_array = parse_ini_file('modules'.DS.'mod_ttm_diff_tool_otf'.DS.'language'.DS.'en-GB'.DS.'en-GB.mod_ttm_diff_tool_otf.ini', true);
	//$to_sort=array();
	//foreach ($ini_array as $key => $text)
	//{
	//	if($key{0} != '#'
	//	&& $key{0} != ';'
	//	&& $key{0} != '/'
	//	&& $key{0} != '*'
	//	&& $key{0} != '[')
	//	{
	//	$to_sort[]=$key."=\"".$text."\"";
	//	}
	//}
	//sort($to_sort);

	//foreach ($to_sort as $value)
	//{
	//echo $value."<br />";
	//}
	$options = array(
		'onActive'     => 'function(title, description){
        description.setStyle("display", "block");
        title.addClass("open").removeClass("closed");
    }',
		'onBackground' => 'function(title, description){
        description.setStyle("display", "none");
        title.addClass("closed").removeClass("open");
    }',
		'startOffset'  => 0,  // 0 starts on the first tab, 1 starts the second, etc...
		'useCookie'    => true, // this must not be a string. Don't use quotes.
	);

	echo JHtml::_('tabs.start', 'tab_group_id', $options);
	echo JHtml::_('tabs.panel', JText::_('MOD_TTM_DIFF_TOOL_OTF_PANEL_1_TITLE'), 'panel_1_id');
	echo $diff_info['content'];

	if ($diff_info['config']['allow_history'] == '1')
	{
		echo JHtml::_('tabs.panel', JText::_('MOD_TTM_DIFF_TOOL_OTF_PANEL_2_TITLE'), 'panel_2_id');
		echo $diff_info['history'];
	}

	echo JHtml::_('tabs.end');
	?>
</div>
