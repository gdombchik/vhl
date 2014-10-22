<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'vhl');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'Am]MqL9ho+OfO?9x7YKYC(^f8gGSJ9%eD%b~-p+6h2.t}M-@xJ*y]$a?%X&^;n-X');
define('SECURE_AUTH_KEY',  '$nZ];=t_n+J}Ue=$=nqU])42O-k^0>)dpTZRmf|q|xU$b&Q`Tc8b[]!N5qyMwC=A');
define('LOGGED_IN_KEY',    ':5ceX:%;H-u+(~nsBs}rhH^E|m-O<.hA G1fb,JOHyl+;3}];99fN)k,cdb PV*g');
define('NONCE_KEY',        '.RmIxC6h;GR.|-SW+)%)]IU^aA;`d$y^Z3:Q G-hYhpEX=Q]9wAL-+L]D $LItT|');
define('AUTH_SALT',        ':W[UdnUfT,?8FnnDqLv 39/pSDr^+f?k%zT>|xi@w4p;XgaKbD=8 -O^2SpK~`(]');
define('SECURE_AUTH_SALT', '|/hvcwxr-,rnna-hC}9ml@JoY+Y,BUfp--.z%izzUib@:-(IJjqnPj6+f_I8<8[1');
define('LOGGED_IN_SALT',   'p3@6i`;E9Ty+:UI*RX(?Z7^188+cX#P9>$=AIu|nQk|V,nisFc96+5mr2Ihn_2i+');
define('NONCE_SALT',       '48}|SvbTk@GG/ZutaJ|;G~IRRh`$l&; Zj?vztCoRZU,CF0YzfxLKe$92WW&QmX/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
