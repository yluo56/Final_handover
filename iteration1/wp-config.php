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
define( 'DB_NAME', 'iteration1' );

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
define( 'AUTH_KEY',         '=*02!BlU#~6tavoPUPL%MS%L3aW7+*JzBD$iMa>E2.Z^N0K,2NfAqIgM>gA-?`![' );
define( 'SECURE_AUTH_KEY',  '[loQS)hPMI,saw<6m[c$.zKR=%$skS4<.tBPb:Ei2CkIvkOLs,b[`$~{0epNGc@7' );
define( 'LOGGED_IN_KEY',    'q,W#wzv(<;![I/XCB,NQ.fA]JcK0)eR,r+(.4!+(`<a`^JNAaHO;3@IF8;LQW%Aa' );
define( 'NONCE_KEY',        '/PO*+!/^V}U8RX0R}cU5Uc0uo;4(vf;f4.m/RZaiX|34:4w9p5~ftDO2*m8:s8G2' );
define( 'AUTH_SALT',        '~c.iCttm4v;`u0H.fzr_H %a+wJ{GpljcS>6~<N5Qs,{5Y|ARWq)tsS!MGH52?Lx' );
define( 'SECURE_AUTH_SALT', ')<uPUx/zHmcl ms}E@,WdSi(Vp)ZH{|$SLVvl227~tEnGE@^6R%8P6]n~0Q ^A%%' );
define( 'LOGGED_IN_SALT',   'j?&EOa<fCK8,ue9jdyfBLWl8LS|k}_2F@$c;mUO~INR6NW;hvgEZX4LO-~A@QZ3q' );
define( 'NONCE_SALT',       'qc {T@0rzTA[IGY:#6n;.Ub87Jc3ebQOZY/QI8f0p}T+plZ#z<>cxPjIewj:& W.' );

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
