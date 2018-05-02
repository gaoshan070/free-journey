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
define('DB_NAME', 'sweetgift');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '123qwe');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');
define( 'WP_MEMORY_LIMIT', '512M' );

define("FS_METHOD","direct");
define("FS_CHMOD_DIR", 0777);
define("FS_CHMOD_FILE", 0777);


/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'QMxu:^/)bU]O.^*l0]f!|@%Wz3kw-1)A:DM{k[0LA~Ek?$=}&H^Z&!z{mS9p`<&+');
define('SECURE_AUTH_KEY',  'qXse6u2p9[8TV+}A-Y9^djT$)L^$.0$0IE@*su2J>A^]#`rvS3F5bLFm_y!a1s5u');
define('LOGGED_IN_KEY',    '1S~e}S!Y/WJ9X/lf<F*bQtW:dZ_|7<^%ha}iUBRiHF5yHUE!{Z1bM[xs^#E7#ETD');
define('NONCE_KEY',        'd=:?@*,RjUCkV.aK0jYV&t/[$6|}#d`pJ!)`zOMkWCAzXldFKrR)*@6h#o vGJ?e');
define('AUTH_SALT',        '/^;mKVMOAnT+t!}8#};WN(yg>MYLJ9c_/J8ZK-1e;Y>>0,=|~;f2~ >^gw22UZD=');
define('SECURE_AUTH_SALT', '@Ih;$]N.vky1?cuHnz8 HF3?C W}AH_kcc3z#RFz:[k<>.(%S]<o>BX)lN/dCQpp');
define('LOGGED_IN_SALT',   'DE3nZ4Dfv1BF8(/YiKD;Ln;}As1:(B5s(:f2g!yWrf/:G8JP~T)T;_6o%gr+hrOj');
define('NONCE_SALT',       'R.lyM!FK;qP4^I;HD*N!}j(#VT5]6:[^V%Bm? 3<Uru!Ein?H5k7;R&]ctP|X?rG');

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
