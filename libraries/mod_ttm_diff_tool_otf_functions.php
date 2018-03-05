<?php
/**
 * mod_ttm_diff_tool_otf_functions.php
 * Copyright (C) 2011-2012 www.comunidadjoomla.org. All rights reserved.
 * GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

function get_diff_from_selected_packages($params = array())
{
//starting vars
	$data                           = array();
	$to_render                      = '';
	$client_selection               = array();
	$source_revision                = '';
	$target_revision                = '';
	$custom_source_revision         = '';
	$custom_target_revision         = '';
	$urls_to_frozen_joomla_releases = array();

	$joomla_sp_link = '';
	$joomla_tp_link = '';
	$unzipped_info  = array();

	$lang     = JFactory::getLanguage();
	$lang_tag = $lang->getTag();

//Determinig joomla releases by type LTS or STS.
	$urls_to_frozen_joomla_releases = array(
		"LTS_FROZEN_2_5_28" => "https://github.com/joomla/joomla-cms/releases/download/2.5.28/Joomla_2.5.28-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_27" => "https://github.com/joomla/joomla-cms/releases/download/2.5.27/Joomla_2.5.27-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_26" => "https://github.com/joomla/joomla-cms/releases/download/2.5.26/Joomla_2.5.26-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_25" => "https://github.com/joomla/joomla-cms/releases/download/2.5.25/Joomla_2.5.25-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_24" => "http://joomlacode.org/gf/download/frsrelease/19663/160037/Joomla_2.5.24-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_23" => "http://joomlacode.org/gf/download/frsrelease/19641/159974/Joomla_2.5.23-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_22" => "http://joomlacode.org/gf/download/frsrelease/19546/159467/Joomla_2.5.22-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_21" => "http://joomlacode.org/gf/download/frsrelease/19522/159401/Joomla_2.5.21-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_20" => "http://joomlacode.org/gf/download/frsrelease/19395/158840/Joomla_2.5.20-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_19" => "http://joomlacode.org/gf/download/frsrelease/19238/158101/Joomla_2.5.19-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_18" => "http://joomlacode.org/gf/download/frsrelease/19145/157516/Joomla_2.5.18-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_17" => "http://joomlacode.org/gf/download/frsrelease/19009/134342/Joomla_2.5.17-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_16" => "http://joomlacode.org/gf/download/frsrelease/18858/91472/Joomla_2.5.16-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_15" => "http://joomlacode.org/gf/download/frsrelease/18840/86941/Joomla_2.5.15-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_14" => "http://joomlacode.org/gf/download/frsrelease/18624/83477/Joomla_2.5.14-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_13" => "http://joomlacode.org/gf/download/frsrelease/18555/83292/Joomla_2.5.13-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_12" => "(not available)",
		"LTS_FROZEN_2_5_11" => "http://joomlacode.org/gf/download/frsrelease/18322/80354/Joomla_2.5.11-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_10" => "http://joomlacode.org/gf/download/frsrelease/18229/80083/Joomla_2.5.10-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_9"  => "http://joomlacode.org/gf/download/frsrelease/17968/78430/Joomla_2.5.9-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_8"  => "http://joomlacode.org/gf/download/frsrelease/17715/77262/Joomla_2.5.8-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_7"  => "http://joomlacode.org/gf/download/frsrelease/17410/76012/Joomla_2.5.7-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_6"  => "http://joomlacode.org/gf/download/frsrelease/17173/74758/Joomla_2.5.6-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_5"  => "http://joomlacode.org/gf/download/frsrelease/17138/74605/Joomla_2.5.5-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_4"  => "http://joomlacode.org/gf/download/frsrelease/16914/73508/Joomla_2.5.4-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_3"  => "http://joomlacode.org/gf/download/frsrelease/16804/73116/Joomla_2.5.3-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_2"  => "http://joomlacode.org/gf/download/frsrelease/16760/72877/Joomla_2.5.2-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_1"  => "http://joomlacode.org/gf/download/frsrelease/16512/72867/Joomla_2.5.1-Stable-Full_Package.zip",
		"LTS_FROZEN_2_5_0"  => "http://joomlacode.org/gf/download/frsrelease/16394/71581/Joomla_2.5.0-Stable-Full_Package.zip",
		"STS_FROZEN_3_4_0"  => "https://github.com/joomla/joomla-cms/releases/download/3.4.0/Joomla_3.4.0-Stable-Full_Package.zip",
		"STS_FROZEN_3_3_6"  => "https://github.com/joomla/joomla-cms/releases/download/3.3.6/Joomla_3.3.6-Stable-Full_Package.zip",
		"STS_FROZEN_3_3_5"  => "http://joomlacode.org/gf/download/frsrelease/19802/161153/Joomla_3.3.5-Stable-Full_Package.zip",
		"STS_FROZEN_3_3_4"  => "http://joomlacode.org/gf/download/frsrelease/19752/160963/Joomla_3.3.4-Stable-Full_Package.zip",
		"STS_FROZEN_3_3_3"  => "http://joomlacode.org/gf/download/frsrelease/19665/160049/Joomla_3.3.3-Stable-Full_Package.zip",
		"STS_FROZEN_3_3_2"  => "http://joomlacode.org/gf/download/frsrelease/19639/159961/Joomla_3.3.2-Stable-Full_Package.zip",
		"STS_FROZEN_3_3_1"  => "http://joomlacode.org/gf/download/frsrelease/19524/159413/Joomla_3.3.1-Stable-Full_Package.zip",
		"STS_FROZEN_3_3_0"  => "http://joomlacode.org/gf/download/frsrelease/19393/158832/Joomla_3.3.0-Stable-Full_Package.zip",
		"STS_FROZEN_3_2_3"  => "http://joomlacode.org/gf/download/frsrelease/19239/158104/Joomla_3.2.3-Stable-Full_Package.zip",
		"STS_FROZEN_3_2_2"  => "http://joomlacode.org/gf/download/frsrelease/19143/157504/Joomla_3.2.2-Stable-Full_Package.zip",
		"STS_FROZEN_3_2_1"  => "http://joomlacode.org/gf/download/frsrelease/19007/134333/Joomla_3.2.1-Stable-Full_Package.zip",
		"STS_FROZEN_3_2_0"  => "http://joomlacode.org/gf/download/frsrelease/18838/86936/Joomla_3.2.0-Stable-Full_Package.zip",
		"STS_FROZEN_3_1_5"  => "http://joomlacode.org/gf/download/frsrelease/18622/83487/Joomla_3.1.5-Stable-Full_Package.zip",
		"STS_FROZEN_3_1_4"  => "http://joomlacode.org/gf/download/frsrelease/18557/83319/Joomla_3.1.4-Stable-Full_Package.zip",
		"STS_FROZEN_3_1_3"  => "(not available)",
		"STS_FROZEN_3_1_2"  => "(not available)",
		"STS_FROZEN_3_1_1"  => "http://joomlacode.org/gf/download/frsrelease/18323/80368/Joomla_3.1.1-Stable-Full_Package.zip",
		"STS_FROZEN_3_1_0"  => "http://joomlacode.org/gf/download/frsrelease/18263/80087/Joomla_3.1.0-Stable-Full_Package.zip",
		"STS_FROZEN_3_0_3"  => "http://joomlacode.org/gf/download/frsrelease/17965/78414/Joomla_3.0.3-Stable-Full_Package.zip",
		"STS_FROZEN_3_0_2"  => "http://joomlacode.org/gf/download/frsrelease/17710/77237/Joomla_3.0.2-Stable-Full_Package.zip",
		"STS_FROZEN_3_0_1"  => "http://joomlacode.org/gf/download/frsrelease/17574/76732/Joomla_3.0.1-Stable-Full_Package.zip",
		"STS_FROZEN_3_0_0"  => "http://joomlacode.org/gf/download/frsrelease/17520/76466/Joomla_3.0.0-Stable-Full_Package.zip");

	$knowed_zipball_urls = array('LTS_DEV' =>
		                             array('https://github.com/joomla/joomla-cms/zipball/2.5.x'     => 'https://api.github.com/repos/joomla/joomla-cms/git/refs/heads/2.5.x',
		                                   'https://github.com/joomla/joomla-cms/archive/2.5.x.zip' => 'https://api.github.com/repos/joomla/joomla-cms/git/refs/heads/2.5.x'),
	                             'STS_DEV' =>
		                             array('https://github.com/joomla/joomla-cms/archive/staging.zip' => 'https://api.github.com/repos/joomla/joomla-cms/git/refs/heads/staging',
		                                   'https://github.com/joomla/joomla-cms/archive/master.zip'  => 'https://api.github.com/repos/joomla/joomla-cms/git/refs/heads/master',
		                                   'https://github.com/joomla/joomla-cms/archive/3.4-dev.zip' => 'https://api.github.com/repos/joomla/joomla-cms/git/refs/heads/3.4-dev',
		                                   'https://github.com/joomla/joomla-cms/zipball/master'      => 'https://api.github.com/repos/joomla/joomla-cms/git/refs/heads/master'
		                             )
	);

	$knowed_joomla_patterns = array(
		'http://joomlacode.org/gf/download/frsrelease/',
		'https://github.com/joomla/joomla-cms/releases/download/');

//Getting the module params within variables.
	$client_selection       = $params->get('client_selection', 'admin_selected');
	$source_revision        = $params->get('source_revision', 'STS_FROZEN_3_3_6');
	$target_revision        = $params->get('target_revision', 'STS_DEV');
	$custom_source_revision = $params->get('custom_source_revision', '');
	$custom_target_revision = $params->get('custom_target_revision', '');
	$allow_history          = $params->get('allow_history', '0');
	$excluded_comments_list = $params->get('excluded_comments_pattern', '');

	if (!empty($excluded_comments_list))
	{
		$ecp = explode(',', $excluded_comments_list);
	}
	else
	{
		$ecp = array();
	}

	$history_path = JPATH_ROOT
		. '/'
		. 'modules'
		. '/'
		. 'mod_ttm_diff_tool_otf'
		. '/'
		. 'storage'
		. '/'
		. 'history';

	$allowed_groups = (array) $params->get('allowed_groups', null);
	$user_groups    = JFactory::getUser()->get('groups');
	$can_do_history = '0';
	$is_deleting    = '0';

	if (!empty($allowed_groups) && !empty($user_groups))
	{
		if (array_intersect($allowed_groups, $user_groups))
		{
			$can_do_history = '1';
			$is_deleting    = history_to_delete($history_path,
				$filter = '\.ttm',
				$can_do_history);
		}
	}

//get valid params or die
	$validate_params = validate_params($client_selection,
		$source_revision,
		$target_revision,
		$custom_source_revision,
		$custom_target_revision,
		$urls_to_frozen_joomla_releases);
//get valid links or die
	$joomla_sp_link = get_joomla_link($source_revision,
		$custom_source_revision,
		$urls_to_frozen_joomla_releases,
		$knowed_zipball_urls);

	$joomla_tp_link = get_joomla_link($target_revision,
		$custom_target_revision,
		$urls_to_frozen_joomla_releases,
		$knowed_zipball_urls);

	$have_report_name = '';
	$source_type      = '';
	$target_type      = '';

//looking for sha values if present.
	$revise_history = revise_history($joomla_sp_link,
		$joomla_tp_link,
		$knowed_zipball_urls,
		$knowed_joomla_patterns);

	if ($allow_history == '1' && !empty($revise_history['both']['file_name']))
	{
		$history_file  = $revise_history['both']['file_name'];
		$added_clients = '';

		foreach ($client_selection as $client_selected)
		{
			switch (true)
			{
				case ($client_selected == 'admin_selected'):
					$added_clients .= "admin_";
					break;
				case ($client_selected == 'site_selected'):
					$added_clients .= "site_";
					break;
				case ($client_selected == 'installation_selected'):
					$added_clients .= "install_";
					break;
			}
		}

		$history_file = $lang_tag
			. '_'
			. $added_clients
			. "diff_"
			. $revise_history['both']['file_name'];

		$history_file_path = JPATH_ROOT
			. '/'
			. 'modules'
			. '/'
			. 'mod_ttm_diff_tool_otf'
			. '/'
			. 'storage'
			. '/'
			. 'history'
			. '/'
			. $history_file;

		if (JFile::exists($history_file_path) && JFile::getExt($history_file) == 'ttm')
		{
			$file_to_load        = $history_file_path;
			$history_data        = fopen($file_to_load, 'rb');
			$history_loaded_data = '';

			if (!$history_data)
			{
				fclose($history_data);
			}
			else
			{

				while (!feof($history_data))
				{
					$history_loaded_data .= fread($history_data, 1024);
				}

				fclose($history_data);

				$to_render .= "<p>* Using history "
					. JFile::getName($history_file)
					. "</p>";

				$to_render .= $history_loaded_data;

				$data['config']['allow_history'] = $allow_history;
				$data['history']                 = get_history_files($history_path,
					$filter = '\.ttm',
					$can_do_history);
				$data['content']                 = $to_render;

				//Returning the stored rendered info to be dumped by the template file.
				return $data;
			}
		}

	}

	$set_source_name = '0';
	$set_target_name = '0';
//Info from source pack
	$source_package = get_pack_from_url_and_extract_info($joomla_sp_link,
		$client_selection,
		$destination = 'source',
		$urls_to_frozen_joomla_releases,
		$knowed_zipball_urls);
//added to the main array
	$unzipped_info[] = $source_package['extracted_zipped_info'];
//Info from target pack
	$target_package = get_pack_from_url_and_extract_info($joomla_tp_link,
		$client_selection,
		$destination = 'target',
		$urls_to_frozen_joomla_releases,
		$knowed_zipball_urls);
//added to the main array
	$unzipped_info[] = $target_package['extracted_zipped_info'];
	$unzipped_info   = array_values(array_filter($unzipped_info));

	//from packs comming from GithHub or type zipball the program get the package name one time we have the zipped file.
	//The 'extra data'stored within the '$source_package' or '$target_package' array have the info about this one.

	if (isset($source_package['extra_data']) && $set_source_name == '0')
	{
		$source_pack_extra_data = get_pack_extra_data($source_package['extra_data'],
			$extra_data_dest = 'source',
			$joomla_sp_link,
			$revise_history);

		$source_package_name    = $source_pack_extra_data['package_name'];
		$source_downloable_from = $source_pack_extra_data['downloable_from'];
		$set_source_name        = $source_pack_extra_data['set_name'];
	}

	if (isset($target_package['extra_data']) && $set_target_name == '0')
	{
		$target_pack_extra_data = get_pack_extra_data($target_package['extra_data'],
			$extra_data_dest = 'target',
			$joomla_tp_link,
			$revise_history);

		$target_package_name    = $target_pack_extra_data['package_name'];
		$target_downloable_from = $target_pack_extra_data['downloable_from'];
		$set_target_name        = $target_pack_extra_data['set_name'];
	}


//Unsetting non required vars.
	$source_package = array();
	$target_package = array();
//Start rendering info.
	$now = JFactory::getDate();

	$to_render .= "<H1 class='starting'>"
		. JText::_('MOD_TTM_DIFF_TOOL_OTF_STARTING_ON')
		. $now
		. "</H1>";

	$to_render .= "<p><span class='source_pack'>"
		. JText::_('MOD_TTM_DIFF_TOOL_OTF_SP_NAME')
		. $source_package_name
		. "</span></p>"
		. $source_downloable_from;

	$to_render .= "<p><span class='target_pack'>"
		. JText::_('MOD_TTM_DIFF_TOOL_OTF_TP_NAME')
		. $target_package_name
		. "</span></p>"
		. $target_downloable_from;

//Calling to revise the changes between language files.
	$changes = revise_changes($unzipped_info,
		$client_selection,
		$ecp);

//Adding rendered changescatched.
	$to_render .= $changes;
//Returning the rendered info to be dumped by the template file.

	if ($allow_history == '1' && !empty($revise_history['both']['file_name']) && $is_deleting == 0)
	{

		if (JFile::exists($history_file_path))
		{
			//nothing to do.
		}
		else
		{

			if (JFile::getExt($history_file) == 'ttm')
			{
				$handle = fopen($history_file_path, "w");
				fwrite($handle, $to_render);
				fclose($handle);

				if (JFile::exists($history_file_path))
				{
					//File really created
					JError::raiseNotice(100,
						'[HISTORY FILE ADDED] '
						. htmlspecialchars($history_file));
				}
				else
				{
					JError::raiseWarning('100',
						'[ERROR CREATING HISTORY] '
						. htmlspecialchars($history_file));
				}
			}
		}
	}

	$data['config']['allow_history'] = $allow_history;

	$data['history'] = get_history_files($history_path,
		$filter = '\.ttm',
		$can_do_history);
	$data['content'] = $to_render;

	return $data;
}

function get_raw_url_content($url = null)
{
	$curl  = curl_init();
	$agent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';
	curl_setopt($curl, CURLOPT_USERAGENT, $agent);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_TIMEOUT, 10);

	$html = curl_exec($curl);

	if (!empty($html))
	{
		curl_close($curl);

		return $html;
	}
	else
	{
		curl_close($curl);
		$html = array();

		return $html;
	}
}

function validate_params($client_selection = array(),
                         $source_revision = '',
                         $target_revision = '',
                         $custom_source_revision = '',
                         $custom_target_revision = '',
                         $urls_to_frozen_joomla_releases = array())
{
	switch (true)
	{
		//cases when the program must die for sure due wrong setting are applied.
		case (!is_array($client_selection) || empty ($client_selection)):
			die ("Bad client selection detected: program dies.");

		case ($source_revision == '' || $target_revision == ''):
			die ("Source or target URLs are empty: program dies.");

		case (!array_key_exists($source_revision, $urls_to_frozen_joomla_releases)
			&& $source_revision != 'LTS_DEV'
			&& $source_revision != 'STS_DEV'
			&& $source_revision != 'CUSTOM_SR'):
			die ("Invalid source type detected: program dies.");

		case (!array_key_exists($target_revision, $urls_to_frozen_joomla_releases)
			&& $target_revision != 'LTS_DEV'
			&& $target_revision != 'STS_DEV'
			&& $target_revision != 'CUSTOM_TR'):
			die ("Invalid target type detected: program dies.");

		case (array_key_exists($source_revision, $urls_to_frozen_joomla_releases)
			&& $urls_to_frozen_joomla_releases[$source_revision] == '(not available)'):
			die ("Detected an 'not available' package selected as source: program dies.");

		case (array_key_exists($target_revision, $urls_to_frozen_joomla_releases)
			&& $urls_to_frozen_joomla_releases[$target_revision] == '(not available)'):
			die ("Detected an 'not available' package selected as target: program dies.");

		case ($source_revision == 'CUSTOM_SR' && $custom_source_revision == ''):
			die ("Detected a custom revision selected as source but it is using an empty URL value: program dies.");

		case ($target_revision == 'CUSTOM_TR' && $custom_target_revision == ''):
			die ("Detected a custom revision selected as target but it is using an empty URL value: program dies.");
	}

	return;
}

function get_joomla_link($revision = '',
                         $custom_revision = '',
                         $urls_to_frozen_joomla_releases = array(),
                         $knowed_zipball_urls = array())
{
	//Cases when the program must determine how to follow or die due wrong setting are applied.
	//CUSTOM_SR or CUSTOM_TR option has been selected from the module configuration.
	if (($revision == 'CUSTOM_SR' && !empty($custom_revision)) || ($revision == 'CUSTOM_TR' && !empty($custom_revision)))
	{

		return $custom_revision;

		//LTS_DEV option has been selected from the module configuration.
	}
	elseif ($revision == 'LTS_DEV')
	{
		//Get the first key that contain the URL
		//$lts_dev_url = array_shift( array_keys ($knowed_zipball_urls['LTS_DEV']));
		$ltsdev      = array_keys($knowed_zipball_urls['LTS_DEV']);
		$lts_dev_url = array_shift($ltsdev);

		return $lts_dev_url;

		//STS_DEV option has been selected from the module configuration.
	}
	elseif ($revision == 'STS_DEV')
	{
		//Get the first key that contain the URL
		//$sts_dev_url = array_shift (array_keys ($knowed_zipball_urls['STS_DEV']));
		$stsdev      = array_keys($knowed_zipball_urls['STS_DEV']);
		$sts_dev_url = array_shift($stsdev);

		return $sts_dev_url;

		//Listed stored package has been selected from the module configuration.
	}
	elseif (array_key_exists($revision, $urls_to_frozen_joomla_releases))
	{

		return $urls_to_frozen_joomla_releases[$revision];

	}
	else
	{
		die ("The program have founded a wrong setting getting the right urls and must die.");
	}
}

function revise_history($joomla_sp_link = '',
                        $joomla_tp_link = '',
                        $knowed_zipball_urls = array(),
                        $knowed_joomla_patterns = array())
{
	$part['source']['full_sha'] = '';
	$part['source']['part_sha'] = '';
	$part['source']['name']     = '';
	$part['source']['type']     = '';
	$part['target']['full_sha'] = '';
	$part['target']['part_sha'] = '';
	$part['target']['name']     = '';
	$part['target']['type']     = '';
	$part['both']['file_name']  = '';
	$have_sp                    = '';
	$have_tp                    = '';

	$dest = array('source', 'target');

	foreach ($dest as $d)
	{
		if ($d == 'source')
		{
			$source_part = get_sha($joomla_sp_link,
				$knowed_zipball_urls,
				$knowed_joomla_patterns);

			if (!empty($source_part['part_sha']))
			{
				$part['source']['type']     = 'sha';
				$part['source']['part_sha'] = $source_part['part_sha'];
				$part['source']['full_sha'] = $source_part['full_sha'];
				$have_sp                    = $source_part['part_sha'];
			}
			elseif (!empty($source_part['name']))
			{
				$part['source']['type'] = 'name';

				if (JFile::getExt($source_part['name']) == 'zip')
				{
					$have_sp = JFile::stripExt($source_part['name']);
				}
				else
				{
					$have_sp = $source_part['name'];
				}
			}

		}
		elseif ($d == 'target')
		{
			$target_part = get_sha($joomla_tp_link,
				$knowed_zipball_urls,
				$knowed_joomla_patterns);

			if (!empty($target_part['part_sha']))
			{
				$part['target']['type']     = 'sha';
				$part['target']['part_sha'] = $target_part['part_sha'];
				$part['target']['full_sha'] = $target_part['full_sha'];
				$have_tp                    = $target_part['part_sha'];
			}
			elseif (!empty($target_part['name']))
			{
				$part['target']['type'] = 'name';

				if (JFile::getExt($target_part['name']) == 'zip')
				{
					$have_tp = JFile::stripExt($target_part['name']);
				}
				else
				{
					$have_tp = $target_part['name'];
				}
			}
		}
	}


	if (!empty($have_sp) && !empty($have_tp))
	{
		$part['both']['file_name'] = $have_sp
			. "_vs_"
			. $have_tp
			. '.ttm';
	}

//return $to_render;
	return $part;
}

function get_pack_extra_data($package_extra_data = array(),
                             $extra_data_dest = '',
                             $joomla_link = '',
                             $revise_history = array())
{
	$package = array();

	if (isset ($revise_history[$extra_data_dest]['full_sha']) && $revise_history[$extra_data_dest]['full_sha'] != '')
	{
		$sha = "<p><b>SHA:</b> "
			. $revise_history[$extra_data_dest]['full_sha']
			. "</p>";
	}
	else
	{
		$sha = '';
	}

	//Without using zipped prefix
	if (isset ($package_extra_data['pack_name']['stored_pack_name_no_prefix'][$extra_data_dest]))
	{
		$package['package_name']    = $package_extra_data['pack_name']['stored_pack_name_no_prefix'][$extra_data_dest];
		$package['downloable_from'] = "<p><span class='downloable_from'>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_FROM')
			. $joomla_link
			. "</span></p>"
			. "<p>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_KNOWN_FROZEN_URL')
			. "</p>"
			. $sha;

		$package['set_name'] = '1';

		return $package;

	}
	elseif (isset ($package_extra_data['pack_name']['unstored_pack_name_no_prefix'][$extra_data_dest]))
	{
		$package['package_name']    = $package_extra_data['pack_name']['unstored_pack_name_no_prefix'][$extra_data_dest];
		$package['downloable_from'] = "<p><span class='downloable_from'>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_FROM')
			. $joomla_link
			. "</span></p>"
			. "<p>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_MAYBE_FROZEN_URL')
			. "</p>"
			. $sha;

		$package['set_name'] = '1';

		return $package;

	}
	elseif (isset ($package_extra_data['pack_name']['zipball_pack_name_no_prefix'][$extra_data_dest]))
	{
		$package['package_name']    = $package_extra_data['pack_name']['zipball_pack_name_no_prefix'][$extra_data_dest];
		$package['downloable_from'] = "<p><span class='downloable_from'>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_FROM')
			. $joomla_link
			. "/"
			. $package['package_name']
			. ".zip</span></p>"
			. "<p>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_ORIENTATIVE_ZIPBALL_URL')
			. "</p>"
			. $sha;

		$package['set_name'] = '1';

		return $package;

		//Using zipped prefix
	}
	elseif (isset ($package_extra_data['pack_name']['zipball_pack_name_with_prefix'][$extra_data_dest]))
	{
		$package['package_name']    = $package_extra_data['pack_name']['zipball_pack_name_with_prefix'][$extra_data_dest];
		$package['downloable_from'] = "<p><span class='downloable_from'>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_FROM')
			. $joomla_link
			. "/"
			. $package['package_name']
			. "</span></p>"
			. "<p>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_ZIPBALL_NOT_STORED_URL')
			. "</p>"
			. "<p>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_ORIENTATIVE_ZIPBALL_URL')
			. "</p>"
			. $sha;

		$package['set_name'] = '1';

		return $package;

	}
	elseif (isset($package_extra_data['pack_name']['unstored_pack_name_width_prefix'][$extra_data_dest]))
	{

		if ($sha != '')
		{
			$text = JText::_('MOD_TTM_DIFF_TOOL_OTF_GITHUB_NOT_STORED_URL');
		}
		else
		{
			$text = JText::_('MOD_TTM_DIFF_TOOL_OTF_FRONZEN_NOT_STORED_URL');
		}

		$package['package_name']    = $package_extra_data['pack_name']['unstored_pack_name_width_prefix'][$extra_data_dest];
		$package['downloable_from'] = "<p><span class='downloable_from'>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_FROM')
			. $joomla_link
			. "</span></p>"
			. "<p>"
			. $text
			. "</p>"
			. $sha;

		$package['set_name'] = '1';

		return $package;

	}
	elseif (isset ($package_extra_data['pack_name']['github_pack_name_with_prefix'][$extra_data_dest]))
	{
		$package['package_name']    = $package_extra_data['pack_name']['github_pack_name_with_prefix'][$extra_data_dest];
		$package['downloable_from'] = "<p><span class='downloable_from'>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_FROM')
			. $joomla_link
			. "/"
			. $package['package_name']
			. ".zip</span></p>"
			. "<p>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_KNOWN_DEV_LINK')
			. "</p>"
			. "<p>"
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_ORIENTATIVE_ZIPBALL_URL')
			. "</p>"
			. $sha;

		$package['set_name'] = '1';

		return $package;
	}

	$package['set_name'] = '0';

	return $package;
}

function get_pack_from_url_and_extract_info($link = null, $client_selection = array(),
                                            $destination = '',
                                            $urls_to_frozen_joomla_releases = array(),
                                            $knowed_zipball_urls = array())
{
	$extracted_zipped_info = array();
	$extra_data            = array();
//Random number to add at the temp dir path.
	$random_number = rand();
//Setting the temp dir to store the pack within the module folder using a random folder name.
	$tmp_storage_dir = 'modules'
		. '/'
		. 'mod_ttm_diff_tool_otf'
		. '/'
		. 'storage'
		. '/'
		. $random_number
		. '_package.zip';

	$package_name = '';

	//start if copy pack
	if (copy($link, $tmp_storage_dir))
	{
		//if created, handle it as zip archive.
		$zipped_package = new ZipArchive;
		//determining the paths to language files without prefix.
		$admin_lang_path = 'administrator'
			. '/'
			. 'language'
			. '/'
			. 'en-GB'
			. '/';

		$site_lang_path = 'language'
			. '/'
			. 'en-GB'
			. '/';

		$install_lang_path = 'installation'
			. '/'
			. 'language'
			. '/'
			. 'en-GB'
			. '/';

		if ($zipped_package->open($tmp_storage_dir, ZIPARCHIVE::CREATE) !== true)
		{
			//if not posible create the pack the program must die here.
			$zipped_package->close();
			unlink($tmp_storage_dir);
			die("The program can not load the package. Please, be sure than your folders are writtables"
				. " or than you are pointing to a right package by valid and allowed URL.");
		}

		//if package is usinga prefix here can get the prefix folder name
		$width_prefix = $zipped_package->getNameIndex('0');

		//start if testing zipped files access
		//Testing access to common files without prefix.
		if ($zipped_package->getFromName($admin_lang_path
				. 'en-GB.xml')
			&& $zipped_package->getFromName($site_lang_path
				. 'en-GB.xml')
			&& $zipped_package->getFromName($install_lang_path
				. 'en-GB.xml'))
		{
			$zipped_admin_path   = $admin_lang_path;
			$zipped_site_path    = $site_lang_path;
			$zipped_install_path = $install_lang_path;
			//Trying to get the package name.
			$uri_data     = test_uri($link);
			$uri_path     = $uri_data->toString(array('path'));
			$uri_parts    = explode('/', $uri_path);
			$package_name = JFile::makeSafe(end($uri_parts));

			//Testing valid URL types.
			if (in_array($link, $urls_to_frozen_joomla_releases))
			{
				//It is stored by the program and pointing to joomla releases.
				$extra_data['pack_name']['stored_pack_name_no_prefix'][$destination] = $package_name;

			}
			elseif (JFile::getExt($package_name) && JFile::getExt($package_name) == 'zip')
			{
				//It is not stored by the program but URL is pointing to one zip package.
				$extra_data['pack_name']['unstored_pack_name_no_prefix'][$destination] = $package_name;

			}
			elseif (!JFile::getExt($package_name) && $package_name != '')
			{
				//It is not stored by the program and URL is not pointing directly to zip extension
				//Without use prefix does not seems an github zipball call, but is using a similar way to call the pack.
				$extra_data['pack_name']['zipball_pack_name_no_prefix'][$destination] = $package_name;
			}
			else
			{
				//Case the type of pack call is unsuported must die.
				$zipped_package->close();
				unlink($tmp_storage_dir);

				die("The program can not load this type of package."
					. " Maybe is ok and the error is due you are trying to load a package without 'zip' extension format."
					. " (rar, tar.gz, etc, is unsuported).");
			}

			//Testing access to common files with prefix.
		}
		elseif ($zipped_package->getFromName($width_prefix
				. $admin_lang_path
				. 'en-GB.xml')

			&& $zipped_package->getFromName($width_prefix
				. $site_lang_path
				. 'en-GB.xml')

			&& $zipped_package->getFromName($width_prefix
				. $install_lang_path
				. 'en-GB.xml'))
		{

			//Common files founded with prefix required.
			$zipped_admin_path = $width_prefix
				. $admin_lang_path;

			$zipped_site_path = $width_prefix
				. $site_lang_path;

			$zipped_install_path = $width_prefix
				. $install_lang_path;

			//Trying to get the package name.
			$uri_data     = test_uri($link);
			$uri_path     = $uri_data->toString(array('path'));
			$uri_parts    = explode('/', $uri_path);
			$package_name = JFile::makeSafe(end($uri_parts));

			//Testing valid URL types.
			if (array_key_exists($link, $knowed_zipball_urls['LTS_DEV'])
				|| array_key_exists($link, $knowed_zipball_urls['STS_DEV']))
			{
				//It is pointing to in dev releases stored by the program.
				$extra_data['pack_name']['github_pack_name_with_prefix'][$destination] = substr($width_prefix, 0, -1);

			}
			elseif (JFile::getExt($package_name) && JFile::getExt($package_name) == 'zip')
			{
				//It is not stored by the program but URL is pointing to one prefixed zip package.
				$extra_data['pack_name']['unstored_pack_name_width_prefix'][$destination] = $package_name;

			}
			elseif (!JFile::getExt($package_name) && $package_name != '')
			{
				//It is not stored by the program and URL is not pointing directly to zip extension
				//With prefix is using a similar zipball way to call the pack, but the URL is not sotred by this program.
				$extra_data['pack_name']['zipball_pack_name_with_prefix'][$destination] = $package_name;
			}
			else
			{
				//Case the type of pack call is unsuported must die.
				$zipped_package->close();
				unlink($tmp_storage_dir);

				die("The program can not load this type of package."
					. " Maybe is ok and the error is due you are trying to load a package without 'zip' extension format"
					. " (rar, tar.gz, etc, is unsuported).");
			}

		}
		else
		{
			//Common files not founded with or without prefix must die.
			$zipped_package->close();
			unlink($tmp_storage_dir);
			die("The program can not load the right files."
				. " Is this one than you are trying to move one full Joomla package? prefix: $width_prefix");
		}//end if testing zipped files access

		//examine all the package files
		for ($i = 0; $i < $zipped_package->numFiles; $i++)
		{
			$file_in_zip_path = $zipped_package->getNameIndex($i);

			$client_admin_filename = preg_replace('/|'
				. preg_quote($zipped_admin_path, '/|.-')
				. '|/', '', $file_in_zip_path);

			$client_site_filename = preg_replace('/|'
				. preg_quote($zipped_site_path, '/|.-')
				. '|/', '', $file_in_zip_path);

			$client_install_filename = preg_replace('/|'
				. preg_quote($zipped_install_path, '/|.-')
				. '|/', '', $file_in_zip_path);

			//Catching language files pointing to admin folder.
			if (in_array('admin_selected', $client_selection)
				&& $client_admin_filename != ''
				&& $file_in_zip_path != $client_admin_filename
				&& !strpos($client_admin_filename, '/'))
			{
				$file_contents = '';
				$file_to_store = $zipped_package->getStream($file_in_zip_path);

				if (!$file_to_store)
				{
					$extracted_zipped_info['client_admin'][$destination]['content_filenames'][$client_admin_filename] =
						'ERROR STORING THIS FILE';
					fclose($file_to_store);
				}
				else
				{

					while (!feof($file_to_store))
					{
						$file_contents .= fread($file_to_store, 1024);
					}

					$extracted_zipped_info['client_admin'][$destination]['content_filenames'][$client_admin_filename] =
						$file_contents;
					fclose($file_to_store);
				}

				$extracted_zipped_info['client_admin'][$destination]['filenames'][] = JFile::makeSafe($client_admin_filename);

				//Catching language files pointing to site folder.
			}
			elseif (in_array('site_selected', $client_selection)
				&& $client_site_filename != ''
				&& $file_in_zip_path != $client_site_filename
				&& !strpos($client_site_filename, "/"))
			{

				$file_contents = '';
				$file_to_store = $zipped_package->getStream($file_in_zip_path);

				if (!$file_to_store)
				{
					$extracted_zipped_info['client_site'][$destination]['content_filenames'][$client_site_filename] =
						'ERROR STORING THIS FILE';
					fclose($file_to_store);
				}
				else
				{

					while (!feof($file_to_store))
					{
						$file_contents .= fread($file_to_store, 1024);
					}

					$extracted_zipped_info['client_site'][$destination]['content_filenames'][$client_site_filename] =
						$file_contents;
					fclose($file_to_store);
				}

				$extracted_zipped_info['client_site'][$destination]['filenames'][] = JFile::makeSafe($client_site_filename);

				//Catching language files pointing to install folder.
			}
			elseif (in_array('installation_selected', $client_selection)
				&& $client_install_filename != ''
				&& $file_in_zip_path != $client_install_filename
				&& !strpos($client_install_filename, "/"))
			{

				$file_contents = '';
				$file_to_store = $zipped_package->getStream($file_in_zip_path);
				if (!$file_to_store)
				{
					$extracted_zipped_info['client_installation'][$destination]['content_filenames'][$client_install_filename] =
						'ERROR STORING THIS FILE';
					fclose($file_to_store);
				}
				else
				{

					while (!feof($file_to_store))
					{
						$file_contents .= fread($file_to_store, 1024);
					}

					$extracted_zipped_info['client_installation'][$destination]['content_filenames'][$client_install_filename] =
						$file_contents;
					fclose($file_to_store);
				}

				$extracted_zipped_info['client_installation'][$destination]['filenames'][] = JFile::makeSafe($client_install_filename);
			}
		}

		$zipped_package->close();
		unlink($tmp_storage_dir);

	}
	else
	{
		//if not created, must die.
		die("The program can not store the package. Link: $link");
	}//end if copy pack

	if (empty ($extra_data))
	{
		return array('extracted_zipped_info' => $extracted_zipped_info);
	}

	return array('extracted_zipped_info' => $extracted_zipped_info, 'extra_data' => $extra_data);
}

function revise_changes($unzipped_info = array(),
                        $client_selection = array(),
                        $ecp = array())
{
	$changes    = array();
	$pre_render = array();

	foreach ($client_selection as $client_selected)
	{
		if ($client_selected == 'admin_selected')
		{
			$source['client_admin']                          = '0';
			$target['client_admin']                          = '0';
			$pre_render['diff_file_content']['client_admin'] = '';
			$pre_render['diff_file_names']['client_admin']   = '';
			$pre_render['new_files_content']['client_admin'] = '';

		}
		elseif ($client_selected == 'site_selected')
		{
			$source['client_site']                          = '0';
			$target['client_site']                          = '0';
			$pre_render['diff_file_content']['client_site'] = '';
			$pre_render['diff_file_names']['client_site']   = '';
			$pre_render['new_files_content']['client_site'] = '';

		}
		elseif ($client_selected == 'installation_selected')
		{
			$source['client_installation']                          = '0';
			$target['client_installation']                          = '0';
			$pre_render['diff_file_content']['client_installation'] = '';
			$pre_render['diff_file_names']['client_installation']   = '';
			$pre_render['new_files_content']['client_installation'] = '';
		}

		unset ($client_selected);
	}

	$clients_order = array("client_admin", "client_site", "client_installation");


	$to_render = "<H2 class='starting_report'>"
		. JText::_('MOD_TTM_DIFF_TOOL_OTF_REPORTED_DIFF')
		. "</H2>";

	foreach ($unzipped_info as $all => $part)
	{

		foreach ($part as $client => $destinations)
		{

			foreach ($destinations as $destination => $all_info)
			{
				if ($destination == 'source')
				{
					$source[$client] = '1';

					$source_files[$client]         = $all_info['filenames'];
					$source_files_content[$client] = $all_info['content_filenames'];

				}
				elseif ($destination == 'target')
				{
					$target[$client]               = '1';
					$target_files[$client]         = $all_info['filenames'];
					$target_files_content[$client] = $all_info['content_filenames'];
				}

				if ($source[$client] == '1' && $target[$client] == '1')
				{
					//now than we have the source and target info by client...
					$pre_render['diff_file_names'][$client] .= "<h2 class='"
						. $client
						. "'>"
						. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
							. strtoupper($client)))
						. " ZONE</h2>";

					$changes[$client]['files_to_add']    = array();
					$changes[$client]['files_to_add']    = array_diff($target_files[$client], $source_files[$client]);
					$changes[$client]['files_to_delete'] = array();
					$changes[$client]['files_to_delete'] = array_diff($source_files[$client], $target_files[$client]);
					$changes[$client]['common_files']    = array();
					$changes[$client]['common_files']    = array_intersect($source_files[$client], $target_files[$client]);

					if (!empty($changes[$client]['files_to_add']))
					{
						sort($changes[$client]['files_to_add']);
						$have_files_to_add[$client]             = '1';
						$pre_render['diff_file_names'][$client] .=
							"<h3 class='files_to_add'>"
							. JText::_('MOD_TTM_DIFF_TOOL_OTF_FILES_ADD_CLIENT')
							. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
								. strtoupper($client)))
							. "</h3>";

						foreach ($changes[$client]['files_to_add'] as $file_to_add)
						{

							$pre_render['diff_file_names'][$client] .=
								"<p class='file_to_add'>["
								. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
									. strtoupper($client)))
								. "] - "
								. $file_to_add
								. "</p>";

							$pre_render['new_files_content'][$client] .=
								"<p class='new_file'><span class='new_word'>["
								. JText::_('MOD_TTM_DIFF_TOOL_OTF_NEW')
								. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
									. strtoupper($client)))
								. JText::_('MOD_TTM_DIFF_TOOL_OTF_FILE')
								. "] </span>"
								. $file_to_add
								. "</p>";

							$pre_render['new_files_content'][$client] .=
								"<pre class='new_file_content'>"
								. htmlspecialchars($target_files_content[$client][$file_to_add])
								. "</pre>";
							unset ($file_to_add);
						}

					}
					else
					{
						$have_files_to_add[$client]             = '0';
						$pre_render['diff_file_names'][$client] .=
							"<h3 class='no_files_to_add'>"
							. JText::_('MOD_TTM_DIFF_TOOL_OTF_NO_FILES_ADD_CLIENT')
							. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
								. strtoupper($client)))
							. "</h3>";
					}

					if (!empty($changes[$client]['files_to_delete']))
					{
						sort($changes[$client]['files_to_delete']);
						$have_files_to_delete[$client]          = '1';
						$pre_render['diff_file_names'][$client] .=
							"<h3 class='files_to_delete'>"
							. JText::_('MOD_TTM_DIFF_TOOL_OTF_FILES_DELETE_CLIENT')
							. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
								. strtoupper($client)))
							. "</h3>";

						foreach ($changes[$client]['files_to_delete'] as $file_to_delete)
						{
							$pre_render['diff_file_names'][$client] .=
								"<p class='file_to_delete'>["
								. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
									. strtoupper($client)))
								. "] "
								. $file_to_delete
								. "</p>";
							unset ($file_to_delete);
						}

					}
					else
					{
						$have_files_to_delete[$client]          = '0';
						$pre_render['diff_file_names'][$client] .=
							"<h3 class='no_files_to_delete'>"
							. JText::_('MOD_TTM_DIFF_TOOL_OTF_NO_FILES_DELETE_CLIENT')
							. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
								. strtoupper($client)))
							. "</h3>";
					}

					if (!empty ($changes[$client]['common_files']))
					{
						sort($changes[$client]['common_files']);
						$have_comon_files[$client] = '1';
						$type_xml                  = '';
						$type_html                 = '';
						$type_php                  = '';
						$type_unusual              = '';
						$type_ini                  = '';

						$have_type_xml     = '0';
						$have_type_html    = '0';
						$have_type_php     = '0';
						$have_type_unusual = '0';
						$have_type_ini     = '0';


						$have_content_file_changes[$client]               = '0';
						$all_excluded_comments[$client]['to_add']         = array();
						$all_excluded_comments[$client]['to_delete']      = array();
						$all_excluded_comments[$client]['to_add_text']    = '';
						$all_excluded_comments[$client]['to_delete_text'] = '';
						foreach ($changes[$client]['common_files'] as $common_file)
						{
							$sf_content = $source_files_content[$client][$common_file];
							$tf_content = $target_files_content[$client][$common_file];

							if ($sf_content != $tf_content)
							{
								$revision     = get_content_diff($common_file,
									$sf_content,
									$tf_content,
									$ecp);
								$content_diff = $revision['changes'];

								$excluded_comments[$client] = $revision['excluded_comments'];
								$comment_types              = array('to_add', 'to_delete');

								if (!empty($excluded_comments[$client]))
								{
									foreach ($comment_types as $comment_type)
									{
										if (isset($excluded_comments[$client][$comment_type]))
										{
											foreach ($excluded_comments[$client][$comment_type]
											         as $the_comment)
											{
												$the_comment = htmlspecialchars($the_comment);
												if (!in_array($the_comment,
													$all_excluded_comments[$client][$comment_type]))
												{
													$all_excluded_comments[$client][$comment_type][] =
														$the_comment;
													$all_excluded_comments[$client]
													[$comment_type . '_text']                        .= '<p>'
														. $the_comment
														. '</p>';
												}
											}

										}
									}


								}

								$have_content_file_changes[$client] = '1';

								if (isset ($content_diff['type_html']))
								{
									$type_html .=
										"<p class='new_file'><span class='new_word'>["
										. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
											. strtoupper($client)))
										. JText::_('MOD_TTM_DIFF_TOOL_OTF_FILE_CHANGES')
										. "] </span>"
										. $common_file
										. "</p>";

									$type_html .=
										"<pre class='common_file_diff'>"
										. $content_diff['type_html']
										. "</pre>";

								}
								elseif (isset($content_diff['type_xml']))
								{
									$type_xml .=
										"<p class='new_file'><span class='new_word'>["
										. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
											. strtoupper($client)))
										. JText::_('MOD_TTM_DIFF_TOOL_OTF_FILE_CHANGES')
										. "] </span>"
										. $common_file
										. "</p>";

									$type_xml .=
										"<pre class='common_file_diff'>"
										. $content_diff['type_xml']
										. "</pre>";

								}
								elseif (isset($content_diff['type_php']))
								{
									$type_php .=
										"<p class='new_file'><span class='new_word'>["
										. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
											. strtoupper($client)))
										. JText::_('MOD_TTM_DIFF_TOOL_OTF_FILE_CHANGES')
										. "] </span>"
										. $common_file
										. "</p>";

									$type_php .=
										"<pre class='common_file_diff'>"
										. $content_diff['type_php']
										. "</pre>";

								}
								elseif (isset($content_diff['type_unusual']))
								{
									$type_unusual .=
										"<p class='new_file'><span class='new_word'>["
										. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
											. strtoupper($client)))
										. JText::_('MOD_TTM_DIFF_TOOL_OTF_FILE_CHANGES')
										. "] </span>"
										. $common_file
										. "</p>";

									$type_unusual .=
										"<p>"
										. $content_diff['type_unusual']
										. "</p>";

								}
								elseif (isset($content_diff['type_ini']))
								{
									$type_ini .=
										"<p class='new_file'><span class='new_word'>["
										. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
											. strtoupper($client)))
										. JText::_('MOD_TTM_DIFF_TOOL_OTF_FILE_CHANGES')
										. "] </span>"
										. $common_file
										. "</p>";

									$type_ini .=
										"<p>"
										. $content_diff['type_ini']
										. "</p>";
								}

								$content_diff = array();
							}

							$sf_content = '';
							$tf_content = '';
							unset ($common_file);
						}

						$comment_types_text  = array('to_add_text', 'to_delete_text');
						$comment_types_name  = array('to_add_text'    => 'Comments to add',
						                             'to_delete_text' => 'Comments to delete');
						$comments_to_exclude = '';

						foreach ($comment_types_text as $comment_type_text)
						{

							if (!empty($all_excluded_comments[$client][$comment_type_text]))
							{
								$comments_to_exclude .= 'The next <b>'
									. $comment_types_name[$comment_type_text]
									. '</b> have been <b>excluded</b> normaly due they are handleds as masive changes to apply within all the ini files of this client:';
								if ($comment_type_text == 'to_add_text')
								{
									$comments_to_exclude .= '<font color="green">'
										. $all_excluded_comments[$client][$comment_type_text]
										. '</font>';
								}
								else
								{
									$comments_to_exclude .= '<font color="red">'
										. $all_excluded_comments[$client][$comment_type_text]
										. '</font>';
								}
							}
						}

						if ($have_content_file_changes[$client] == '1')
						{
							$pre_render['diff_file_content'][$client] .=
								"<h3 class='show_common_files'>"
								. JText::_('MOD_TTM_DIFF_TOOL_OTF_DETAILING_CHANGES_COMMON_FILES_BY_CLIENT')
								. "</h3>";

							$pre_render['diff_file_content'][$client] .=
								$comments_to_exclude
								. $type_html
								. $type_xml
								. $type_php
								. $type_html
								. $type_unusual
								. $type_html
								. $type_ini;

						}
						else
						{
							$pre_render['diff_file_content'][$client] .=
								"<p class='show_common_files'>"
								. JText::_('MOD_TTM_DIFF_TOOL_OTF_DETAILING_NO_CHANGES_COMMON_FILES_BY_CLIENT')
								. "</p>";
						}


					}
					else
					{
						$have_comon_files[$client]              = '0';
						$pre_render['diff_file_names'][$client] .=
							"<h3 class='no_common_files'>"
							. "There are no common files at the client "
							. strtoupper(JText::_('MOD_TTM_DIFF_TOOL_OTF_'
								. strtoupper($client)))
							. "</h3>";
					}

				}
				unset ($destination, $all_info);
			}
			unset ($client, $destinations);
		}
		unset ($all, $part);
	}

	foreach ($clients_order as $client_order)
	{
		if (isset ($pre_render['diff_file_names'][$client_order]))
		{
			$to_render .= $pre_render['diff_file_names'][$client_order];

			if (isset ($pre_render['new_files_content'][$client_order]))
			{
				if ($have_files_to_add[$client_order] == '1')
				{
					$to_render .=
						"<br /><H3 class='show_new_files'>"
						. JText::_('MOD_TTM_DIFF_TOOL_OTF_DETAILING_NEW_FILES_CONTENT')
						. "</H3>";
				}

				$to_render .= $pre_render['new_files_content'][$client_order];
				$to_render .= "<br />";
			}

			if (isset ($pre_render['diff_file_content'][$client_order]))
			{
				$to_render .= $pre_render['diff_file_content'][$client_order];
			}
			$to_render .= "<br />";
		}
		unset ($client_order);
	}

	return $to_render;
}

function test_uri($url = '')
{
	$uri = JURI::getInstance($url);

	return $uri;
}

function get_content_diff($common_file = '',
                          $sf_content = '',
                          $tf_content = '',
                          $ecp)
{
	$content                      = array();
	$content['changes']           = array();
	$content['excluded_comments'] = array();


	if (JFile::getExt($common_file) == 'ini')
	{
		$file_type            = 'type_ini';
		$content['changes'][] = $file_type;

	}
	elseif (JFile::getExt($common_file) == 'xml'
		&& ($common_file == 'install.xml' || $common_file == 'en-GB.xml'))
	{
		$file_type            = 'type_xml';
		$content['changes'][] = $file_type;

	}
	elseif (JFile::getExt($common_file) == 'php'
		&& $common_file == 'en-GB.localise.php')
	{
		$file_type            = 'type_php';
		$content['changes'][] = $file_type;

	}
	elseif (JFile::getExt($common_file) == 'html'
		&& $common_file == 'index.html')
	{
		$file_type            = 'type_html';
		$content['changes'][] = $file_type;

	}
	else
	{
		$file_type            = 'type_unusual';
		$content['changes'][] = $file_type;
	}

	if ($file_type == 'type_ini')
	{
		$content['changes'][$file_type] = "";
		$source_lines                   = extract_file_content(preg_split('/\r\n|\r|\n/', $sf_content));
		$target_lines                   = extract_file_content(preg_split('/\r\n|\r|\n/', $tf_content));

		if (isset ($source_lines['comments']))
		{
			$source_comments = $source_lines['comments'];
		}
		else
		{
			$source_comments = array();
		}

		if (isset ($target_lines['comments']))
		{
			$target_comments = $target_lines['comments'];
		}
		else
		{
			$target_comments = array();
		}

		if (isset ($source_lines['sections']))
		{
			$source_sections = $source_lines['sections'];
		}
		else
		{
			$source_sections = array();
		}

		if (isset ($target_lines['sections']))
		{
			$target_sections = $target_lines['sections'];
		}
		else
		{
			$target_sections = array();
		}


		if (isset ($source_lines['keys']))
		{
			$source_keys = $source_lines['keys'];
		}
		else
		{
			$source_keys = array();
		}

		if (isset ($target_lines['keys']))
		{
			$target_keys = $target_lines['keys'];
		}
		else
		{
			$target_keys = array();
		}

		$comments_to_add              = array_diff($target_comments, $source_comments);
		$comments_to_delete           = array_diff($source_comments, $target_comments);
		$sections_to_add              = array_diff($target_sections, $source_sections);
		$sections_to_delete           = array_diff($source_sections, $target_sections);
		$keys_to_add                  = array_diff($target_keys, $source_keys);
		$keys_to_delete               = array_diff($source_keys, $target_keys);
		$source_common_keys           = array_diff($source_keys, $keys_to_delete);
		$target_common_keys           = array_diff($target_keys, $keys_to_add);
		$have_comments_to_add         = '';
		$have_comments_to_delete      = '';
		$have_sections_to_add         = '';
		$have_sections_to_delete      = '';
		$have_keys_to_add             = '';
		$have_keys_to_delete          = '';
		$have_keys_to_revise          = '';
		$have_keys_to_move            = '';
		$have_keys_to_move_and_revise = '';

		if (!empty ($comments_to_add))
		{

			foreach ($comments_to_add as $comment_to_add)
			{
				$show_it = '1';

				if (empty($ecp))
				{
					$show_it = '1';
				}
				else
				{

					foreach ($ecp as $excluded_comment_pattern)
					{
						$replaced_content = preg_replace('/' . $excluded_comment_pattern . '/', '', $comment_to_add);

						if ($replaced_content != $comment_to_add)
						{
							$show_it = '0';

							if (!isset($content['excluded_comments']['to_add']))
							{
								$content['excluded_comments']['to_add'][] = $comment_to_add;
							}
							else
							{
								$content['excluded_comments']['to_add'][] = $comment_to_add;
							}

							continue;
						}

					}
				}

				if ($show_it == '1')
				{
					$have_comments_to_add .=
						"<p class='line_number'>"
						. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
						. htmlspecialchars($target_lines['comments_lines_number'][$comment_to_add])
						. ":</p><p class='comment_to_add'>"
						. htmlspecialchars($comment_to_add)
						. "</p>";
				}

				unset ($comment_to_add);
			}
		}

		if (!empty ($comments_to_delete))
		{

			foreach ($comments_to_delete as $comment_to_delete)
			{
				$show_it = '1';

				if (empty($ecp))
				{
					$show_it = '1';
				}
				else
				{

					foreach ($ecp as $excluded_comment_pattern)
					{
						$replaced_content = preg_replace('/' . $excluded_comment_pattern . '/', '', $comment_to_delete);

						if ($replaced_content != $comment_to_delete)
						{
							$show_it = '0';

							if (!isset($content['excluded_comments']['to_delete']))
							{
								$content['excluded_comments']['to_delete'][] = $comment_to_delete;
							}
							else
							{
								$content['excluded_comments']['to_delete'][] = $comment_to_delete;
							}

							continue;
						}

					}
				}

				if ($show_it == '1')
				{
					$have_comments_to_delete .=
						"<p class='line_number'>"
						. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
						. $source_lines['comments_lines_number'][$comment_to_delete]
						. ":</p><p class='comment_to_delete'>"
						. htmlspecialchars($comment_to_delete)
						. "</p>";
				}

				unset ($comment_to_delete);
			}
		}

		if (!empty ($sections_to_add))
		{

			foreach ($sections_to_add as $section_to_add)
			{
				$have_sections_to_add .=
					"<p class='line_number'>"
					. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
					. $target_lines['sections_lines_number'][$section_to_add]
					. ":</p><p class='section_to_add'>"
					. htmlspecialchars($section_to_add)
					. "</p>";
				unset ($section_to_add);
			}
		}

		if (!empty ($sections_to_delete))
		{

			foreach ($sections_to_delete as $section_to_delete)
			{
				$have_sections_to_delete .=
					"<p class='line_number'>"
					. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
					. $source_lines['sections_lines_number'][$section_to_delete]
					. ":</p><p class='section_to_delete'>"
					. htmlspecialchars($section_to_delete)
					. "</p>";
				unset ($section_to_delete);
			}
		}

		if (!empty ($keys_to_add))
		{

			foreach ($keys_to_add as $key_to_add)
			{
				$have_keys_to_add .=
					"<p class='line_number'>"
					. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
					. $target_lines['keys_lines_number'][$key_to_add]
					. ":</p><p class='key_to_add'>"
					. htmlspecialchars($key_to_add
						. "="
						. $target_lines['keys_text'][$key_to_add])
					. "</p>";
				unset ($key_to_add);
			}
		}

		if (!empty ($keys_to_delete))
		{

			foreach ($keys_to_delete as $key_to_delete)
			{
				$have_keys_to_delete .=
					"<p class='line_number'>"
					. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
					. $source_lines['keys_lines_number'][$key_to_delete]
					. ":</p><p class='key_to_delete'>"
					. htmlspecialchars($key_to_delete
						. "="
						. $source_lines['keys_text'][$key_to_delete])
					. "</p>";
				unset ($key_to_delete);
			}
		}

		if (!empty ($source_common_keys))
		{
			reset($target_common_keys);

			foreach ($source_common_keys as $source_common_key)
			{
				//Revise text changes
				$source_text = $source_lines['keys_text'][$source_common_key];
				$target_text = $target_lines['keys_text'][$source_common_key];
				$source_line = $source_lines['keys_lines_number'][$source_common_key];
				$target_line = $target_lines['keys_lines_number'][$source_common_key];

				if ($source_text != $target_text)
				{
					$text_changes = htmlspecialchars(htmlDiff_TTM($source_text, $target_text));
					$text_changes = preg_replace('/TTMINSSTART/', "<ins class='diff_ins'>", $text_changes);
					$text_changes = preg_replace('/TTMINSSTOP/', "</ins>", $text_changes);
					$text_changes = preg_replace('/TTMDELSTART/', "<del class='diff_del'>", $text_changes);
					$text_changes = preg_replace('/TTMDELSTOP/', "</del>", $text_changes);

					if (current($target_common_keys) != $source_common_key)
					{
						$have_keys_to_move_and_revise .=
							"<p class='line_number'>"
							. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
							. $source_line
							. JText::_('MOD_TTM_DIFF_TOOL_OTF_TO')
							. $target_line
							. ":</p><p class='key_to_move_and_revise'>"
							. htmlspecialchars($source_common_key)
							. "</p><p class='text_changes'>"
							. $text_changes . "</p>";

					}
					else
					{
						$have_keys_to_revise .=
							"<p class='line_number'>"
							. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
							. $target_line
							. ":</p><p class='key_to_revise'>"
							. htmlspecialchars($source_common_key)
							. "</p><p class='text_changes'>"
							. $text_changes . "</p>";
					}

				}
				elseif (current($target_common_keys) != $source_common_key)
				{
					$have_keys_to_move .=
						"<p class='line_number'>"
						. JText::_('MOD_TTM_DIFF_TOOL_OTF_LINE')
						. $source_line
						. JText::_('MOD_TTM_DIFF_TOOL_OTF_TO')
						. $target_line
						. ":</p><p class='key_to_move'>"
						. htmlspecialchars($source_common_key)
						. "="
						. htmlspecialchars($target_text)
						. "</p>";
				}

				next($target_common_keys);
				unset($source_common_key);
			}
		}
		$detected_changes = '';
		$have_changes     = 0;
		if ($have_comments_to_delete != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='comments_to_delete'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_COMMENTS_TO_DELETE')
				. "</p>";
			$detected_changes .= $have_comments_to_delete;
		}

		if ($have_comments_to_add != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='comments_to_add'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_COMMENTS_TO_ADD')
				. "</p>";
			$detected_changes .= $have_comments_to_add;
		}

		if ($have_sections_to_delete != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='sections_to_delete'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_SECTIONS_TO_DELETE')
				. "</p>";
			$detected_changes .= $have_sections_to_delete;
		}

		if ($have_sections_to_add != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='sections_to_add'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_SECTIONS_TO_ADD')
				. "</p>";
			$detected_changes .= $have_sections_to_add;
		}

		if ($have_keys_to_delete != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='keys_to_delete'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_KEYS_TO_DELETE')
				. "</p>";
			$detected_changes .= $have_keys_to_delete;
		}

		if ($have_keys_to_add != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='keys_to_add'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_KEYS_TO_ADD')
				. "</p>";
			$detected_changes .= $have_keys_to_add;
		}

		if ($have_keys_to_move != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='keys_to_move'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_KEYS_TO_MOVE')
				. "</p>";
			$detected_changes .= $have_keys_to_move;
		}

		if ($have_keys_to_move_and_revise != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='keys_to_move_and_revise'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_KEYS_TO_MOVE_AND_REVISE')
				. "</p>";
			$detected_changes .= $have_keys_to_move_and_revise;
		}

		if ($have_keys_to_revise != '')
		{
			$have_changes     = 1;
			$detected_changes .=
				"<p class='keys_to_revise'>"
				. JText::_('MOD_TTM_DIFF_TOOL_OTF_HAVE_KEYS_TO_REVISE')
				. "</p>";
			$detected_changes .= $have_keys_to_revise;
		}
		if ($have_changes == 1)
		{
			$content['changes'][$file_type] = $detected_changes;
		}
		else
		{
			$content['changes'] = '';
		}

	}
	elseif ($file_type == 'type_xml' || $file_type == 'type_php' || $file_type == 'type_html')
	{
		$have_changes                   = 1;
		$content['changes'][$file_type] = htmlspecialchars(htmlDiff_TTM($sf_content, $tf_content));
		$content['changes'][$file_type] = preg_replace('/TTMINSSTART/', "<ins class='diff_ins'>", $content['changes'][$file_type]);
		$content['changes'][$file_type] = preg_replace('/TTMINSSTOP/', "</ins>", $content['changes'][$file_type]);
		$content['changes'][$file_type] = preg_replace('/TTMDELSTART/', "<del class='diff_del'>", $content['changes'][$file_type]);
		$content['changes'][$file_type] = preg_replace('/TTMDELSTOP/', "</del>", $content['changes'][$file_type]);
	}
	elseif ($file_type == 'type_unusual')
	{
		$have_changes                   = 1;
		$content['changes'][$file_type] = JText::_('MOD_TTM_DIFF_TOOL_OTF_NOT_EVALUATED_FILE');
	}

	return $content;
}

function extract_file_content($lines = array())
{
	$content               = array();
	$catched_key           = '0';
	$catched_commented_key = '0';
	$catched_comment       = '0';
	$catched_section       = '0';

	if (!empty ($lines))
	{

		foreach ($lines as $id => $line_content)
		{
			$trimmed = trim($line_content);

			if (!empty ($line_content))
			{
				if ($line_content{0} != '#'
					&& $line_content{0} != ';'
					&& $line_content{0} != '/'
					&& $line_content{0} != '*'
					&& $line_content{0} != '['
					&& $trimmed != '')
				{
					if (strpos($line_content, "="))
					{
						$catched_key = '1';
						list ($key, $text) = explode('=', $line_content, 2);
						$content['keys'][]                  = $key;
						$content['keys_text'][$key]         = $text;
						$content['keys_lines_number'][$key] = $id + 1;
					}
				}
				elseif ($line_content{0} == '(')
				{

					if (strpos($line_content, "="))
					{
						$catched_commented_key = '1';
						list ($commented_key, $commented_text) = explode('=', $line_content, 2);
						$content['commented_keys'][]                            = $commented_key;
						$content['commented_keys_text'][$commented_key]         = $commented_text;
						$content['commented_keys_lines_number'][$commented_key] = $id + 1;
					}

				}
				elseif ($line_content{0} == '#' || $line_content{0} == ';'
					|| $line_content{0} == '/' || $line_content{0} == '*'
					|| ($line_content{0} == ' ' && $line_content{1} == '*'))
				{
					$catched_comment                                 = '1';
					$content['comments'][]                           = $line_content;
					$content['comments_lines_number'][$line_content] = $id + 1;

				}
				elseif ($line_content{0} == '[')
				{

					if (!strpos($line_content, "="))
					{
						$catched_section                           = '1';
						$content['sections'][]                     = $section;
						$content['section_lines_number'][$section] = $id + 1;
					}

				}

			}
			unset ($id, $line_content);
		}
	}

	return $content;
}


function get_sha($link = '',
                 $knowed_zipball_urls = array(),
                 $knowed_joomla_patterns = array())
{
	$file_to_load             = '';
	$github_data              = '';
	$loaded_lines             = '';
	$part                     = array();
	$knowed_lts               = $knowed_zipball_urls['LTS_DEV'];
	$knowed_sts               = $knowed_zipball_urls['STS_DEV'];
	$allowed_zipball_patterns = array_merge(array_diff($knowed_lts, $knowed_sts), array_diff($knowed_sts, $knowed_lts));

	$allowed_joomla_patterns = $knowed_joomla_patterns;

	foreach ($allowed_zipball_patterns as $pattern => $git_hub_target)
	{
		//$test = strpos($pattern, $link);
		$test = preg_replace('/|'
			. preg_quote($pattern, '/|.-')
			. '|/', '', $link);

		//if ($test !== false)
		if ($test != $link)
		{
			$file_to_load   = $git_hub_target;
			$github_data    = get_raw_url_content($file_to_load);
			$gh_loaded_data = '';

			if (empty($github_data))
			{
				//fclose($github_data);
				//If denied by server github access, then use generic value
				$tested_parts     = explode('/', $git_hub_target);
				$part['name']     = end($tested_parts) . '_' . date('Y-m-d');
				$part['part_sha'] = '';
				$part['full_sha'] = '';

				return $part;
			}
			else
			{

				//while (!feof ($github_data))
				//{
				//$gh_loaded_data .= fread($github_data, 1024);
				//}

				//fclose($github_data);
				$loaded_lines = preg_split('/\r\n|\r|\n/', $github_data);

				foreach ($loaded_lines as $line_id => $line_content)
				{

					if (strpos($line_content, '"sha":"'))
					{
						list ($first_part, $brute_text) = explode('"sha":"', $line_content, 2);

						if (strpos($brute_text, '"'))
						{
							list ($full_part['full_sha'], $resting_text) = explode('"', $brute_text, 2);
							$part['full_sha'] = $full_part['full_sha'];
							$part['part_sha'] = substr($full_part['full_sha'], 0, -33);
							$part['name']     = '';

							return $part;
						}
					}
					unset ($line_content);
				}

			}
		}

		unset ($pattern, $git_hub_target);
	}

	foreach ($allowed_joomla_patterns as $pattern)
	{

		$test = preg_replace('/|'
			. preg_quote($pattern, '/|.-')
			. '|/', '', $link);

		if ($test != $link)
		{
			$tested_link  = test_uri($link);
			$tested_path  = $tested_link->toString(array('path'));
			$tested_parts = explode('/', $tested_path);
			$part['name'] = JFile::makeSafe(end($tested_parts));

			if (JFile::getExt($part['name']) && JFile::getExt($part['name']) == 'zip')
			{
				$part['part_sha'] = '';
				$part['full_sha'] = '';
				$part['name']     = JFile::getName($part['name']);

				return $part;
			}
		}
		unset ($pattern);
	}

	$part['part_sha'] = '';
	$part['full_sha'] = '';
	$part['name']     = '';

	return $part;
}

function history_to_delete($path = '',
                           $filter = '',
                           $can_do_history = '0')
{
	$have_deleted_files = 0;

	if ($can_do_history == '1')
	{

		if (!empty($path))
		{
			$jinput           = JFactory::getApplication()->input;
			$selectable_files = array();
			$selectable_files = JFolder::files($path, $filter);

			if ($jinput->get('history_selected', null, null))
			{
				$files_to_delete = $jinput->get('history_selected', null, null);

				foreach ($files_to_delete as $file_to_delete => $value)
				{
					if ($value == 'on' && JFile::getExt($file_to_delete) == 'ttm')
					{
						if (JFile::exists($path . '/' . $file_to_delete))
						{
							JFile::delete($path . '/' . $file_to_delete);

							if (JFile::exists($path . '/' . $file_to_delete))
							{
								JError::raiseWarning('100',
									'[ERROR DELETING HISTORY] '
									. htmlspecialchars($file_to_delete));
							}
							else
							{
								//File really deleted
								JError::raiseNotice(100,
									'[HISTORY FILE DELETED] '
									. htmlspecialchars($file_to_delete));
								$have_deleted_files = '1';
							}
						}
					}

					unset ($file_to_delete, $value);
				}

			}

		}

	}

	return $have_deleted_files;
}

function get_history_files($path = '',
                           $filter = '',
                           $can_do_history = '0')
{
	$check_box = '';
	if ($can_do_history == '1')
	{

		if (!empty($path))
		{
			$selectable_files = array();
			$selectable_files = JFolder::files($path, $filter);

			if (!empty($selectable_files))
			{
				$check_box = '<form name="history_form" action="" method="POST"><div style="history_checkbox">';

				foreach ($selectable_files as $id => $history_file)
				{
					$check_box .= '<input type="checkbox" name="history_selected['
						. $history_file
						. '] value="">'
						. $history_file
						. '<br>';
					unset ($id, $history_file);
				}

				$check_box .= '<input name="submit" type="submit" value="'
					. JText::_("MOD_TTM_DIFF_TOOL_OTF_DELETE_SELECTED")
					. '" />';
				$check_box .= '</div></form>';
			}
			else
			{
				$check_box = '<p>'
					. JText::_('MOD_TTM_DIFF_TOOL_OTF_EMPTY_HISTORY_FOLDER')
					. '</p>';
			}

		}
		else
		{
			$check_box = '<p>Empty of files to select.</p>';
		}

	}
	else
	{
		$check_box = '<p>'
			. JText::_('MOD_TTM_DIFF_TOOL_OTF_ACCESS_NOT_ALLOWED')
			. '</p>';
	}


	return $check_box;
}

/*
Paul's Simple Diff Algorithm v 0.1
(C) Paul Butler 2007 <http://www.paulbutler.org/>
May be used and distributed under the zlib/libpng license.
This code is intended for learning purposes; it was written with short
code taking priority over performance. It could be used in a practical
application, but there are a few ways it could be optimized.
Given two arrays, the function diff will return an array of the changes.
I won't describe the format of the array, but it will be obvious
if you use print_r() on the result of a diff on some test data.
htmlDiff is a wrapper for the diff command, it takes two strings and
returns the differences in HTML. The tags used are <ins> and <del>,
which can easily be styled with CSS.

Modiffied a bit by valc
*/

