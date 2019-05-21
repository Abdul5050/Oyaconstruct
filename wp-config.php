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
define('DB_NAME', 'oyaconstruct_wor');

/** MySQL database username */
define('DB_USER', 'oyaconstruct_wor');

/** MySQL database password */
define('DB_PASSWORD', 'U3WTmY1z');

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
define('AUTH_KEY', 'NFP53HxMnBOD5UoNIfCLx4acUGvxmPD6RnHaUANRcPee4CNxJaSTrBQWBmeMQUcV');
define('SECURE_AUTH_KEY', '5QamIdBtMOQC3IAMLzl4YDvoFqXY7mJrr35HX9zf1v9QKEBVrkijPV7LyyCpRPt6');
define('LOGGED_IN_KEY', 'OoUefAKgeuCrhJGX9X8U6MuKXX3Rn1seDpcCwuXx3PylLM8EFlbtFXq18UXrm3Xe');
define('NONCE_KEY', 'snosqfDTy9RIOVccjlG6y5qLZfUO4Or7xXZJzdVaMqcYl3A0kmH3a7ZukhwhhAfn');
define('AUTH_SALT', '8RFsAYzz4en5yFexVnMALWSzwGVVETi20d0EAQISiFTxDMbrYOWB0jzyF4QzWGxq');
define('SECURE_AUTH_SALT', '7Lc99z5hKWB5IunqGW4hLayEWkUO86r7Dy5VEaACMSR7Iu6tKVEF7LkmOcuPrSqU');
define('LOGGED_IN_SALT', 'MwWXsAe129bwlM83yKRVYJhdxOcm0XIDhzJ061lV4ZbWWSQVBBjQy0ByzoyozJnQ');
define('NONCE_SALT', 'CRkgGYKL7nWMjdBouuok89q0sXQcyFifvDpBbd8NSmsttiPZsk464xV9QUXqAfuF');

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
