<?php
/**
 * Plugin Name: Libpar Connect
* Plugin URI: http://www.libpar.com/threads/libpar-connect.29193/
* Description: Libpar Connect allows libertarian blogs to be seen by users of Libpar.com, and synchronises to Libpar's user database so that more people will view and comment on your blog.
* Version: 1.0.1
* Author: Libpar
* Author URI: http://libpar.com
* License: GPL2
*/

// Exit if accessed directly
if (!defined('ABSPATH'))
{
	exit();
}

define('XFAC_API_SCOPE', 'read post conversate');
define('XFAC_PLUGIN_PATH', WP_PLUGIN_DIR . '/libpar-connect');
define('XFAC_PLUGIN_URL', WP_PLUGIN_URL . '/libpar-connect');

define('XFAC_CACHE_RECORDS_BY_USER_ID', 'libpar');
define('XFAC_CACHE_RECORDS_BY_USER_ID_TTL', 3600);

// Define options
define('LPC_API_ROOT', 'http://libpar.com/api/');
define('POST_WP_TO_XF', TRUE);
define('EXCERT_ONLY', FALSE);
define('INCLUDE_POST_LINK', TRUE);
define('THREAD_XF_TO_WP', FALSE);
define('AUTO_PUB_SYNC_POST', FALSE);
define('SYNC_GUEST_WP_TO_XF', TRUE);
define('COMMENT_WP_TO_XF', TRUE);
define('SYNC_GUEST_XF_TO_WP', TRUE);
define('REPLY_XF_TO_WP', TRUE);
define('AVATAR_XF_TO_WP', TRUE);

define('ALWAYS_REGISTER_USERS', TRUE);
define('SYNC_PASSWORDS', TRUE);
define('SYNC_LOGIN', TRUE);
define('SYNC_USER_WP_TO_XF', TRUE);


define('TOP_BAR_NOTIFICATION', FALSE);
define('TOP_BAR_CONVERSATION', FALSE);
define('TOP_BAR_REPLACE', FALSE);

function xfac_activate()
{
	if (!function_exists('is_multisite'))
	{
		// requires WordPress v3.0+
		deactivate_plugins(basename(dirname(__FILE__)) . '/' . basename(__FILE__));
		wp_die(__("XenForo API Consumer plugin requires WordPress 3.0 or newer.", 'xenforo-api-consumer'));
	}

	xfac_install();

	do_action('xfac_activate');
}

register_activation_hook(__FILE__, 'xfac_activate');

function xfac_init()
{
	$loaded = load_plugin_textdomain('xenforo-api-consumer', false, 'xenforo-api-consumer/lang/');
}

add_action('init', 'xfac_init');

require_once (dirname(__FILE__) . '/includes/helper/api.php');
require_once (dirname(__FILE__) . '/includes/helper/dashboard.php');
require_once (dirname(__FILE__) . '/includes/helper/installer.php');
require_once (dirname(__FILE__) . '/includes/helper/option.php');
require_once (dirname(__FILE__) . '/includes/helper/template.php');
require_once (dirname(__FILE__) . '/includes/helper/user.php');

if (is_admin())
{
	require_once (dirname(__FILE__) . '/includes/dashboard/options.php');
	require_once (dirname(__FILE__) . '/includes/dashboard/profile.php');
}
else
{
	require_once (dirname(__FILE__) . '/includes/ui/login.php');
	require_once (dirname(__FILE__) . '/includes/ui/top_bar.php');
	require_once (dirname(__FILE__) . '/includes/sync/login.php');
}

require_once (dirname(__FILE__) . '/includes/helper/sync.php');
require_once (dirname(__FILE__) . '/includes/sync/avatar.php');
require_once (dirname(__FILE__) . '/includes/sync/post.php');
require_once (dirname(__FILE__) . '/includes/sync/comment.php');

require_once (dirname(__FILE__) . '/includes/widget/threads.php');