<?php
/**
 * MVCLight
 *
 * An open source application development framework for PHP
 *
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @since		0.1a
 * @version 	0.2a
 * @filesource
 */

/**
 * application/config/config.ini.php
 * 
 * This file contains the application main configuration
 * 
 */

/**
 * Enables profiler, used to collect information about the framework footprint
 * 
 */
$config['enable_profiler'] = true;

/**
 * Enables profiler for ajax requests (requires enable_profiler to be true)
 * 
 */
$config['enable_profiler_ajax'] = false;

/**
 * Auto loads database connection
 * 
 */
$config['db_auto_load'] = false;

/**
 * Default controller/action to be executed on root
 * 
 */
$config['default_route'] = 'home/index';

/**
 * Default Language code
 *
 */
$config['lang_code'] = "en_GB";

/**
 * Default plural form
 * 
 */
$config['lang_default_plural'] = 's';

/**
 * Supported languages
 * 
 */
$config['supported_langs']	= array('en_GB','es_ES');

/**
 * Language domain
 *
 */
$config['lang_domain']	= 'mvclight';

/**
 * 
 * Translation path
 */
$config['lang_path']	= __APP_PATH . '/lang';


/**
 * Cross scripting filter, avoids JS and PHP to be executed in our server
 * 
 */
$config['global_xss_filtering'] = true;

/**
 * Enable GET parameters
 * 
 */
$config['enable_query_strings'] = true;

/**
 * Production server configuration, for further actions like showing errors
 * 
 */
$config['production_server'] = PRODUCTION_SERVER;

/**
 * Default charset for this application
 * 
 */
$config['charset'] = 'utf-8';

/**
 * Mail configuration values
 *
 * $config['mail_user'] 	for the SMTP server
 * $config['mail_password'] for the SMTP server
 * $config['webmaster'] 	person to send notification emails to
 * $config['mail_user'] 	person to send notifications from
 * 
 */
$config['mail_host']		= '';
$config['mail_user']		= '';
$config['mail_password']	= '';
$config['webmaster']		= '';
$config['mail_from_name']	= '';

/**
 * Google Analytics Key
 * 
 */
$config['ga_key'] = '';

/**
 * Default page layout
 * 
 */
$config['page_layout'] = 'layout';

/**
 * Default page title
 * 
 */
$config['page_title'] = 'MVCLight demo application';

/**
 * Default page description
 * 
 */
$config['page_description'] = 'MVCLight is a lightweight MVC framework written in PHP. It is intended for custom projects where a home-brew solution is required or where performance is critical and an MVC architecture is wanted.';

/**
 * Default page keywords
 * 
 */
$config['page_keywords'] = 'MVCLight, MVC Light, PHP MVC, MVC framework, PHP MVC framework, lightweight MVC';
?>