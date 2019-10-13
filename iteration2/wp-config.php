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
define( 'DB_NAME', 'iteration2' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '5PMvVVgb2WWY' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'AE)$,*[5! xu8WAtIGqo$ZdB}?#mos,D18~dI=v*OEuc0?CYFFxA,Y!zUtIgBm[H' );
define( 'SECURE_AUTH_KEY',  'qOwF0!<],i$Mf_,|6SewPrj`P;o<2w9DIUf[V3AP(+^KLx`ykakHsDnLNGz|G3 i' );
define( 'LOGGED_IN_KEY',    'o|__GMG^cfLh~XV[A11$o5XW=yO} bB`? x5a]=]bkN:@w6.FP-zg!njtDM%Qmr@' );
define( 'NONCE_KEY',        '9W1ZG*<N,#+o+:m`XWv{vu#~HLf9b!XMhw1ElptX2PNKV$>2Bg@gD4O9q -_`5,!' );
define( 'AUTH_SALT',        'y~=sLybg&z%5rSz2e}z.G/Hc=2a4J}lz2jbom._A9yD|v5OuJV)@`P89<X0`;-[2' );
define( 'SECURE_AUTH_SALT', 'y-AF[ye&1[%jO4PVK]NO:kr bj IJ( iYktxD$}/&<0RItO%XIJp|P[)+pmU[}ep' );
define( 'LOGGED_IN_SALT',   'E*(h2)d.CW!CAu@f-:r7+$K%7hUpg,W7sH^5EAMntH+hhU<e#Hr^o2niXt9va6-<' );
define( 'NONCE_SALT',       'xQk%Y$#BSp^<7,g*eHDX=}]qR2[[x4T,e!e0JO=3 taA3}+xfo(M&ov/i]3_Y%tJ' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
