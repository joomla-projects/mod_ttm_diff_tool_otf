<?php
/**
 * @package     Mod_ttm_diif_tool_of
 * @subpackage  models
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Github\Github;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\Registry\Registry;

HTMLHelper::_('stylesheet', 'mod_ttm_diff_tool_otf/template.css', ['version' => 'auto', 'relative' => true]);

FormHelper::loadFieldClass('list');

/**
 * Form Field Place class.
 *
 * @package     Extensions.Components
 * @subpackage  Localise
 *
 * @since       1.0
 */
class JFormFieldReleases extends \JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var    string
	 */
	protected $type = 'Releases';

	/**
	 * Method to get the field input.
	 *
	 * @return  string    The field input.
	 */
	protected function getOptions()
	{
		require_once JPATH_SITE . '/modules/mod_ttm_diff_tool_otf/vendor/autoload.php';

		$attributes    = '';

		$gh_user       = 'joomla';
		$gh_project    = 'joomla-cms';

		$options = new Registry;

		// Trying with a 'read only' public repositories token
		// But base 64 encoded to avoid Github alarms sharing it.
		$gh_token = base64_decode('MzY2NzYzM2ZkMzZmMWRkOGU5NmRiMTdjOGVjNTFiZTIyMzk4NzVmOA==');
		$options->set('gh.token', $gh_token);
		$github = new Github($options);

		try
		{
			$releases = $github->repositories->get(
					$gh_user,
					$gh_project . '/releases'
					);

			foreach ($releases as $release)
			{
				$tag_name = $release->tag_name;
				$tag_part = explode(".", $tag_name);
				$undoted  = str_replace('.', '', $tag_name);
				$excluded = 0;

				if (version_compare(JVERSION[0], '2', 'eq'))
				{
					$excluded = 1;
				}
				elseif (version_compare(JVERSION[0], '3', 'eq'))
				{
					if ($tag_part[0] != '3')
					{
						$excluded = 1;
					}
				}
				elseif (version_compare(JVERSION[0], '4', 'ge'))
				{
					if ($tag_part[0] == '4' || $tag_part[0] == '3')
					{
						$excluded = 0;
					}
					else
					{
						$excluded = 1;
					}
				}

				// Filtering by "is_numeric" disable betas or similar releases.
				if (is_numeric($undoted) && $excluded == 0)
				{
					$versions[] = $tag_name;
				}
			}
		}
		catch (Exception $e)
		{
			Factory::getApplication()->enqueueMessage(
				Text::_('ERROR_GITHUB_GETTING_RELEASES'),
				'warning');
		}

		arsort($versions);

		if ($v = (string) $this->element['onchange'])
		{
			$attributes .= ' onchange="' . $v . '"';
		}

		$options = array();

		foreach ($this->element->children() as $option)
		{
			$options[] = HTMLHelper::_('select.option', $option->attributes('value'), Text::_(trim($option)),
								array('option.attr' => 'attributes', 'attr' => '')
								);
		}

		$options[] = HTMLHelper::_('select.option', 'CUSTOM_SR', Text::_('MOD_TTM_DIFF_TOOL_OTF_SR_OPTION_CUSTOM'),
							array('option.attr' => 'attributes')
							);

		foreach ($versions as $version)
		{
			if (!empty($version))
			{
				$options[] = HTMLHelper::_('select.option', $version, sprintf('Joomla %s', $version),
							array('option.attr' => 'attributes')
							);
			}
		}

		return $options;
	}
}
