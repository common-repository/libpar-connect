<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
{
	exit();
}

function xfac_admin_init()
{
	if (xfac_option_getWorkingMode() === 'blog')
	{
		register_setting('xfac-settings', 'xfac_client_id');
		register_setting('xfac-settings', 'xfac_client_secret');
	}

	$config = xfac_option_getConfig();
	$meta = xfac_option_getMeta($config);
	if (!empty($meta['linkIndex']))
	{
		register_setting('xfac-settings', 'xfac_tag_forum_mappings');

		register_setting('xfac-settings', 'xfac_top_bar_forums');

		register_setting('xfac-settings', 'xfac_top_bar_always');

		register_setting('xfac-settings', 'xfac_xf_guest_account');
	}
}

add_action('admin_init', 'xfac_admin_init');

function xfac_admin_menu()
{
	add_options_page('Libpar Connect', 'Libpar Connect', 'manage_options', 'xfac', 'xfac_options_init');
}

add_action('admin_menu', 'xfac_admin_menu');

function xfac_plugin_action_links($links, $file)
{
	if ($file == 'libpar-connect/index.php')
	{
		$settings_link = '<a href="options-general.php?page=xfac">' . __("Settings") . '</a>';

		array_unshift($links, $settings_link);
	}

	return $links;
}

add_filter('plugin_action_links', 'xfac_plugin_action_links', 10, 2);

function xfac_whitelist_options($whitelist_options)
{
	if (xfac_option_getWorkingMode() === 'blog')
	{
		$whitelist_options['xfac'][] = 'xfac_client_id';
		$whitelist_options['xfac'][] = 'xfac_client_secret';
	}

	$config = xfac_option_getConfig();
	$meta = xfac_option_getMeta($config);
	if (!empty($meta['linkIndex']))
	{
		$whitelist_options['xfac'][] = 'xfac_tag_forum_mappings';

		$whitelist_options['xfac'][] = 'xfac_top_bar_forums';

		$whitelist_options['xfac'][] = 'xfac_top_bar_always';

		$whitelist_options['xfac'][] = 'xfac_xf_guest_account';
	}

	return $whitelist_options;
}

add_filter('whitelist_options', 'xfac_whitelist_options');
