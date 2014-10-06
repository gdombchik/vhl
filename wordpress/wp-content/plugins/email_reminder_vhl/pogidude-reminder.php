<?php
/*
Plugin Name: Email Reminder
Plugin URI: http://pogidude.com/email-reminder/
Description: Schedules email reminders. Enter your reminder, where you'd like to email the reminder to, and when you'd like the reminder to be sent.
Version: 1.2
License: GPLv2
Author: Ryann Micua
Author URI: http://pogidude.com/about/

TODO:
1. Clean up database on uninstall *
2. Add hour option ***
3. Add option to set FROM email **
5. Create description **
6. Add dashboard widget to add reminder from dashboard
7. Add dashboard notifications
9. Add option for recurring reminders
*/

/* Constants */
define( 'PDER_DIR_VHL', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'PDER_URI_VHL', trailingslashit( plugins_url( '', __FILE__ ) ) );
define( 'PDER_ASSETS_VHL', PDER_URI_VHL . 'assets' );
define( 'PDER_CSS_VHL', PDER_ASSETS_VHL . '/css' );
define( 'PDER_JS_VHL', PDER_ASSETS_VHL . '/js' );
define( 'PDER_INC_DIR_VHL', trailingslashit( PDER_DIR_VHL ) . 'includes' );
define( 'PDER_CLASSES_VHL', trailingslashit( PDER_INC_DIR_VHL ). 'classes' );
define( 'PDER_VIEWS_VHL', PDER_DIR_VHL . 'views' );
define( 'PDER_POSTTYPE_VHL', 'ereminder' );
define( 'PDER_DOMAIN_VHL', 'pd-ereminder' );

//echo PDER_CSS_VHL;

/* Load Base class */
//9/29 temporary commented out this code
require_once( trailingslashit( PDER_CLASSES_VHL ) . 'PDER_Base.php' );

/* View Cron Events Page */
//require_once( trailingslashit( PDER_INC_DIR_VHL ) . 'admin-cron-events.php' );

/* activation/deactivation stuff */
//register_activation_hook( __FILE__, array('PDER_Base','on_activate' ) );
//register_deactivation_hook( __FILE__, array('PDER_Base','on_deactivate' ) );
