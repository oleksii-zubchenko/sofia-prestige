<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'ch173f9ccf_sofia');

/** MySQL database username */
define('DB_USER', 'ch173f9ccf_sofia');

/** MySQL database password */
define('DB_PASSWORD', '7sIN8Udk4Z');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

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
define('AUTH_KEY',         'm6C&VASiG*)@%nuhmkFjmk1eKb6MWG#Z1pLOLb^w9zKef8q(69ZrQSHm7eI3yH)X');
define('SECURE_AUTH_KEY',  '%LwcNIkG)Hc2(*8od3Cg!0K&O(E()v9)0!dEDAymGKdynnp7%Ph@Q%&rsM0LAmpd');
define('LOGGED_IN_KEY',    '5jRUvg6zGAZ*ozDxeI4NK!@*%6yKxrGVGCUYVehItqVLb!pKhVLG0Zr@Sxu5*!*5');
define('NONCE_KEY',        'eeJA2b^KR)iey%X)Pc#k*o!b&LUAZMiyiRmclYncfAh8VWui2bUjWM0XZZmm%vMh');
define('AUTH_SALT',        'AeX%Iddqo()Y9mMYUIGyS9MxzcQrhbBP8xoWa*964jL*px3f2AvYnu^0^32A)T#&');
define('SECURE_AUTH_SALT', '4Pbh9ZyC(qfLTwpQVhXR3@oluML^8)7ellyJ4(xTwekGjIn89sB6&KzGOYI965Yg');
define('LOGGED_IN_SALT',   'olpF3FsbpqzPdJiUERrML29SrLYcHHyMaYEt&c3i!mjGQA0GrIiaGUgY^jKaf3Hd');
define('NONCE_SALT',       '@#19IQhL^rgnxfjkLoM#F@&oxJeLinl@^QT87CI*Ym9U40Oa!&geDUDbykXazn#G');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define( 'WP_ALLOW_MULTISITE', true );

define ('FS_METHOD', 'direct');