function diff_TTM($old, $new)
{
	$maxlen = 0;
	$ret    = '';
	foreach ($old as $oindex => $ovalue)
	{
		$nkeys = array_keys($new, $ovalue);

		foreach ($nkeys as $nindex)
		{
			$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
				$matrix[$oindex - 1][$nindex - 1] + 1 : 1;

			if ($matrix[$oindex][$nindex] > $maxlen)
			{
				$maxlen = $matrix[$oindex][$nindex];
				$omax   = $oindex + 1 - $maxlen;
				$nmax   = $nindex + 1 - $maxlen;
			}

			unset ($nkeys, $nindex);
		}
		unset ($oindex, $ovalue);
	}

	if ($maxlen == 0) return array(array('d' => $old, 'i' => $new));

	return array_merge(
		diff_TTM(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
		array_slice($new, $nmax, $maxlen),
		diff_TTM(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
}

function htmlDiff_TTM($old, $new)
{
	$ret  = '';
	$diff = diff_TTM(explode(' ', $old), explode(' ', $new));
	foreach ($diff as $k)
	{
		if (is_array($k))
		{
			$ret .= (!empty ($k['d']) ? "TTMDELSTART"
					. implode(' ', $k['d'])
					. "TTMDELSTOP " : '') .
				(!empty($k['i']) ? "TTMINSSTART"
					. implode(' ', $k['i'])
					. "TTMINSSTOP " : '');
		}
		else
		{
			$ret .= $k . ' ';
		}
		unset ($k);
	}

	return $ret;
}

