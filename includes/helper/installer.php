<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
{
	exit();
}

function xfac_install()
{
	global $wpdb;

	$currentVersion = 11;
	$installedVersion = intval(get_option('lpc_version'));

	$tblAuth = xfac_getTableAuth();
	$tblSync = $wpdb->prefix . 'lpc_sync';

	if ($installedVersion < 1)
	{
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta('
			CREATE TABLE ' . $tblAuth . ' (
				id INT(11) NOT NULL AUTO_INCREMENT,
				user_id INT(11) NOT NULL,
				provider VARCHAR(50) NOT NULL,
				identifier VARCHAR(100) NOT NULL,
				profile MEDIUMBLOB,
				token MEDIUMBLOB,
				PRIMARY KEY (id),
				UNIQUE KEY `user_id` (`user_id`) 
			);
		');
	}

	if ($installedVersion < 2)
	{
		if (!$wpdb->get_col($wpdb->prepare('SHOW KEYS FROM ' . $tblAuth . ' WHERE key_name = %s', 'user_id')))
		{
			$wpdb->query('ALTER TABLE ' . $tblAuth . ' ADD UNIQUE KEY `user_id` (`user_id`);');
		}
	}

	if ($installedVersion < 3)
	{
		require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta('
			CREATE TABLE ' . $tblSync . ' (
				provider VARCHAR(50) NOT NULL,
				provider_content_type VARCHAR(50) NOT NULL,
				provider_content_id VARCHAR(100) NOT NULL,
				sync_id INT(11) NOT NULL,
				sync_date INT(10) UNSIGNED NOT NULL,
				sync_data MEDIUMBLOB,
				PRIMARY KEY (provider, provider_content_type, provider_content_id, sync_id)
			);
		');
	}

	if ($installedVersion < $currentVersion)
	{
		update_option('lpc_version', $currentVersion);
	}

	xfac_setupCrons();
}

function xfac_setupCrons()
{
	$hourlyNext = wp_next_scheduled('xfac_cron_hourly');

	if ($hourlyNext === false)
	{
		wp_schedule_event(time(), 'hourly', 'xfac_cron_hourly');
	}
}

function xfac_getTableAuth()
{
	global $wpdb;

	if (xfac_option_getWorkingMode() === 'network')
	{
		return $wpdb->base_prefix . 'lpc_auth';
	}
	else
	{
		return $wpdb->prefix . 'lpc_auth';
	}
}

// TODO: drop this action? It is called within xfac_activate already
add_action('plugins_loaded', 'xfac_install');
