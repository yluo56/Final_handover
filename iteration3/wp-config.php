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
define( 'DB_NAME', 'iteration3' );

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
define( 'AUTH_KEY',         'Jgzi*sTj-Fcw#Rx_/)L)-yW&~w5ofz`?vOyrkd&Rv21xk+E3F=q?CQ/|a;IQMK1:' );
define( 'SECURE_AUTH_KEY',  'P5VL~A~8gR]*)N|d/%M<mbuPkxH+7DQ8iw.OSH1>(C^la#w*l$d=6rSJ->^a40=T' );
define( 'LOGGED_IN_KEY',    'g BZjiMRgR*lRyQ!N9&fnx8:uL+`U=-wU| ,=`XbS1!~32;A1/fsM:D(a$R]Nb?X' );
define( 'NONCE_KEY',        '(/67lDbf~Xd3(W)KI9k:1yA}S0|KEJD`GjW2=DkO4>Uk2s(7=u~Rn3MWR{|4EHD@' );
define( 'AUTH_SALT',        'pT-}$+*E%DbP>cBL;nvdN|t9Wag.}|*&wJ5&6Prd[&401+Bd{7qNOI8E?1)VP%SN' );
define( 'SECURE_AUTH_SALT', 'Pw.IU-oBN=7W#xN`N#Nq#u{lN-vM{axn!:egPUX&D/`FuO~WqzX{ZIx:D.4U:Sqn' );
define( 'LOGGED_IN_SALT',   '2]a]|R6b~O<35GId#[B#$@YUxY=<*XN:Q#+uqjqy7$hTU8btutkhyK-?+.AO`JyZ' );
define( 'NONCE_SALT',       '&1fFC1/UdE5azewO@J-$4]!xoN$oV3}TT=isLxR7C04Zpev2}0)h}`@LGsjqo1/3' );

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
